<?php

namespace App\Models;

class Category extends BaseModel
{
    protected $table = 'categories';

    public function all()
    {
        $sql = 'SELECT id, name, description, image_url FROM ' . $this->table . ' ORDER BY name ASC';
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
