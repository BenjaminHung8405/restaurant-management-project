<?php

namespace App\Controllers;

use App\Models\Order;
use Throwable;

class AdminOrderController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        \App\Middlewares\AuthMiddleware::requireRole(['admin', 'cashier']);
    }

    private const STATUS_FLOW = array(
        'pending' => array('preparing', 'cancelled'),
        'preparing' => array('serving'),
        'serving' => array('completed'),
        'completed' => array(),
        'cancelled' => array()
    );

    public function index()
    {
        $this->render('admin/orders/index', array(
            'title' => 'Quản lý đơn hàng',
            'flashSuccess' => $_SESSION['admin_orders_success'] ?? '',
            'flashError' => $_SESSION['admin_orders_error'] ?? ''
        ), 'layouts/admin');

        unset($_SESSION['admin_orders_success'], $_SESSION['admin_orders_error']);
    }

    public function getOrdersAjax()
    {
        header('Content-Type: application/json');
        
        $tableId = $_GET['table_id'] ?? null;
        $filters = array();
        if ($tableId && $this->isValidUuid($tableId)) {
            $filters['table_id'] = $tableId;
        }

        try {
            $orderModel = new Order();
            $orders = $orderModel->getAllWithDetails($filters);
            
            echo json_encode(array(
                'success' => true,
                'data' => $orders
            ));
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(array(
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ));
        }
    }

    public function getOrderItemsAjax()
    {
        header('Content-Type: application/json');
        
        $orderId = $_GET['id'] ?? '';
        if (!$this->isValidUuid($orderId)) {
            http_response_code(400);
            echo json_encode(array('success' => false, 'message' => 'ID đơn hàng không hợp lệ.'));
            return;
        }

        try {
            $orderModel = new Order();
            $items = $orderModel->getItems($orderId);
            
            echo json_encode(array(
                'success' => true,
                'data' => $items
            ));
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(array(
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ));
        }
    }

    public function updateStatus()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(array('success' => false, 'message' => 'Method not allowed'));
            return;
        }

        $orderId = $_POST['order_id'] ?? '';
        $newStatus = $_POST['status'] ?? '';
        $currentStatus = $_POST['current_status'] ?? '';

        // 1. Validate ID
        if (!$this->isValidUuid($orderId)) {
            echo json_encode(array('success' => false, 'message' => 'ID đơn hàng không hợp lệ.'));
            return;
        }

        // 2. Validate Transition
        if (!isset(self::STATUS_FLOW[$currentStatus]) || !in_array($newStatus, self::STATUS_FLOW[$currentStatus], true)) {
            echo json_encode(array(
                'success' => false, 
                'message' => "Chuyển đổi trạng thái từ '$currentStatus' sang '$newStatus' không hợp lệ."
            ));
            return;
        }

        try {
            $orderModel = new Order();
            
            // 3. Atomic Update with Concurrency Check
            $updated = $orderModel->updateStatus($orderId, $newStatus, $currentStatus);

            if ($updated) {
                echo json_encode(array(
                    'success' => true,
                    'message' => 'Cập nhật trạng thái thành công.'
                ));
            } else {
                // Means the status has already changed (Race condition)
                $order = $orderModel->find($orderId);
                $latestStatus = $order['order_status'] ?? 'unknown';
                
                echo json_encode(array(
                    'success' => false,
                    'message' => "Không thể cập nhật. Trạng thái hiện tại đã thay đổi thành '$latestStatus'. Vui lòng làm mới trang.",
                    'latest_status' => $latestStatus
                ));
            }
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(array(
                'success' => false,
                'message' => 'Lỗi database: ' . $e->getMessage()
            ));
        }
    }

    public function cleanup()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(array('success' => false, 'message' => 'Method not allowed'));
            return;
        }

        try {
            $orderModel = new Order();
            $orderModel->cleanupOldOrders();

            echo json_encode(array(
                'success' => true,
                'message' => 'Đã dọn dẹp các đơn hàng cũ thành công.'
            ));
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(array(
                'success' => false,
                'message' => 'Lỗi khi dọn dẹp: ' . $e->getMessage()
            ));
        }
    }
}
