<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
if ($_SESSION["user_role"] !== "admin") {
    header("Location: dashboard.php");
    exit;
}
require_once "../config/db.php";
require_once "../models/Reservation.php";
$reservationId = $_GET["id"] ?? null;
$status = $_GET["status"] ?? null;
$allowedStatuses = ["confirmed", "cancelled"];
if (empty($reservationId) || empty($status) || !in_array($status, $allowedStatuses)) {
    header("Location: admin_reservations.php?error=invalid_request");
    exit;
}
$database = new Database();
$db = $database->connect();
$reservationModel = new Reservation($db);
$updated = $reservationModel->updateStatus($reservationId, $status);
if ($updated) {
    header("Location: admin_reservations.php?success=status_updated");
    exit;
}
header("Location: admin_reservations.php?error=update_failed");
exit;
