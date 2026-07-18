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
        $sql = "INSERT INTO " . $this->table . " (name, email, password)
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
        $sql = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":email" => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function login($email, $password)
    {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user["password"])) {
            return $user;
        }
        return false;
    }
}
