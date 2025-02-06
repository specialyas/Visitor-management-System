<?php
include '../database/db_connection.php';
include '../pages/checkuserName.php';

session_start();

$email = $_SESSION['email']; // Fetch the email from the session


$username = getUsernameByEmail($conn, $email);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $visitorname = $_POST['visitor_name'];
    $phoneNumber = $_POST['phone_number'];
    $visitPurpose = $_POST['visit_purpose'];
    $signedInBy = $username; // Username of the person signing in the visitor
    $visitDate = date('Y-m-d'); // Current date as the visit date
    $visitorID = 'VST-' . strtoupper(bin2hex(random_bytes(4))); 



    $stmt = $conn->prepare("INSERT INTO visitors (visitor_name, phone_number, visit_purpose, visit_date, status, signed_in_by, visitor_id) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)");
$status = 'signed_in'; // Define the status variable
$stmt->bind_param("sssssss", $visitorname, $phoneNumber, $visitPurpose, $visitDate, $status, $signedInBy, $visitorID);


    // Prepare and bind
   /*  $stmt = $conn->prepare("INSERT INTO visitors (visitor_name, phone_number, visit_purpose, visit_date, status, signed_in_by, visitor_id) 
                            VALUES (?, ?, ?, ?, 'signed_in', ?, ?)");
    $stmt->bind_param("ssssss", $visitorname, $phoneNumber, $visitPurpose, $visitDate, $signedInBy, $visitorID);
 */
    if ($stmt->execute()) {
        echo "Visitor signed in successfully. Visitor ID: " . $visitorID;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- 
nerate a unique visitor ID (Example: VST-65A7B9E1C2)
    $visitorID = 'VST-' . strtoupper(bin2hex(random_bytes(4))); 

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO visitors (visitor_id, visitor_name, phone_number, visit_purpose, status, signed_in_by) VALUES (?, ?, ?, ?, 'signed_in', ?)");
    $stmt->bind_param("sssss", $visitorID, $visitorname, $phoneNumber, $visitPurpose, $signedInBy);

    if ($stmt->execute()) {
        echo "Visitor signed in successfully. Visitor ID: " . $visitorID;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?> -->


