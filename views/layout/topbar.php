<header class="topbar">
    <div class="topbar-left">
        <h3><?php echo htmlspecialchars($pageTitle ?? "Dashboard"); ?></h3>
        <span>Espace utilisateur</span>
    </div>

    <div class="profile-menu">
        <button class="profile-button" id="profileButton" type="button">
            <span class="profile-avatar">
                <?php echo strtoupper(substr($_SESSION["user_name"], 0, 1)); ?>
            </span>

            <span class="profile-name">
                Bonjour, <?php echo htmlspecialchars($_SESSION["user_name"]); ?> 👋
            </span>
        </button>

        <div class="profile-dropdown" id="profileDropdown">
            <p class="profile-dropdown-title">
                <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
            </p>

            <a href="logout.php" class="dropdown-logout">
                Déconnexion
            </a>
        </div>
    </div>
</header>