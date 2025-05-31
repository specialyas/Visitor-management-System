<?php
session_start();
include '../database/db_connection.php';

// Verify admin access
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Check if required parameters are provided
if (isset($_GET['id']) && isset($_GET['role'])) {
    $user_id = $_GET['id'];
    $new_role = $_GET['role'];
    
    // Validate the new role (only allow 'admin' or 'user')
    if ($new_role !== 'admin' && $new_role !== 'user') {
        header("Location: index.php?error=invalid_role");
        exit();
    }
    
    // Get current user's ID to prevent self-demotion
    $current_user_email = $_SESSION['email'];
    $current_user_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $current_user_stmt->bind_param("s", $current_user_email);
    $current_user_stmt->execute();
    $current_user_result = $current_user_stmt->get_result();
    $current_user_row = $current_user_result->fetch_assoc();
    $current_user_id = $current_user_row['id'];
    $current_user_stmt->close();
    
    // Prevent admin from demoting themselves
    if ($user_id == $current_user_id && $new_role == 'user') {
        header("Location: index.php?error=cannot_demote_self");
        exit();
    }
    
    // Update user role using prepared statement
    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $new_role, $user_id);
    
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: index.php?success=role_updated");
    } else {
        $stmt->close();
        header("Location: index.php?error=update_failed");
    }
} else {
    header("Location: index.php?error=missing_parameters");
}
?>