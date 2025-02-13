<?php
include('connect.php');

if (isset($_GET['EmployeeID'])) {
    $EmployeeID = mysqli_real_escape_string($connection, $_GET['EmployeeID']);

    // Validate EmployeeID
    if (empty($EmployeeID)) {
        echo "<script>alert('Error: Invalid Employee ID.');</script>";
        echo "<script>window.location='users.php';</script>";
        exit();
    }

    // Check if the employee is linked to active records
    $activeLinks = [];

    // Check purchases
    $checkPurchasesQuery = "SELECT * FROM purchase WHERE EmployeeID = ?";
    $stmtPurchases = mysqli_prepare($connection, $checkPurchasesQuery);
    mysqli_stmt_bind_param($stmtPurchases, "s", $EmployeeID);
    mysqli_stmt_execute($stmtPurchases);
    if (mysqli_stmt_get_result($stmtPurchases)->num_rows > 0) {
        $activeLinks[] = "purchases";
    }

    // Check sales
    $checkSalesQuery = "SELECT * FROM sale WHERE EmployeeID = ?";
    $stmtSales = mysqli_prepare($connection, $checkSalesQuery);
    mysqli_stmt_bind_param($stmtSales, "s", $EmployeeID);
    mysqli_stmt_execute($stmtSales);
    if (mysqli_stmt_get_result($stmtSales)->num_rows > 0) {
        $activeLinks[] = "sales";
    }

    // Check deliveries
    $checkDeliveriesQuery = "SELECT * FROM delivery WHERE EmployeeID = ?";
    $stmtDeliveries = mysqli_prepare($connection, $checkDeliveriesQuery);
    mysqli_stmt_bind_param($stmtDeliveries, "s", $EmployeeID);
    mysqli_stmt_execute($stmtDeliveries);
    if (mysqli_stmt_get_result($stmtDeliveries)->num_rows > 0) {
        $activeLinks[] = "deliveries";
    }

    // Check transfers
    $checkTransfersQuery = "SELECT * FROM transfer WHERE EmployeeID = ?";
    $stmtTransfers = mysqli_prepare($connection, $checkTransfersQuery);
    mysqli_stmt_bind_param($stmtTransfers, "s", $EmployeeID);
    mysqli_stmt_execute($stmtTransfers);
    if (mysqli_stmt_get_result($stmtTransfers)->num_rows > 0) {
        $activeLinks[] = "transfers";
    }

    // If there are active links, prevent deletion
    if (!empty($activeLinks)) {
        $activeTables = implode(", ", $activeLinks);
        echo "<script>alert('Error: Cannot delete employee. There are active links to the following: $activeTables.');</script>";
        echo "<script>window.location='users.php';</script>";
    } else {
        // Safe to delete the employee
        $deleteQuery = "DELETE FROM employee WHERE EmployeeID = ?";
        $deleteStmt = mysqli_prepare($connection, $deleteQuery);
        mysqli_stmt_bind_param($deleteStmt, "s", $EmployeeID);

        if (mysqli_stmt_execute($deleteStmt)) {
            echo "<script>alert('Employee deleted successfully.');</script>";
            echo "<script>window.location='users.php';</script>";
        } else {
            echo "<p>Error deleting employee: " . mysqli_error($connection) . "</p>";
        }
    }

    // Free statements
    mysqli_stmt_close($stmtPurchases);
    mysqli_stmt_close($stmtSales);
    mysqli_stmt_close($stmtDeliveries);
    mysqli_stmt_close($stmtTransfers);
    mysqli_stmt_close($deleteStmt);
} else {
    echo "<script>alert('Error: No Employee ID provided.');</script>";
    echo "<script>window.location='users.php';</script>";
}

// Close connection
mysqli_close($connection);
?>
