<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$pageTitle = "Terrains disponibles";
$activePage = "fields";

require_once "../config/db.php";
require_once "../models/Field.php";

$database = new Database();
$db = $database->connect();

$fieldModel = new Field($db);
$fields = $fieldModel->getAll();

$message = "";

if (isset($_GET["error"])) {
    if ($_GET["error"] === "empty_fields") {
        $message = "Veuillez remplir tous les champs.";
    } elseif ($_GET["error"] === "past_date") {
        $message = "La date de réservation ne peut pas être dans le passé.";
    } elseif ($_GET["error"] === "invalid_time") {
        $message = "L'heure de fin doit être supérieure à l'heure de début.";
    } elseif ($_GET["error"] === "not_available") {
        $message = "Ce terrain est déjà réservé sur ce créneau.";
    } elseif ($_GET["error"] === "reservation_failed") {
        $message = "Erreur lors de la création de la réservation.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des terrains</title>
        <link rel="icon" type="image/png" href="../assets/images/terraingo-logo.png">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <div class="dashboard-layout">

        <?php require_once "../views/layout/sidebar.php"; ?>

        <main class="main-content">

            <?php require_once "../views/layout/topbar.php"; ?>

            <section class="dashboard-card">
                <h1>Terrains disponibles</h1>

                <?php if (!empty($message)): ?>
                    <p class="message"><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>

                <?php if (empty($fields)): ?>
                    <p class="message">Aucun terrain disponible pour le moment.</p>
                <?php else: ?>

                    <div class="fields-list">
                        <?php foreach ($fields as $field): ?>
                            <div class="field-card">
                                <span class="sport-badge">
                                    <?php echo htmlspecialchars($field["sport_type"]); ?>
                                </span>

                                <h2><?php echo htmlspecialchars($field["name"]); ?></h2>

                                <p>
                                    <strong>Lieu :</strong>
                                    <?php echo htmlspecialchars($field["location"]); ?>
                                </p>

                                <p class="price">
                                    <?php echo htmlspecialchars($field["price"]); ?> €
                                </p>

                                <button class="btn open-reservation-modal"
                                    data-field-id="<?php echo htmlspecialchars($field["id"]); ?>"
                                    data-field-name="<?php echo htmlspecialchars($field["name"]); ?>">
                                    Réserver
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php endif; ?>
            </section>

        </main>
    </div>

    <div id="reservationModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>

            <h2>Réserver un terrain</h2>

            <p id="selectedFieldName"></p>

            <form method="POST" action="reserve.php" id="reservationForm">
                <input type="hidden" name="field_id" id="fieldId">

                <label for="date">Date :</label>
                <input type="date" id="date" name="date" required>

                <label for="start_time">Heure de début :</label>
                <input type="time" id="start_time" name="start_time" required>

                <label for="end_time">Heure de fin :</label>
                <input type="time" id="end_time" name="end_time" required>

                <button type="submit">Confirmer la réservation</button>
            </form>
        </div>
    </div>

    <script src="../assets/js/script.js"></script>

</body>

</html>