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

// Gestion des messages de notification (Toasts)
$successMessage = '';
$errorMessage = '';

if (isset($_GET['success'])) {
    if ($_GET['success'] === 'reservation_cancelled') {
        $successMessage = 'Réservation annulée avec succès.';
    } elseif ($_GET['success'] === 'reservation_created') {
        $successMessage = 'Réservation créée avec succès.';
    }
}

if (isset($_GET['error'])) {
    if ($_GET['error'] === 'cancel_failed') {
        $errorMessage = 'Impossible d’annuler cette réservation.';
    } elseif ($_GET['error'] === 'missing_reservation') {
        $errorMessage = 'Réservation introuvable.';
    } elseif ($_GET['error'] === 'invalid_request') {
        $errorMessage = 'Action non autorisée.';
    } else {
        $errorMessage = 'Une erreur est survenue.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Mes réservations - TerrainGo</title>
    <link rel="icon" type="image/png" href="../assets/images/terraingo-logo.png">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <div class="dashboard-layout">

        <?php require_once "../views/layout/sidebar.php"; ?>

        <main class="main-content">

            <?php require_once "../views/layout/topbar.php"; ?>

            <?php if (!empty($successMessage)) : ?>
                <div class="toast toast-success">
                    <?php echo htmlspecialchars($successMessage); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errorMessage)) : ?>
                <div class="toast toast-error">
                    <?php echo htmlspecialchars($errorMessage); ?>
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
                                    <?php echo htmlspecialchars(substr($reservation["start_time"], 0, 5)); ?>
                                    -
                                    <?php echo htmlspecialchars(substr($reservation["end_time"], 0, 5)); ?>
                                </p>

                                <p>
                                    <strong>Statut :</strong>
                                    <span class="status-badge status-<?php echo htmlspecialchars($reservation["status"]); ?>">
                                        <?php echo htmlspecialchars($reservation["status"]); ?>
                                    </span>
                                </p>

                                <?php
                                $isCancellable = $reservation['status'] !== 'cancelled'
                                    && $reservation['date'] >= date('Y-m-d');
                                ?>

                                <?php if ($isCancellable) : ?>
                                    <form method="POST" action="cancel_reservation.php" class="cancel-form mt-3">
                                        <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                                        <button type="submit" class="admin-btn cancel"
                                            onclick="return confirm('Voulez-vous vraiment annuler cette réservation ?');">
                                            Annuler
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php endif; ?>
            </section>

        </main>
    </div>

    <?php require_once "../views/layout/footer.php"; ?>

    <script src="../assets/js/script.js"></script>
</body>

</html>