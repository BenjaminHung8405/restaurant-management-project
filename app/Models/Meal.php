<?php

namespace App\Models;

class Meal extends BaseModel
{
    protected $table = 'menu_items';

    public function getAllAvailable($search = '', $categoryId = '')
    {
        $sql = '
            SELECT
                m.id,
                m.name,
                m.description,
                m.price,
                m.image_url,
                m.is_available,
                m.is_featured,
                c.id AS category_id,
                c.name AS category_name
            FROM ' . $this->table . ' m
            INNER JOIN categories c ON c.id = m.category_id
            WHERE m.is_available = 1
        ';

        $params = array();

        if ($search !== '') {
            $sql .= ' AND (m.name LIKE :search OR COALESCE(m.description, "") LIKE :search)';
            $params['search'] = '%' . $search . '%';
        }

        if ($categoryId !== '') {
            $sql .= ' AND m.category_id = :category_id';
            $params['category_id'] = $categoryId;
        }

        $sql .= ' ORDER BY m.name ASC';

        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public function find($id)
    {
        $sql = '
            SELECT m.*, c.name as category_name 
            FROM ' . $this->table . ' m
            LEFT JOIN categories c ON c.id = m.category_id 
            WHERE m.id = :id LIMIT 1
        ';
        $statement = $this->db->prepare($sql);
        $statement->execute(array('id' => $id));
        return $statement->fetch();
    }

    public function all($search = '', $categoryId = '', $status = '')
    {
        $sql = '
            SELECT m.*, c.name as category_name 
            FROM ' . $this->table . ' m
            LEFT JOIN categories c ON c.id = m.category_id
            WHERE 1=1
        ';
        $params = array();

        if ($search !== '') {
            $sql .= ' AND (m.name LIKE :search OR m.description LIKE :search)';
            $params['search'] = '%' . $search . '%';
        }

        if ($categoryId !== '') {
            $sql .= ' AND m.category_id = :category_id';
            $params['category_id'] = $categoryId;
        }

        if ($status !== '') {
            $sql .= ' AND m.is_available = :status';
            $params['status'] = $status === 'available' ? 1 : 0;
        }

        $sql .= ' ORDER BY m.created_at DESC';
        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
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
}
