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
require_once "../models/Field.php";
$id = $_GET["id"] ?? null;
if (empty($id)) {
    header("Location: admin_fields.php");
    exit;
}
$database = new Database();
$db = $database->connect();
$fieldModel = new Field($db);
$deleted = $fieldModel->delete($id);
if ($deleted) {
    header("Location: admin_fields.php?success=field_deleted");
    exit;
}
header("Location: admin_fields.php?error=delete_failed");
exit;
