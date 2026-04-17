<?php

namespace App\Controllers;

use App\Models\Reservation;

class AdminOrderController extends AdminBaseController
{
    public function index()
    {
        $reservationModel = new Reservation();
        
        $stats = $reservationModel->getStats();
        $reservations = $reservationModel->getAllWithDetails();

        $this->render('admin/orders/index', array(
            'title' => 'Quản lý đơn hàng',
            'stats' => $stats,
            'reservations' => $reservations,
            'flashSuccess' => $_SESSION['admin_orders_success'] ?? '',
            'flashError' => $_SESSION['admin_orders_error'] ?? ''
        ), 'layouts/admin');

        unset($_SESSION['admin_orders_success'], $_SESSION['admin_orders_error']);
    }

    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('/admin/orders'));
            exit;
        }

        $reservationId = trim((string) ($_POST['reservation_id'] ?? ''));
        $newStatus = strtolower(trim((string) ($_POST['status'] ?? '')));
        $allowedStatuses = array('confirmed', 'cancelled', 'completed');

        if (!$this->isValidUuid($reservationId) || !in_array($newStatus, $allowedStatuses, true)) {
            $_SESSION['admin_orders_error'] = 'Yêu cầu cập nhật trạng thái không hợp lệ.';
            header('Location: ' . url('/admin/orders'));
            exit;
        }

        $reservationModel = new Reservation();
        $reservation = $reservationModel->find($reservationId);
        if (!$reservation) {
            $_SESSION['admin_orders_error'] = 'Đơn đặt bàn không tồn tại hoặc đã bị xóa.';
            header('Location: ' . url('/admin/orders'));
            exit;
        }

        if (($reservation['status'] ?? '') === $newStatus) {
            $_SESSION['admin_orders_success'] = 'Trạng thái đơn đã ở giá trị yêu cầu.';
            header('Location: ' . url('/admin/orders'));
            exit;
        }

        if ($reservationModel->updateStatus($reservationId, $newStatus)) {
            $_SESSION['admin_orders_success'] = 'Cập nhật trạng thái đặt bàn thành công.';
        } else {
            $_SESSION['admin_orders_error'] = 'Cập nhật trạng thái thất bại.';
        }

        header('Location: ' . url('/admin/orders'));
        exit;
    }
}
