<?php
class Field
{
    private $conn;
    private $table = 'fields';

    public function __construct($db)
    {
        $this->conn = $db;
    }
    // Compte le nombre total de terrains disponibles
public function countAll() {
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
