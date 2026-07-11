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
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="dashboard-page"> 

    <div class="container">
        <h1>Tableau de bord</h1>
        <p>Bienvenue, <?php echo htmlspecialchars($_SESSION["user_name"]); ?>.</p>
        
        <nav>
            <a href="../index.php">Accueil</a>
            <a href="fields.php">Voir les terrains</a>
            <a href="logout.php">Déconnexion</a>
        </nav>
    </div>

</body>
</html>