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
$pdf->SetTitle('Transfer Records');
$pdf->SetSubject('Transfer Records PDF');
$pdf->SetKeywords('Transfer, records, PDF');

// Set margins
$pdf->SetMargins(15, 20, 15);
$pdf->SetHeaderData('', 0, 'Transfer Records', 'Generated on ' . date('Y-m-d'));

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Query to fetch the specific Transfer record
// Ensure TransferID is passed via GET request
if (isset($_GET['TransferID'])) {
    $TransferID=$_GET['TransferID'];
} else {
    die('TransferID not provided.');
}
 // Assuming the Transfer ID is passed as a query parameter
$sql = "SELECT ta.*, e.Position,e.EmployeeName,w1.WarehouseName AS FromWarehouseName, w2.WarehouseName AS ToWarehouseName,w1.Location AS FromWarehouseLocation, w2.Location AS ToWarehouseLocation
		 FROM transfer ta,employee e,warehouse w1, warehouse w2
		 WHERE ta.TransferID='$TransferID'
		 AND ta.EmployeeID=e.EmployeeID
     AND ta.fromWarehouseID=w1.WarehouseID
		 AND ta.toWarehouseID=w2.WarehouseID";  
$result = mysqli_query($connection, $sql);

// Check if the Transfer record is found
if ($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell(0, 10, 'Transfer Detail', 0, 1, 'C');
    $pdf->Ln(10);

    $pdf->Cell(35, 10, 'FromWarehouse: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['FromWarehouseName'], 0, 1, 'L'); 
    $pdf->Cell(18, 10, 'Location: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['FromWarehouseLocation'], 0, 1, 'L'); 
    $pdf->Cell(30, 10, 'ToWarehouse: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['ToWarehouseName'], 0, 1, 'L'); 
    $pdf->Cell(18, 10, 'Location: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['ToWarehouseLocation'], 0, 1, 'L'); 
    $pdf->Cell(30, 10, 'Employee: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['EmployeeName'], 0, 1, 'L');  
    $pdf->Cell(30, 10, 'Status: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['Status'], 0, 1, 'L');   
    $pdf->Ln(10);

    // Order summary
    $pdf->Cell(50, 10, 'Product', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Cost', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Qty', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Subtotal', 1, 1, 'C'); // Move to the next line after the header

    // Assuming you have a separate query to fetch Transfer details
    $detailSql = "SELECT ta.*, tad.*, pro.ProductCost,pro.ProductName
		 FROM transfer ta,transferdetail tad,product pro
		 WHERE tad.TransferID='$TransferID'
		 AND ta.TransferID=tad.TransferID
		 AND tad.ProductID=pro.ProductID";  
    $detailResult = mysqli_query($connection, $detailSql);

    // Table content
    while ($detailRow = mysqli_fetch_assoc($detailResult)) {
        $pdf->Cell(50, 10, $detailRow['ProductName'], 1, 0, 'C');
        $pdf->Cell(20, 10, $detailRow['Price'], 1, 0, 'C');
        $pdf->Cell(20, 10, $detailRow['Quantity'], 1, 0, 'C');
        $pdf->Cell(30, 10, number_format($detailRow['Quantity']*$detailRow['Price']), 1, 1, 'C'); // Move to the next line after each row
    }

    // Total summary
    $pdf->Ln(10);
    $pdf->Cell(30, 10, 'Total Quantity: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['TotalQuantity'], 0, 1, 'L');  // Replace with the actual total quantity
    $pdf->Cell(30, 10, 'Total Amount: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['TotalAmount'], 0, 1, 'L');  // Replace with the actual total amount

} else {
    $pdf->Cell(0, 10, 'No Transfer record found.', 0, 1, 'C');
}

// Output the PDF (force download)
$pdf->Output('Transferdetail.pdf', 'D'); // 'D' will trigger download

// Close database connection
mysqli_close($connection);
?>
