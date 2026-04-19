<?php

namespace App\Controllers;

use App\Core\Database;
use PDO;
use Throwable;

class KitchenController extends AdminBaseController
{
    public function index()
    {
        // Standalone view, so we pass null as the third argument (layout)
        $this->render('admin/kitchen/index', [
            'title' => 'Kitchen Display System'
        ], null);
    }

    public function getPendingOrders()
    {
        header('Content-Type: application/json');

        try {
            $db = Database::connection();
            $sql = "SELECT 
                        o.id as order_id, 
                        o.created_at, 
                        t.table_number,
                        oi.id as item_id,
                        oi.quantity,
                        oi.notes,
                        oi.status,
                        mi.name as menu_item_name
                    FROM orders o
                    JOIN tables t ON o.table_id = t.id
                    JOIN order_items oi ON o.id = oi.order_id
                    JOIN menu_items mi ON oi.menu_item_id = mi.id
                    WHERE oi.status IN ('pending', 'cooking')
                    AND (
                        o.order_status IN ('pending', 'preparing', 'serving')
                        OR o.created_at >= DATE_SUB(NOW(), INTERVAL 18 HOUR)
                    )
                    ORDER BY o.created_at ASC";
            
            $statement = $db->query($sql);
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);

            $orders = [];
            foreach ($results as $row) {
                $orderId = $row['order_id'];
                if (!isset($orders[$orderId])) {
                    $orders[$orderId] = [
                        'order_id' => $orderId,
                        'created_at' => $row['created_at'],
                        'table_number' => $row['table_number'],
                        'items' => []
                    ];
                }
                $orders[$orderId]['items'][] = [
                    'id' => $row['item_id'],
                    'name' => $row['menu_item_name'],
                    'quantity' => $row['quantity'],
                    'notes' => $row['notes'],
                    'status' => $row['status']
                ];
            }

            echo json_encode(array_values($orders));
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function markItemCooking()
    {
        header('Content-Type: application/json');

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $itemId = $data['order_item_id'] ?? null;

        if (!$itemId) {
            http_response_code(400);
            echo json_encode(['error' => 'Order item ID is required.']);
            return;
        }

        try {
            $db = \App\Core\Database::connection();
            $db->beginTransaction();
            
            // Update item status to 'cooking' ONLY if it's currently 'pending'
            $sql = "UPDATE order_items SET status = 'cooking' WHERE id = :id AND status = 'pending'";
            $stmt = $db->prepare($sql);
            $stmt->execute(['id' => $itemId]);

            if ($stmt->rowCount() > 0) {
                $db->commit();
                echo json_encode(['success' => true]);
            } else {
                $db->rollBack();
                http_response_code(404);
                echo json_encode(['error' => 'Item not found or not in pending status.']);
            }
        } catch (\Throwable $e) {
            if ($db->inTransaction()) $db->rollBack();
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function markItemDone()
    {
        header('Content-Type: application/json');

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $itemId = $data['order_item_id'] ?? null;

        if (!$itemId) {
            http_response_code(400);
            echo json_encode(['error' => 'Order item ID is required.']);
            return;
        }

        try {
            $db = Database::connection();
            $db->beginTransaction();
            
            // 1. Get order_id before updating
            $sqlGetOrder = "SELECT order_id FROM order_items WHERE id = :id";
            $stmtGetOrder = $db->prepare($sqlGetOrder);
            $stmtGetOrder->execute(['id' => $itemId]);
            $orderItem = $stmtGetOrder->fetch(PDO::FETCH_ASSOC);

            if (!$orderItem) {
                $db->rollBack();
                http_response_code(404);
                echo json_encode(['error' => 'Order item not found.']);
                return;
            }

            $orderId = $orderItem['order_id'];

            // 2. Update item status to 'done'
            $sqlUpdateItem = "UPDATE order_items SET status = 'done' WHERE id = :id";
            $stmtUpdateItem = $db->prepare($sqlUpdateItem);
            $stmtUpdateItem->execute(['id' => $itemId]);

            // 3. Check for remaining pending items in this order
            $sqlCountPending = "SELECT COUNT(*) FROM order_items WHERE order_id = :order_id AND status = 'pending'";
            $stmtCountPending = $db->prepare($sqlCountPending);
            $stmtCountPending->execute(['order_id' => $orderId]);
            $pendingCount = $stmtCountPending->fetchColumn();

            $orderUpdated = false;
            if ($pendingCount == 0) {
                // 4. Update order status to 'serving'
                $sqlUpdateOrder = "UPDATE orders SET order_status = 'serving' WHERE id = :order_id";
                $stmtUpdateOrder = $db->prepare($sqlUpdateOrder);
                $stmtUpdateOrder->execute(['order_id' => $orderId]);
                $orderUpdated = true;
            }

            $db->commit();
            echo json_encode([
                'success' => true,
                'order_id' => $orderId,
                'all_done' => ($pendingCount == 0),
                'order_updated_to_serving' => $orderUpdated
            ]);

        } catch (Throwable $e) {
            if ($db->inTransaction()) $db->rollBack();
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
