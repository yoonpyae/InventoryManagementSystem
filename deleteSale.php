<?php
include('connect.php');

if (isset($_GET['SaleID'])) {
    $SaleID = mysqli_real_escape_string($connection, $_GET['SaleID']);

    // Validate the SaleID
    if (empty($SaleID)) {
        echo "<script>alert('Error: Invalid Sale ID.');</script>";
        echo "<script>window.location='saleList.php';</script>";
        exit();
    }

    // Check if the sale is linked to any active deliveries
    $checkDeliveryQuery = "SELECT * FROM deliverydetail WHERE SaleID = ?";
    $stmtDelivery = mysqli_prepare($connection, $checkDeliveryQuery);
    mysqli_stmt_bind_param($stmtDelivery, "s", $SaleID);
    mysqli_stmt_execute($stmtDelivery);
    $deliveryResult = mysqli_stmt_get_result($stmtDelivery);

    if (mysqli_num_rows($deliveryResult) > 0) {
        // Sale is linked to active deliveries
        echo "<script>alert('Error: Cannot delete sale. There are active deliveries linked to this sale.');</script>";
        echo "<script>window.location='saleList.php';</script>";
    } else {
        // Safe to delete the sale
        $deleteQuery = "DELETE FROM sale WHERE SaleID = ?";
        $deleteStmt = mysqli_prepare($connection, $deleteQuery);
        mysqli_stmt_bind_param($deleteStmt, "s", $SaleID);

        if (mysqli_stmt_execute($deleteStmt)) {
            echo "<script>alert('Sale deleted successfully.');</script>";
            echo "<script>window.location='saleList.php';</script>";
        } else {
            echo "<p>Error deleting sale: " . mysqli_error($connection) . "</p>";
        }
    }

    // Free result sets and close statements
    mysqli_stmt_close($stmtDelivery);
    mysqli_stmt_close($deleteStmt);
} else {
    echo "<script>alert('Error: No Sale ID provided.');</script>";
    echo "<script>window.location='saleList.php';</script>";
}

// Close the connection
mysqli_close($connection);
?>
