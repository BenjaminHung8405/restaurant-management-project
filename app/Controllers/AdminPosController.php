<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Meal;
use App\Models\Order;
use App\Models\Table;
use Throwable;

class AdminPosController extends AdminBaseController
{
    /**
     * GET /admin/api/pos/menu
     * Returns categories and available menu items
     */
    public function menu()
    {
        header('Content-Type: application/json');
        try {
            $mealModel = new Meal();
            $categoryModel = new Category();
            
            $categories = $categoryModel->all();
            $items = $mealModel->getAllAvailable();

            echo json_encode([
                'categories' => $categories,
                'items' => $items
            ]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * GET /admin/api/pos/order?table_id=X
     * Returns active order and its items for a table
     */
    public function getOrder()
    {
        header('Content-Type: application/json');
        $tableId = $_GET['table_id'] ?? null;
        if (!$tableId) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing table_id']);
            return;
        }

        try {
            $orderModel = new Order();
            // Find active order for table
            $sql = "SELECT * FROM orders WHERE table_id = :table_id AND order_status IN ('pending', 'preparing', 'serving') LIMIT 1";
            $db = \App\Core\Database::connection();
            $stmt = $db->prepare($sql);
            $stmt->execute(['table_id' => $tableId]);
            $order = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($order) {
                $items = $orderModel->getItems($order['id']);
                echo json_encode([
                    'order' => $order,
                    'items' => $items
                ]);
            } else {
                echo json_encode([
                    'order' => null,
                    'items' => []
                ]);
            }
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * POST /admin/api/pos/order/save
     * Creates or updates an order for a table using Delta Semantics
     */
    public function saveOrder()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        
        $tableId = $data['table_id'] ?? null;
        $items = $data['items'] ?? [];
        
        if (!$tableId) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing table_id']);
            return;
        }

        try {
            $db = \App\Core\Database::connection();
            $db->beginTransaction();

            $orderModel = new Order();
            
            // 1. Get or Create Order (If new, status = 'preparing'. If existing and 'pending', upgrade to 'preparing')
            $sql = "SELECT id, order_status FROM orders WHERE table_id = :table_id AND order_status IN ('pending', 'preparing', 'serving') LIMIT 1";
            $stmt = $db->prepare($sql);
            $stmt->execute(['table_id' => $tableId]);
            $existingOrder = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($existingOrder) {
                $orderId = $existingOrder['id'];
                // Upgrade to 'preparing' if it was 'pending'
                if ($existingOrder['order_status'] === 'pending') {
                    $sqlUpd = "UPDATE orders SET order_status = 'preparing' WHERE id = :id";
                    $db->prepare($sqlUpd)->execute(['id' => $orderId]);
                }
            } else {
                $orderId = $this->uuid();
                $orderModel->create([
                    'id' => $orderId,
                    'user_id' => $_SESSION['user_id'] ?? ($_SESSION['user']['id'] ?? null),
                    'table_id' => $tableId,
                    'total_amount' => 0, // Will be set by syncOrderItems
                    'order_status' => 'preparing',
                    'payment_status' => 'unpaid'
                ]);
            }

            // 2. $this->orderModel->syncOrderItems($orderId, $items);
            $orderModel->syncOrderItems($orderId, $items);

            // 3. $this->db->commit();
            $db->commit();

            echo json_encode(['success' => true, 'order_id' => $orderId]);
        } catch (Throwable $e) {
            if (isset($db) && $db->inTransaction()) $db->rollBack();
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * POST /admin/api/pos/order/checkout
     * Finalizes order and sets table to cleaning
     */
    public function checkout()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        $orderId = $data['order_id'] ?? null;
        $tableId = $data['table_id'] ?? null;

        if (!$orderId || !$tableId) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing order_id or table_id']);
            return;
        }

        try {
            $db = \App\Core\Database::connection();
            $db->beginTransaction();

            // Mark order as completed
            $sql = "UPDATE orders SET order_status = 'completed', payment_status = 'paid' WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute(['id' => $orderId]);

            // Set table to cleaning
            $tableModel = new Table();
            $tableModel->updateStatus($tableId, 'cleaning');

            $db->commit();
            echo json_encode(['success' => true]);
        } catch (Throwable $e) {
            if ($db->inTransaction()) $db->rollBack();
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * POST /admin/api/tables/clean
     * Resets table to available
     */
    public function cleanTable()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        $tableId = $data['table_id'] ?? null;

        if (!$tableId) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing table_id']);
            return;
        }

        try {
            $tableModel = new Table();
            $tableModel->updateStatus($tableId, 'available');
            echo json_encode(['success' => true]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function uuid()
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf("%s%s-%s-%s-%s-%s%s%s", str_split(bin2hex($data), 4));
    }
}
