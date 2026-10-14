<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: profile.php");
    exit;
}
require_once "../config/db.php";
require_once "../models/User.php";
$database = new Database();
$db = $database->connect();
$userModel = new User($db);
$userId = $_SESSION["user_id"];
$action = $_POST["action"] ?? "";
if ($action === "profile") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    if (empty($name) || empty($email)) {
        header("Location: profile.php?error=missing_fields");
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: profile.php?error=invalid_email");
        exit;
    }
    if ($userModel->emailExists($email, $userId)) {
        header("Location: profile.php?error=email_exists");
        exit;
    }
    $updated = $userModel->updateProfile(
        $userId,
        $name,
        $email
    );
    if ($updated) {
        $_SESSION["user_name"] = $name;
        $_SESSION["user_email"] = $email;
        header("Location: profile.php?success=profile_updated");
        exit;
    }
    header("Location: profile.php?error=update_failed");
    exit;
    if ($action === "password") {
        $currentPassword =
            $_POST["current_password"] ?? "";
        $newPassword =
            $_POST["new_password"] ?? "";
        $confirmPassword =
            $_POST["confirm_password"] ?? "";
        if (
            empty($currentPassword) ||
            empty($newPassword) ||
            empty($confirmPassword)
        ) {
            header("Location: profile.php?error=missing_password_fields");
            exit;
        }
        if ($newPassword !== $confirmPassword) {
            header("Location: profile.php?error=password_mismatch");
            exit;
        }
        if (strlen($newPassword) < 8) {
            header("Location: profile.php?error=password_too_short");
            exit;
        }
        $currentHash =
            $userModel->getPasswordHash($userId);
        if (
            !$currentHash ||
            !password_verify($currentPassword, $currentHash)
        ) {
            header("Location: profile.php?error=wrong_password");
            exit;
        }
        $newHash = password_hash(
            $newPassword,
            PASSWORD_DEFAULT
        );
        $updated = $userModel->updatePassword(
            $userId,
            $newHash
        );
        if ($updated) {
            header("Location: profile.php?success=password_updated");
            exit;
        }
        header("Location: profile.php?error=password_update_failed");
        exit;
    }
    header("Location: profile.php?error=invalid_action");
    exit;
}
