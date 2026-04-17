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

    public function create($data)
    {
        $sql = 'INSERT INTO ' . $this->table . ' (id, name, description, image_url) VALUES (:id, :name, :description, :image_url)';
        $statement = $this->db->prepare($sql);
        return $statement->execute($data);
    }

    public function update($id, $data)
    {
        $fields = array();
        foreach (array_keys($data) as $key) {
            $fields[] = "$key = :$key";
        }
        $data['id'] = $id;
        $sql = 'UPDATE ' . $this->table . ' SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $statement = $this->db->prepare($sql);
        return $statement->execute($data);
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $statement = $this->db->prepare($sql);
        return $statement->execute(array('id' => $id));
    }

    public function hasMenuItems($id)
    {
        $sql = 'SELECT COUNT(*) FROM menu_items WHERE category_id = :id';
        $statement = $this->db->prepare($sql);
        $statement->execute(array('id' => $id));
        return (int)$statement->fetchColumn() > 0;
    }
}
