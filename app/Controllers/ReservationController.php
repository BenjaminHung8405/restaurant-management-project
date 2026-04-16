<?php

namespace App\Controllers;

use App\Models\Meal;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\Order;
use Throwable;

class ReservationController extends BaseController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        $cart = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : array();
        if (empty($cart)) {
            header('Location: /menu');
            exit;
        }

        $mealModel = new Meal();
        $cartItems = array();
        $grandTotal = 0;

        foreach ($cart as $id => $quantity) {
            $item = $mealModel->find($id);
            if ($item) {
                $item['quantity'] = $quantity;
                $item['subtotal'] = $item['price'] * $quantity;
                $cartItems[] = $item;
                $grandTotal += $item['subtotal'];
            }
        }

        $this->render('reservations/create', array(
            'title' => 'Đặt bàn / Thanh toán',
            'cartItems' => $cartItems,
            'grandTotal' => $grandTotal,
            'formData' => array(
                'guest_name' => '',
                'guest_phone' => '',
                'reservation_date' => '',
                'reservation_time' => '',
                'party_size' => 2,
                'notes' => ''
            )
        ));
    }

    public function store()
    {
        $cart = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : array();
        if (empty($cart)) {
            header('Location: /menu');
            exit;
        }

        $mealModel = new Meal();
        $reservationModel = new Reservation();
        $tableModel = new Table();
        $orderModel = new Order();

        $cartItems = array();
        $grandTotal = 0;
        foreach ($cart as $id => $quantity) {
            $item = $mealModel->find($id);
            if ($item) {
                $item['quantity'] = $quantity;
                $item['subtotal'] = $item['price'] * $quantity;
                $cartItems[] = $item;
                $grandTotal += $item['subtotal'];
            }
        }

        $formData = array(
            'guest_name' => trim((string) ($_POST['guest_name'] ?? '')),
            'guest_phone' => trim((string) ($_POST['guest_phone'] ?? '')),
            'reservation_date' => trim((string) ($_POST['reservation_date'] ?? '')),
            'reservation_time' => trim((string) ($_POST['reservation_time'] ?? '')),
            'party_size' => max(1, (int) ($_POST['party_size'] ?? 1)),
            'notes' => trim((string) ($_POST['notes'] ?? ''))
        );

        $errors = array();
        if ($formData['guest_name'] === '') $errors[] = 'Vui lòng nhập tên khách.';
        if ($formData['guest_phone'] === '') $errors[] = 'Vui lòng nhập số điện thoại.';
        if ($formData['reservation_date'] === '') $errors[] = 'Vui lòng chọn ngày đặt bàn.';
        if ($formData['reservation_time'] === '') $errors[] = 'Vui lòng chọn giờ đặt bàn.';

        $reservationDateTime = null;
        if ($formData['reservation_date'] !== '' && $formData['reservation_time'] !== '') {
            $reservationDateTime = $formData['reservation_date'] . ' ' . $formData['reservation_time'] . ':00';
        }

        if (empty($errors)) {
            try {
                $reservationId = $this->uuid();
                
                $reservationModel->create(array(
                    'id' => $reservationId,
                    'user_id' => null,
                    'table_id' => null, // Will assign later
                    'reservation_time' => $reservationDateTime,
                    'guest_count' => $formData['party_size'],
                    'guest_name' => $formData['guest_name'],
                    'guest_phone' => $formData['guest_phone'],
                    'notes' => $formData['notes'] !== '' ? $formData['notes'] : null,
                    'status' => 'pending'
                ));

                // Table assignment logic (simplified)
                $tables = $tableModel->getAllAvailable();
                $assignedTableId = null;
                foreach ($tables as $table) {
                    if ($table['capacity'] >= $formData['party_size']) {
                        $assignedTableId = $table['id'];
                        break;
                    }
                }

                if ($assignedTableId) {
                    $orderId = $this->uuid();
                    $orderModel->create(array(
                        'id' => $orderId,
                        'user_id' => null,
                        'table_id' => $assignedTableId,
                        'total_amount' => $grandTotal,
                        'order_status' => 'pending',
                        'payment_status' => 'unpaid'
                    ));

                    $orderModel->addItems($orderId, $cartItems);
                }

                unset($_SESSION['cart']);
                
                $this->render('reservations/success', array(
                    'title' => 'Đặt bàn thành công',
                    'reservationId' => $reservationId,
                    'grandTotal' => $grandTotal
                ));
                return;

            } catch (Throwable $e) {
                error_log('Reservation error: ' . $e->getMessage());
                $errors[] = 'Đặt bàn thất bại. Vui lòng thử lại.';
            }
        }

        $this->render('reservations/create', array(
            'title' => 'Đặt bàn / Thanh toán',
            'cartItems' => $cartItems,
            'grandTotal' => $grandTotal,
            'formData' => $formData,
            'errors' => $errors
        ));
    }

    private function uuid()
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
