<?php 
include '../database/db_connection.php';
$nid=$_GET['id'];

$q=mysqli_query($conn,"delete from visitors where visitor_id='$nid'");

header('location:index.php?page=visitors');

?>