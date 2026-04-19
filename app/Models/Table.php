<?php

namespace App\Models;

class Table extends BaseModel
{
    protected $table = 'tables';

    public function all()
    {
        $sql = 'SELECT * FROM ' . $this->table . ' ORDER BY table_number ASC';
        return $this->db->query($sql)->fetchAll();
    }

    public function getAllAvailable()
    {
        $sql = "
            SELECT t.id, t.table_number, t.capacity, t.status 
            FROM {$this->table} t
            LEFT JOIN orders o ON t.id = o.table_id AND o.order_status IN ('pending', 'preparing', 'serving')
            WHERE o.id IS NULL
            ORDER BY t.table_number ASC
        ";
        $statement = $this->db->query($sql);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
        $statement = $this->db->prepare($sql);
        $statement->execute(array('id' => $id));
        return $statement->fetch();
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        $statement = $this->db->prepare($sql);
        return $statement->execute([
            'status' => $status,
            'id' => $id
        ]);
    }

    /**
     * Priority 4 (UX): Auto-release Ghost Cleaning Tables
     * If a table has been in 'cleaning' status for more than 15 minutes,
     * automatically mark it as 'available'.
     */
    public function autoReleaseCleaningTables()
    {
        $sql = "UPDATE {$this->table} 
                SET status = 'available' 
                WHERE status = 'cleaning' 
                AND updated_at < DATE_SUB(NOW(), INTERVAL 15 MINUTE)";
        $statement = $this->db->prepare($sql);
        return $statement->execute();
    }
}
