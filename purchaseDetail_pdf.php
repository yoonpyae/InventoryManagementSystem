<?php
require_once 'C:\xampp\htdocs\AungBiWinRiceTradingHouse\vendor\autoload.php';  // Include the TCPDF library

// Database connection (Make sure to use your actual connection details)
include('connect.php'); 
session_start();

// Create instance of TCPDF
$pdf = new TCPDF();

// Set document properties
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Aung Bi Win Rice Trading House');
$pdf->SetTitle('Purchase Records');
$pdf->SetSubject('Purchase Records PDF');
$pdf->SetKeywords('purchase, records, PDF');

// Set margins
$pdf->SetMargins(15, 20, 15);
$pdf->SetHeaderData('', 0, 'Purchase Records', 'Generated on ' . date('Y-m-d'));

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Query to fetch the specific purchase record
// Ensure PurchaseID is passed via GET request
if (isset($_GET['PurchaseID'])) {
    $PurchaseID=$_GET['PurchaseID'];
} else {
    die('PurchaseID not provided.');
}
 // Assuming the purchase ID is passed as a query parameter
$sql = "SELECT pu.*, e.Position,e.EmployeeName,sup.ContactPerson,sup.SupplierName,sup.Address,sup.PhoneNumber,w.WarehouseName
		 FROM purchase pu,employee e,supplier sup,warehouse w
		 WHERE pu.PurchaseID='$PurchaseID'
		 AND pu.EmployeeID=e.EmployeeID
     AND pu.WarehouseID=w.WarehouseID
		 AND pu.SupplierID=sup.SupplierID";  
$result = mysqli_query($connection, $sql);

// Check if the purchase record is found
if ($row = mysqli_fetch_assoc($result)) {
    // Supplier, employee, and purchase details
    $pdf->Cell(0, 10, 'Purchase Detail', 0, 1, 'C');
    $pdf->Ln(10);

    $pdf->Cell(30, 10, 'Supplier: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['SupplierName'], 0, 1, 'L');  // Replace with $row['Supplier']
    $pdf->Cell(30, 10, 'Employee: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['EmployeeName'], 0, 1, 'L');  // Replace with $row['Employee']
    $pdf->Cell(30, 10, 'Status: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['Status'], 0, 1, 'L');  // Replace with $row['Status']
    $pdf->Cell(30, 10, 'Warehouse: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['WarehouseName'], 0, 1, 'L');  // Replace with $row['Warehouse']
    $pdf->Ln(10);

    // Order summary
    $pdf->Cell(50, 10, 'Product', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Cost', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Qty', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Subtotal', 1, 1, 'C'); // Move to the next line after the header

    // Assuming you have a separate query to fetch purchase details
    $detailSql = "SELECT pu.*, pud.*, pro.ProductCost,pro.ProductName
		 FROM purchase pu,purchasedetail pud,product pro
		 WHERE pud.PurchaseID='$PurchaseID'
		 AND pu.PurchaseID=pud.PurchaseID
		 AND pud.ProductID=pro.ProductID";  
    $detailResult = mysqli_query($connection, $detailSql);

    // Table content
    while ($detailRow = mysqli_fetch_assoc($detailResult)) {
        $pdf->Cell(50, 10, $detailRow['ProductName'], 1, 0, 'C');
        $pdf->Cell(20, 10, $detailRow['ProductCost'], 1, 0, 'C');
        $pdf->Cell(20, 10, $detailRow['Quantity'], 1, 0, 'C');
        $pdf->Cell(30, 10, number_format($detailRow['Quantity']*$detailRow['ProductCost']), 1, 1, 'C'); // Move to the next line after each row
    }

    // Total summary
    $pdf->Ln(10);
    $pdf->Cell(30, 10, 'Total Quantity: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['TotalQuantity'], 0, 1, 'L');  // Replace with the actual total quantity
    $pdf->Cell(30, 10, 'Total Amount: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['TotalAmount'], 0, 1, 'L');  // Replace with the actual total amount
    $pdf->Cell(30, 10, 'VAT (1%): ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['VAT'], 0, 1, 'L');  // Replace with the actual VAT
    $pdf->Cell(30, 10, 'Grand Total: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['GrandTotal'], 0, 1, 'L');  // Replace with the actual grand total

} else {
    $pdf->Cell(0, 10, 'No purchase record found.', 0, 1, 'C');
}

// Output the PDF (force download)
$pdf->Output('purchasedetail.pdf', 'D'); // 'D' will trigger download

// Close database connection
mysqli_close($connection);
?>
