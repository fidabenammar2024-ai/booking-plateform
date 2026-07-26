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
$pageTitle = "Modifier un terrain";
require_once "../config/db.php";
require_once "../models/Field.php";
$database = new Database();
$db = $database->connect();
$fieldModel = new Field($db);
$id = $_GET["id"] ?? null;
if (empty($id)) {
    header("Location: admin_fields.php");
    exit;
}
$field = $fieldModel->getById($id);
if (!$field) {
    header("Location: admin_fields.php");
    exit;
}
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
        $updated = $fieldModel->update($id, $name, $sportType, $location, $price);
        if ($updated) {
            header("Location: admin_fields.php?success=field_updated");
            exit;
        }
        $errorMessage = "Erreur lors de la modification du terrain.";
    }
}
?>
<form method="POST" class="admin-form">
    <label for="name">Nom du terrain</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($field["name"]);
                                                    ?>" required>
    <label for="sport_type">Sport</label>
    <select id="sport_type" name="sport_type" required>
        <option value="football" <?php echo ($field["sport_type"] === "football") ? "selected" : "";
                                    ?>>Football</option>
        <option value="basket" <?php echo ($field["sport_type"] === "basket") ? "selected" : "";
                                ?>>Basket</option>
        <option value="tennis" <?php echo ($field["sport_type"] === "tennis") ? "selected" : "";
                                ?>>Tennis</option>
    </select>
    <label for="location">Lieu</label>
    <input type="text" id="location" name="location" value="<?php echo
                                                            htmlspecialchars($field["location"]); ?>" required>
    <label for="price">Prix</label>
    <input type="number" id="price" name="price" step="0.01" min="1" value="<?php echo
                                                                            htmlspecialchars($field["price"]); ?>" required>
    <div class="form-actions">
        <button type="submit">Modifier</button>
        <a href="admin_fields.php" class="admin-btn cancel">Retour</a>
    </div>
</form>