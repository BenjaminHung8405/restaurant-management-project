<?php

namespace App\Models;

class Reservation extends BaseModel
{
    protected $table = 'reservations';

    public function find($id)
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
        $statement = $this->db->prepare($sql);
        $statement->execute(array('id' => $id));
        return $statement->fetch();
    }

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

    public function getAllWithDetails($limitToCurrentAndFuture = true)
    {
        $whereClause = '';
        if ($limitToCurrentAndFuture) {
            $whereClause = ' WHERE DATE(r.reservation_time) >= CURDATE() ';
        }

        $sql = '
            SELECT
                r.*,
                t.table_number
            FROM reservations r
            LEFT JOIN tables t ON t.id = r.table_id
            ' . $whereClause . '
            ORDER BY 
                CASE 
                    WHEN r.status IN ("pending", "confirmed") THEN 1 
                    ELSE 2 
                END,
                r.reservation_time ASC
        ';
        return $this->db->query($sql)->fetchAll();
    }

    public function updateStatus($id, $status)
    {
        $sql = 'UPDATE ' . $this->table . ' SET status = :status, updated_at = NOW() WHERE id = :id';
        $statement = $this->db->prepare($sql);
        return $statement->execute(array('status' => $status, 'id' => $id));
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $statement = $this->db->prepare($sql);
        return $statement->execute(array('id' => $id));
    }
}
