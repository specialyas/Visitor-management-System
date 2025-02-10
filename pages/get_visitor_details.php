<?php
// Include the database connection file
include '../database/db_connection.php';

// Check if the 'name' parameter is provided in the request
if (!isset($_GET['name'])) {
    echo json_encode(["error" => "No visitor name provided."]);
    exit(); // Stop further execution
}

// Get the visitor name from the GET request
$visitorName = $_GET['name'];

// Prepare an SQL statement to fetch visitor details based on the provided name
$stmt = $conn->prepare("
    SELECT visitor_id, visitor_name, phone_number, visit_purpose, signed_in_by, visit_date, sign_in_time 
    FROM visitors 
    WHERE visitor_name = ? 
    LIMIT 1
");

// Bind the visitor name to the SQL query to prevent SQL injection
$stmt->bind_param("s", $visitorName);

// Execute the query
$stmt->execute();

// Get the result of the query
$result = $stmt->get_result();

// Check if any visitor record is found
if ($row = $result->fetch_assoc()) {
    // If a visitor is found, return the details as a JSON response
    echo json_encode($row);
} else {
    // If no record is found, return an error message as JSON
    echo json_encode(["error" => "Visitor not found."]);
}

// Close the prepared statement and database connection to free resources
$stmt->close();
$conn->close();
?>
