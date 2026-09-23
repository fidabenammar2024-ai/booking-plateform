<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: my_reservations.php?error=invalid_request');
    exit;
}

require_once '../config/db.php';
require_once '../models/Reservation.php';

$reservationId = $_POST['reservation_id'] ?? null;

if (empty($reservationId)) {
    header('Location: my_reservations.php?error=missing_reservation');
    exit;
}

try {
    $database = new Database();
    $db = $database->connect();
    $reservationModel = new Reservation($db);

    $cancelled = $reservationModel->cancelByUser($reservationId, $_SESSION['user_id']);

    if ($cancelled) {
        header('Location: my_reservations.php?success=reservation_cancelled');
        exit;
    }

    header('Location: my_reservations.php?error=cancel_failed');
    exit;
} catch (Exception $e) {
    header('Location: my_reservations.php?error=cancel_failed');
    exit;
}
