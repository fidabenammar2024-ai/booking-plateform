<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$activePage = "reservations";
$pageTitle = "Mes réservations";

require_once "../config/db.php";
require_once "../models/Reservation.php";

$database = new Database();
$db = $database->connect();

$reservationModel = new Reservation($db);

$status = $_GET["status"] ?? null;
$reservations = $reservationModel->getByUserId($_SESSION["user_id"], $status);

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
    <link rel="icon" type="image/png" href="../assets/images/terraingo-logo.png">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<div class="dashboard-layout">

    <?php require_once "../views/layout/sidebar.php"; ?>

    <main class="main-content">

        <?php require_once "../views/layout/topbar.php"; ?>

        <?php if (!empty($message)) : ?>
            <div class="toast toast-success">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <section class="dashboard-card">
            <h1>Mes réservations</h1>

            <div class="filter-tabs">
                <a href="my_reservations.php"
                   class="<?php echo empty($status) ? 'active' : ''; ?>">
                    Tous
                </a>

                <a href="my_reservations.php?status=pending"
                   class="<?php echo ($status === 'pending') ? 'active' : ''; ?>">
                    En attente
                </a>

                <a href="my_reservations.php?status=confirmed"
                   class="<?php echo ($status === 'confirmed') ? 'active' : ''; ?>">
                    Confirmées
                </a>

                <a href="my_reservations.php?status=cancelled"
                   class="<?php echo ($status === 'cancelled') ? 'active' : ''; ?>">
                    Annulées
                </a>
            </div>

            <?php if (empty($reservations)) : ?>

                <p class="empty-state">
                    Aucune réservation trouvée pour ce filtre.
                </p>

            <?php else : ?>

                <div class="reservations-list">
                    <?php foreach ($reservations as $reservation) : ?>
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

        <?php require_once __DIR__ . "/../views/layout/footer.php"; ?>

    </main>
</div>

<script src="../assets/js/script.js"></script>
</body>

</html>