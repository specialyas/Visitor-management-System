<script>
	function Deletevisitor(id)
	{
		if(confirm("You want to delete this record ?"))
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
		<th colspan="7"><a href="index.php?page=add_visitor">Add New visitor</a></th>
	</tr>
	<Tr class="success">
		<th>Sr.No</th>
		<th>Visitor Name</th>
		<th>Phone Number</th>
		<th>Visit Purpose</th>
		<th>Date</th>
		<th>Delete</th>
		<th>Update</th>
	</Tr>
		<?php 


$i=1;
while($row=mysqli_fetch_assoc($q))
{

echo "<Tr>";
echo "<td>".$i."</td>";
echo "<td>".$row['visitor_name']."</td>";
echo "<td>".$row['phone_number']."</td>";
echo "<td>".$row['visit_purpose']."</td>";
echo "<td>".$row['visit_date']."</td>";

?>

<Td><a href="javascript:Deletevisitor('<?php echo $row['visitor_id']; ?>')" style='color:Red'><span class='glyphicon glyphicon-trash'></span></a></td>


<?php 
echo "<Td><a href='index.php?page=update_visitor&visitor_id=".$row['visitor_id']."' style='color:green'><span class='glyphicon glyphicon-edit'></span></a></td>";
echo "</Tr>";
$i++;
}
		?>
		
</table>
