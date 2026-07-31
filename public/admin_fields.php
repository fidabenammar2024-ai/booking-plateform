<?php
require_once "admin_guard.php";
$activePage = "admin_fields";
$pageTitle = "Gestion des terrains";
require_once "../config/db.php";
require_once "../models/Field.php";
$database = new Database();
$db = $database->connect();
$fieldModel = new Field($db);
$fields = $fieldModel->getAll();
$successMessage = "";
$errorMessage = "";
if (isset($_GET["success"])) {
    if ($_GET["success"] === "field_added") {
        $successMessage = "Terrain ajouté avec succès.";
    } elseif ($_GET["success"] === "field_updated") {
        $successMessage = "Terrain modifié avec succès.";
    } elseif ($_GET["success"] === "field_deleted") {
        $successMessage = "Terrain supprimé avec succès.";
    }
}
if (isset($_GET["error"])) {
    if ($_GET["error"] === "empty_fields") {
        $errorMessage = "Veuillez remplir tous les champs.";
    } elseif ($_GET["error"] === "invalid_price") {
        $errorMessage = "Le prix doit être supérieur à 0.";
    } elseif ($_GET["error"] === "field_has_reservations") {
        $errorMessage = "Impossible de supprimer ce terrain car il possède déjà des réservations.";
    } elseif ($_GET["error"] === "delete_failed") {
        $errorMessage = "Erreur lors de la suppression du terrain.";
    } else {
        $errorMessage = "Une erreur est survenue.";
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
                                            <a href="admin_edit_field.php?id=<?php echo $field["id"]; ?>" class="admin-btn edit">Modifier</a>
                                            <form method="POST" action="admin_delete_field.php" class="inline-form"
                                                onsubmit="return confirm('Voulez-vous vraiment supprimer ce terrain ?');">
                                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($field["id"]); ?>">
                                                <button type="submit" class="admin-btn cancel">
                                                    Supprimer
                                                </button>
                                            </form>
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