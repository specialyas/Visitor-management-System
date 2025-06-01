
<style>
  .form-container {
    max-width: 500px;
    margin: auto;
    padding: 30px;
    background-color: #f8f9fa; /* light gray background */
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  .form-container h2{
  color: #AF3E3E
 }
 .submit-btn, btn{
      /* background-color: #AF3E3E;  */
      color: white;

 }
</style>

<?php
// Debug connection status
if (!isset($conn)) {
    // Include database connection if not available
    include_once '../database/db_connection.php';
}

// Check if connection is valid
if (!$conn || $conn->connect_error) {
    die("Database connection failed: " . ($conn ? $conn->connect_error : "Connection not established"));
}

        // Make sure we have the admin username from session
if (!isset($admin)) {
    session_start();
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        // Get username directly here instead of relying on index.php
        $stmt = $conn->prepare("SELECT username FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $admin = $row['username'];
        }
        $stmt->close();
    }
}

extract($_POST);
if (isset($save)) {
    if ($np == "" || $cp == "" || $op == "") {
        $err = "<div class='alert alert-danger'>Please fill in all fields.</div>";
    } else {
        // Check if connection is still active
        if ($conn && !$conn->connect_error) {
            // Get the current hashed password from database
            $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
            $stmt->bind_param("s", $admin);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $stored_password = $row['password'];
                
                // Verify the old password using password_verify()
                if (password_verify($op, $stored_password)) {
                    if ($np == $cp) {
                        // Hash the new password before storing
                        $hashed_new_password = password_hash($np, PASSWORD_DEFAULT);
                        
                        // Update password using prepared statement
                        $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
                        $update_stmt->bind_param("ss", $hashed_new_password, $admin);
                        
                        if ($update_stmt->execute()) {
                            $err = "<div class='alert alert-success'>Password updated successfully.</div>";
                        } else {
                            $err = "<div class='alert alert-danger'>Error updating password: " . $conn->error . "</div>";
                        }
                        $update_stmt->close();
                    } else {
                        $err = "<div class='alert alert-warning'>New password and Confirm Password do not match.</div>";
                    }
                } else {
                    $err = "<div class='alert alert-danger'>Wrong Old Password.</div>";
                }
            } else {
                $err = "<div class='alert alert-danger'>User not found.</div>";
            }
            $stmt->close();
        } else {
            $err = "<div class='alert alert-danger'>Database connection error.</div>";
        }
    }
}
?>

<div class="container mt-5 d-flex justify-content-center">
  <div class="form-container">
    <h2 class="mb-4  text-center">Update Password</h2>

    <?php echo @$err; ?>

    <form method="post">
      <div class="mb-3">
        <label for="op" class="form-label">Old Password</label>
        <input type="password" name="op" id="op" class="form-control" placeholder="Enter old password">
      </div>

      <div class="mb-3">
        <label for="np" class="form-label">New Password</label>
        <input type="password" name="np" id="np" class="form-control" placeholder="Enter new password">
      </div>

      <div class="mb-3">
        <label for="cp" class="form-label">Confirm Password</label>
        <input type="password" name="cp" id="cp" class="form-control" placeholder="Re-enter new password">
      </div>

      <div class="d-flex gap-2 justify-content-center">
        <input type="submit" value="Update Password" name="save" class="btn btn-info">
        <input type="reset" class="btn btn-danger">
      </div>
    </form>
  </div>
</div>