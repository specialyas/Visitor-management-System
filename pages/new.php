<?php
include '../database/db_connection.php';
include '../pages/checkuserName.php';

session_start();

// Check if the user is logged in, if
// not then redirect them to the login page
/* if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

 // Prepare and execute
 $stmt = $conn->prepare("SELECT username FROM userdata WHERE email = ?");
 $stmt->bind_param("s", $email);
 $stmt->execute();
 $stmt->store_result();
 $username = "";
 if ($stmt->num_rows > 0) {
    $stmt->bind_result($username);
    $stmt->fetch();

 }
 */


// Check if the user is logged in, if
// not then redirect them to the login page
/* if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
} */

// Check if the user is logged in and is a regular user
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email']; // Fetch the email from the session

// Prepare and execute
/* $stmt = $conn->prepare("SELECT username FROM userdata WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
$username = "";
if ($stmt->num_rows > 0) {
    $stmt->bind_result($username);
    $stmt->fetch();
}

$stmt->close(); */
$username = getUsernameByEmail($conn, $email);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   <link rel="stylesheet" href="../css/visitorFormStyles.css">
    <title>Leave Feedback</title>
</head>
<style>
   
</style>


<body>
    <?php include 'inc/nav.php'; ?>

    <div class="main">
        <form action="">
            <h2>Sign In Visitor</h2>
            <label for="first">First Name:</label>
            <input class="input" type="text" id="first" name="first" required />

            <label for="last">Last Name:</label>
            <input type="text" class="input" id="last" name="last" required />

            <label for="phone_number">Phone Number</label>
            <input type="tel" class="input" name="phone_number" id="phone_number">

            <label for="visit_purpose">Purpose of Visit</label>
            <input type="text" class="input" name="visit_purpose" id="visit_purpose" required>

            <button type="submit">Sign In</button>
        </form>
    </div>
</body>

</html>
