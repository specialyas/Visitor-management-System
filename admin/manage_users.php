<?php 

if (isset($_GET['success'])) {
    if ($_GET['success'] == 'role_updated') {
        echo "<div class='alert alert-success'>User role updated successfully!</div>";
    } elseif ($_GET['success'] == 'user_deleted') {
        echo "<div class='alert alert-success'>User deleted successfully!</div>";
    }
}
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'cannot_demote_self') {
        echo "<div class='alert alert-danger'>You cannot demote yourself!</div>";
    } elseif ($_GET['error'] == 'update_failed') {
        echo "<div class='alert alert-danger'>Failed to update user role!</div>";
    }
}

$q=mysqli_query($conn,"select * from users ");
$rr=mysqli_num_rows($q);
if(!$rr)
{
echo "<h2 style='color:red'>No  user exists !!!</h2>";
}
else
{
?>
<script>
	function DeleteUser(id)
	{
		if(confirm("You want to delete this user?"))
		{
		window.location.href="delete_user.php?id="+id;
		}
	}
	
	function ToggleRole(id, currentRole)
	{
		var newRole = (currentRole === 'admin') ? 'user' : 'admin';
		var message = "Change user role to '" + newRole + "'?";
		
		if(confirm(message))
		{
			window.location.href="toggle_role.php?id="+id+"&role="+newRole;
		}
	}
</script>
<h2 style="color:#AF3E3E">All Users</h2>

<table class="table table-bordered">
	<Tr class="success">
		<th>Sr.No</th>
		<th>User Name</th>
		<th>Email</th>
		<th>Role</th>
		<th>Change Role</th>
		<th>Delete</th>
	</Tr>
		<?php 

$i=1;
while($row=mysqli_fetch_assoc($q))
{

echo "<Tr>";
echo "<td>".$i."</td>";
echo "<td>".$row['username']."</td>";
echo "<td>".$row['email']."</td>";

// Display role with color coding
if($row['role'] == 'admin') {
    echo "<td><span class='badge badge-success'>Admin</span></td>";
} else {
    echo "<td><span class='badge badge-primary'>User</span></td>";
}

// Role toggle button
echo "<td>";
if($row['role'] == 'admin') {
    echo "<button class='btn btn-warning btn-sm' onclick=\"ToggleRole('".$row['id']."', 'admin')\">
          <span class='glyphicon glyphicon-arrow-down'></span> Make User
          </button>";
} else {
    echo "<button class='btn btn-success btn-sm' onclick=\"ToggleRole('".$row['id']."', 'user')\">
          <span class='glyphicon glyphicon-arrow-up'></span> Make Admin
          </button>";
}
echo "</td>";

// Delete button
?>
<td><a href="javascript:DeleteUser('<?php echo $row['id']; ?>')" style='color:Red'><span class='glyphicon glyphicon-trash'></span></a></td>
<?php 

echo "</Tr>";
$i++;
}
		?>
		
</table>
<?php }?>