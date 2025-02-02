<?php
include '../database/db_connection.php';

function getUsernameByEmail($conn, $email) {
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
    $stmt->close();
    return $username;
}
?>
