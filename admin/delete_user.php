<?php 
include '../database/db_connection.php';
$nid=$_GET['id'];

$q=mysqli_query($conn,"delete from users where id='$nid'");

header('location:index.php?page=manage_users&success=user_deleted');
?>