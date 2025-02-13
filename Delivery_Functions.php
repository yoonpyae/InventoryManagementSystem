<?php  
function AddSale($SaleID,$DeliveryPrice,$DeliveryQuantity)
{
	include('connect.php');

	$query="SELECT * FROM sale WHERE SaleID='$SaleID' ";
	$ret=mysqli_query($connection,$query);
	$count=mysqli_num_rows($ret);
	$arr=mysqli_fetch_array($ret);

	if($count < 1) 
	{
		echo "<p>No Sale Information Found!</p>";
		exit();
	}

	if($DeliveryQuantity < 1) 
	{
		echo "<script>window.alert('Please enter correct quantity!')</script>";
		echo "<script>window.location='createDelivery.php'</script>";
	}

	if(isset($_SESSION['Delivery_Functions'])) 
	{
		$Index=IndexOf($SaleID);

		if($Index == -1) 
		{
			$size=count($_SESSION['Delivery_Functions']);

			$_SESSION['Delivery_Functions'][$size]['SaleID']=$SaleID;
			$_SESSION['Delivery_Functions'][$size]['DeliveryQuantity']=$DeliveryQuantity;
            $_SESSION['Delivery_Functions'][$size]['DeliveryPrice']=$DeliveryPrice;

			$_SESSION['Delivery_Functions'][$size]['GrandTotal']=$arr['GrandTotal'];
            $_SESSION['Delivery_Functions'][$size]['TotalQuantity']=$arr['TotalQuantity'];
		}
		else
		{
			$_SESSION['Delivery_Functions'][$Index]['DeliveryQuantity']+=$DeliveryQuantity;
		}
	}
	else
	{
		$_SESSION['Delivery_Functions']=array(); // Create Session Array List

		$_SESSION['Delivery_Functions'][0]['SaleID']=$SaleID;
        $_SESSION['Delivery_Functions'][0]['DeliveryPrice']=$DeliveryPrice;
		$_SESSION['Delivery_Functions'][0]['DeliveryQuantity']=$DeliveryQuantity;

		$_SESSION['Delivery_Functions'][0]['GrandTotal']=$arr['GrandTotal'];
        $_SESSION['Delivery_Functions'][0]['TotalQuantity']=$arr['TotalQuantity'];
	}
	echo "<script>window.location='createDelivery.php'</script>";
}

function RemoveSale($SaleID)
{
	$Index=IndexOf($SaleID);

	unset($_SESSION['Delivery_Functions'][$Index]);
	$_SESSION['Delivery_Functions']=array_values($_SESSION['Delivery_Functions']);

	echo "<script>window.location='createDelivery.php'</script>";
}

function ClearAll()
{
	unset($_SESSION['Delivery_Functions']);
	echo "<script>window.location='createDelivery.php'</script>";
}

function CalculateTotalQuantity()
{
	$TotalQuantity=0;

	if(!isset($_SESSION['Delivery_Functions'])) 
	{
		$TotalQuantity=0;
		return $TotalQuantity;
	}
	else
	{
		$size=count($_SESSION['Delivery_Functions']);

		for($y=0;$y<$size;$y++) 
		{ 
			$DeliveryQuantity=$_SESSION['Delivery_Functions'][$y]['DeliveryQuantity'];
			$TotalQuantity+=$DeliveryQuantity;
		}
		return $TotalQuantity;
	}
}

function CalculateTotalAmount()
{
	$TotalAmount=0;

	if(!isset($_SESSION['Delivery_Functions'])) 
	{
		$TotalAmount=0;
		return $TotalAmount;
	}
	else
	{
		$size=count($_SESSION['Delivery_Functions']);

		for($z=0;$z<$size;$z++) 
		{ 
			$DeliveryQuantity=$_SESSION['Delivery_Functions'][$z]['DeliveryQuantity'];
			$DeliveryPrice=$_SESSION['Delivery_Functions'][$z]['DeliveryPrice'];

			$TotalAmount+=($DeliveryQuantity * $DeliveryPrice);
		}
		return $TotalAmount;
	}
}

function IndexOf($SaleID)
{
	if(!isset($_SESSION['Delivery_Functions'])) 
	{
		return -1;
	}

	$size=count($_SESSION['Delivery_Functions']);

	if($size < 1) 
	{
		return -1;
	}
	else
	{
		for ($i=0; $i < $size; $i++) 
		{ 
			if($SaleID == $_SESSION['Delivery_Functions'][$i]['SaleID']) 
			{
				return $i;
			}
		}
		return -1;
	}
}
?>