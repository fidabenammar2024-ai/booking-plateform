<?php
session_start();
if (!isset($_SESSION["user_id"])) {
header("Location: login.php");
exit;
}
require_once "../config/db.php";
require_once "../models/Field.php";
$database = new Database();
$db = $database->connect();
$fieldModel = new Field($db);
$fields = $fieldModel->getAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Liste des terrains</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="fields-page">
<div class="container">
<h1>Terrains disponibles</h1>
<nav>
<a href="../index.php">Accueil</a>
<a href="dashboard.php">Tableau de bord</a>
<a href="logout.php">Déconnexion</a>
</nav>
<?php if (empty($fields)) : ?>
<p class="message">Aucun terrain disponible pour le moment.</p>
<?php else : ?>
<div class="fields-list">
<?php foreach ($fields as $field) : ?>
<div class="field-card">
<h2><?php echo htmlspecialchars($field["name"]); ?></h2>
<p>
<strong>Sport :</strong>
<?php echo htmlspecialchars($field["sport_type"]); ?>
</p>
<p>
<strong>Lieu :</strong>
<?php echo htmlspecialchars($field["location"]); ?>
</p>
<p>
<strong>Prix :</strong>
<?php echo htmlspecialchars($field["price"]); ?> €
</p>
<a class="btn" href="#">
Réserver
</a>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
</div>
</body>
</html>