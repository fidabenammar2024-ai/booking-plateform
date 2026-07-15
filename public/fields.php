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

                        <button class="btn open-reservation-modal"
                            data-field-id="<?php echo $field['id']; ?>"
                            data-field-name="<?php echo htmlspecialchars($field['name']); ?>">
                            Réserver
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <div id="reservationModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Réserver un terrain</h2>
            <p id="selectedFieldName"></p>
            <form method="POST" action="reserve.php" id="reservationForm">
                <input type="hidden" name="field_id" id="fieldId">
                <label for="date">Date :</label>
                <input type="date" id="date" name="date">
                <label for="start_time">Heure de début :</label>
                <input type="time" id="start_time" name="start_time">
                <label for="end_time">Heure de fin :</label>
                <input type="time" id="end_time" name="end_time">
                <button type="submit">Confirmer la réservation</button>
            </form>
        </div>
    </div>
    <script src="../assets/js/script.js"></script>

</body>

</html>