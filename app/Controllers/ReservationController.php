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
        $this->render('reservations/create', array(
            'title' => 'Đặt bàn nhanh',
            'formData' => array(
                'guest_name' => '',
                'guest_phone' => '',
                'reservation_date' => date('Y-m-d'),
                'reservation_time' => '',
                'party_size' => 2,
                'notes' => ''
            )
        ));
    }

    public function store()
    {
        $reservationModel = new Reservation();

        $formData = $this->buildReservationFormData($_POST);
        $reservationDateTime = null;
        $errors = $this->validateReservationFormData($formData, $reservationDateTime);

        if (empty($errors)) {
            try {
                $reservationId = $this->uuid();
                
                $reservationModel->create(array(
                    'id' => $reservationId,
                    'user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null,
                    'table_id' => null, 
                    'reservation_time' => $reservationDateTime,
                    'guest_count' => $formData['party_size'],
                    'guest_name' => $formData['guest_name'],
                    'guest_phone' => $formData['guest_phone'],
                    'notes' => $formData['notes'] !== '' ? $formData['notes'] : null,
                    'status' => 'pending'
                ));

                $this->render('reservations/success', array(
                    'title' => 'Đặt bàn thành công',
                    'reservationId' => $reservationId
                ));
                return;

            } catch (Throwable $e) {
                error_log('Reservation error: ' . $e->getMessage());
                $errors[] = 'Đặt bàn thất bại. Vui lòng thử lại.';
            }
        }

        $this->render('reservations/create', array(
            'title' => 'Đặt bàn nhanh',
            'formData' => $formData,
            'errors' => $errors
        ));
    }
    public function apiStore()
    {
        // Set header to JSON
        header('Content-Type: application/json');

        // Read JSON input
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(['error' => 'Dữ liệu không hợp lệ.']);
            return;
        }

        $formData = $this->buildReservationFormData($data);
        $reservationDateTime = null;
        $errors = $this->validateReservationFormData($formData, $reservationDateTime);

        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode([
                'error' => $errors[0],
                'errors' => $errors
            ]);
            return;
        }

        try {
            $reservationModel = new Reservation();
            $reservationId = $this->uuid();
            
            $reservationModel->create(array(
                'id' => $reservationId,
                'user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null,
                'table_id' => null,
                'reservation_time' => $reservationDateTime,
                'guest_count' => $formData['party_size'],
                'guest_name' => $formData['guest_name'],
                'guest_phone' => $formData['guest_phone'],
                'notes' => $formData['notes'] !== '' ? $formData['notes'] : null,
                'status' => 'pending'
            ));

            echo json_encode(['success' => true, 'message' => 'Đặt bàn thành công!', 'id' => $reservationId]);
        } catch (Throwable $e) {
            error_log('API Reservation error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Không thể đặt bàn lúc này. Vui lòng thử lại sau.']);
        }
    }

    private function buildReservationFormData($input)
    {
        return array(
            'guest_name' => trim((string) ($input['guest_name'] ?? '')),
            'guest_phone' => $this->normalizePhone($input['guest_phone'] ?? ''),
            'reservation_date' => trim((string) ($input['reservation_date'] ?? '')),
            'reservation_time' => trim((string) ($input['reservation_time'] ?? '')),
            'party_size' => trim((string) ($input['party_size'] ?? '')),
            'notes' => trim((string) ($input['notes'] ?? ''))
        );
    }

    private function validateReservationFormData(&$formData, &$reservationDateTime)
    {
        $errors = array();

        if ($formData['guest_name'] === '') {
            $errors[] = 'Vui lòng nhập tên khách.';
        } elseif (mb_strlen($formData['guest_name']) > 255) {
            $errors[] = 'Tên khách không được vượt quá 255 ký tự.';
        }

        if ($formData['guest_phone'] === '') {
            $errors[] = 'Vui lòng nhập số điện thoại.';
        } elseif (!$this->isValidVietnamesePhone($formData['guest_phone'])) {
            $errors[] = 'Số điện thoại không hợp lệ.';
        }

        if ($formData['reservation_date'] === '') {
            $errors[] = 'Vui lòng chọn ngày đặt bàn.';
        }

        if ($formData['reservation_time'] === '') {
            $errors[] = 'Vui lòng chọn giờ đặt bàn.';
        }

        $partySize = $this->parseIntInRange($formData['party_size'], 1, 50);
        if ($partySize === false) {
            $errors[] = 'Số lượng khách phải là số nguyên từ 1 đến 50.';
        } else {
            $formData['party_size'] = $partySize;
        }

        if (mb_strlen($formData['notes']) > 1000) {
            $errors[] = 'Ghi chú không được vượt quá 1000 ký tự.';
        }

        if ($formData['reservation_date'] !== '' && $formData['reservation_time'] !== '') {
            $dateTimeError = null;
            $reservationDateTime = $this->parseReservationDateTime(
                $formData['reservation_date'],
                $formData['reservation_time'],
                $dateTimeError
            );

            if ($reservationDateTime === null) {
                $errors[] = $dateTimeError ?: 'Thời gian đặt bàn không hợp lệ.';
            } elseif (strtotime($reservationDateTime) < time()) {
                $errors[] = 'Thời gian đặt bàn không được ở quá khứ.';
            }
        }

        return $errors;
    }


    private function uuid()
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
