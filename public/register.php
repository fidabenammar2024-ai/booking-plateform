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
<link rel="stylesheet" href="../assets/css/style.css">
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Inscription</title>
</head>
<body>
<h1>Créer un compte</h1>
<nav>
<a href="index.php">Accueil</a> |
<a href="login.php">Connexion</a>
</nav>
<?php if (!empty($message)) : ?>
<p><?php echo $message; ?></p>
<?php endif; ?>
<form method="POST" action="">
<div>
<label for="name">Nom :</label>
<input type="text" id="name" name="name">
</div>
<br>
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
<button type="submit">Créer le compte</button>
</form>
</body>
</html>