<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="images/logo.png" alt="Miku Logo" width="30" height="30" class="d-inline-block align-top">
            Hatsune Miku Fan Site
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'index') ? 'active' : ''; ?>" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'about') ? 'active' : ''; ?>" href="about.php">About Miku</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'music') ? 'active' : ''; ?>" href="music.php">Music</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'events') ? 'active' : ''; ?>" href="events.php">Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'gallery') ? 'active' : ''; ?>" href="gallery.php">Gallery</a>
                </li>
            </ul>
            <div class="navbar-nav">
                <?php if (isset($_SESSION['login'])): ?>
                    <span class="nav-link">Welcome, <?php echo $_SESSION['login']; ?></span>
                    <a class="nav-link" href="logout.php">Logout</a>
                <?php else: ?>
                    <a class="nav-link" href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>