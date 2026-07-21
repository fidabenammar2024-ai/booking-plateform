<?php if (
    isset($_SESSION["user_role"]) && $_SESSION["user_role"] ===
    "admin"
) : ?>
    <a href="admin_dashboard.php" class="<?php echo ($activePage === 'admin') ?
                                                'active' : ''; ?>">
        <span class="nav-icon">🛠️</span>
        <span>Administration</span>
    </a>
<?php endif; ?>
<aside class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <img src="../assets/images/terraingo-logo.png" alt="Logo TerrainGo">
        </div>
        <div>
            <h2>TerrainGo</h2>
            <p>Réservation sportive</p>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="dashboard.php" class="<?php echo ($activePage === 'dashboard') ? 'active' : ''; ?>">
            <span class="nav-icon">🏠</span>
            <span>Dashboard</span>
        </a>

        <a href="fields.php" class="<?php echo ($activePage === 'fields') ? 'active' : ''; ?>">
            <span class="nav-icon">⚽</span>
            <span>Voir les terrains</span>
        </a>

        <a href="my_reservations.php" class="<?php echo ($activePage === 'reservations') ? 'active' : ''; ?>">
            <span class="nav-icon">📅</span>
            <span>Mes réservations</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <p>Réservez simplement vos terrains sportifs.</p>
    </div>
</aside>