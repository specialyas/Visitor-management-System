<?php
// Function to check if the user is logged in and has the correct role
function checkUserLoginStatus()
{
    // Check if the session contains an email and if the user role is 'user'
    if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'user') {
        // Redirect the user to the login page if not authenticated or not a 'user'
        header("Location: login.php");
        exit(); // Stop further script execution after redirection
    }
}

// Include the database connection and other necessary files
include '../database/db_connection.php';
include '../user/checkuserName.php';

// Start the session to access session variables
session_start();

// Call the function to check the login status
checkUserLoginStatus();

// Retrieve the logged-in user's email from the session
$email = $_SESSION['email'];

// Fetch the username associated with the logged-in user's email
$username = getUsernameByEmail($conn, $email);

// chcek if a valid username is retrieved
if ($username === null) {
    die("Error: No username found for the given email.");
}


// Prepare the SQL statement to select the last 5 signed-in visitors
$stmt = $conn->prepare("SELECT visitor_name, sign_in_time FROM visitors WHERE status = 'signed_in' ORDER BY sign_in_time DESC LIMIT 5");


// Execute the query
$stmt->execute();

// Get the result set
$result = $stmt->get_result();
// Store visitor names in an array
$visitors = [];
$visitors_time = [];

while ($row = $result->fetch_assoc()) {
    $visitors[] = $row['visitor_name'];
    $visitors_time[] = $row['sign_in_time'];
}

// Close the statement and database connection
$stmt->close();

$conn->close(); // Close the database connection to free resources
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/style.css">
    <title>Check in Vistor</title>

</head>



<body>
    <?php include 'inc/nav.php'; ?>

    <div class="container-fluid mt-6">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Check Out Visitor</h5>
                        <div class="mb-3">
                            <form action="checkout_visitor.php" method="post">
                                <label for="receipt-id" class="form-label">Visitor ID</label>
                                <input type="text" class="form-control mb-4" id="visitor_id" name="visitor_id" placeholder="Enter Visitor ID">
                                <button class="btn btn-primary" onclick='return confirm("Are you sure you want to Checkout?")'>Checkout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" id="visitorDetails" style="display: none;">
                    <div class="card-body">
                        <h5 class="card-title">Details</h5>
                        <p class="card-text" id="detailName">Name: </p>
                        <p class="card-text" id="detailDate">Date: </p>
                        <p class="card-text" id="detailTime">Time in: </p>
                        <p class="card-text" id="detailContact">Contact No: </p>
                        <p class="card-text" id="detailPurpose">Purpose: </p>
                        <p class="card-text" id="detailID">Visitor ID: </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">

                    <!-- HTML to display visitors in a card -->
                    <div class="card-body">
                        <h5 class="card-title">Active Visitors</h5>

                        <?php
                        // Check if the $visitors array is not empty
                        if (!empty($visitors)) {
                            // Loop through each visitor in the $visitors array
                            foreach ($visitors as $index => $visitor) {
                                // Output a paragraph element containing visitor name and time
                                echo "<p class='card-text visitor-name' 
                data-name='" . htmlspecialchars($visitor) . "' 
                data-time='" . htmlspecialchars($visitors_time[$index]) . "'>
                <a href='#' class='visitor-link'>" .
                                    htmlspecialchars($visitor) . " - " .
                                    date('h:i A', strtotime($visitors_time[$index])) . // Format time to 12-hour format (e.g., 03:45 PM)
                                    "</a></p>";
                            }
                        } else {
                            // If no visitors exist, display a message indicating no active visitors
                            echo "<p class='card-text'>No active visitors</p>";
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Select all visitor name elements with the class 'visitor-name'
            const visitorNames = document.querySelectorAll(".visitor-name");

            // Select the details card container where visitor details will be displayed
            const detailsCard = document.getElementById("visitorDetails");

            // Loop through each visitor name element and add a click event listener
            visitorNames.forEach(visitor => {
                visitor.addEventListener("click", function(event) {
                    event.preventDefault(); // Prevent the default behavior of the anchor tag

                    // Remove active class from all visitors
                    visitorNames.forEach(v => v.classList.remove("active-visitor"));

                    // Add active class to the clicked visitor
                    this.classList.add("active-visitor");
                    // Fetch visitor name and sign-in time from data attributes
                    const name = this.getAttribute("data-name");
                    const time = this.getAttribute("data-time");

                    // Make an AJAX request to fetch full visitor details from the backend
                    fetch(`get_visitor_details.php?name=${encodeURIComponent(name)}`)
                        .then(response => response.json()) // Parse response as JSON
                        .then(data => {
                            if (data.error) {
                                alert(data.error); // Show an error message if visitor is not found
                                return;
                            }

                            // Populate the details section with visitor data
                            document.getElementById("detailName").textContent = "Name: " + data.visitor_name;
                            document.getElementById("detailTime").textContent = "Time in: " + data.sign_in_time;
                            document.getElementById("detailDate").textContent = "Date: " + data.visit_date;
                            document.getElementById("detailContact").textContent = "Contact No: " + data.phone_number;
                            document.getElementById("detailPurpose").textContent = "Purpose: " + data.visit_purpose;
                            document.getElementById("detailID").textContent = "Visitor ID: " + data.visitor_id;

                            // Make the details section visible
                            detailsCard.style.display = "block";

                            // Add a slight delay to make the transition smooth
                            setTimeout(() => {
                                detailsCard.style.opacity = "1";
                            }, 50);
                        })
                        .catch(error => {
                            console.error("Error fetching visitor details:", error);
                            alert("Failed to fetch visitor details. Please try again.");
                        });
                });
            });
        });
    </script>



</body>

</html>