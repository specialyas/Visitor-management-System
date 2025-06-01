<?php
session_start();


include '../database/db_connection.php';
include '../user/checkuserName.php';



// Check if the user is logged in and is an admin
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
$email = $_SESSION['email']; // Fetch the email from the session

// if ($_SESSION['role'] === 'admin'){
//   $admin = $_SESSION['username'];
// }
$admin = getUsernameByEmail($conn, $email);



// Get current page
$current_page = isset($_GET['page']) ? $_GET['page'] : '';

// Fetch total users
$user_query = mysqli_query($conn, "SELECT COUNT(*) AS total_users FROM users");
$user_data = mysqli_fetch_assoc($user_query);
$total_users = $user_data['total_users'];


// Fetch active visitors
$visitor_query = mysqli_query($conn, "SELECT COUNT(*) AS active_visitors FROM visitors where status = 'signed_in'");
$visitor_data = mysqli_fetch_assoc($visitor_query);
$active_visitors = $visitor_data['active_visitors'];
// $conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"content="width=device-width, initial-scale=1.0">
    
     <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Add to HTML head -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="../css/dashboard.css" rel="stylesheet">

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script> -->
    <title>Dashboard</title>
</head>

<body>

<!-- navbar  --> 
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" onclick="toggleSidebar()">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Welcome <?php echo 'Admin' . ucfirst("$admin") ?></a>
        </div>
        <div class="navbar-collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </nav>  

 

    <!-- side bar  -->
         <div class="sidebar-overlay" onclick="toggleSidebar()"></div>


       <div class="sidebar" id="sidebar">
      <!-- <div class="sidebar-header">
        <h4>Welcome Admin!</h4>
      </div> -->
      <ul class="nav nav-sidebar">
        <li <?php echo ($current_page == '') ? 'class="active"' : ''; ?>>
          <a href="index.php">
            <i class="bi bi-speedometer2"></i>
            Dashboard
          </a>
        </li>
        <li  <?php echo ($current_page == 'update_password') ? 'class="active"' : ''; ?>>
          <a href="index.php?page=update_password"> 
            <i class="bi bi-key"></i>
            Update Password
          </a>
        </li>
        <li <?php echo ($current_page == 'manage_users') ? 'class="active"' : ''; ?>>
          <a href="index.php?page=manage_users">  <!--  -->
            <i class="bi bi-people"></i>
            Manage Users
          </a>
        </li>
        <li <?php echo ($current_page == 'visitors' || $current_page == 'add_notice' || $current_page == 'update_notice') ? 'class="active"' : ''; ?>>
          <a href="index.php?page=visitors"> <!--  -->
            <i class="bi bi-bell"></i>
            Manage Visitors
          </a>
        </li>
        <li <?php echo ($current_page == 'report') ? 'class="active"' : ''; ?>>
          <a href="index.php?page=report">  
            <i class="bi bi-file-earmark-text"></i>
            View Report
          </a>
        </li>
      </ul>
    </div>


    <!-- main content  -->
    <div class="main">
 <?php 
        @$page = $_GET['page'];
        if ($page != "") {
          if ($page == "manage_users") {
            include('manage_users.php');
          }
          if ($page == "update_password") {
            include('update_password.php');
          }
          if ($page == "visitors") {
            include('display_visitors.php');
          }
          if ($page == "update_notice") {
            include('update_notice.php');
          }
          if ($page == "add_notice") {
            include('add_notice.php');
          }
           if ($page == "report") {
            include('report.php');
          }
        } else {
      ?>

 <h1 class="page-header">Dashboard</h1>
          <!-- <h2 class="mb-4 text-primary text-center">Update Password</h2> -->


      <div class="dashboard-cards">
        <div class="stat-card users">
          <div class="stat-card-header">
            <i class="bi bi-people"></i>
            <h3>Total Users</h3>
          </div>
          <div class="stat-card-body">
            <h2><?= $total_users; ?></h2> <!---->
            <p>Registered users on the platform</p>
          </div>
        </div>

        <div class="stat-card notices">
          <div class="stat-card-header">
            <i class="bi bi-bell"></i>
            <h3>Total Active Visitors</h3>
          </div>
          <div class="stat-card-body">
            <h2><?= $active_visitors; ?></h2> <!-- -->
            <p>Active Visitors</p>
          </div>
        </div>
      </div>

      <?php } ?>
    </div>

    <!-- </div> -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../js/vendor/jquery.min.js"><\/script>')</script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/vendor/holder.min.js"></script>
    <script src="../js/ie10-viewport-bug-workaround.js"></script>
    
    <script>
      function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
      }
      
      // Close sidebar when clicking on overlay
      document.querySelector('.sidebar-overlay').addEventListener('click', function() {
        toggleSidebar();
      });
    </script>
</body>

</html>
