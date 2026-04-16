<?php

namespace App\Models;

class Table extends BaseModel
{
    protected $table = 'tables';

    public function getAllAvailable()
    {
        $sql = 'SELECT id, table_number, capacity, status FROM ' . $this->table . ' WHERE status = "available" ORDER BY table_number ASC';
        $statement = $this->db->query($sql);
        return $statement->fetchAll();
    }

    public function find($id)
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
        $statement = $this->db->prepare($sql);
        $statement->execute(array('id' => $id));
        return $statement->fetch();
    }
}
