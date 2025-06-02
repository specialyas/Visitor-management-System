<?php
session_start(); // Start session
require '../database/db_connection.php';
require '../user/checkuserName.php';

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
$visitorsPerPage = 6;
$currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($currentPage - 1) * $visitorsPerPage;
$errors = [];

// Get filter parameters from GET (for pagination compatibility) - Default to active visitors
$selectedFilter = $_GET['searchFilter'] ?? ($_POST['searchFilter'] ?? 'active_visitors');
$startDate = $_GET['startDate'] ?? ($_POST['startDate'] ?? '');
$endDate = $_GET['endDate'] ?? ($_POST['endDate'] ?? '');

// Input validation
if (!empty($startDate) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate)) {
    $errors[] = "Invalid start date format";
    $startDate = '';
}
if (!empty($endDate) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate)) {
    $errors[] = "Invalid end date format";
    $endDate = '';
}
if (!empty($startDate) && !empty($endDate) && $startDate > $endDate) {
    $errors[] = "Start date cannot be after end date";
    $startDate = '';
    $endDate = '';
}
if (!in_array($selectedFilter, ['all_entries', 'active_visitors', 'signed_out_visitors'])) {
    $selectedFilter = 'active_visitors';
}

// Handle "Clear" button - redirect to clean URL
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear'])) {
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle form submission - redirect to GET to maintain state
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $params = http_build_query([
        'searchFilter' => $_POST['searchFilter'] ?? 'active_visitors',
        'startDate' => $_POST['startDate'] ?? '',
        'endDate' => $_POST['endDate'] ?? '',
        'page' => 1 // Reset to first page on new search
    ]);
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $params);
    exit();
}

// Build consolidated query with all conditions
function buildVisitorQuery($isCountQuery = false) {
    global $selectedFilter, $startDate, $endDate, $visitorsPerPage, $offset;
    
    $select = $isCountQuery ? "COUNT(*) as total" : "*";
    $query = "SELECT $select FROM visitors WHERE 1=1";
    $params = [];
    $types = "";

    // Apply date filter
    if (!empty($startDate) && !empty($endDate)) {
        $query .= " AND DATE(sign_in_time) BETWEEN ? AND ?";
        $params[] = $startDate;
        $params[] = $endDate;
        $types .= "ss";
    }
    
    // Apply status filter
    if ($selectedFilter === 'active_visitors') {
        $query .= " AND status = 'signed_in'";
    }elseif ($selectedFilter === 'signed_out_visitors'){
        $query .= " AND status = 'signed_out'";
    }
    
    // Add ordering and pagination for data query
    if (!$isCountQuery) {
        $query .= " ORDER BY sign_in_time DESC LIMIT ? OFFSET ?";
        $params[] = $visitorsPerPage;
        $params[] = $offset;
        $types .= "ii";
    }
    
    return [$query, $params, $types];
}

// Get total count of filtered results
list($countQuery, $countParams, $countTypes) = buildVisitorQuery(true);
$stmt = $conn->prepare($countQuery);
if (!empty($countTypes)) {
    $stmt->bind_param($countTypes, ...$countParams);
}
$stmt->execute();
$result = $stmt->get_result();
$totalVisitors = $result->fetch_assoc()['total'];
$stmt->close();

$totalPages = ceil($totalVisitors / $visitorsPerPage);

// Get visitor data
if (empty($errors)) {
    list($dataQuery, $dataParams, $dataTypes) = buildVisitorQuery(false);
    $stmt = $conn->prepare($dataQuery);
    if (!empty($dataTypes)) {
        $stmt->bind_param($dataTypes, ...$dataParams);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $visitors = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Helper function to build pagination URLs
function buildPaginationUrl($page) {
    global $selectedFilter, $startDate, $endDate;
    $params = [
        'page' => $page,
        'searchFilter' => $selectedFilter,
        'startDate' => $startDate,
        'endDate' => $endDate
    ];
    return $_SERVER['PHP_SELF'] . '?' . http_build_query(array_filter($params));
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
    <!-- <link rel="stylesheet" href="../css/style.css"> -->

    <style>
         /* Set background color for the body */
        body {
            margin: 0;
            padding: 0;
            background-color: #aacc9d;
        }

        /* Navbar styling */
        .navbar {
            background-color: #157347;
        }

        .nav-link:hover {
            color: #157347 !important; 
            background-color: #aacc9d;
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: white;
        }

        /* Sidebar styling */
        .sidebar {
            /* background-color: #b3e5fc; */
            border-right: 1px solid black;
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
        .error-alert {
            margin-bottom: 15px;
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
                
                <!-- Display validation errors -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger error-alert">
                        <?php foreach ($errors as $error): ?>
                            <div><?= htmlspecialchars($error) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" id="filter-form">
                    <div class="search-box">
                        <label>Filter by:</label>

                        <!-- Radio Button: Current Visitors (Default) -->
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="searchFilter" value="active_visitors" 
                                <?= ($selectedFilter === 'active_visitors') ? 'checked' : '' ?>>
                            <label class="form-check-label">Current Visitors (in building)</label>
                        </div>

                        <!-- Radio Button: Exited Visitors -->
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="searchFilter" value="signed_out_visitors" 
                                <?= ($selectedFilter === 'signed_out_visitors') ? 'checked' : '' ?>>
                            <label class="form-check-label">Past Visitors (Exited building)</label>
                        </div>

                        <!-- Radio Button: All Visitor History -->
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="radio" name="searchFilter" value="all_entries" 
                                <?= ($selectedFilter === 'all_entries') ? 'checked' : '' ?>>
                            <label class="form-check-label">All Visitor History</label>
                        </div>

                        <hr>

                        <!-- Date Range Filter -->
                        <label>Filter by Date:</label>
                        <input type="date" class="form-control mt-2" name="startDate" 
                               value="<?= htmlspecialchars($startDate) ?>" 
                               max="<?= date('Y-m-d') ?>">
                        <input type="date" class="form-control mt-2" name="endDate" 
                               value="<?= htmlspecialchars($endDate) ?>" 
                               max="<?= date('Y-m-d') ?>">
                        
                        <!-- Search and Clear Buttons -->
                        <button type="submit" name="search" class="btn btn-success mt-3 search-btn">Search</button>
                        <button type="submit" name="clear" class="btn clear-btn mt-2">Clear Filters</button>
                    </div>
                </form>
            </div>

            <!-- Main Content Section -->
            <div class="col-md-9 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>
                        <?= ($selectedFilter === 'active_visitors') ? 'Current Visitors' : 'Visitor Records' ?>
                        <?php if ($selectedFilter === 'active_visitors' && $totalVisitors > 0): ?>
                            <span class="badge bg-primary ms-2"><?= $totalVisitors ?> in building</span>
                        <?php endif; ?>
                    </h3>
                </div>
                
                <!-- Results summary -->
                <?php if (empty($errors)): ?>
                    <p class="text-muted mb-3">
                        Showing <?= count($visitors) ?> of <?= $totalVisitors ?> 
                        <?= ($selectedFilter === 'active_visitors') ? 'current visitors' : 'total visitor records' ?>
                        <?php if (!empty($startDate) && !empty($endDate)): ?>
                            between <?= htmlspecialchars($startDate) ?> and <?= htmlspecialchars($endDate) ?>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
                
                <div class="row">
                    <!-- Display Visitor Cards -->
                    <?php if (!empty($visitors)) : ?>
                        <?php foreach ($visitors as $visitor) : ?>
                            <div class="col-md-4">
                                <div class="visitor-card">
                                    <p><strong>Name:</strong> <?= htmlspecialchars($visitor['visitor_name']) ?></p>
                                    <p><strong>Sign-in Time:</strong> <?= htmlspecialchars($visitor['sign_in_time']) ?></p>
                                    <p><strong>Phone:</strong> <?= htmlspecialchars($visitor['phone_number'] ?? 'N/A') ?></p>
                                    <p><strong>Purpose:</strong> <?= htmlspecialchars($visitor['visit_purpose'] ?? 'N/A') ?></p>
                                    <p><strong>Status:</strong> 
                                        <span class="badge <?= $visitor['status'] === 'signed_in' ? 'bg-success' : 'bg-secondary' ?>">
                                            <?= htmlspecialchars($visitor['status']) ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="col-12">
                            <div class="alert alert-info">
                                <?php if (!empty($errors)): ?>
                                    Please correct the errors above to view visitor records.
                                <?php elseif ($selectedFilter === 'active_visitors'): ?>
                                    <div class="text-center">
                                        <h5>No visitors currently in the building</h5>
                                        <p class="mb-0">All visitors have signed out or no one has signed in today.</p>
                                    </div>
                                <?php else: ?>
                                    No visitor records found matching your criteria.
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1 && empty($errors)): ?>
                    <nav aria-label="Visitor pagination">
                        <ul class="pagination">
                            <?php if ($currentPage > 1) : ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= buildPaginationUrl($currentPage - 1) ?>">Previous</a>
                                </li>
                            <?php endif; ?>

                            <?php 
                            // Show pagination numbers with intelligent range
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($totalPages, $currentPage + 2);
                            
                            if ($startPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= buildPaginationUrl(1) ?>">1</a>
                                </li>
                                <?php if ($startPage > 2): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php for ($i = $startPage; $i <= $endPage; $i++) : ?>
                                <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= buildPaginationUrl($i) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($endPage < $totalPages): ?>
                                <?php if ($endPage < $totalPages - 1): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= buildPaginationUrl($totalPages) ?>"><?= $totalPages ?></a>
                                </li>
                            <?php endif; ?>

                            <?php if ($currentPage < $totalPages) : ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= buildPaginationUrl($currentPage + 1) ?>">Next</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add client-side date validation
        document.addEventListener('DOMContentLoaded', function() {
            const startDate = document.querySelector('input[name="startDate"]');
            const endDate = document.querySelector('input[name="endDate"]');
            
            function validateDates() {
                if (startDate.value && endDate.value && startDate.value > endDate.value) {
                    endDate.setCustomValidity('End date must be after start date');
                } else {
                    endDate.setCustomValidity('');
                }
            }
            
            startDate.addEventListener('change', validateDates);
            endDate.addEventListener('change', validateDates);
        });
    </script>
</body>
</html>