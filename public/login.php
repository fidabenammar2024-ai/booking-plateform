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
    <title>Connexion</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h1>Connexion</h1>

<nav>
    <a href="../index.php">Accueil</a> |
    <a href="register.php">Inscription</a>
</nav>

<?php if (!empty($message)) : ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form method="POST" action="">
    <div>
        <label for="email">Email :</label>
        <input type="email" id="email" name="email">
    </div>

    <br>

    <div>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password">
    </div>

    <br>

    <button type="submit">Se connecter</button>
</form>

</body>
</html>