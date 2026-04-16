<?php

namespace App\Models;

class Order extends BaseModel
{
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

    private function uuid()
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
