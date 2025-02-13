<?php
include('connect.php');

if (isset($_GET['ProductID'])) {
    $ProductID = mysqli_real_escape_string($connection, $_GET['ProductID']);

    // Check if the product is linked to any active purchases
    $checkPurchasesQuery = "SELECT COUNT(*) AS count FROM purchasedetail WHERE ProductID = ?";
    $stmtPurchases = mysqli_prepare($connection, $checkPurchasesQuery);
    mysqli_stmt_bind_param($stmtPurchases, "s", $ProductID);
    mysqli_stmt_execute($stmtPurchases);
    mysqli_stmt_bind_result($stmtPurchases, $purchaseCount);
    mysqli_stmt_fetch($stmtPurchases);
    mysqli_stmt_close($stmtPurchases);

    // Check if the product is linked to any active sales
    $checkSalesQuery = "SELECT COUNT(*) AS count FROM saledetail WHERE ProductID = ?";
    $stmtSales = mysqli_prepare($connection, $checkSalesQuery);
    mysqli_stmt_bind_param($stmtSales, "s", $ProductID);
    mysqli_stmt_execute($stmtSales);
    mysqli_stmt_bind_result($stmtSales, $salesCount);
    mysqli_stmt_fetch($stmtSales);
    mysqli_stmt_close($stmtSales);

    // Check if the product is linked to any active transfers
    $checkTransfersQuery = "SELECT COUNT(*) AS count FROM transferdetail WHERE ProductID = ?";
    $stmtTransfers = mysqli_prepare($connection, $checkTransfersQuery);
    mysqli_stmt_bind_param($stmtTransfers, "s", $ProductID);
    mysqli_stmt_execute($stmtTransfers);
    mysqli_stmt_bind_result($stmtTransfers, $transferCount);
    mysqli_stmt_fetch($stmtTransfers);
    mysqli_stmt_close($stmtTransfers);

    // Check if the product is linked to any active transactions
    if ($purchaseCount > 0 || $salesCount > 0 || $transferCount > 0) {
        echo "<script>alert('Error: Cannot delete product. There are active purchases, sales, or transfers linked to this product.');</script>";
        echo "<script>window.location='productList.php';</script>";
    } else {
        // Safe to delete the product
        $deleteQuery = "DELETE FROM product WHERE ProductID = ?";
        $deleteStmt = mysqli_prepare($connection, $deleteQuery);
        mysqli_stmt_bind_param($deleteStmt, "s", $ProductID);

        if (mysqli_stmt_execute($deleteStmt)) {
            echo "<script>alert('Product deleted successfully.');</script>";
            echo "<script>window.location='productList.php';</script>";
        } else {
            echo "<p>Error deleting product: " . mysqli_error($connection) . "</p>";
        }

        mysqli_stmt_close($deleteStmt);
    }
} else {
    echo "<script>alert('Error: No Product ID provided.');</script>";
    echo "<script>window.location='productList.php';</script>";
}

// Close the connection
mysqli_close($connection);
?>
