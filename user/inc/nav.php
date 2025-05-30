<nav class="navbar navbar-expand-sm navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">Visitor Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../user/user_dashboard.php" class="<?= $current_page == '../user/user_dashboard.php' ? 'active' : '' ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="../user/add_visitor.php" class="nav-link <?= $current_page == '../user/add_visitor.php' ? 'active' : '' ?>">Add Vistor</a>
                    </li>
                    <li class="nav-item">
                        <a href="query_data.php" class="nav-link">View</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a href="../user/checked_out_visitors.php" class="nav-link <?= $current_page == '../user/checked_out_visitors.php' ? 'active' : '' ?>">CheckedOut Vistors</a>
                    </li> -->
                    <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <?php
$current_page = basename($_SERVER['PHP_SELF']); // Get the current page name
?>
<!-- <nav class="navbar">
    <ul>
        <li><a href="dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>">Dashboard</a></li>
        <li><a href="visitors.php" class="<?= $current_page == 'visitors.php' ? 'active' : '' ?>">Visitors</a></li>
        <li><a href="reports.php" class="<?= $current_page == 'reports.php' ? 'active' : '' ?>">Reports</a></li>
        <li><a href="settings.php" class="<?= $current_page == 'settings.php' ? 'active' : '' ?>">Settings</a></li>
    </ul>
</nav> -->
