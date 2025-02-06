<?php include '../database/db_connection.php'; ?>

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


    <!-- 
        <form class= "myForm" action= "<?php echo $_SERVER["PHP_SELF"];?>" method= "POST" id ="form">
        <?echo $displayError ;?>
	<div class="row">
         <div class="col-sm-7">
          <div class="form-group">
            <label for="name"> Name :</label> 
  <input autocomplete="off" class="form-control" type= "text" name ="name" placeholder= "Enter Visitor's Name." required id = "name"
         oninvalid="this.setCustomValidity(this.willValidate?'':'Name is required')" onblur="isEmpty('name')" onfocus="onfo('name')"
	 data-toggle="popover" title="Popover Header" data-content="Some content inside the popover" data-trigger = "onfocus"/>
          </div>
         </div>
	
</div>

	

<div class="form-group">
<label for="cno"> Contact No. :</label> <span id = "span" style = "padding-bottom: 5px;float:right;"></span>
 <input autocomplete="off" class="form-control" type="number" id = "ContactInfo" onkeyup = "Ccheck('ContactInfo')" 
	onblur = "isEmpty('ContactInfo')" onfocus = "onfo('ContactInfo')" name="cno" placeholder="Enter Contact Number." 
	required min="1000000000" max = "9999999999" 
        oninvalid="this.setCustomValidity(this.willValidate?'':'Enter correct Contact number please')"
	data-toggle="popover" title="Popover Header" data-content="Some content inside the popover" data-trigger = "onfocus"/>
</div>
<div class="form-group">
<label for ="prps">Purpose :</label> 
<input autocomplete="off" class="form-control" type="text" name="purpose" placeholder="Enter Purpose." required id = "Purpose" 
       oninvalid="this.setCustomValidity(this.willValidate?'':'Enter your Purpose')" maxlength="30" onblur="isEmpty('Purpose')"
       data-toggle="popover" title="Popover Header" data-content="Some content inside the popover" data-trigger = "onfocus" />
</div>
<div class="row">
 <div class="col-sm-7">
  <div class="form-group">
   <label for = "meetingTo">Meeting to :</label>
    <input autocomplete="off" class="form-control" type="text" required name = "MeetingTo" id = "meetingTo" 
	   placeholder="Whom will you meet ?"       oninvalid="this.setCustomValidity(this.willValidate?'':'Whom do you want to meet ?')" maxlength="30"  onblur="isEmpty('meetingTo')"
	   data-toggle="popover" title="Popover Header" data-content="Some content inside the popover" data-trigger = "onfocus"/>
   </div>
  </div>

</div>

 <div class="form-group">
   <label  for = "comment">Comment :</label>  
     <input autocomplete="off" class="form-control" type= "varchar" maxlength="30" name = "comment" height="50px" >
     <br>
 </div>
<div>
 <input id="submitme" type="submit" name="submit_post" 
	class="btn btn-success" value="Submit" onclick="return emptys()"/>
 <input autocomplete="off" id="mydata" type="hidden" name="mydata">
		
  </div>
 </form>

    -->
</body>




</html>


    
