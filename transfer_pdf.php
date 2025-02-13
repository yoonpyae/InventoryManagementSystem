<?php
require_once 'C:\xampp\htdocs\AungBiWinRiceTradingHouse\vendor\autoload.php';  // Include the TCPDF library

// Database connection (Make sure to use your actual connection details)
include('connect.php'); 

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

// Query to fetch all Transfer records
$sql = "SELECT ta.*, w1.WarehouseName AS FromWarehouseName, w2.WarehouseName AS ToWarehouseName
                        FROM transfer ta
                        INNER JOIN warehouse w1 ON ta.fromWarehouseID = w1.WarehouseID
                        INNER JOIN warehouse w2 ON ta.toWarehouseID = w2.WarehouseID
                    ";
$result = mysqli_query($connection, $sql);

// Check if any records are found
if (mysqli_num_rows($result) > 0) {
    // Start table
    $pdf->Cell(0, 10, 'Transfer Records', 0, 1, 'C');
    $pdf->Ln(10);

    // Table headers
    $pdf->Cell(20, 10, 'ID', 1, 0, 'C');
    $pdf->Cell(43, 10, 'Date', 1, 0, 'C');
    $pdf->Cell(35, 10, 'FromWarehouse', 1, 0, 'C');
    $pdf->Cell(33, 10, 'ToWarehouse', 1, 0, 'C');
    $pdf->Cell(10, 10, 'Qty', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Status', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Amount', 1, 1, 'C'); // Move to the next line after the header

    // Table content
    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(20, 10, $row['TransferID'], 1, 0, 'C');
        $pdf->Cell(43, 10, $row['TransferDate'], 1, 0, 'C');
        $pdf->Cell(35, 10, $row['FromWarehouseName'], 1, 0, 'C');
        $pdf->Cell(33, 10, $row['ToWarehouseName'], 1, 0, 'C');
        $pdf->Cell(10, 10, $row['TotalQuantity'], 1, 0, 'C');
        $pdf->Cell(20, 10, $row['Status'], 1, 0, 'C');
        $pdf->Cell(20, 10, $row['TotalAmount'], 1, 1, 'C'); // Move to the next line after each row
    }


} else {
    $pdf->Cell(0, 10, 'No Transfer records found.', 0, 1, 'C');
}

// Output the PDF (force download)
$pdf->Output('Transfer_records.pdf', 'D'); // 'D' will trigger download

// Close database connection
mysqli_close($connection);
?>
