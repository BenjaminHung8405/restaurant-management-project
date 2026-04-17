<?php

namespace App\Controllers;

use App\Models\Reservation;
use App\Models\Table;
use Throwable;

class AdminReservationController extends AdminBaseController
{
    public function create()
    {
        $tableModel = new Table();
        $tables = $tableModel->getAllAvailable();

        $this->render('admin.reservations.create', array(
            'title' => 'Tạo Đặt bàn mới',
            'tables' => $tables,
            'formData' => array(
                'guest_name' => '',
                'guest_phone' => '',
                'reservation_date' => date('Y-m-d'),
                'reservation_time' => date('H:i'),
                'guest_count' => 2,
                'table_id' => '',
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
            'guest_name' => trim((string) ($_POST['guest_name'] ?? '')),
            'guest_phone' => trim((string) ($_POST['guest_phone'] ?? '')),
            'reservation_date' => trim((string) ($_POST['reservation_date'] ?? '')),
            'reservation_time' => trim((string) ($_POST['reservation_time'] ?? '')),
            'guest_count' => (int) ($_POST['guest_count'] ?? 0),
            'table_id' => trim((string) ($_POST['table_id'] ?? '')),
            'notes' => trim((string) ($_POST['notes'] ?? ''))
        );

        $errors = array();
        if ($formData['guest_name'] === '') $errors[] = 'Vui lòng nhập tên khách hàng.';
        if ($formData['guest_phone'] === '') $errors[] = 'Vui lòng nhập số điện thoại.';
        if ($formData['reservation_date'] === '') $errors[] = 'Vui lòng chọn ngày đặt bàn.';
        if ($formData['reservation_time'] === '') $errors[] = 'Vui lòng chọn giờ đặt bàn.';
        if ($formData['guest_count'] <= 0) $errors[] = 'Số lượng khách phải lớn hơn 0.';
        if ($formData['table_id'] === '') $errors[] = 'Vui lòng chọn bàn được gán.';

        $reservationDateTime = null;
        if ($formData['reservation_date'] !== '' && $formData['reservation_time'] !== '') {
            $reservationDateTime = $formData['reservation_date'] . ' ' . $formData['reservation_time'] . ':00';
            
            // Check if reservation time is in the past
            if (strtotime($reservationDateTime) < time()) {
                $errors[] = 'Thời gian đặt bàn không được ở quá khứ.';
            }
        }

        if (empty($errors)) {
            try {
                $reservationId = generate_uuid();
                
                $result = $reservationModel->create(array(
                    'id' => $reservationId,
                    'user_id' => $_SESSION['user']['id'] ?? null,
                    'table_id' => $formData['table_id'],
                    'reservation_time' => $reservationDateTime,
                    'guest_count' => $formData['guest_count'],
                    'guest_name' => $formData['guest_name'],
                    'guest_phone' => $formData['guest_phone'],
                    'notes' => $formData['notes'] !== '' ? $formData['notes'] : null,
                    'status' => 'confirmed'
                ));

                if ($result) {
                    $_SESSION['admin_reservation_success'] = 'Đặt bàn đã được tạo và xác nhận thành công.';
                    header('Location: ' . url('/admin/orders')); // Fallback redirect
                    exit;
                } else {
                    $errors[] = 'Không thể lưu thông tin đặt bàn vào cơ sở dữ liệu.';
                }

            } catch (Throwable $e) {
                error_log('Admin Reservation Store Error: ' . $e->getMessage());
                $errors[] = 'Đã xảy ra lỗi trong quá trình xử lý: ' . $e->getMessage();
            }
        }

        // If we reach here, there were errors
        $tables = $tableModel->getAllAvailable();
        $this->render('admin.reservations.create', array(
            'title' => 'Tạo Đặt bàn mới',
            'tables' => $tables,
            'formData' => $formData,
            'errors' => $errors
        ), 'layouts/admin');
    }
}
