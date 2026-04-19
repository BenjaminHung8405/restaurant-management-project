<?php

namespace App\Models;

class User extends BaseModel
{
    protected $table = 'users';

    public function all()
    {
        $sql = 'SELECT * FROM ' . $this->table . ' ORDER BY created_at DESC';
        $statement = $this->db->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
        $statement = $this->db->prepare($sql);
        $statement->execute(array('id' => $id));
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function findByUsername($username)
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE username = :username LIMIT 1';
        $statement = $this->db->prepare($sql);
        $statement->execute(array('username' => $username));

        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = '
            INSERT INTO ' . $this->table . ' (
                id, username, password, full_name, role, status
            ) VALUES (
                :id, :username, :password, :full_name, :role, :status
            )
        ';
        
        $statement = $this->db->prepare($sql);
        return $statement->execute($data);
    }

    public function update($id, $data)
    {
        $fields = [];
        $params = ['id' => $id];
        
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[$key] = $value;
        }
        
        $sql = 'UPDATE ' . $this->table . ' SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $statement = $this->db->prepare($sql);
        return $statement->execute($params);
    }

    public function toggleStatus($id)
    {
        $user = $this->find($id);
        if (!$user) return false;

        $newStatus = ($user['status'] === 'active') ? 'inactive' : 'active';
        
        $sql = 'UPDATE ' . $this->table . ' SET status = :status WHERE id = :id';
        $statement = $this->db->prepare($sql);
        return $statement->execute(array(
            'status' => $newStatus,
            'id' => $id
        ));
    }

    public function uuid()
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf("%s%s-%s-%s-%s-%s%s%s", str_split(bin2hex($data), 4));
    }
}
