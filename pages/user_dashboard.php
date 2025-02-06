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

<div>
    <h2>Check out Visitor </h2>
    <form>
    <div class="mb-3">
                <label for="exampleInput" class="form-label">Example Input</label>
                <input type="text" class="form-control w-25" id="exampleInput">
            </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="text" class="form-control"  placeholder="Enter visitor id">
  </div>
  
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>
    



</body>

</html>