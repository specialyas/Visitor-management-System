<?php
session_start(); // Start session
require '../database/db_connection.php';
require '../pages/checkuserName.php';

// Function to check if the user is logged in
function checkUserLoginStatus()
{
    if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'user') {
        header("Location: login.php");
        exit();
    }
}

checkUserLoginStatus(); // Ensure the user is logged in

// Get user details
$email = $_SESSION['email'];
$username = getUsernameByEmail($conn, $email);

// Initialize variables
$visitors = [];
$selectedFilter = $_POST['searchFilter'] ?? null;
$startDate = $_POST['startDate'] ?? null;
$endDate = $_POST['endDate'] ?? null;
$visitorsPerPage = 6;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $visitorsPerPage;

// Handle search form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $query = "SELECT * FROM visitors WHERE 1=1";
    $params = [];
    $types = "";

    if (!empty($startDate) && !empty($endDate)) {
        $query .= " AND DATE(sign_in_time) BETWEEN ? AND ?";
        $params[] = $startDate;
        $params[] = $endDate;
        $types .= "ss";
    }
    if ($selectedFilter === 'active_visitors') {
        $query .= " AND status = 'signed_in'";
    }
    
    $query .= " ORDER BY sign_in_time DESC LIMIT ? OFFSET ?";
    $params[] = $visitorsPerPage;
    $params[] = $offset;
    $types .= "ii";

    $stmt = $conn->prepare($query);
    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $visitors = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    // Default visitor retrieval with pagination
    $query = "SELECT * FROM visitors ORDER BY sign_in_time DESC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $visitorsPerPage, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $visitors = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Get total visitor count
$totalVisitorsQuery = "SELECT COUNT(*) as total FROM visitors";
$totalVisitorsResult = $conn->query($totalVisitorsQuery);
$totalVisitors = $totalVisitorsResult->fetch_assoc()['total'];
$totalPages = ceil($totalVisitors / $visitorsPerPage);

// Handle "Clear" button
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear'])) {
    header("Location: query_data.php");
    exit();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in Visitor</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="../../css/style.css">

    <style>
        /* Set background color for the body */
        body {
            background-color: #f8f9fa;
        }

        /* Navbar styling */
        .navbar {
            background-color: #4a4a4a;
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: white;
        }

        /* Sidebar styling */
        .sidebar {
            background-color: #b3e5fc;
            padding: 20px;
            height: 100vh;
        }

        /* Search box styling */
        .search-box {
            background: white;
            padding: 15px;
            border-radius: 5px;
        }

        /* Input field background */
        .form-control {
            background: #f1f1f1;
        }

        /* Search and Clear button styling */
        .search-btn, .clear-btn {
            width: 100%;
        }
        .clear-btn {
            background-color: #dc3545;
            color: white;
        }

        /* Visitor card styling */
        .visitor-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin-bottom: 20px;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .page-link {
            color: #007bff;
            border: 1px solid #007bff;
        }
        .page-link:hover {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Include Navigation Bar -->
    <?php include 'inc/nav.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Section -->
            <div class="col-md-3 sidebar">
                <h4 class="mb-4">Search Visitors</h4>
                <form method="POST">
                    <div class="search-box">
                        <label>Filter by:</label>

                        <!-- Radio Button: View All Entries -->
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="searchFilter" value="all_entries" 
                                <?= ($selectedFilter === 'all_entries') ? 'checked' : '' ?>>
                            <label class="form-check-label">View All Entries</label>
                        </div>

                        <!-- Radio Button: View Active Visitors -->
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="radio" name="searchFilter" value="active_visitors" 
                                <?= ($selectedFilter === 'active_visitors') ? 'checked' : '' ?>>
                            <label class="form-check-label">View Active Visitors</label>
                        </div>

                        <hr>

                        <!-- Date Range Filter -->
                        <label>Filter by Date:</label>
                        <input type="date" class="form-control mt-2" name="startDate" value="<?= htmlspecialchars($startDate) ?>">
                        <input type="date" class="form-control mt-2" name="endDate" value="<?= htmlspecialchars($endDate) ?>">
                        
                        <!-- Search and Clear Buttons -->
                        <button type="submit" name="search" class="btn btn-success mt-3 search-btn">Search</button>
                        <button type="submit" name="clear" id="clear-filters" class="btn clear-btn mt-2">Clear Filters</button>
                    </div>
                </form>
            </div>

            <!-- Main Content Section -->
            <div class="col-md-9 p-4">
                <h3>Visitor Records</h3>
                <div class="row">
                    <!-- Display Visitor Cards -->
                    <?php if (!empty($visitors)) : ?>
                        <?php foreach ($visitors as $visitor) : ?>
                            <div class="col-md-4">
                                <div class="visitor-card">
<!--                                     <h5><?= htmlspecialchars($visitor['visitor_name']) ?></h5> -->                                    <p><strong>Name:</strong> <?= htmlspecialchars($visitor['visitor_name']) ?></p>
                                    <p><strong>Sign-in Time:</strong> <?= htmlspecialchars($visitor['sign_in_time']) ?></p>
                                    <p><strong>Phone:</strong> <?= htmlspecialchars($visitor['phone_number'] ?? 'N/A') ?></p>
                                    <p><strong>Purpose:</strong> <?= htmlspecialchars($visitor['visit_purpose'] ?? 'N/A') ?></p>
                                    <p><strong>Status:</strong> <?= htmlspecialchars($visitor['status']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="text-muted">No visitor records found.</p>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                   <!-- Pagination -->
        <nav class="pagination">
            <ul class="pagination">
                <?php if ($currentPage > 1) : ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $currentPage - 1 ?>">Previous</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages) : ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $currentPage + 1 ?>">Next</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('clear-filters').addEventListener('click', function() {
    document.getElementById('filter-form').reset(); // Reset form fields
    window.location.href = 'new.php'; // Reload the page without filters
});

    </script>
</body>
</html>
