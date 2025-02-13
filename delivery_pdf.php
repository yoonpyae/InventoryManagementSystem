<?php
require_once 'C:\xampp\htdocs\AungBiWinRiceTradingHouse\vendor\autoload.php';  // Include the TCPDF library

// Database connection (Make sure to use your actual connection details)
include('connect.php'); 

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

// Query to fetch all Delivery records
$sql = "SELECT da.*, e.EmployeeName
                        FROM delivery da
                        INNER JOIN employee e ON da.EmployeeID = e.EmployeeID
                    ";
$result = mysqli_query($connection, $sql);

// Check if any records are found
if (mysqli_num_rows($result) > 0) {
    // Start table
    $pdf->Cell(0, 10, 'Delivery Records', 0, 1, 'C');
    $pdf->Ln(10);

    // Table headers
    $pdf->Cell(20, 10, 'ID', 1, 0, 'C');
    $pdf->Cell(43, 10, 'Date', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Employee', 1, 0, 'C');
    $pdf->Cell(10, 10, 'Qty', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Status', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Amount', 1, 1, 'C'); // Move to the next line after the header

    // Table content
    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(20, 10, $row['DeliveryID'], 1, 0, 'C');
        $pdf->Cell(43, 10, $row['DeliveryDate'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['EmployeeName'], 1, 0, 'C');
        $pdf->Cell(10, 10, $row['TotalQuantity'], 1, 0, 'C');
        $pdf->Cell(20, 10, $row['Status'], 1, 0, 'C');
        $pdf->Cell(20, 10, $row['TotalAmount'], 1, 1, 'C'); // Move to the next line after each row
    }


} else {
    $pdf->Cell(0, 10, 'No Delivery records found.', 0, 1, 'C');
}

// Output the PDF (force download)
$pdf->Output('Delivery_records.pdf', 'D'); // 'D' will trigger download

// Close database connection
mysqli_close($connection);
?>
