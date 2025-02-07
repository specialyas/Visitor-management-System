<?php
// Function to check if the user is logged in and has the correct role
function checkUserLoginStatus() {
    // Check if the session contains an email and if the user role is 'user'
    if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'user') {
        // Redirect the user to the login page if not authenticated or not a 'user'
        header("Location: login.php");
        exit(); // Stop further script execution after redirection
    }
}

// Include the database connection and other necessary files
include '../database/db_connection.php';
include '../pages/checkuserName.php';

// Start the session to access session variables
session_start();

// Call the function to check the login status
checkUserLoginStatus();

// Retrieve the logged-in user's email from the session
$email = $_SESSION['email'];

// Fetch the username associated with the logged-in user's email
$username = getUsernameByEmail($conn, $email);

// chcek if a valid username is retrieved
if ($username === null) {
    die("Error: No username found for the given email.");
}

$conn->close();// Close the database connection to free resources
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   <link rel="stylesheet" href="../../css/style.css">
    <title>Check in Vistor</title>
</head>



<body> 
<?php include 'inc/nav.php'; ?>
 
<div class="container-fluid mt-6">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Check Out Visitor</h5>
                        <div class="mb-3">
                            <form action="checkout_visitor.php" method="post">
                            <label for="receipt-id" class="form-label">Visitor ID</label>
                            <input type="text" class="form-control mb-4" id="visitor_id" name="visitor_id" placeholder="Enter Visitor ID">
                            <button class="btn btn-primary">Checkout</button>
                        </form>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Details</h5>
                        <p class="card-text">Date: 2025-01-21</p>
                        <p class="card-text">Time in: 17:08:04</p>
                        <p class="card-text">Name: kishan</p>
                        <p class="card-text">Contact No: 9943454224</p>
                        <p class="card-text">Purpose: meeting</p>
                        <p class="card-text">Meeting: leader</p>
                        <p class="card-text">Receipt ID: 322870</p>
                        <p class="card-text">Comment: commenr</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Recent Visitors</h5>
                        <p class="card-text">kisan</p>
                        <p class="card-text">kishan</p>
                    </div>
                </div>
            </div>
        </div>
    </div> 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>




</body>

</html>