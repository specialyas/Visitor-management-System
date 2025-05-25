<?php include '../database/db_connection.php'; 
include '../user/checkuserName.php';

session_start();

$email = $_SESSION['email']; // Fetch the email from the session


$username = getUsernameByEmail($conn, $email);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">

    <title>HTML Registration Form</title>
    <style>
        
    </style>
</head>

<body>

<?php include 'inc/nav.php'; ?>

<div>
        <h2 class="p-4 mt-5"><?php echo "Logged in as: " . ucfirst("$username") ?></h2>
    </div>
    <div class="main">
    <form method="POST" action="check_in_visitor.php">
            <h2>Sign In Visitor</h2>
            <label for="first">Visitor Name:</label>
            <input class="input"  type="text" id="first" name="visitor_name" placeholder="Enter Visitor Name" required />

            <label for="visit_purpose">Purpose of Visit</label>
            <input type="text" class="input" name="visit_purpose" placeholder="Enter Purpose." id="visit_purpose" required>     
          
            <label for="phone_number">Phone Number</label>
            <input type="tel" class="input" name="phone_number" placeholder="Enter phone number" id="phone_number">
      
           
            <button type="submit">Sign In</button>

        </form> 
    </div>


 
</body>




</html>


    
