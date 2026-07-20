<?php
session_start();

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}

require_once "../config/db.php";
require_once "../models/User.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    if (empty($email) || empty($password)) {
        $message = "Veuillez remplir tous les champs.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email invalide.";
    } else {
        $database = new Database();
        $db = $database->connect();

        $userModel = new User($db);
        $user = $userModel->login($email, $password);

        if ($user) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];
            $_SESSION["user_email"] = $user["email"];
            $_SESSION["user_role"] = $user["role"];

            header("Location: dashboard.php");
            exit;
        } else {
            $message = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion - TerrainGo</title>
    <link rel="icon" type="image/png" href="../assets/images/favicon.png">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="auth-page">
    <?php if (!empty($message)) : ?>
        <div class="toast toast-error">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    <main class="auth-container">
        <section class="auth-card">
            <div class="auth-brand">
                <div class="auth-logo">T</div>
                <h1>TerrainGo</h1>
                <p>Connexion à votre espace</p>
            </div>
            <form method="POST" action="">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                    placeholder="exemple@email.com">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password"
                    placeholder="Votre mot de passe">
                <button type="submit">Se connecter</button>
            </form>
            <p class="auth-link">
                Pas encore de compte ?
                <a href="register.php">Créer un compte</a>
            </p>
            <p class="auth-link">
                <a href="../index.php">Retour à l’accueil</a>
            </p>
        </section>
    </main>
</body>

</html>