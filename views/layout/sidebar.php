<aside class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <img src="../assets/images/terraingo-logo.png"
                alt="Logo TerrainGo">
        </div>
        <div>
            <h2>TerrainGo</h2>
            <p>Réservation sportive</p>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="sidebar-section-title">
            Mon espace
        </div><a href="dashboard.php"
            class="<?php echo ($activePage === 'dashboard') ? 'active' : ''; ?>">
            <span class="nav-icon">■</span>
            <span>Dashboard</span>
        </a>
        <a href="fields.php"
            class="<?php echo ($activePage === 'fields') ? 'active' : ''; ?>">
            <span class="nav-icon">■</span>
            <span>Voir les terrains</span>
        </a>
        <a href="my_reservations.php"
            class="<?php echo ($activePage === 'reservations') ? 'active' : ''; ?>">
            <span class="nav-icon">■</span>
            <span>Mes réservations</span>
        </a>
        <?php if (
            isset($_SESSION["user_role"])
            && $_SESSION["user_role"] === "admin"
        ) : ?>
            <div class="sidebar-section-title admin-section">
                Administration
            </div>
            <a href="admin_dashboard.php"
                class="<?php echo ($activePage === 'admin_dashboard') ? 'active' : ''; ?>">
                <span class="nav-icon">■■</span>
                <span>Dashboard admin</span>
            </a>
            <a href="admin_reservations.php"
                class="<?php echo ($activePage === 'admin_reservations') ? 'active' : ''; ?>">
                <span class="nav-icon">■</span>
                <span>Réservations</span>
            </a>
            <a href="admin_fields.php"
                class="<?php echo ($activePage === 'admin_fields') ? 'active' : ''; ?>">
                <span class="nav-icon">■■</span>
                <span>Terrains</span>
            </a>
            <a href="admin_field_availability.php"
                class="<?php echo ($activePage === 'admin_availability') ? 'active' : ''; ?>">
                <span class="nav-icon">■</span>
                <span>Horaires</span>
            </a>
        <?php endif; ?>
    </nav>
    <div class="sidebar-user">
        <div class="sidebar-user-avatar">
            <?php
            echo strtoupper(
                substr($_SESSION["user_name"] ?? "U", 0, 1)
            );
            ?>
        </div>
        <div class="sidebar-user-info"><strong>
                <?php
                echo htmlspecialchars(
                    $_SESSION["user_name"] ?? "Utilisateur"
                );
                ?>
            </strong>
            <span>
                <?php
                echo (
                    isset($_SESSION["user_role"])
                    && $_SESSION["user_role"] === "admin"
                )
                    ? "Administrateur"
                    : "Utilisateur";
                ?>
            </span>
        </div>
    </div>
    <a href="logout.php" class="sidebar-logout">
        <span>■</span>
        <span>Déconnexion</span>
    </a>
</aside>