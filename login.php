<?php

include './database/db_connection.php';

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute
    $stmt = $conn->prepare("SELECT password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_password, $role);
        $stmt->fetch();

        // Use password_verify to check the hashed password
        if (password_verify($password, $db_password)) {
            $message = "Login successful";
            $toastClass = "bg-success";

            // Start the session and redirect to the dashboard or homepage
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;
            
            if ($role == 'admin') {
                header("Location: ./admin/index.php");
                exit();
            } else {
                header("Location: ./user/index.php");
            }
            exit();
        } else {
            $message = "Invalid credentials";
            $toastClass = "bg-danger";
        }
    } else {
        $message = "Invalid credentials";
        $toastClass = "bg-warning";
    }

    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="shortcut icon" href="https://cdn-icons-png.flaticon.com/512/295/295128.png">
    <link rel="stylesheet" href="css/login.css">
    <title>Login Page</title>
</head>

<body class="">
    <div class="container p-5 d-flex flex-column align-items-center">
        <?php if ($message): ?>
            <div class="toast align-items-center text-white <?php echo $toastClass; ?> border-0" 
                 role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo $message; ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                            data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>
        
        <form action="" method="post" class="login-form">
            <div class="row">
                <i class="fa fa-user-circle-o fa-3x mt-1 mb-2 login-icon"></i>
                <h5 class="login-title">Login Into Your Account</h5>
            </div>
            
            <div class="col-mb-3">
                <label for="email">
                    <i class="fa fa-envelope"></i> Email
                </label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            
            <div class="col mb-3 mt-3">
                <label for="password">
                    <i class="fa fa-lock"></i> Password
                </label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            
            <div class="col mb-3 mt-3">
                <button type="submit" class="btn btn-success login-btn">Login</button>
            </div>
            
            <div class="col mb-2 mt-4">
                <p class="login-links">
                    <a href="./register.php">Create Account</a> OR 
                    <a href="./resetpassword.php">Forgot Password</a>
                </p>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/login.js"></script>
</body>
</html>