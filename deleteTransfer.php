<?php  
include('connect.php');

$TransferID=$_GET['TransferID'];

$Delete="DELETE FROM transfer WHERE TransferID='$TransferID' ";
$ret=mysqli_query($connection,$Delete);	

if($ret) //true 
{
	echo "<script>window.alert('Successfully Deleted!')</script>";
	echo "<script>window.location='transferList.php'</script>";
}
else
{
	echo "<p>Something went wrong in transfer delete " . mysqli_error($connection) .  "</p>";
}
?>