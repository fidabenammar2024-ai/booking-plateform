<?php
require_once "admin_guard.php";
require_once "../config/db.php";
require_once "../models/Field.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: admin_fields.php?error=invalid_request");
    exit;
}
$fieldId = $_POST["id"] ?? null;
if (empty($fieldId)) {
    header("Location: admin_fields.php?error=missing_id");
    exit;
}
$database = new Database();
$db = $database->connect();
$fieldModel = new Field($db);
if ($fieldModel->hasReservations($fieldId)) {
    header("Location: admin_fields.php?error=field_has_reservations");
    exit;
}
$deleted = $fieldModel->delete($fieldId);
if ($deleted) {
    header("Location: admin_fields.php?success=field_deleted");
    exit;
}
header("Location: admin_fields.php?error=delete_failed");
exit;
