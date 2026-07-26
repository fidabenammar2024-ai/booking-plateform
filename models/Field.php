<?php
class Field
{
    private $conn;
    private $table = 'fields';

    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function getById($id)
    {
        $sql = "SELECT id, NAME AS name, sport_type, location, price, created_at
FROM fields
WHERE id = :id
LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function create($name, $sportType, $location, $price)
    {
        $sql = "INSERT INTO fields (NAME, sport_type, location, price)
VALUES (:name, :sport_type, :location, :price)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":name" => $name,
            ":sport_type" => $sportType,
            ":location" => $location,
            ":price" => $price
        ]);
    }
    public function update($id, $name, $sportType, $location, $price)
    {
        $sql = "UPDATE fields
SET NAME = :name,
sport_type = :sport_type,
location = :location,
price = :price
WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":id" => $id,
            ":name" => $name,
            ":sport_type" => $sportType,
            ":location" => $location,
            ":price" => $price
        ]);
    }
    public function delete($id)
    {
        $sql = "DELETE FROM fields WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }
    // Compte le nombre total de terrains disponibles
    public function countAll()
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    public function getAll($sport = null)
    {
        $sql = 'SELECT id, NAME AS name, sport_type, location, price, created_at FROM fields';
        if (!empty($sport)) {
            $sql .= ' WHERE sport_type = :sport';
        }
        $sql .= ' ORDER BY created_at DESC';
        $stmt = $this->conn->prepare($sql);
        if (!empty($sport)) {
            $stmt->execute([':sport' => $sport]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
