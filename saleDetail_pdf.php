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
$pdf->SetTitle('Sale Records');
$pdf->SetSubject('Sale Records PDF');
$pdf->SetKeywords('Sale, records, PDF');

// Set margins
$pdf->SetMargins(15, 20, 15);
$pdf->SetHeaderData('', 0, 'Sale Records', 'Generated on ' . date('Y-m-d'));

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Query to fetch the specific Sale record
// Ensure SaleID is passed via GET request
if (isset($_GET['SaleID'])) {
    $SaleID=$_GET['SaleID'];
} else {
    die('SaleID not provided.');
}
 // Assuming the Sale ID is passed as a query parameter
$sql = "SELECT sa.*, e.Position,e.EmployeeName,c.CustomerName,c.Address,c.PhoneNumber,w.WarehouseName
		 FROM sale sa,employee e,customer c,warehouse w
		 WHERE sa.SaleID='$SaleID'
		 AND sa.EmployeeID=e.EmployeeID
     AND sa.WarehouseID=w.WarehouseID
		 AND sa.CustomerID=c.CustomerID";  
$result = mysqli_query($connection, $sql);

// Check if the Sale record is found
if ($row = mysqli_fetch_assoc($result)) {
    // Supplier, employee, and Sale details
    $pdf->Cell(0, 10, 'Sale Detail', 0, 1, 'C');
    $pdf->Ln(10);

    $pdf->Cell(30, 10, 'Customer: ', 0, 0, 'L');
    $pdf->Cell(0, 10, $row['CustomerName'], 0, 1, 'L');  // Replace with $row['Supplier']
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

    // Assuming you have a separate query to fetch Sale details
    $detailSql = "SELECT sa.*, sad.*, pro.ProductPrice,pro.ProductName
		 FROM sale sa,saledetail sad,product pro
		 WHERE sad.SaleID='$SaleID'
		 AND sa.SaleID=sad.SaleID
		 AND sad.ProductID=pro.ProductID";  
    $detailResult = mysqli_query($connection, $detailSql);

    // Table content
    while ($detailRow = mysqli_fetch_assoc($detailResult)) {
        $pdf->Cell(50, 10, $detailRow['ProductName'], 1, 0, 'C');
        $pdf->Cell(20, 10, $detailRow['ProductPrice'], 1, 0, 'C');
        $pdf->Cell(20, 10, $detailRow['Quantity'], 1, 0, 'C');
        $pdf->Cell(30, 10, number_format($detailRow['Quantity']*$detailRow['ProductPrice']), 1, 1, 'C'); // Move to the next line after each row
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
    $pdf->Cell(0, 10, 'No Sale record found.', 0, 1, 'C');
}

// Output the PDF (force download)
$pdf->Output('saledetail.pdf', 'D'); // 'D' will trigger download

// Close database connection
mysqli_close($connection);
?>
