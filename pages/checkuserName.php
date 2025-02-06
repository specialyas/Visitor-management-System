<?php
include '../database/db_connection.php';


/**
 * Fetches the username associated with a given email from the database.
 *
 * @param mysqli $conn The database connection object.
 * @param string $email The email address to search for.
 * @return string|null Returns the username if found, or null if no matching email exists.
 */
function getUsernameByEmail($conn, $email) {
    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT username FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // Bind the email parameter to the prepared statement
    $stmt->execute(); // Execute the query
    $stmt->store_result(); // Store the result to check if any rows were returned
    
    
    if ($stmt->num_rows === 0) { 
        $stmt->close();
        return null; // If no matching email is found, return null
    }
    
    // Bind the result to a variable and fetch the data
    $stmt->bind_result($username);
    $stmt->fetch();
    
    $stmt->close(); // Close the statement
    
    
    return $username; // Return the found username
}

?>

