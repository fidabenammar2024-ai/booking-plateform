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
require_once "../models/FieldAvailability.php";
require_once "../models/Reservation.php";

$fieldId = $_POST["field_id"] ?? null;
$date = $_POST["date"] ?? "";
$slot = $_POST["slot"] ?? "";

if (empty($fieldId) || empty($date) || empty($slot)) {
    header("Location: fields.php?error=empty_fields");
    exit;
}

$today = date("Y-m-d");
$currentTime = date("H:i");

if ($date < $today) {
    header("Location: fields.php?error=past_date");
    exit;
}

$slotParts = explode("|", $slot);
if (count($slotParts) !== 2) {
    header("Location: fields.php?error=invalid_slot");
    exit;
}

$startTime = $slotParts[0];
$endTime = $slotParts[1];

if ($date === $today && $startTime <= $currentTime) {
    header("Location: fields.php?error=past_time");
    exit;
}

$database = new Database();
$db = $database->connect();

$availabilityModel = new FieldAvailability($db);
$reservationModel = new Reservation($db);

$dateObject = new DateTime($date);
$dayOfWeek = (int) $dateObject->format("N"); // 1 = Lundi, 7 = Dimanche

$availability = $availabilityModel->getByFieldAndDay($fieldId, $dayOfWeek);

if (!$availability || (int) $availability["is_closed"] === 1) {
    header("Location: fields.php?error=field_closed");
    exit;
}
$openingTime = substr($availability["opening_time"], 0, 5);
$closingTime = substr($availability["closing_time"], 0, 5);

if ($startTime < $openingTime || $endTime > $closingTime) {
    header("Location: fields.php?error=outside_opening_hours");
    exit;
}
$start = new DateTime($startTime);
$end = new DateTime($endTime);
$durationMinutes = ($end->getTimestamp() - $start->getTimestamp()) / 60;

if ($durationMinutes !== (int) $availability["slot_duration"]) {
    header("Location: fields.php?error=invalid_slot_duration");
    exit;
}
$isAvailable = $reservationModel->isAvailable($fieldId, $date, $startTime, $endTime);

if (!$isAvailable) {
    header("Location: fields.php?error=slot_no_longer_available");
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
