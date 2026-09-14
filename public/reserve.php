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

// Récupération des données POST
$fieldId = $_POST["field_id"] ?? null;
$date = $_POST["date"] ?? "";
$slot = $_POST["slot"] ?? "";

// 1. Vérification des champs vides
if (empty($fieldId) || empty($date) || empty($slot)) {
    header("Location: fields.php?error=empty_fields");
    exit;
}

// 2. Découpage du créneau (format attendu : "08:00|09:00")
$slotParts = explode("|", $slot);
if (count($slotParts) !== 2) {
    header("Location: fields.php?error=invalid_slot");
    exit;
}

$startTime = $slotParts[0];
$endTime = $slotParts[1];

// 3. Connexion à la BDD et modèle
$database = new Database();
$db = $database->connect();
$reservationModel = new Reservation($db);

// 4. Vérification de la disponibilité
$isAvailable = $reservationModel->isAvailable($fieldId, $date, $startTime, $endTime);
if (!$isAvailable) {
    header("Location: fields.php?error=not_available");
    exit;
}

// 5. Création de la réservation
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
