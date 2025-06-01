<?php
include '../database/db_connection.php';
// Include the file that contains the function to fetch the username by email
include '../user/checkuserName.php';

// Start the session to access session variables (email and user role)
session_start();

// Retrieve the logged-in user's email from the session
$email = $_SESSION['email']; 

// Fetch the username associated with the logged-in user's email using the helper function
$username = getUsernameByEmail($conn, $email);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $visitorID = $_POST['visitor_id']; // Get visitor ID from form input
    $signedOutBy = $username; // User signing out the visitor

    // Check if visitor exists and is signed in
    $stmt = $conn->prepare("SELECT id FROM visitors WHERE visitor_id = ? AND status = 'signed_in'");
    $stmt->bind_param("s", $visitorID);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update the status and sign-out time
        $updateStmt = $conn->prepare("UPDATE visitors SET status = 'signed_out', sign_out_time = NOW(), signed_out_by = ? WHERE visitor_id = ?");
        $updateStmt->bind_param("ss", $signedOutBy, $visitorID);

        if ($updateStmt->execute()) {
                    // Redirect the user to the login page if not authenticated or not a 'user'
        header("Location: index.php");
        echo "Visitor signed out successfully.";
       
        } else {
            echo "Error: " . $updateStmt->error;
        }

        $updateStmt->close();
    } else {
        echo "Invalid visitor ID or visitor already signed out.";
    }

    $stmt->close();
    $conn->close();
}
?>
