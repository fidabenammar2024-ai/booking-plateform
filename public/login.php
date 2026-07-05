<?php
$message = "";
// Utilisateur statique pour le test
$staticUser = [
"name" => "Test User",
"email" => "test@test.com",
"password" => "123456"
];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
$email = $_POST["email"] ?? "";
$password = $_POST["password"] ?? "";
if (empty($email) || empty($password)) {
$message = "Veuillez remplir tous les champs.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
$message = "Email invalide.";
} elseif ($email === $staticUser["email"] && $password === $staticUser["password"]) {
$message = "Connexion reussie. Bienvenue " . htmlspecialchars($staticUser["name"]) .
".";
} else {
$message = "Email ou mot de passe incorrect.";
}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion</title>
</head>
<body>
<h1>Connexion</h1>
<nav>
<a href="index.php">Accueil</a> |
<a href="register.php">Inscription</a>
</nav>
<?php if (!empty($message)) : ?>
<p><?php echo $message; ?></p>
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