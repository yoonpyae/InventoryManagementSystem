<?php  
function AddProduct($ProductID,$SaleQuantity)
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

	if($SaleQuantity < 1) 
	{
		echo "<script>window.alert('Please enter correct quantity!')</script>";
		echo "<script>window.location='createSale.php'</script>";
	}

	if(isset($_SESSION['Sale_Functions'])) 
	{
		$Index=IndexOf($ProductID);

		if($Index == -1) 
		{
			$size=count($_SESSION['Sale_Functions']);

			$_SESSION['Sale_Functions'][$size]['ProductID']=$ProductID;
			$_SESSION['Sale_Functions'][$size]['SaleQuantity']=$SaleQuantity;

			$_SESSION['Sale_Functions'][$size]['ProductName']=$arr['ProductName'];
			$_SESSION['Sale_Functions'][$size]['ProductPrice']=$arr['ProductPrice'];
		}
		else
		{
			$_SESSION['Sale_Functions'][$Index]['SaleQuantity']+=$SaleQuantity;
		}
	}
	else
	{
		$_SESSION['Sale_Functions']=array(); // Create Session Array List

		$_SESSION['Sale_Functions'][0]['ProductID']=$ProductID;
		$_SESSION['Sale_Functions'][0]['SaleQuantity']=$SaleQuantity;

		$_SESSION['Sale_Functions'][0]['ProductName']=$arr['ProductName'];
		$_SESSION['Sale_Functions'][0]['ProductPrice']=$arr['ProductPrice'];
	}
	echo "<script>window.location='createSale.php'</script>";
}

function RemoveProduct($ProductID)
{
	$Index=IndexOf($ProductID);

	unset($_SESSION['Sale_Functions'][$Index]);
	$_SESSION['Sale_Functions']=array_values($_SESSION['Sale_Functions']);

	echo "<script>window.location='createSale.php'</script>";
}

function ClearAll()
{
	unset($_SESSION['Sale_Functions']);
	echo "<script>window.location='createSale.php'</script>";
}

function CalculateTotalQuantity()
{
	$TotalQuantity=0;

	if(!isset($_SESSION['Sale_Functions'])) 
	{
		$TotalQuantity=0;
		return $TotalQuantity;
	}
	else
	{
		$size=count($_SESSION['Sale_Functions']);

		for($y=0;$y<$size;$y++) 
		{ 
			$SaleQuantity=$_SESSION['Sale_Functions'][$y]['SaleQuantity'];
			$TotalQuantity+=$SaleQuantity;
		}
		return $TotalQuantity;
	}
}

function CalculateTotalAmount()
{
	$TotalAmount=0;

	if(!isset($_SESSION['Sale_Functions'])) 
	{
		$TotalAmount=0;
		return $TotalAmount;
	}
	else
	{
		$size=count($_SESSION['Sale_Functions']);

		for($z=0;$z<$size;$z++) 
		{ 
			$SaleQuantity=$_SESSION['Sale_Functions'][$z]['SaleQuantity'];
			$ProductPrice=$_SESSION['Sale_Functions'][$z]['ProductPrice'];

			$TotalAmount+=($SaleQuantity * $ProductPrice);
		}
		return $TotalAmount;
	}
}

function IndexOf($ProductID)
{
	if(!isset($_SESSION['Sale_Functions'])) 
	{
		return -1;
	}

	$size=count($_SESSION['Sale_Functions']);

	if($size < 1) 
	{
		return -1;
	}
	else
	{
		for ($i=0; $i < $size; $i++) 
		{ 
			if($ProductID == $_SESSION['Sale_Functions'][$i]['ProductID']) 
			{
				return $i;
			}
		}
		return -1;
	}
}
?>