<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
$activePage = "profile";
$pageTitle = "Mon profil";
require_once "../config/db.php";
require_once "../models/User.php";
$database = new Database();
$db = $database->connect();
$userModel = new User($db);
$user = $userModel->getById($_SESSION["user_id"]);
if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit;
}
$successMessage = "";
$errorMessage = "";
if (isset($_GET["success"])) {
    if ($_GET["success"] === "profile_updated") {
        $successMessage =
            "Profil mis à jour avec succès.";
    } elseif ($_GET["success"] === "password_updated") {
        $successMessage =
            "Mot de passe modifié avec succès.";
    }
}
if (isset($_GET["error"])) {
    if ($_GET["error"] === "missing_fields") {
        $errorMessage =
            "Veuillez remplir tous les champs.";
    } elseif ($_GET["error"] === "invalid_email") {
        $errorMessage =
            "L'adresse email n'est pas valide.";
    } elseif ($_GET["error"] === "email_exists") {
        $errorMessage =
            "Cette adresse email est déjà utilisée.";
    } elseif ($_GET["error"] === "wrong_password") {
        $errorMessage =
            "Le mot de passe actuel est incorrect.";
    } elseif ($_GET["error"] === "password_mismatch") {
        $errorMessage =
            "Les nouveaux mots de passe ne correspondent pas.";
    } elseif ($_GET["error"] === "password_too_short") {
        $errorMessage =
            "Le nouveau mot de passe doit contenir au moins 8 caractères.";
    } else {
        $errorMessage =
            "Une erreur est survenue.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Administration - TerrainGo</title>
    <link rel="icon" type="image/png" href="../assets/images/terraingo-logo.png">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <?php if (!empty($successMessage)) : ?>
        <div class="toast toast-success">
            <?php echo htmlspecialchars($successMessage); ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($errorMessage)) : ?>
        <div class="toast toast-error">
            <?php echo htmlspecialchars($errorMessage); ?>
        </div>
    <?php endif; ?>
    <section class="dashboard-card">
        <h1>Mon profil</h1>
        <form method="POST"
            action="update_profile.php"
            class="profile-form">
            <input type="hidden"
                name="action"
                value="profile">
            <label for="name">Nom</label>
            <input type="text"
                id="name"
                name="name"
                value="<?php echo htmlspecialchars($user["name"]); ?>"
                required>
            <label for="email">Email</label>
            <input type="email"
                id="email"
                name="email"
                value="<?php echo htmlspecialchars($user["email"]); ?>"
                required>
            <button type="submit"
                class="action-btn primary">
                Enregistrer les modifications
            </button>
        </form>
    </section>
    <section class="dashboard-card profile-password-card">
        <h2>Changer mon mot de passe</h2>
        <form method="POST"
            action="update_profile.php"
            class="profile-form">
            <input type="hidden"
                name="action"
                value="password">
            <label for="current_password">
                Mot de passe actuel
            </label>
            <input type="password"
                id="current_password"
                name="current_password"
                required>
            <label for="new_password">
                Nouveau mot de passe
            </label>
            <input type="password"
                id="new_password"
                name="new_password"
                required>
            <label for="confirm_password">
                Confirmer le nouveau mot de passe
            </label>
            <input type="password"
                id="confirm_password"
                name="confirm_password"
                required>
            <button type="submit"
                class="action-btn primary">
                Modifier le mot de passe
            </button>
        </form>
    </section>
    <section class="dashboard-card profile-password-card">
        <h2>Changer mon mot de passe</h2>
        <form method="POST"
            action="update_profile.php"
            class="profile-form">
            <input type="hidden"
                name="action"
                value="password">
            <label for="current_password">
                Mot de passe actuel
            </label>
            <input type="password"
                id="current_password"
                name="current_password"
                required>
            <label for="new_password">
                Nouveau mot de passe
            </label>
            <input type="password"
                id="new_password"
                name="new_password"
                required>
            <label for="confirm_password">
                Confirmer le nouveau mot de passe
            </label>
            <input type="password"
                id="confirm_password"
                name="confirm_password"
                required>
            <button type="submit"
                class="action-btn primary">
                Modifier le mot de passe
            </button>
        </form>
    </section>
</body>


</html>