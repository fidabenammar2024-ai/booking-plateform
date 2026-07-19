<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/db.php";
require_once "../models/Reservation.php";

$database = new Database();
$db = $database->connect();

$reservationModel = new Reservation($db);
$reservations = $reservationModel->getByUserId($_SESSION["user_id"]);

$message = "";

if (isset($_GET["success"]) && $_GET["success"] === "reservation_created") {
    $message = "Réservation créée avec succès.";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Mes réservations</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <div class="dashboard-layout">

        <?php require_once "../views/layout/sidebar.php"; ?>

        <main class="main-content">

            <?php require_once "../views/layout/topbar.php"; ?>


            <section class="dashboard-card">
                <h1>Mes réservations</h1>

                <?php if (!empty($message)): ?>
                    <p class="message"><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>

                <?php if (empty($reservations)): ?>
                    <p class="message">Vous n’avez aucune réservation pour le moment.</p>
                <?php else: ?>

                    <div class="reservations-list">
                        <?php foreach ($reservations as $reservation): ?>
                            <div class="reservation-card">
                                <h2><?php echo htmlspecialchars($reservation["field_name"]); ?></h2>

                                <p>
                                    <strong>Sport :</strong>
                                    <?php echo htmlspecialchars($reservation["sport_type"]); ?>
                                </p>

                                <p>
                                    <strong>Lieu :</strong>
                                    <?php echo htmlspecialchars($reservation["location"]); ?>
                                </p>

                                <p>
                                    <strong>Date :</strong>
                                    <?php echo htmlspecialchars($reservation["date"]); ?>
                                </p>

                                <p>
                                    <strong>Heure :</strong>
                                    <?php echo htmlspecialchars($reservation["start_time"]); ?>
                                    -
                                    <?php echo htmlspecialchars($reservation["end_time"]); ?>
                                </p>

                                <p>
                                    <strong>Statut :</strong>
                                    <span class="status-badge status-<?php echo htmlspecialchars($reservation["status"]); ?>">
                                        <?php echo htmlspecialchars($reservation["status"]); ?>
                                    </span>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php endif; ?>
            </section>

        </main>
    </div>

</body>

</html>