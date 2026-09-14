<?php
require_once "../config/db.php";
require_once "../models/FieldAvailability.php";
require_once "../models/Reservation.php";
header("Content-Type: application/json");
$fieldId = $_GET["field_id"] ?? null;
$date = $_GET["date"] ?? null;
if (empty($fieldId) || empty($date)) {
    echo json_encode(["success" => false, "message" => "Donnees manquantes.", "slots" => []]);
    exit;
}
if ($date < date("Y-m-d")) {
    echo json_encode(["success" => false, "message" => "La date ne peut pas etre dans le passe.", "slots" =>
    []]);
    exit;
}
$database = new Database();
$db = $database->connect();
$availabilityModel = new FieldAvailability($db);
$reservationModel = new Reservation($db);
$dayOfWeek = (int) date("N", strtotime($date));
$availability = $availabilityModel->getByFieldAndDay($fieldId, $dayOfWeek);
if (!$availability || $availability["is_closed"] == 1) {
    echo json_encode(["success" => true, "message" => "Terrain ferme ce jour-la.", "slots" => []]);
    exit;
}
$openingTime = $availability["opening_time"];
$closingTime = $availability["closing_time"];
$slotDuration = (int) $availability["slot_duration"];
$reservedSlots = $reservationModel->getReservedSlotsByFieldAndDate($fieldId, $date);
$slots = [];
$current = strtotime($date . " " . $openingTime);
$end = strtotime($date . " " . $closingTime);
while (($current + ($slotDuration * 60)) <= $end) {
    $slotStart = date("H:i", $current);
    $slotEnd = date("H:i", $current + ($slotDuration * 60));
    $isReserved = false;
    foreach ($reservedSlots as $reserved) {
        $reservedStart = substr($reserved["start_time"], 0, 5);
        $reservedEnd = substr($reserved["end_time"], 0, 5);
        if ($slotStart < $reservedEnd && $slotEnd > $reservedStart) {
            $isReserved = true;
            break;
        }
    }
    if (!$isReserved) {
        $slots[] = [
            "label" => $slotStart . " - " . $slotEnd,
            "value" => $slotStart . "|" . $slotEnd
        ];
    }
    $current += $slotDuration * 60;
}
echo json_encode([
    "success" => true,
    "message" => count($slots) > 0 ? "Creneaux disponibles." : "Aucun creneau disponible.",
    "slots" => $slots
]);
exit;
