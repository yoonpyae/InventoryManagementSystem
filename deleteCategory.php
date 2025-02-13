<?php
include('connect.php');

if (isset($_GET['CategoryID'])) {
    $CategoryID = mysqli_real_escape_string($connection, $_GET['CategoryID']);
    
    // Check if the category is linked to any active products
    $checkQuery = "SELECT * FROM product WHERE CategoryID = '$CategoryID'";
    $checkResult = mysqli_query($connection, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Category has active products
        echo "<script>alert('Error: Cannot delete category. There are active products linked to this category.');</script>";
        echo "<script>window.location='categoryList.php';</script>";
    } else {
        // Safe to delete the category
        $deleteQuery = "DELETE FROM category WHERE CategoryID = '$CategoryID'";
        $deleteResult = mysqli_query($connection, $deleteQuery);

        if ($deleteResult) {
            echo "<script>alert('Category deleted successfully.');</script>";
            echo "<script>window.location='categoryList.php';</script>";
        } else {
            echo "<p>Error deleting category: " . mysqli_error($connection) . "</p>";
        }
    }
}
?>
