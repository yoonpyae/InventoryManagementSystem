<?php  
function AddProduct($ProductID,$TransferPrice,$TransferQuantity)
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

	if($TransferQuantity < 1) 
	{
		echo "<script>window.alert('Please enter correct quantity!')</script>";
		echo "<script>window.location='createTransfer.php'</script>";
	}


	if(isset($_SESSION['Transfer_Functions'])) 
	{
		$Index=IndexOf($ProductID);

		if($Index == -1) 
		{
			$size=count($_SESSION['Transfer_Functions']);

			$_SESSION['Transfer_Functions'][$size]['ProductID']=$ProductID;
			$_SESSION['Transfer_Functions'][$size]['TransferQuantity']=$TransferQuantity;
            $_SESSION['Transfer_Functions'][$size]['TransferPrice']=$TransferPrice;

			$_SESSION['Transfer_Functions'][$size]['ProductName']=$arr['ProductName'];
            $_SESSION['Transfer_Functions'][$size]['Quantity']=$arr['Quantity'];
		}
		else
		{
			$_SESSION['Transfer_Functions'][$Index]['TransferQuantity']+=$TransferQuantity;
		}
	}
	else
	{
		$_SESSION['Transfer_Functions']=array(); // Create Session Array List

		$_SESSION['Transfer_Functions'][0]['ProductID']=$ProductID;
        $_SESSION['Transfer_Functions'][0]['TransferPrice']=$TransferPrice;
		$_SESSION['Transfer_Functions'][0]['TransferQuantity']=$TransferQuantity;

		$_SESSION['Transfer_Functions'][0]['ProductName']=$arr['ProductName'];
        $_SESSION['Transfer_Functions'][0]['Quantity']=$arr['Quantity'];
	}
	echo "<script>window.location='createTransfer.php'</script>";
}

function RemoveProduct($ProductID)
{
	$Index=IndexOf($ProductID);

	unset($_SESSION['Transfer_Functions'][$Index]);
	$_SESSION['Transfer_Functions']=array_values($_SESSION['Transfer_Functions']);

	echo "<script>window.location='createTransfer.php'</script>";
}

function ClearAll()
{
	unset($_SESSION['Transfer_Functions']);
	echo "<script>window.location='createTransfer.php'</script>";
}

function CalculateTotalQuantity()
{
	$TotalQuantity=0;

	if(!isset($_SESSION['Transfer_Functions'])) 
	{
		$TotalQuantity=0;
		return $TotalQuantity;
	}
	else
	{
		$size=count($_SESSION['Transfer_Functions']);

		for($y=0;$y<$size;$y++) 
		{ 
			$TransferQuantity=$_SESSION['Transfer_Functions'][$y]['TransferQuantity'];
			$TotalQuantity+=$TransferQuantity;
		}
		return $TotalQuantity;
	}
}

function CalculateTotalAmount()
{
	$TotalAmount=0;

	if(!isset($_SESSION['Transfer_Functions'])) 
	{
		$TotalAmount=0;
		return $TotalAmount;
	}
	else
	{
		$size=count($_SESSION['Transfer_Functions']);

		for($z=0;$z<$size;$z++) 
		{ 
			$TransferQuantity=$_SESSION['Transfer_Functions'][$z]['TransferQuantity'];
			$TransferPrice=$_SESSION['Transfer_Functions'][$z]['TransferPrice'];

			$TotalAmount+=($TransferQuantity * $TransferPrice);
		}
		return $TotalAmount;
	}
}

function IndexOf($ProductID)
{
	if(!isset($_SESSION['Transfer_Functions'])) 
	{
		return -1;
	}

	$size=count($_SESSION['Transfer_Functions']);

	if($size < 1) 
	{
		return -1;
	}
	else
	{
		for ($i=0; $i < $size; $i++) 
		{ 
			if($ProductID == $_SESSION['Transfer_Functions'][$i]['ProductID']) 
			{
				return $i;
			}
		}
		return -1;
	}
}
?>