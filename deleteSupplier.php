<?php  
include('connect.php');

if (isset($_GET['SupplierID'])) {
    $supplierID = mysqli_real_escape_string($connection, $_GET['SupplierID']);
    
    // Check if the supplier is linked to any active purchases
    $checkQuery = "SELECT * FROM purchase WHERE SupplierID = '$supplierID'";
    $checkResult = mysqli_query($connection, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Supplier has active purchases
        echo "<script>alert('Error: Cannot delete supplier. There are active purchases linked to this supplier.');</script>";
        echo "<script>window.location='supplier.php';</script>";
    } else {
        // Safe to delete
        $deleteQuery = "DELETE FROM supplier WHERE SupplierID = '$supplierID'";
        $deleteResult = mysqli_query($connection, $deleteQuery);

        if ($deleteResult) {
            echo "<script>alert('Supplier deleted successfully.');</script>";
            echo "<script>window.location='supplier.php';</script>";
        } else {
            echo "<p>Error deleting supplier: " . mysqli_error($connection) . "</p>";
        }
    }
}

?>