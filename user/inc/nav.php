<nav class="navbar navbar-expand-sm  navbar-fixed-top  mb-4">
        <div class="container">
            <a class="navbar-brand nav-link" href="../user/index.php">Visitor Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="../user/index.php" class=" nav-link <?= $current_page == '../user/user_dashboard.php' ? 'active' : '' ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="../user/add_visitor.php" class="nav-link <?= $current_page == '../user/add_visitor.php' ? 'active' : '' ?>">Add Vistor</a>
                    </li>
                    <li class="nav-item">
                        <a href="query_data.php" class="nav-link">View</a>
                    </li>
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

