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

$successMessage = "";
$errorMessage = "";

if (isset($_GET["success"]) && $_GET["success"] === "availability_saved") {
    $successMessage = "Horaires enregistrés avec succès.";
} elseif (isset($_GET["error"])) {
    $errorMessage = "Une erreur est survenue lors de l'enregistrement des horaires.";
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Horaires des terrains - TerrainGo</title>
    <link rel="icon" type="image/png" href="../assets/images/terraingo-logo.png">
    <link rel="stylesheet" href="../assets/css/style.css?v=1">
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
                <h1>Horaires des terrains</h1>
                <p class="dashboard-intro">Définissez les plages horaires de disponibilité pour chaque jour de la semaine.</p>

                <!-- Formulaire de sélection du terrain -->
                <form method="GET" class="admin-form filter-form">
                    <div class="form-group">
                        <label for="field_id">Choisir un terrain</label>
                        <select name="field_id" id="field_id" onchange="this.form.submit()">
                            <option value="">Sélectionner un terrain</option>
                            <?php foreach ($fields as $field) : ?>
                                <option value="<?php echo $field["id"]; ?>"
                                    <?php echo ($fieldId == $field["id"]) ? "selected" : ""; ?>>
                                    <?php echo htmlspecialchars($field["name"]); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>

                <?php if ($selectedField) : ?>
                    <form method="POST" action="admin_save_availability.php" class="availability-form">
                        <input type="hidden" name="field_id" value="<?php echo $selectedField["id"]; ?>">

                        <h2>Horaires pour : <?php echo htmlspecialchars($selectedField["name"]); ?></h2>

                        <div class="availability-table-wrapper">
                            <table class="admin-table availability-table">
                                <thead>
                                    <tr>
                                        <th>Jour</th>
                                        <th>Fermé</th>
                                        <th>Heure d'ouverture</th>
                                        <th>Heure de fermeture</th>
                                        <th>Durée créneau (minutes)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($days as $dayNum => $dayName) : 
                                        $currentDayData = $availabilityByDay[$dayNum] ?? null;
                                        $isClosed = isset($currentDayData["is_closed"]) ? $currentDayData["is_closed"] : 0;
                                        $startTime = $currentDayData["start_time"] ?? "08:00";
                                        $endTime = $currentDayData["end_time"] ?? "22:00";
                                        $slotDuration = $currentDayData["slot_duration"] ?? 60;
                                    ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo $dayName; ?></strong>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="days[<?php echo $dayNum; ?>][is_closed]" value="1" <?php echo ($isClosed == 1) ? "checked" : ""; ?>>
                                            </td>
                                            <td>
                                                <input type="time" name="days[<?php echo $dayNum; ?>][start_time]" value="<?php echo htmlspecialchars($startTime); ?>">
                                            </td>
                                            <td>
                                                <input type="time" name="days[<?php echo $dayNum; ?>][end_time]" value="<?php echo htmlspecialchars($endTime); ?>">
                                            </td>
                                            <td>
                                                <input type="number" name="days[<?php echo $dayNum; ?>][slot_duration]" value="<?php echo htmlspecialchars($slotDuration); ?>" min="15" step="15">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-actions mt-4">
                            <button type="submit" class="action-btn primary">Enregistrer les horaires</button>
                        </div>
                    </form>
                <?php else : ?>
                    <p class="empty-state">Veuillez sélectionner un terrain dans la liste ci-dessus pour configurer ses horaires.</p>
                <?php endif; ?>
            </section>

            <?php require_once "../views/layout/footer.php"; ?>
        </main>
    </div>
    <script src="../assets/js/script.js"></script>
</body>

</html>