<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$pageTitle = "Tableau de bord";
$activePage = "dashboard";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>TerrainGo</title>
    <link rel="icon" type="image/png" href="../assets/images/terraingo-logo.png">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="dashboard-layout">

        <?php require_once "../views/layout/sidebar.php"; ?>

        <main class="main-content">

            <?php require_once "../views/layout/topbar.php"; ?>

            <section class="dashboard-card">
                <h1>Tableau de bord</h1>

                <p class="dashboard-intro">
                    Bienvenue dans votre espace utilisateur.
                    Vous pouvez consulter les terrains disponibles et suivre vos réservations.
                </p>

                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Mes réservations</h3>
                        <p>3</p>
                    </div>

                    <div class="stat-card">
                        <h3>Réservations à venir</h3>
                        <p>2</p>
                    </div>

                    <div class="stat-card">
                        <h3>Terrains disponibles</h3>
                        <p>3</p>
                    </div>
                </div>

                <div class="quick-actions">
                    <h2>Que souhaitez-vous faire ?</h2>

                    <div class="action-buttons">
                        <a href="fields.php" class="action-btn primary">Voir les terrains</a>
                        <a href="my_reservations.php" class="action-btn secondary">Mes réservations</a>
                    </div>
                </div>
            </section>

        </main>

    </div>
    <?php require_once __DIR__ . "/../views/layout/footer.php"; ?>
    <script src="../assets/js/script.js"></script>
</body>

</html>