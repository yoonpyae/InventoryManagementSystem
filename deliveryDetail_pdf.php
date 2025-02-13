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
$pdf->SetTitle('Delivery Records');
$pdf->SetSubject('Delivery Records PDF');
$pdf->SetKeywords('Delivery, records, PDF');

// Set margins
$pdf->SetMargins(15, 20, 15);
$pdf->SetHeaderData('', 0, 'Delivery Records', 'Generated on ' . date('Y-m-d'));

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Query to fetch the specific Delivery record
// Ensure DeliveryID is passed via GET request
if (isset($_GET['DeliveryID'])) {
    $DeliveryID=$_GET['DeliveryID'];
} else {
    die('DeliveryID not provided.');
}
 // Assuming the Delivery ID is passed as a query parameter
$sql = "SELECT da.*, e.Position,e.EmployeeName
		 FROM delivery da,employee e
		 WHERE da.DeliveryID='$DeliveryID'
		 AND da.EmployeeID=e.EmployeeID";  
$result = mysqli_query($connection, $sql);

// Check if the Delivery record is found
if ($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell(0, 10, 'Delivery Detail', 0, 1, 'C');
    $pdf->Ln(10);

    $pdf->Cell(30, 10, 'Employee: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['EmployeeName'], 0, 1, 'L'); 
    $pdf->Cell(30, 10, 'Position: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['Position'], 0, 1, 'L'); 
    $pdf->Cell(30, 10, 'DeliveryID: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['DeliveryID'], 0, 1, 'L'); 
    $pdf->Cell(30, 10, 'Note: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['Note'], 0, 1, 'L'); 
    $pdf->Cell(30, 10, 'Status: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['Status'], 0, 1, 'L');   
    $pdf->Ln(10);

    // Order summary
    $pdf->Cell(30, 10, 'SaleID', 1, 0, 'C');
    $pdf->Cell(30, 10, 'DeliveryFee', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Qty', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Subtotal', 1, 1, 'C'); // Move to the next line after the header

    // Assuming you have a separate query to fetch Delivery details
    $detailSql = "SELECT da.*, dad.*, s.SaleID,s.TotalQuantity,s.GrandTotal
		 FROM delivery da,deliverydetail dad,sale s
		 WHERE dad.DeliveryID='$DeliveryID'
		 AND da.DeliveryID=dad.DeliveryID
		 AND dad.SaleID=s.SaleID";  
    $detailResult = mysqli_query($connection, $detailSql);

    // Table content
    while ($detailRow = mysqli_fetch_assoc($detailResult)) {
        $pdf->Cell(30, 10, $detailRow['SaleID'], 1, 0, 'C');
        $pdf->Cell(30, 10, $detailRow['DeliveryFee'], 1, 0, 'C');
        $pdf->Cell(20, 10, $detailRow['Quantity'], 1, 0, 'C');
        $pdf->Cell(30, 10, number_format($detailRow['Quantity']*$detailRow['DeliveryFee']), 1, 1, 'C'); // Move to the next line after each row
    }

    // Total summary
    $pdf->Ln(10);
    $pdf->Cell(30, 10, 'Total Quantity: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['TotalQuantity'], 0, 1, 'L');  // Replace with the actual total quantity
    $pdf->Cell(30, 10, 'Total Amount: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['TotalAmount'], 0, 1, 'L');  // Replace with the actual total amount

} else {
    $pdf->Cell(0, 10, 'No Delivery record found.', 0, 1, 'C');
}

// Output the PDF (force download)
$pdf->Output('Deliverydetail.pdf', 'D'); // 'D' will trigger download

// Close database connection
mysqli_close($connection);
?>
