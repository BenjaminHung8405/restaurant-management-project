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
        $keys = array_keys($data);
        $fields = implode(', ', $keys);
        $placeholders = ':' . implode(', :', $keys);

        $sql = "INSERT INTO {$this->table} ($fields) VALUES ($placeholders)";
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

    public function existsByName($name, $excludeId = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->table . ' WHERE name = :name';
        $params = array('name' => $name);

        if ($excludeId !== null) {
            $sql .= ' AND id <> :exclude_id';
            $params['exclude_id'] = $excludeId;
        }

        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        return (int) $statement->fetchColumn() > 0;
    }
}
