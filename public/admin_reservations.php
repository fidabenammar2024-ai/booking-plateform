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

try {
    foreach ($days as $dayOfWeek => $dayData) {
        $dayOfWeek = (int) $dayOfWeek;

        $isClosed = isset($dayData["is_closed"]) ? 1 : 0;

        $openingTime = $dayData["start_time"] ?? null;
        $closingTime = $dayData["end_time"] ?? null;
        $slotDuration = $dayData["slot_duration"] ?? 60;

        $slotDuration = (int) $slotDuration;

        if ($slotDuration <= 0) {
            header("Location: admin_field_availability.php?field_id=" . $fieldId . "&error=invalid_duration");
            exit;
        }

        if ($isClosed === 1) {
            $openingTime = null;
            $closingTime = null;
        } else {
            if (empty($openingTime) || empty($closingTime)) {
                header("Location: admin_field_availability.php?field_id=" . $fieldId . "&error=missing_hours");
                exit;
            }

            if ($openingTime >= $closingTime) {
                header("Location: admin_field_availability.php?field_id=" . $fieldId . "&error=invalid_hours");
                exit;
            }
        }

        $availabilityModel->saveDay(
            $fieldId,
            $dayOfWeek,
            $openingTime,
            $closingTime,
            $slotDuration,
            $isClosed
        );
    }

    header("Location: admin_field_availability.php?field_id=" . $fieldId . "&success=availability_saved");
    exit;

} catch (Exception $e) {
    $logDir = __DIR__ . "/../logs";

    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }

    error_log(
        date("Y-m-d H:i:s") . " - Erreur sauvegarde horaires : " . $e->getMessage() . PHP_EOL,
        3,
        $logDir . "/app.log"
    );

    header("Location: admin_field_availability.php?field_id=" . $fieldId . "&error=save_failed");
    exit;
}