<?php  
include('connect.php');

$PurchaseID=$_GET['PurchaseID'];

$Delete="DELETE FROM purchase WHERE PurchaseID='$PurchaseID' ";
$ret=mysqli_query($connection,$Delete);	

if($ret) //true 
{
	echo "<script>window.alert('Successfully Deleted!')</script>";
	echo "<script>window.location='purchaseList.php'</script>";
}
else
{
	echo "<p>Something went wrong in purchase delete " . mysqli_error($connection) .  "</p>";
}
?>