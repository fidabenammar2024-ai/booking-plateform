<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: fields.php");
    exit;
}

require_once "../config/db.php";
require_once "../models/Reservation.php";

$fieldId = $_POST["field_id"] ?? null;
$date = $_POST["date"] ?? "";
$startTime = $_POST["start_time"] ?? "";
$endTime = $_POST["end_time"] ?? "";

if (empty($fieldId) || empty($date) || empty($startTime) || empty($endTime)) {
    header("Location: fields.php?error=empty_fields");
    exit;
}

if ($date < date("Y-m-d")) {
    header("Location: fields.php?error=past_date");
    exit;
}

if ($startTime >= $endTime) {
    header("Location: fields.php?error=invalid_time");
    exit;
}

$database = new Database();
$db = $database->connect();

$reservationModel = new Reservation($db);

$isAvailable = $reservationModel->isAvailable($fieldId, $date, $startTime, $endTime);

if (!$isAvailable) {
    header("Location: fields.php?error=not_available");
    exit;
}

$created = $reservationModel->create(
    $_SESSION["user_id"],
    $fieldId,
    $date,
    $startTime,
    $endTime
);

if ($created) {
    header("Location: my_reservations.php?success=reservation_created");
    exit;
}

header("Location: fields.php?error=reservation_failed");
exit;