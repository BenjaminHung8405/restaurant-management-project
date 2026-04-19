<?php

namespace App\Controllers;

use App\Models\Reservation;
use App\Models\Table;
use Throwable;

class AdminReservationController extends AdminBaseController
{
    public function index()
    {
        $reservationModel = new Reservation();
        $reservations = $reservationModel->getAllWithDetails();

        $this->render('admin.reservations.index', array(
            'title' => 'Quản lý Đặt bàn',
            'reservations' => $reservations
        ), 'layouts/admin');
    }

    public function create()
    {
        $tableModel = new Table();
        $tables = $tableModel->all(); // Fetch all tables for dropdown

        $this->render('admin.reservations.create', array(
            'title' => 'Tạo Đặt bàn mới',
            'tables' => $tables,
            'formData' => array(
                'customer_name' => '',
                'customer_phone' => '',
                'reservation_time' => date('Y-m-d\TH:i'), // Standard datetime-local format
                'party_size' => 2,
                'table_id' => $_GET['table_id'] ?? '',
                'notes' => ''
            ),
            'errors' => array()
        ), 'layouts/admin');
    }

    public function store()
    {
        $reservationModel = new Reservation();
        $tableModel = new Table();

        $formData = array(
            'customer_name' => trim($_POST['customer_name'] ?? ''),
            'customer_phone' => trim($_POST['customer_phone'] ?? ''),
            'reservation_time' => trim($_POST['reservation_time'] ?? ''),
            'party_size' => (int)($_POST['party_size'] ?? 0),
            'table_id' => trim($_POST['table_id'] ?? ''),
            'notes' => trim($_POST['notes'] ?? '')
        );

        $errors = $this->validate($formData);

        if (empty($errors)) {
            $data = array(
                'id' => generate_uuid(),
                'user_id' => $_SESSION['user_id'] ?? null,
                'table_id' => $formData['table_id'],
                'reservation_time' => str_replace('T', ' ', $formData['reservation_time']) . ':00',
                'guest_count' => $formData['party_size'],
                'guest_name' => $formData['customer_name'],
                'guest_phone' => $formData['customer_phone'],
                'notes' => $formData['notes'],
                'status' => 'confirmed'
            );

            if ($reservationModel->create($data)) {
                $_SESSION['success'] = 'Đã tạo đặt bàn thành công.';
                header('Location: ' . url('/admin/reservations'));
                exit;
            } else {
                $errors[] = 'Đã xảy ra lỗi khi tạo đặt bàn.';
            }
        }

        $tables = $tableModel->all();
        $this->render('admin.reservations.create', array(
            'title' => 'Tạo Đặt bàn mới',
            'tables' => $tables,
            'formData' => $formData,
            'errors' => $errors
        ), 'layouts/admin');
    }

    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('/admin/reservations'));
            exit;
        }

        $id = $_POST['id'] ?? '';
        $newStatus = $_POST['status'] ?? '';
        
        $validStatuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        if (!in_array($newStatus, $validStatuses)) {
            $_SESSION['error'] = 'Trạng thái không hợp lệ.';
            header('Location: ' . url('/admin/reservations'));
            exit;
        }

        $reservationModel = new Reservation();
        $reservation = $reservationModel->find($id);
        
        if (!$reservation) {
            $_SESSION['error'] = 'Không tìm thấy đặt bàn.';
            header('Location: ' . url('/admin/reservations'));
            exit;
        }

        $success = $reservationModel->updateStatus($id, $newStatus);

        // Handle check-in logic (transition to completed)
        if ($success && $newStatus === 'completed') {
            $orderModel = new \App\Models\Order();
            $orderModel->createOrderForCheckin($reservation['table_id'], $_SESSION['user_id'] ?? null);
        }

        // AJAX Support
        if (isset($_GET['format']) && $_GET['format'] === 'json' || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')) {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
            exit;
        }

        if ($success) {
            if ($newStatus === 'completed') {
                // Redirect to Table Map with auto-open parameters
                header('Location: ' . url('/admin/tables?checkin_success=1&table_id=' . $reservation['table_id']));
                exit;
            }
            $_SESSION['success'] = 'Cập nhật trạng thái thành công.';
        } else {
            $_SESSION['error'] = 'Không thể cập nhật trạng thái.';
        }

        header('Location: ' . url('/admin/reservations'));
        exit;
    }

    private function validate($data)
    {
        $errors = [];
        $tableModel = new Table();

        // 1. Tên khách hàng: không trống, độ dài tối đa
        if (empty($data['customer_name'])) {
            $errors[] = 'Tên khách hàng không được để trống.';
        } elseif (mb_strlen($data['customer_name']) > 255) {
            $errors[] = 'Tên khách hàng không được vượt quá 255 ký tự.';
        }

        // 2. Số điện thoại: định dạng Việt Nam
        if (empty($data['customer_phone'])) {
            $errors[] = 'Số điện thoại không được để trống.';
        } elseif (!$this->isValidVietnamesePhone($data['customer_phone'])) {
            $errors[] = 'Số điện thoại không hợp lệ (định dạng 0xxxxxxxxx).';
        }

        // 3. Thời gian đặt bàn: không trống, phải ở tương lai
        if (empty($data['reservation_time'])) {
            $errors[] = 'Thời gian đặt bàn không được để trống.';
        } else {
            $resTime = str_replace('T', ' ', $data['reservation_time']);
            if (strtotime($resTime) < time()) {
                $errors[] = 'Thời gian đặt bàn phải ở tương lai.';
            }
        }

        // 4. Số lượng khách: từ 1 đến 50
        $partySize = $this->parseIntInRange($data['party_size'], 1, 50);
        if ($partySize === false) {
            $errors[] = 'Số lượng khách phải là số từ 1 đến 50.';
        }

        // 5. Bàn: UUID hợp lệ, tồn tại trong DB, đủ sức chứa
        if (empty($data['table_id'])) {
            $errors[] = 'Vui lòng chọn bàn.';
        } elseif (!$this->isValidUuid($data['table_id'])) {
            $errors[] = 'Mã bàn không hợp lệ.';
        } else {
            $table = $tableModel->find($data['table_id']);
            if (!$table) {
                $errors[] = 'Bàn đã chọn không tồn tại.';
            } else {
                if ($partySize !== false && (int)$table['capacity'] < $partySize) {
                    $errors[] = "Bàn số {$table['table_number']} chỉ chứa được tối đa {$table['capacity']} người.";
                }
            }
        }

        return $errors;
    }
}
