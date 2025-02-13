<?php  
session_start();

session_regenerate_id();
session_destroy();

echo "<script>window.alert('Successfully Logged Out!')</script>";
echo "<script>window.location='login.php'</script>";
?>
