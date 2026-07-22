<?php session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
if ($_SESSION["user_role"] !== "admin") {
    header("Location: dashboard.php");
    exit;
}
$activePage = "admin";
$pageTitle = "Administration"; ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Administration - TerrainGo</title>
    <link rel="icon" type="image/png" href="../assets/images/terraingo-logo.png">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="dashboard-layout"> <?php require_once "../views/layout/sidebar.php"; ?>
        <main class="main-content"> <?php require_once "../views/layout/topbar.php"; ?>
            <section class="dashboard-card">
                <h1>Espace administrateur</h1>
                <p class="dashboard-intro"> Bienvenue dans l’espace admin de TerrainGo. Depuis cet espace, vous pourrez
                    bientôt gérer les terrains et les réservations. </p>
                <div class="admin-actions"> <a href="#" class="admin-action-card">
                        <h3>📅 Réservations</h3>
                        <p>Voir et gérer toutes les réservations.</p>
                    </a> <a href="#" class="admin-action-card">
                        <h3>⚽ Terrains</h3>
                        <p>Ajouter, modifier ou supprimer des terrains.</p>
                    </a> <a href="#" class="admin-action-card">
                        <h3>👥 Utilisateurs</h3>
                        <p>Consulter les utilisateurs inscrits.</p>
                    </a> </div>
            </section> <?php require_once __DIR__ . "/../views/layout/footer.php"; ?>
        </main>
    </div>
    <script src="../assets/js/script.js"></script>
</body>

</html>