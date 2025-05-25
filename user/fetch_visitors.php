<?php
include '../database/db_connection.php';
session_start();

// Ensure user is logged in
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'user') {
    die("Unauthorized access.");
}

$email = $_SESSION['email'];
$filter = $_POST['filter'] ?? '';
$startDate = $_POST['startDate'] ?? '';
$endDate = $_POST['endDate'] ?? '';

// Base query
$query = "SELECT visitor_id, visitor_name, phone_number AS contact, sign_in_time, sign_out_time, status, visit_date, visit_purpose 
          FROM visitors";
$conditions = [];
$params = [];
$paramTypes = "";

// Apply filters
if ($filter === "active_visitors") {
    $conditions[] = "status = ?";
    $params[] = "signed_in";
    $paramTypes .= "s";
} elseif ($filter === "search_by_date" && !empty($startDate) && !empty($endDate)) {
    $conditions[] = "visit_date BETWEEN ? AND ?";
    $params[] = $startDate;
    $params[] = $endDate;
    $paramTypes .= "ss";
}

// Append conditions to query
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

// Sort results
$query .= " ORDER BY sign_in_time DESC";

// Prepare statement
$stmt = $conn->prepare($query);
if (!empty($paramTypes)) {
    $stmt->bind_param($paramTypes, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Records</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Visitor Records</h2>
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Status</th>
                <th>Visit Date</th>
                <th>Purpose</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['visitor_id']) . "</td>
                            <td>" . htmlspecialchars($row['visitor_name']) . "</td>
                            <td>" . htmlspecialchars($row['contact']) . "</td>
                            <td>" . htmlspecialchars($row['sign_in_time']) . "</td>
                            <td>" . ($row['sign_out_time'] ? htmlspecialchars($row['sign_out_time']) : 'N/A') . "</td>
                            <td>" . htmlspecialchars($row['status']) . "</td>
                            <td>" . htmlspecialchars($row['visit_date']) . "</td>
                            <td>" . htmlspecialchars($row['visit_purpose']) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8' class='text-center'>No results found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
