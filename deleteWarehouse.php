<?php
include('connect.php');

if (isset($_GET['WarehouseID'])) {
    $WarehouseID = mysqli_real_escape_string($connection, $_GET['WarehouseID']);

    // Validate the WarehouseID
    if (empty($WarehouseID)) {
        echo "<script>alert('Error: Invalid warehouse ID.');</script>";
        echo "<script>window.location='warehouse.php';</script>";
        exit();
    }

    // Check if the warehouse is linked to any active purchases
    $checkPurchasesQuery = "SELECT * FROM purchase WHERE WarehouseID = ?";
    $stmtPurchases = mysqli_prepare($connection, $checkPurchasesQuery);
    mysqli_stmt_bind_param($stmtPurchases, "s", $WarehouseID);
    mysqli_stmt_execute($stmtPurchases);
    $purchaseResult = mysqli_stmt_get_result($stmtPurchases);

    // Check if the warehouse is linked to any active sales
    $checkSalesQuery = "SELECT * FROM sale WHERE WarehouseID = ?";
    $stmtSales = mysqli_prepare($connection, $checkSalesQuery);
    mysqli_stmt_bind_param($stmtSales, "s", $WarehouseID);
    mysqli_stmt_execute($stmtSales);
    $salesResult = mysqli_stmt_get_result($stmtSales);

    if (mysqli_num_rows($purchaseResult) > 0 || mysqli_num_rows($salesResult) > 0) {
        // warehouse is linked to active purchases or sales
        echo "<script>alert('Error: Cannot delete warehouse. There are active purchases or sales linked to this warehouse.');</script>";
        echo "<script>window.location='warehouse.php';</script>";
    } else {
        // Safe to delete the warehouse
        $deleteQuery = "DELETE FROM warehouse WHERE WarehouseID = ?";
        $deleteStmt = mysqli_prepare($connection, $deleteQuery);
        mysqli_stmt_bind_param($deleteStmt, "s", $WarehouseID);

        if (mysqli_stmt_execute($deleteStmt)) {
            echo "<script>alert('warehouse deleted successfully.');</script>";
            echo "<script>window.location='warehouse.php';</script>";
        } else {
            echo "<p>Error deleting warehouse: " . mysqli_error($connection) . "</p>";
        }
    }

    // Free result sets and close statements
    mysqli_stmt_close($stmtPurchases);
    mysqli_stmt_close($stmtSales);
} else {
    echo "<script>alert('Error: No warehouse ID provided.');</script>";
    echo "<script>window.location='warehouse.php';</script>";
}

// Close the connection
mysqli_close($connection);
?>
