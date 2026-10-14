<?php

class User
{
    private $conn;
    private $table = "users";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($name, $email, $password)
    {
        $sql = "INSERT INTO " . $this->table . " (NAME, email, PASSWORD)
                VALUES (:name, :email, :password)";

        $stmt = $this->conn->prepare($sql);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        return $stmt->execute([
            ":name" => $name,
            ":email" => $email,
            ":password" => $hashedPassword
        ]);
    }

    public function findByEmail($email)
    {
        $sql = "SELECT 
                    id,
                    NAME AS name,
                    email,
                    PASSWORD AS password,
                    role,
                    created_at
                FROM " . $this->table . "
                WHERE email = :email
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ":email" => $email
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function login($email, $password)
    {
        $user = $this->findByEmail($email);

        if (!$user || !isset($user["password"])) {
            return false;
        }

        if (password_verify($password, $user["password"])) {
            return $user;
        }

        return false;
    }
    public function getById($id)
    {
        $sql = "SELECT
            id,
            NAME AS name,
            email,
            role,
            created_at
            FROM " . $this->table . "
            WHERE id = :id
            LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function updateProfile($userId, $name, $email)
    {
        $sql = "UPDATE " . $this->table . "
            SET NAME = :name,
            email = :email
            WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":name" => $name,
            ":email" => $email,
            ":id" => $userId
        ]);
    }
    public function updatePassword($userId, $hashedPassword)
    {
        $sql = "UPDATE " . $this->table . "
            SET PASSWORD = :password
            WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":password" => $hashedPassword,
            ":id" => $userId
        ]);
    }
    public function getPasswordHash($userId)
    {
        $sql = "SELECT PASSWORD AS password
            FROM " . $this->table . "
            WHERE id = :id
            LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user["password"] ?? null;
    }
    public function emailExists($email, $excludeUserId)
    {
        $sql = "SELECT COUNT(*) AS total
            FROM " . $this->table . "
            WHERE email = :email
            AND id != :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ":email" => $email,
            ":id" => $excludeUserId
        ]);
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)["total"] > 0;
    }
}
