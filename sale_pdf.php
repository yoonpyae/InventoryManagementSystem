<?php
require_once 'C:\xampp\htdocs\AungBiWinRiceTradingHouse\vendor\autoload.php';  // Include the TCPDF library

// Database connection (Make sure to use your actual connection details)
include('connect.php'); 

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

// Query to fetch all Sale records
$sql = "SELECT sa.*, c.CustomerName, w.WarehouseName
                        FROM sale sa
                        INNER JOIN customer c ON sa.CustomerID = c.CustomerID
                        INNER JOIN warehouse w ON sa.WarehouseID = w.WarehouseID
                    ";
$result = mysqli_query($connection, $sql);

// Check if any records are found
if (mysqli_num_rows($result) > 0) {
    // Start table
    $pdf->Cell(0, 10, 'Sale Records', 0, 1, 'C');
    $pdf->Ln(10);

    // Table headers
    $pdf->Cell(20, 10, 'ID', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Customer', 1, 0, 'C');
    $pdf->Cell(25, 10, 'Warehouse', 1, 0, 'C');
    $pdf->Cell(23, 10, 'GrandTotal', 1, 0, 'C');
    $pdf->Cell(10, 10, 'Qty', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Status', 1, 0, 'C');
    $pdf->Cell(15, 10, 'VAT', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Amount', 1, 1, 'C'); // Move to the next line after the header

    // Table content
    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(20, 10, $row['SaleID'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['CustomerName'], 1, 0, 'C');
        $pdf->Cell(25, 10, $row['WarehouseName'], 1, 0, 'C');
        $pdf->Cell(23, 10, $row['GrandTotal'], 1, 0, 'C');
        $pdf->Cell(10, 10, $row['TotalQuantity'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['Status'], 1, 0, 'C');
        $pdf->Cell(15, 10, $row['VAT'], 1, 0, 'C');
        $pdf->Cell(20, 10, $row['TotalAmount'], 1, 1, 'C'); // Move to the next line after each row
    }


} else {
    $pdf->Cell(0, 10, 'No Sale records found.', 0, 1, 'C');
}

// Output the PDF (force download)
$pdf->Output('sale_records.pdf', 'D'); // 'D' will trigger download

// Close database connection
mysqli_close($connection);
?>
