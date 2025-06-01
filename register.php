<?php
include './database/db_connection.php';

$message = "";
$toastClass = "";
$redirect = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = isset($_POST['role']) ? $_POST['role'] : 'user'; // Default to 'user'

    // Check if email already exists
    $checkEmailStmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();

    if ($checkEmailStmt->num_rows > 0) {
        $message = "Email ID already exists";
        $toastClass = "bg-primary";
    } else {
        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);

        if ($stmt->execute()) {
            $message = "Account created successfully";
            $toastClass = "bg-success";
            $redirect = true; // Set the flag for redirection
        } else {
            $message = "Error: " . $stmt->error;
            $toastClass = "bg-danger";
        }

        $stmt->close();
    }

    $checkEmailStmt->close();
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
    <link rel="stylesheet" href="./css/register.css">
    <title>Registration</title>
</head>

<body>
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
        
        <form method="post" class="register-form">
            <div class="row text-center">
                <i class="fa fa-user-circle-o fa-3x mt-1 mb-2 register-icon"></i>
                <h5 class="register-title">Create Your Account</h5>
            </div>
            
            <div class="mb-2">
                <label for="username">
                    <i class="fa fa-user"></i> User Name
                </label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            
            <div class="mb-2 mt-2">
                <label for="email">
                    <i class="fa fa-envelope"></i> Email
                </label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            
            <div class="mb-2 mt-2">
                <label for="password">
                    <i class="fa fa-lock"></i> Password
                </label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            
            <div class="mb-2 mt-3">
                <button type="submit" class="btn btn-success register-btn">
                    Create Account
                </button>
            </div>
            
            <div class="mb-2 mt-4">
                <p class="register-links">
                    I have an Account <a href="./login.php">Login</a>
                </p>
            </div>
        </form>
    </div>
    
    <!-- Pass PHP variables to JavaScript -->
    <script>
        window.registerConfig = {
            redirect: <?php echo $redirect ? 'true' : 'false'; ?>
        };
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
 <script>
        let toastElList = [].slice.call(document.querySelectorAll('.toast'));
        let toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl, { delay: 3000 });
        });
        toastList.forEach(toast => toast.show());
        
        // Delay redirection if account creation was successful
        <?php if ($redirect) { ?>
            setTimeout(() => {
                window.location.href = 'login.php';
            }, 3000); // Delay for 3 seconds
        <?php } ?>
    </script>
    </body>
</html>