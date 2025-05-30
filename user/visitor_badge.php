<?php
// Include database connection to fetch visitor details
include '../database/db_connection.php';

// Check if the visitor ID is provided in the URL
if (!isset($_GET['visitor_id'])) {
    die("Error: Visitor ID is missing.");
}

// Retrieve visitor ID from the URL
$visitorID = $_GET['visitor_id']; 

// Prepare an SQL statement to fetch visitor details from the 'visitors' table
$stmt = $conn->prepare("SELECT visitor_name, phone_number, visit_purpose, visit_date, visitor_id, signed_in_by FROM visitors WHERE id = ?");
$stmt->bind_param("i", $visitorID); // Bind visitor ID as an integer
$stmt->execute();
$result = $stmt->get_result();

// Check if the visitor record exists
if ($result->num_rows === 0) {
    die("Error: Visitor not found.");
}

// Fetch visitor details as an associative array
$visitor = $result->fetch_assoc();

// Close the prepared statement and database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Badge</title>
    <style>
        /* Badge container styling */
        .badge-container {
            width: 300px;
            border: 2px solid black;
            padding: 20px;
            text-align: center;
            font-family: Arial, sans-serif;
            margin: auto;
            margin-top: 50px;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2);
        }

        /* Title styling */
        .badge-title {
            font-size: 18px;
            font-weight: bold;
        }

        /* Badge details styling */
        .badge-details {
            margin-top: 10px;
        }

        /* Print button styling */
        .print-btn {
            margin-top: 20px;
            padding: 10px;
            background: blue;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        /* Print button hover effect */
        .print-btn:hover {
            background: darkblue;
        }
    </style>
</head>
<body>

<!-- Badge Container -->
<div class="badge-container">
    <!-- Badge Title -->
    <div class="badge-title">Visitor Badge</div>

    <!-- Display Visitor Information -->
    <div class="badge-details">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($visitor['visitor_name']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($visitor['phone_number']); ?></p>
        <p><strong>Purpose:</strong> <?php echo htmlspecialchars($visitor['visit_purpose']); ?></p>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($visitor['visit_date']); ?></p>
        <p><strong>Visitor ID:</strong> <?php echo htmlspecialchars($visitor['visitor_id']); ?></p>
        <p><strong>Signed In By:</strong> <?php echo htmlspecialchars($visitor['signed_in_by']); ?></p>

    </div>

    <!-- Print Button -->
    <button class="print-btn" onclick="window.print()">Print Badge</button>
</div>
<a class="nav-link" href="./index.php">Home</a>

</body>
</html>
