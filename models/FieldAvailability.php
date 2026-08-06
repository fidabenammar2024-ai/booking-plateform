<?php
class FieldAvailability
{
    private $conn;
    private $table = "field_availability_settings";
    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function getByFieldId($fieldId)
    {
        $sql = "SELECT * FROM " . $this->table . "
WHERE field_id = :field_id
ORDER BY day_of_week ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":field_id" => $fieldId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function saveDay(
        $fieldId,
        $dayOfWeek,
        $openingTime,
        $closingTime,
        $slotDuration,
        $isClosed
    ) {
        $sql = "INSERT INTO " . $this->table . "
(field_id, day_of_week, opening_time, closing_time, slot_duration,
is_closed, updated_at)
VALUES
(:field_id, :day_of_week, :opening_time, :closing_time, :slot_duration, :
is_closed, NOW())
ON DUPLICATE KEY UPDATE
opening_time = VALUES(opening_time),
closing_time = VALUES(closing_time),
slot_duration = VALUES(slot_duration),
is_closed = VALUES(is_closed),
updated_at = NOW()";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":field_id" => $fieldId,
            ":day_of_week" => $dayOfWeek,
            ":opening_time" => $isClosed ? null : $openingTime,
            ":closing_time" => $isClosed ? null : $closingTime,
            ":slot_duration" => $slotDuration,
            ":is_closed" => $isClosed ? 1 : 0
        ]);
    }
}
