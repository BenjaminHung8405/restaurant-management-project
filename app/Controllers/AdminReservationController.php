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

        $formData = $this->buildAdminReservationFormData($_POST);
        $reservationDateTime = null;
        $errors = $this->validateAdminReservationFormData($tableModel, $formData, $reservationDateTime);

        if (empty($errors)) {
            try {
                $reservationId = generate_uuid();
                
                $result = $reservationModel->create(array(
                    'id' => $reservationId,
                    'user_id' => $_SESSION['user']['user_id'] ?? ($_SESSION['user_id'] ?? null),
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

    private function buildAdminReservationFormData($input)
    {
        return array(
            'guest_name' => trim((string) ($input['guest_name'] ?? '')),
            'guest_phone' => $this->normalizePhone($input['guest_phone'] ?? ''),
            'reservation_date' => trim((string) ($input['reservation_date'] ?? '')),
            'reservation_time' => trim((string) ($input['reservation_time'] ?? '')),
            'guest_count' => trim((string) ($input['guest_count'] ?? '')),
            'table_id' => trim((string) ($input['table_id'] ?? '')),
            'notes' => trim((string) ($input['notes'] ?? ''))
        );
    }

    private function validateAdminReservationFormData(Table $tableModel, &$formData, &$reservationDateTime)
    {
        $errors = array();

        if ($formData['guest_name'] === '') {
            $errors[] = 'Vui lòng nhập tên khách hàng.';
        } elseif (mb_strlen($formData['guest_name']) > 255) {
            $errors[] = 'Tên khách hàng không được vượt quá 255 ký tự.';
        }

        if ($formData['guest_phone'] === '') {
            $errors[] = 'Vui lòng nhập số điện thoại.';
        } elseif (!$this->isValidVietnamesePhone($formData['guest_phone'])) {
            $errors[] = 'Số điện thoại không hợp lệ.';
        }

        $guestCount = $this->parseIntInRange($formData['guest_count'], 1, 50);
        if ($guestCount === false) {
            $errors[] = 'Số lượng khách phải là số nguyên từ 1 đến 50.';
        } else {
            $formData['guest_count'] = $guestCount;
        }

        if ($formData['table_id'] === '') {
            $errors[] = 'Vui lòng chọn bàn được gán.';
        } elseif (!$this->isValidUuid($formData['table_id'])) {
            $errors[] = 'Mã bàn không hợp lệ.';
        }

        if (mb_strlen($formData['notes']) > 1000) {
            $errors[] = 'Ghi chú không được vượt quá 1000 ký tự.';
        }

        if ($formData['reservation_date'] === '') {
            $errors[] = 'Vui lòng chọn ngày đặt bàn.';
        }

        if ($formData['reservation_time'] === '') {
            $errors[] = 'Vui lòng chọn giờ đặt bàn.';
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

        if ($formData['table_id'] !== '' && $this->isValidUuid($formData['table_id'])) {
            // Kiểm tra FK và sức chứa bàn trước khi ghi DB để tránh lỗi ràng buộc.
            $table = $tableModel->find($formData['table_id']);
            if (!$table) {
                $errors[] = 'Bàn được chọn không tồn tại.';
            } else {
                if (($table['status'] ?? '') !== 'available') {
                    $errors[] = 'Bàn được chọn hiện không ở trạng thái sẵn sàng.';
                }

                if ($guestCount !== false && (int) ($table['capacity'] ?? 0) < $guestCount) {
                    $errors[] = 'Số khách vượt quá sức chứa của bàn được chọn.';
                }
            }
        }

        return $errors;
    }
}
