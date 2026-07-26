<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
if ($_SESSION["user_role"] !== "admin") {
    header("Location: dashboard.php");
    exit;
}

$activePage = "admin_reservations";
$pageTitle = "Gestion des réservations";
require_once "../config/db.php";
require_once "../models/Reservation.php";
$database = new Database();
$db = $database->connect();
$reservationModel = new Reservation($db);
$status = $_GET["status"] ?? null;
$reservations = $reservationModel->getAllReservations($status);
$successMessage = "";
$errorMessage = "";
if (isset($_GET["success"]) && $_GET["success"] === "status_updated") {
    $successMessage = "Statut de la réservation mis à jour avec succès.";
}
if (isset($_GET["error"])) {
    if ($_GET["error"] === "invalid_request") {
        $errorMessage = "Requête invalide.";
    } elseif ($_GET["error"] === "update_failed") {
        $errorMessage = "Erreur lors de la mise à jour du statut.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des réservations - TerrainGo</title>
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
                <h1>Gestion des réservations</h1>
                <p class="dashboard-intro">
                    Retrouvez ici toutes les réservations effectuées par les utilisateurs.
                </p>
                <div class="filter-tabs">
                    <a href="admin_reservations.php"
                        class="<?php echo empty($status) ? 'active' : ''; ?>">
                        Toutes
                    </a>
                    <a href="admin_reservations.php?status=pending"
                        class="<?php echo ($status === 'pending') ? 'active' : ''; ?>">
                        En attente
                    </a>
                    <a href="admin_reservations.php?status=confirmed"
                        class="<?php echo ($status === 'confirmed') ? 'active' : ''; ?>">
                        Confirmées
                    </a>
                    <a href="admin_reservations.php?status=cancelled"
                        class="<?php echo ($status === 'cancelled') ? 'active' : ''; ?>">
                        Annulées
                    </a>
                </div>
                <?php if (empty($reservations)) : ?>
                    <p class="empty-state">
                        Aucune réservation trouvée pour ce filtre.
                    </p>
                <?php else : ?>
                    <div class="admin-table-wrapper">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Utilisateur</th>
                                    <th>Email</th>
                                    <th>Terrain</th>
                                    <th>Sport</th>
                                    <th>Lieu</th>
                                    <th>Date</th>
                                    <th>Heure</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reservations as $reservation) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($reservation["user_name"]); ?></td>
                                        <td><?php echo htmlspecialchars($reservation["user_email"]); ?></td>
                                        <td><?php echo htmlspecialchars($reservation["field_name"]); ?></td>
                                        <td><?php echo htmlspecialchars($reservation["sport_type"]); ?></td>
                                        <td><?php echo htmlspecialchars($reservation["location"]); ?></td>
                                        <td><?php echo htmlspecialchars($reservation["date"]); ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($reservation["start_time"]); ?>
                                            -
                                            <?php echo htmlspecialchars($reservation["end_time"]); ?>
                                        </td>
                                        <td>
                                            <span class="status-badge
status-<?php echo htmlspecialchars($reservation["status"]); ?>">
                                                <?php echo htmlspecialchars($reservation["status"]); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($reservation["status"] !== "confirmed") : ?>
                                                <a href="admin_update_reservation.php?id=<?php echo $reservation["id"]; ?>&status=confirmed"
                                                    class="admin-btn confirm">
                                                    Confirmer
                                                </a>
                                                <?php endif; ?>
                                                <?php if ($reservation["status"] !== "cancelled") : ?>
                                                    <a href="admin_update_reservation.php?id=<?php echo $reservation["id"]; ?>&status=cancelled"
                                                        class="admin-btn cancel">
                                                        Annuler
                                                    </a>
                                                <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
            <?php require_once __DIR__ . "/../views/layout/footer.php"; ?>
        </main>
    </div>
    <script src="../assets/js/script.js"></script>
</body>

</html>