<script>
	function Deletevisitor(id)
	{
		if(confirm("You want to delete this visitor?"))
		{
		window.location.href="delete_visitor.php?id="+id;
		}
	}
</script>
<?php 
$q=mysqli_query($conn,"select * from visitors ");

?>
<h2 style="color:#27548A">All visitors</h2>

<table class="table table-bordered">
	<tr>
		<th colspan="12"><a href="index.php?page=add_visitor">Add New visitor</a></th>
	</tr>
	<Tr class="success">
		<th>Sr.No</th>
		<th>Visitor ID</th>
		<th>Visitor Name</th>
		<th>Phone Number</th>
		<th>Visit Purpose</th>
		<th>Date</th>
		<th>Sign In Time</th>
		<th>Sign Out Time</th>
		<th>Status</th>
		<th>Signed In By</th>
		<th>Signed Out By</th>
		<th>Delete</th>
		<!-- <th>Update</th> -->
	</Tr>
		<?php 


$i=1;
while($row=mysqli_fetch_assoc($q))
{

echo "<Tr>";
echo "<td>".$i."</td>";
echo "<td>".$row['visitor_id']."</td>";
echo "<td>".$row['visitor_name']."</td>";
echo "<td>".$row['phone_number']."</td>";
echo "<td>".$row['visit_purpose']."</td>";
echo "<td>".$row['visit_date']."</td>";
echo "<td>".$row['sign_in_time']."</td>";
echo "<td>".$row['sign_out_time']."</td>";
echo "<td>".$row['status']."</td>";
echo "<td>".$row['signed_in_by']."</td>";
echo "<td>".$row['signed_out_by']."</td>";

?>

<Td><a href="javascript:Deletevisitor('<?php echo $row['visitor_id']; ?>')" style='color:Red'><span class='glyphicon glyphicon-trash'></span></a></td>


<?php 
// echo "<Td><a href='index.php?page=update_visitor&visitor_id=".$row['visitor_id']."' style='color:green'><span class='glyphicon glyphicon-edit'></span></a></td>";
echo "</Tr>";
$i++;
}
		?>
		
</table>