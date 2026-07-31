<?php
require_once "admin_guard.php";
$activePage = "admin_fields";
$pageTitle = "Ajouter un terrain";
require_once "../config/db.php";
require_once "../models/Field.php";
$errorMessage = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $sportType = trim($_POST["sport_type"] ?? "");
    $location = trim($_POST["location"] ?? "");
    $price = trim($_POST["price"] ?? "");
    if (empty($name) || empty($sportType) || empty($location) || empty($price)) {
        $errorMessage = "Veuillez remplir tous les champs.";
    } elseif (!is_numeric($price) || $price <= 0) {
        $errorMessage = "Le prix doit etre un nombre positif.";
    } else {
        $database = new Database();
        $db = $database->connect();
        $fieldModel = new Field($db);
        $created = $fieldModel->create($name, $sportType, $location, $price);
        if ($created) {
            header("Location: admin_fields.php?success=field_created");
            exit;
        }
        $errorMessage = "Erreur lors de l ajout du terrain.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un terrain - TerrainGo</title>
    <link rel="icon" type="image/png" href="../assets/images/terraingo-logo.png">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="dashboard-layout">
        <?php require_once "../views/layout/sidebar.php"; ?>
        <main class="main-content">
            <?php require_once "../views/layout/topbar.php"; ?>
            <?php if (!empty($errorMessage)) : ?>
                <div class="toast toast-error"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>
            <section class="dashboard-card">
                <h1>Ajouter un terrain</h1>
                <form method="POST" class="admin-form">
                    <label for="name">Nom du terrain</label>
                    <input type="text" id="name" name="name" required>
                    <label for="sport_type">Sport</label>
                    <select id="sport_type" name="sport_type" required>
                        <option value="">Choisir un sport</option>
                        <option value="football">Football</option>
                        <option value="basket">Basket</option>
                        <option value="tennis">Tennis</option>
                    </select>
                    <label for="location">Lieu</label>
                    <input type="text" id="location" name="location" required>
                    <label for="price">Prix</label>
                    <input type="number" id="price" name="price" step="0.01" min="1" required>
                    <div class="form-actions">
                        <button type="submit">Ajouter</button>
                        <a href="admin_fields.php" class="admin-btn cancel">Retour</a>
                    </div>
                </form>
            </section>
            <?php require_once __DIR__ . "/../views/layout/footer.php"; ?>
        </main>
    </div>
    <script src="../assets/js/script.js"></script>
</body>

</html>