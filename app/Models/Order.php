<?php

namespace App\Models;

class Order extends BaseModel
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_PREPARING = 'preparing';
    public const STATUS_SERVING = 'serving';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $table = 'orders';

    public function create($data)
    {
        $sql = '
            INSERT INTO ' . $this->table . ' (
                id, user_id, table_id, total_amount, order_status, payment_status
            ) VALUES (
                :id, :user_id, :table_id, :total_amount, :order_status, :payment_status
            )
        ';
        
        $statement = $this->db->prepare($sql);
        return $statement->execute($data);
    }

    public function addItems($orderId, $items)
    {
        $sql = '
            INSERT INTO order_items (
                id, order_id, menu_item_id, quantity, unit_price, notes
            ) VALUES (
                :id, :order_id, :menu_item_id, :quantity, :unit_price, :notes
            )
        ';
        
        $statement = $this->db->prepare($sql);
        
        foreach ($items as $item) {
            $statement->execute(array(
                'id' => $this->uuid(),
                'order_id' => $orderId,
                'menu_item_id' => $item['id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'notes' => $item['notes'] ?? null
            ));
        }
    }

    public function find($id)
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE id = :id';
        $statement = $this->db->prepare($sql);
        $statement->execute(array('id' => $id));
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function getAllWithDetails($filters = array())
    {
        $whereClause = '';
        $params = array();

        if (!empty($filters['table_id'])) {
            $whereClause = ' WHERE o.table_id = :table_id';
            $params['table_id'] = $filters['table_id'];
        }

        $sql = '
            SELECT 
                o.*, 
                t.table_number,
                u.full_name as customer_name
            FROM ' . $this->table . ' o
            JOIN tables t ON o.table_id = t.id
            LEFT JOIN users u ON o.user_id = u.id' . 
            $whereClause . '
            ORDER BY o.created_at DESC
        ';
        
        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $newStatus, $currentStatus)
    {
        // Concurrency handling: only update if status matches $currentStatus
        // Also atomically set payment_status to 'paid' if transitioning to 'completed'
        $sql = '
            UPDATE ' . $this->table . ' 
            SET 
                order_status = :new_status,
                payment_status = IF(:new_status = "completed", "paid", payment_status)
            WHERE id = :id AND order_status = :current_status
        ';
        
        $statement = $this->db->prepare($sql);
        $success = $statement->execute(array(
            'new_status' => $newStatus,
            'id' => $id,
            'current_status' => $currentStatus
        ));

        return $success && $statement->rowCount() > 0;
    }

    public function cleanupOldOrders()
    {
        // Cancel all pending or preparing orders from previous days
        $sql = '
            UPDATE ' . $this->table . ' 
            SET order_status = "cancelled" 
            WHERE order_status IN ("pending", "preparing", "serving") 
            AND created_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)
        ';
        
        $statement = $this->db->prepare($sql);
        return $statement->execute();
    }

    public function getItems($orderId)
    {
        $sql = '
            SELECT 
                oi.*, 
                mi.name as menu_item_name,
                mi.image_url
            FROM order_items oi
            JOIN menu_items mi ON oi.menu_item_id = mi.id
            WHERE oi.order_id = :order_id
        ';
        
        $statement = $this->db->prepare($sql);
        $statement->execute(array('order_id' => $orderId));
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Delta-Sync Algorithm for POS Order Items.
     * Strictly synchronizes database state with payload items while protecting non-pending items.
     */
    public function syncOrderItems($orderId, $payloadItems)
    {
        // 1. Fetch current items in DB
        $sqlFetch = "SELECT * FROM order_items WHERE order_id = :order_id";
        $stmtFetch = $this->db->prepare($sqlFetch);
        $stmtFetch->execute(['order_id' => $orderId]);
        $dbItems = $stmtFetch->fetchAll(\PDO::FETCH_ASSOC);

        // 2. Create lookup map for payload items
        $payloadMap = [];
        foreach ($payloadItems as $pItem) {
            $key = $pItem['id'] . '_' . md5(trim($pItem['notes'] ?? ''));
            $payloadMap[$key] = $pItem;
        }

        // 3. Loop through current DB items
        foreach ($dbItems as $dbItem) {
            $key = $dbItem['menu_item_id'] . '_' . md5(trim($dbItem['notes'] ?? ''));

            if ($dbItem['status'] !== 'pending') {
                // RULE: Items that are 'cooking' or 'done' are IMMUTABLE.
                // We preserve the database state and subtract from payload to avoid duplicates.
                if (isset($payloadMap[$key])) {
                    // Logic Adjustment: Decrement quantity in map so we only add/update what's missing.
                    $payloadMap[$key]['quantity'] -= $dbItem['quantity'];
                    if ($payloadMap[$key]['quantity'] <= 0) {
                        unset($payloadMap[$key]);
                    }
                }
            } else {
                // Item is pending
                if (isset($payloadMap[$key]) && $payloadMap[$key]['quantity'] > 0) {
                    // UPDATE quantity. Adjusting for cooking items if any.
                    $sqlUpd = "UPDATE order_items SET quantity = :qty WHERE id = :id";
                    $stmtUpd = $this->db->prepare($sqlUpd);
                    $stmtUpd->execute([
                        'qty' => $payloadMap[$key]['quantity'],
                        'id' => $dbItem['id']
                    ]);
                    unset($payloadMap[$key]);
                } else {
                    // Staff removed it or it's fully covered by cooking items
                    $sqlDel = "DELETE FROM order_items WHERE id = :id";
                    $stmtDel = $this->db->prepare($sqlDel);
                    $stmtDel->execute(['id' => $dbItem['id']]);
                }
            }
        }

        // 4. INSERT remaining items in payload map as 'pending'
        foreach ($payloadMap as $pItem) {
            if ($pItem['quantity'] > 0) {
                $sqlIns = "INSERT INTO order_items (id, order_id, menu_item_id, quantity, unit_price, status, notes) 
                           VALUES (:id, :order_id, :menu_item_id, :quantity, :unit_price, 'pending', :notes)";
                $stmtIns = $this->db->prepare($sqlIns);
                $stmtIns->execute([
                    'id' => $this->uuid(),
                    'order_id' => $orderId,
                    'menu_item_id' => $pItem['id'],
                    'quantity' => $pItem['quantity'],
                    'unit_price' => $pItem['price'],
                    'notes' => $pItem['notes'] ?? null
                ]);
            }
        }

        // 5. Finally, recalculate the total_amount of the orders table
        $sqlTotal = "SELECT SUM(quantity * unit_price) as total FROM order_items WHERE order_id = :order_id";
        $stmtTotal = $this->db->prepare($sqlTotal);
        $stmtTotal->execute(['order_id' => $orderId]);
        $result = $stmtTotal->fetch(\PDO::FETCH_ASSOC);
        $newTotal = $result['total'] ?? 0;

        $sqlUpdTotal = "UPDATE orders SET total_amount = :total WHERE id = :id";
        $stmtUpdTotal = $this->db->prepare($sqlUpdTotal);
        $stmtUpdTotal->execute(['total' => $newTotal, 'id' => $orderId]);
    }


    /**
     * Automatically creates a pending order for a table during check-in
     * if no active order already exists.
     */
    public function createOrderForCheckin($tableId, $staffId)
    {
        // 1. Check if active order already exists
        $sqlCheck = "SELECT id FROM " . $this->table . " 
                     WHERE table_id = :table_id 
                     AND order_status IN ('pending', 'preparing', 'serving') 
                     LIMIT 1";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->execute(['table_id' => $tableId]);
        $existingOrder = $stmtCheck->fetch(\PDO::FETCH_ASSOC);

        if ($existingOrder) {
            return $existingOrder['id'];
        }

        // 2. Create new empty order
        $orderId = $this->uuid();
        $data = [
            'id' => $orderId,
            'user_id' => $staffId,
            'table_id' => $tableId,
            'total_amount' => 0,
            'order_status' => self::STATUS_PENDING,
            'payment_status' => 'unpaid'
        ];

        if ($this->create($data)) {
            return $orderId;
        }

        return false;
    }

    private function uuid()
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf("%s%s-%s-%s-%s-%s%s%s", str_split(bin2hex($data), 4));
    }
}
