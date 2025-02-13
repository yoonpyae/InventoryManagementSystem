<?php  
function AddProduct($ProductID,$PurchaseQuantity)
{
	include('connect.php');

	$query="SELECT * FROM product WHERE ProductID='$ProductID' ";
	$ret=mysqli_query($connection,$query);
	$count=mysqli_num_rows($ret);
	$arr=mysqli_fetch_array($ret);

	if($count < 1) 
	{
		echo "<p>No Product Information Found!</p>";
		exit();
	}

	if($PurchaseQuantity < 1) 
	{
		echo "<script>window.alert('Please enter correct quantity!')</script>";
		echo "<script>window.location='createPurchase.php'</script>";
	}

	if(isset($_SESSION['Purchase_Functions'])) 
	{
		$Index=IndexOf($ProductID);

		if($Index == -1) 
		{
			$size=count($_SESSION['Purchase_Functions']);

			$_SESSION['Purchase_Functions'][$size]['ProductID']=$ProductID;
			$_SESSION['Purchase_Functions'][$size]['PurchaseQuantity']=$PurchaseQuantity;

			$_SESSION['Purchase_Functions'][$size]['ProductName']=$arr['ProductName'];
			$_SESSION['Purchase_Functions'][$size]['ProductCost']=$arr['ProductCost'];
		}
		else
		{
			$_SESSION['Purchase_Functions'][$Index]['PurchaseQuantity']+=$PurchaseQuantity;
		}
	}
	else
	{
		$_SESSION['Purchase_Functions']=array(); // Create Session Array List

		$_SESSION['Purchase_Functions'][0]['ProductID']=$ProductID;
		$_SESSION['Purchase_Functions'][0]['PurchaseQuantity']=$PurchaseQuantity;

		$_SESSION['Purchase_Functions'][0]['ProductName']=$arr['ProductName'];
		$_SESSION['Purchase_Functions'][0]['ProductCost']=$arr['ProductCost'];
	}
	echo "<script>window.location='createPurchase.php'</script>";
}

function RemoveProduct($ProductID)
{
	$Index=IndexOf($ProductID);

	unset($_SESSION['Purchase_Functions'][$Index]);
	$_SESSION['Purchase_Functions']=array_values($_SESSION['Purchase_Functions']);

	echo "<script>window.location='createPurchase.php'</script>";
}

function ClearAll()
{
	unset($_SESSION['Purchase_Functions']);
	echo "<script>window.location='createPurchase.php'</script>";
}

function CalculateTotalQuantity()
{
	$TotalQuantity=0;

	if(!isset($_SESSION['Purchase_Functions'])) 
	{
		$TotalQuantity=0;
		return $TotalQuantity;
	}
	else
	{
		$size=count($_SESSION['Purchase_Functions']);

		for($y=0;$y<$size;$y++) 
		{ 
			$PurchaseQuantity=$_SESSION['Purchase_Functions'][$y]['PurchaseQuantity'];
			$TotalQuantity+=$PurchaseQuantity;
		}
		return $TotalQuantity;
	}
}

function CalculateTotalAmount()
{
	$TotalAmount=0;

	if(!isset($_SESSION['Purchase_Functions'])) 
	{
		$TotalAmount=0;
		return $TotalAmount;
	}
	else
	{
		$size=count($_SESSION['Purchase_Functions']);

		for($z=0;$z<$size;$z++) 
		{ 
			$PurchaseQuantity=$_SESSION['Purchase_Functions'][$z]['PurchaseQuantity'];
			$ProductCost=$_SESSION['Purchase_Functions'][$z]['ProductCost'];

			$TotalAmount+=($PurchaseQuantity * $ProductCost);
		}
		return $TotalAmount;
	}
}

function IndexOf($ProductID)
{
	if(!isset($_SESSION['Purchase_Functions'])) 
	{
		return -1;
	}

	$size=count($_SESSION['Purchase_Functions']);

	if($size < 1) 
	{
		return -1;
	}
	else
	{
		for ($i=0; $i < $size; $i++) 
		{ 
			if($ProductID == $_SESSION['Purchase_Functions'][$i]['ProductID']) 
			{
				return $i;
			}
		}
		return -1;
	}
}
?>