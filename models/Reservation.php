<?php
class Reservation
{
    private $conn;
    private $table = "reservations";
    
    public function getByUserId($userId)
    {
        $sql = "SELECT
reservations.id,
reservations.date,
reservations.start_time,
reservations.end_time,
reservations.status,
fields.name AS field_name,
fields.sport_type,
fields.location,
fields.price
FROM " . $this->table . "
INNER JOIN fields ON reservations.field_id = fields.id
WHERE reservations.user_id = :user_id
ORDER BY reservations.date DESC, reservations.start_time DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ":user_id" => $userId
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function create($userId, $fieldId, $date, $startTime, $endTime)
    {
        $sql = "INSERT INTO " . $this->table . "
(user_id, field_id, date, start_time, end_time, status)
VALUES
(:user_id, :field_id, :date, :start_time, :end_time, :status)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":user_id" => $userId,
            ":field_id" => $fieldId,
            ":date" => $date,
            ":start_time" => $startTime,
            ":end_time" => $endTime,
            ":status" => "pending"
        ]);
    }
    public function isAvailable($fieldId, $date, $startTime, $endTime)
    {$sql = "SELECT COUNT(*) as total
FROM reservations
WHERE field_id = :field_id
AND date = :date
AND status != 'cancelled'
AND (
start_time < :end_time
AND end_time > :start_time
)";
$stmt = $this->conn->prepare($sql);
$stmt->execute([
":field_id" => $fieldId,
":date" => $date,
":start_time" => $startTime,
":end_time" => $endTime
      
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result["total"] == 0;
    }
}
