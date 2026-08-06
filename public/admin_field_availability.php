<?php
require_once "admin_guard.php";
$activePage = "admin_availability";
$pageTitle = "Horaires des terrains";
require_once "../config/db.php";
require_once "../models/Field.php";
require_once "../models/FieldAvailability.php";
$database = new Database();
$db = $database->connect();
$fieldModel = new Field($db);
$availabilityModel = new FieldAvailability($db);
$fields = $fieldModel->getAll();
$fieldId = $_GET["field_id"] ?? null;
$selectedField = null;
$availabilities = [];
if (!empty($fieldId)) {
    $selectedField = $fieldModel->getById($fieldId);
    $availabilities = $availabilityModel->getByFieldId($fieldId);
}
$availabilityByDay = [];
foreach ($availabilities as $availability) {
    $availabilityByDay[$availability["day_of_week"]] = $availability;
}
$days = [
    1 => "Lundi",
    2 => "Mardi",
    3 => "Mercredi",
    4 => "Jeudi",
    5 => "Vendredi",
    6 => "Samedi",
    7 => "Dimanche"
];
?>
<section class="dashboard-card">
    <h1>Horaires des terrains</h1>
    <form method="GET" class="admin-form">
        <label for="field_id">Choisir un terrain</label>
        <select name="field_id" id="field_id" onchange="this.form.submit()">
            <option value="">Sélectionner un terrain</option>
            <?php foreach ($fields as $field) : ?>
                <option value="<?php echo $field["id"]; ?>"
                    <?php echo ($fieldId == $field["id"]) ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($field["name"]); ?>
                </option>
            <?php endforeach; ?>
            TerrainGo - Fiche admin étape 6
            Page 3
        </select>
    </form>
    <?php if ($selectedField) : ?>
        <form method="POST" action="admin_save_availability.php" class="availability-form">
            <input type="hidden" name="field_id" value="<?php echo $selectedField["id"];
                                                        ?>">
            <!-- Boucle sur les jours : Lundi à Dimanche -->
        </form>
    <?php endif; ?>
</section>