<?php
// Include the database connection file to interact with the database
include '../database/db_connection.php';
// Include the file that contains the function to fetch the username by email
include '../pages/checkuserName.php';

// Start the session to access session variables (email and user role)
session_start();

// Retrieve the logged-in user's email from the session
$email = $_SESSION['email']; 

// Fetch the username associated with the logged-in user's email using the helper function
$username = getUsernameByEmail($conn, $email);

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data submitted by the user
    $visitorname = $_POST['visitor_name'];      
    $phoneNumber = $_POST['phone_number'];      
    $visitPurpose = $_POST['visit_purpose'];    
    $signedInBy = $username;                    
    $visitDate = date('Y-m-d');                  
    $visitorID = 'VST-' . strtoupper(bin2hex(random_bytes(4)));  // Generate a unique visitor ID using random bytes

    // Prepare SQL statement to insert the visitor's data into the 'visitors' table
    $stmt = $conn->prepare("INSERT INTO visitors (visitor_name, phone_number, visit_purpose, visit_date, status, signed_in_by, visitor_id) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    // Define the default status for the visitor as 'signed_in'
    $status = 'signed_in'; 

    // Bind parameters to the SQL query, using the appropriate data types
    $stmt->bind_param("sssssss", $visitorname, $phoneNumber, $visitPurpose, $visitDate, $status, $signedInBy, $visitorID);

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        // Success message, with the generated visitor ID
        echo "Visitor signed in successfully. Visitor ID: " . $visitorID;
    } else {
        // Error message if something goes wrong with the query execution
        echo "Error: " . $stmt->error;
    }

    $stmt->close();     // Close the prepared statement to free up resources
    $conn->close();     // Close the database connection

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   

<div>
<a class="nav-link" href="../pages/user_dashboard.php">Home</a>

</div>


</body>
</html>
