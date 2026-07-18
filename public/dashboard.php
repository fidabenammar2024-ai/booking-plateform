<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Booking Platform</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <h2>Booking</h2>
            <nav>
                <a href="dashboard.php">Dashboard</a>
                <a href="fields.php">Voir les terrains</a>
                <a href="my_reservations.php">Mes réservations</a>
            </nav>
        </aside>
        <main class="main-content">
            <header class="topbar">
                <p>Bonjour, <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
                    👋</p>
                <a href="logout.php" class="logout-btn">Déconnexion</a>
            </header>
            <section class="dashboard-card">
                <h1>Tableau de bord</h1>
                <p>
                    Bienvenue dans votre espace utilisateur.
                    Vous pouvez consulter les terrains disponibles et suivre vos
                    réservations.
                </p>
            </section>
        </main>
    </div>
</body>

</html>