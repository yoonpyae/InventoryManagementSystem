<?php
include('connect.php');

// Create table SQL
$DeliveryDetailCreate = "INSERT INTO permissions (EmployeeID, ModuleName, CanCreate, CanRead, CanUpdate, CanDelete)
VALUES
(4, 'dashboard', 1, 1, 1, 0),
(4, 'purchase', 1, 1, 1, 0),
(4, 'sale', 1, 1, 1, 0),
(4, 'delivery', 1, 1, 1, 0),
(4, 'transfer', 1, 1, 1, 0),
(4, 'reports', 1, 1, 1, 0)";

// Execute query
$runningQuery = mysqli_query($connection, $DeliveryDetailCreate);

// Check for errors
if ($runningQuery) {
    echo "<script>window.alert('Table DeliveryDetail created successfully!')</script>";
} else {
    echo "Error: " . mysqli_error($connection);
}
?>
