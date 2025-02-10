<?php
// Function to check if the user is logged in and has the correct role
function checkUserLoginStatus()
{
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


// Prepare the SQL statement to select the last 5 signed-in visitors
$stmt = $conn->prepare("SELECT visitor_name, sign_out_time FROM visitors WHERE status = 'signed_out' ORDER BY sign_out_time");


// Execute the query
$stmt->execute();


// Get the result set
$result = $stmt->get_result();
// Store visitor names in an array
$visitors = [];
$visitors_time = [];

while ($row = $result->fetch_assoc()) {
    $visitors[] = $row['visitor_name'];
    $visitors_time[] = $row['sign_out_time'];
}

// Close the statement and database connection
$stmt->close();

$conn->close(); // Close the database connection to free resources
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
        
          
            <div class="col-md-4">
                <div class="card">
                    <!-- HTML to display visitors in a card -->
                    <div class="card-body">
                        <h5 class="card-title">Signed Out Visitors</h5>

                        <?php
                        // Check if there are visitors
                        if (!empty($visitors)) {
                            foreach ($visitors as $index => $visitor) {
                                echo "<p class='card-text'>" . htmlspecialchars($visitor) .
                                    " - " . date('h:i A', strtotime($visitors_time[$index])) . "</p>";
                            }
                        } else {
                            echo "<p class='card-text'>No signed out visitors</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>



</body>

</html>