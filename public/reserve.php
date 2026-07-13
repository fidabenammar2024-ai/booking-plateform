<?php
session_start();
if (!isset($_SESSION["user_id"])) {
header("Location: login.php");
exit;
}
require_once "../config/db.php";
require_once "../models/Reservation.php";
$message = "";
$fieldId = $_GET["field_id"] ?? null;
if (!$fieldId) {
header("Location: fields.php");
exit;
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
$date = $_POST["date"] ?? "";
$startTime = $_POST["start_time"] ?? "";
$endTime = $_POST["end_time"] ?? "";
if (empty($date) || empty($startTime) || empty($endTime)) {
$message = "Veuillez remplir tous les champs.";
} elseif ($startTime >= $endTime) {
$message = "L'heure de fin doit être supérieure à l'heure de début.";
} else {
$database = new Database();
$db = $database->connect();
$reservationModel = new Reservation($db);
$isAvailable = $reservationModel->isAvailable($fieldId, $date,
$startTime, $endTime);
if (!$isAvailable) {
$message = "Ce terrain est déjà réservé sur ce créneau.";
} else {
$created = $reservationModel->create(
$_SESSION["user_id"],
$fieldId,
$date,
$startTime,
$endTime
);
if ($created) {
$message = "Réservation créée avec succès.";
} else {
$message = "Erreur lors de la création de la réservation.";
}
}
}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Réserver un terrain</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">
<h1>Réserver un terrain</h1>
<nav>
<a href="../index.php">Accueil</a>
<a href="dashboard.php">Tableau de bord</a>
<a href="fields.php">Terrains</a>
<a href="logout.php">Déconnexion</a>
</nav>
<?php if (!empty($message)) : ?>
<p class="message"><?php echo $message; ?></p>
<?php endif; ?>
<form method="POST" action="">
<label for="date">Date :</label>
<input type="date" id="date" name="date">
<label for="start_time">Heure de début :</label>
<input type="time" id="start_time" name="start_time">
<label for="end_time">Heure de fin :</label>
<input type="time" id="end_time" name="end_time">
<button type="submit">Confirmer la réservation</button>
</form>
</div>
</body>
</html>