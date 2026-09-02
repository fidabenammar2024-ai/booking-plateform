<?php
require_once "admin_guard.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: admin_fields.php");
    exit;
}
require_once "../config/db.php";
require_once "../models/FieldAvailability.php";
$fieldId = $_POST["field_id"] ?? null;
$days = $_POST["days"] ?? [];
if (empty($fieldId) || empty($days)) {
    header("Location: admin_field_availability.php?error=missing_data");
    exit;
}
$database = new Database();
$db = $database->connect();
$availabilityModel = new FieldAvailability($db);
foreach ($days as $day) {
    $isClosed = isset($_POST["closed_" . $day]);
    $openingTime = $_POST["opening_" . $day] ?? null;
    $closingTime = $_POST["closing_" . $day] ?? null;
    $slotDuration = $_POST["duration_" . $day] ?? 60;
    if (!$isClosed && $openingTime >= $closingTime) {
        header("Location: admin_field_availability.php?field_id=" . $fieldId .
            "&error=invalid_hours");
        exit;
    }
    $availabilityModel->saveDay(
        $fieldId,
        $day,
        $openingTime,
        $closingTime,
        $slotDuration,
        $isClosed
    );
}
header("Location: admin_field_availability.php?field_id=" . $fieldId . "&success=saved");
exit;

