<?php

namespace App\Models;

class Reservation extends BaseModel
{
    protected $table = 'reservations';

    public function create($data)
    {
        $sql = '
            INSERT INTO ' . $this->table . ' (
                id, user_id, table_id, reservation_time, guest_count, 
                guest_name, guest_phone, notes, status
            ) VALUES (
                :id, :user_id, :table_id, :reservation_time, :guest_count, 
                :guest_name, :guest_phone, :notes, :status
            )
        ';
        
        $statement = $this->db->prepare($sql);
        return $statement->execute($data);
    }

    public function getByTableIdAndTime($tableId, $time)
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE table_id = :table_id AND reservation_time = :time LIMIT 1';
        $statement = $this->db->prepare($sql);
        $statement->execute(array('table_id' => $tableId, 'time' => $time));
        return $statement->fetch();
    }

    public function getStats()
    {
        $stats = array();
        
        $stats['total'] = (int) $this->db->query('SELECT COUNT(*) FROM reservations')->fetchColumn();
        
        $sqlPendingToday = 'SELECT COUNT(*) FROM reservations WHERE status = "pending" AND DATE(reservation_time) = CURDATE()';
        $stats['pending_today'] = (int) $this->db->query($sqlPendingToday)->fetchColumn();
        
        $sqlCompleted = 'SELECT COUNT(*) FROM reservations WHERE status = "completed"';
        $stats['completed'] = (int) $this->db->query($sqlCompleted)->fetchColumn();
        
        return $stats;
    }

    public function getAllWithDetails()
    {
        $sql = '
            SELECT
                r.*,
                t.table_number,
                o.id AS order_id,
                o.total_amount AS order_total,
                o.order_status,
                o.payment_status
            FROM reservations r
            LEFT JOIN tables t ON t.id = r.table_id
            LEFT JOIN (
                SELECT o1.id, o1.table_id, o1.total_amount, o1.order_status, o1.payment_status, o1.created_at
                FROM orders o1
                INNER JOIN (
                    SELECT table_id, MAX(created_at) AS latest_created_at
                    FROM orders
                    GROUP BY table_id
                ) latest ON latest.table_id = o1.table_id AND latest.latest_created_at = o1.created_at
            ) o ON o.table_id = r.table_id
            ORDER BY r.created_at DESC
        ';
        return $this->db->query($sql)->fetchAll();
    }

    public function updateStatus($id, $status)
    {
        $sql = 'UPDATE ' . $this->table . ' SET status = :status, updated_at = NOW() WHERE id = :id';
        $statement = $this->db->prepare($sql);
        return $statement->execute(array('status' => $status, 'id' => $id));
    }
}
