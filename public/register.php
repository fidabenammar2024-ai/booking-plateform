<?php
require_once "../config/db.php";
require_once "../models/User.php";
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"] ?? "";
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";
    if (empty($name) || empty($email) || empty($password)) {
        $message = "Veuillez remplir tous les champs.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email invalide.";
    } else {
        $database = new Database();
        $db = $database->connect();
        $userModel = new User($db);
        $existingUser = $userModel->findByEmail($email);
        if ($existingUser) {
            $message = "Cet email existe deja.";
        } else {
            $created = $userModel->create($name, $email, $password);
            $message = $created
                ? "Compte cree avec succes. Vous pouvez maintenant vous connecter."
                : "Erreur lors de la creation du compte.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription - TerrainGo</title>
    <link rel="icon" type="image/png" href="../assets/images/favicon.png">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="auth-page">
    <?php if (!empty($message)) : ?>
        <div class="toast <?php echo str_contains($message, 'succes') ? 'toast-success' : 'toast-error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    <main class="auth-container">
        <section class="auth-card">
            <div class="auth-brand">
                <div class="auth-logo">T</div>
                <h1>TerrainGo</h1>
                <p>Créer votre compte</p>
            </div>
            <form method="POST" action="">
                <label for="name">Nom</label>
                <input type="text" id="name" name="name" placeholder="Votre nom">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                    placeholder="exemple@email.com">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password"
                    placeholder="Votre mot de passe">
                <button type="submit">Créer le compte</button>
            </form>
            <p class="auth-link">
                Vous avez déjà un compte ?
                <a href="login.php">Se connecter</a>
            </p>
        </section>
    </main>
</body>

</html>