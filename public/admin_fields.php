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
$activePage = "admin_fields";
$pageTitle = "Gestion des terrains";
require_once "../config/db.php";
require_once "../models/Field.php";
$database = new Database();
$db = $database->connect();
$fieldModel = new Field($db);
$fields = $fieldModel->getAll();
$message = "";
$errorMessage = "";
if (isset($_GET["success"])) {
    if ($_GET["success"] === "field_created") {
        $message = "Terrain ajoute avec succes.";
    } elseif ($_GET["success"] === "field_updated") {
        $message = "Terrain modifie avec succes.";
    } elseif ($_GET["success"] === "field_deleted") {
        $message = "Terrain supprime avec succes.";
    }
}
if (isset($_GET["error"])) {
    if ($_GET["error"] === "delete_failed") {
        $errorMessage = "Erreur lors de la suppression du terrain.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des terrains - TerrainGo</title>
    <link rel="icon" type="image/png" href="../assets/images/terraingo-logo.png">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="dashboard-layout">
        <?php require_once "../views/layout/sidebar.php"; ?>
        <main class="main-content">
            <?php require_once "../views/layout/topbar.php"; ?>
            <?php if (!empty($message)) : ?>
                <div class="toast toast-success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <?php if (!empty($errorMessage)) : ?>
                <div class="toast toast-error"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>
            <section class="dashboard-card">
                <div class="admin-page-header">
                    <div>
                        <h1>Gestion des terrains</h1>
                        <p class="dashboard-intro">Ajoutez, modifiez ou supprimez les terrains disponibles.</p>
                    </div>
                    <a href="admin_add_field.php" class="admin-btn confirm">Ajouter un terrain</a>
                </div>
                <?php if (empty($fields)) : ?>
                    <p class="empty-state">Aucun terrain disponible pour le moment.</p>
                <?php else : ?>
                    <div class="admin-table-wrapper">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Sport</th>
                                    <th>Lieu</th>
                                    <th>Prix</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($fields as $field) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($field["name"]); ?></td>
                                        <td><?php echo htmlspecialchars($field["sport_type"]); ?></td>
                                        <td><?php echo htmlspecialchars($field["location"]); ?></td>
                                        <td><?php echo htmlspecialchars($field["price"]); ?> €</td>
                                        <td>
                                            <a href="admin_edit_field.php?id=<?php echo $field["id"]; ?>" class="admin-btn
edit">Modifier</a>
                                            <a href="admin_delete_field.php?id=<?php echo $field["id"]; ?>" class="admin-btn cancel"
                                                onclick="return confirm('Supprimer ce terrain ?');">Supprimer</a>
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