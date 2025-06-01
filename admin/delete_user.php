<?php 
include '../database/db_connection.php';
$uid=$_GET['id'];

$q=mysqli_query($conn,"delete from users where id='$uid'");

header('location:index.php?page=manage_users&success=user_deleted');
?>