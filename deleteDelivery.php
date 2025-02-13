<?php  
include('connect.php');

$DeliveryID=$_GET['DeliveryID'];

$Delete="DELETE FROM delivery WHERE DeliveryID='$DeliveryID' ";
$ret=mysqli_query($connection,$Delete);	

if($ret) //true 
{
	echo "<script>window.alert('Successfully Deleted!')</script>";
	echo "<script>window.location='deliveryList.php'</script>";
}
else
{
	echo "<p>Something went wrong in delivery delete " . mysqli_error($connection) .  "</p>";
}
?>