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
    <link rel="stylesheet" href="../css/dashboard.css">
    <link href=
"https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href=
"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="shortcut icon" href="https://cdn-icons-png.flaticon.com/512/295/295128.png">
    <script src=
"https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport"
  content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>

<body>
    <nav class="navbar navbar-expand-sm navbar-light bg-success">
        <div class="container">
            <a class="navbar-brand" href="#" style="font-weight:bold; color:white;"><h3>Welcome <?php echo ucfirst("$username") ?>!</h3></a>
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavId">
                <ul class="navbar-nav m-auto mt-2 mt-lg-0">
                </ul>
                <form class="d-flex my-2 my-lg-0">
                    <a href="./logout.php" class="btn btn-light my-2 my-sm-0"
                      type="submit" style="font-weight:bolder;color:green;">
                        logout</a>
                </form>
            </div>
        </div>
    </nav>

    <div>
        <h2 class="p-4 mt-5">User Dashboard</h2>

    </div>
</body>

</html>
