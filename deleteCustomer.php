<?php  
include('connect.php');

if (isset($_GET['CustomerID'])) {
    $CustomerID = mysqli_real_escape_string($connection, $_GET['CustomerID']);

    // Validate the CustomerID
    if (empty($CustomerID)) {
        echo "<script>alert('Error: Invalid Customer ID.');</script>";
        echo "<script>window.location='customer.php';</script>";
        exit();
    }

    // Check if the customer is linked to any active sales
    $checkQuery = "SELECT * FROM sale WHERE CustomerID = ?";
    $stmt = mysqli_prepare($connection, $checkQuery);
    mysqli_stmt_bind_param($stmt, "s", $CustomerID);
    mysqli_stmt_execute($stmt);
    $checkResult = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($checkResult) > 0) {
        // Customer has active sales
        echo "<script>alert('Error: Cannot delete customer. There are active sales linked to this customer.');</script>";
        echo "<script>window.location='customer.php';</script>";
    } else {
        // Safe to delete
        $deleteQuery = "DELETE FROM customer WHERE CustomerID = ?";
        $deleteStmt = mysqli_prepare($connection, $deleteQuery);
        mysqli_stmt_bind_param($deleteStmt, "s", $CustomerID);

        if (mysqli_stmt_execute($deleteStmt)) {
            echo "<script>alert('Customer deleted successfully.');</script>";
            echo "<script>window.location='customer.php';</script>";
        } else {
            echo "<p>Error deleting customer: " . mysqli_error($connection) . "</p>";
        }
    }
    
    // Free result and close statement
    mysqli_stmt_close($stmt);
} else {
    echo "<script>alert('Error: No Customer ID provided.');</script>";
    echo "<script>window.location='customer.php';</script>";
}

// Close the connection
mysqli_close($connection);
?>
