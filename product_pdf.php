<?php
require_once 'C:\xampp\htdocs\AungBiWinRiceTradingHouse\vendor\autoload.php';  // Include the TCPDF library

// Database connection (Make sure to use your actual connection details)
include('connect.php'); 

// Create instance of TCPDF
$pdf = new TCPDF();

// Set document properties
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Aung Bi Win Rice Trading House');
$pdf->SetTitle('Product Records');
$pdf->SetSubject('Product Records PDF');
$pdf->SetKeywords('Product, records, PDF');

// Set margins
$pdf->SetMargins(15, 20, 15);
$pdf->SetHeaderData('', 0, 'Product Records', 'Generated on ' . date('Y-m-d'));

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Query to fetch all product records
$sql = "SELECT p.*, c.CategoryName 
                        FROM product p
                        LEFT JOIN category c ON p.CategoryID = c.CategoryID
                    ";
$result = mysqli_query($connection, $sql);

// Check if any records are found
if (mysqli_num_rows($result) > 0) {
    // Start table
    $pdf->Cell(0, 10, 'Product Records', 0, 1, 'C');
    $pdf->Ln(10);

    // Table headers
    $pdf->Cell(10, 10, 'ID', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Product', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Category', 1, 0, 'C');
    $pdf->Cell(20, 10, 'StockAlert', 1, 0, 'C');
    $pdf->Cell(15, 10, 'Qty', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Cost', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Price', 1, 1, 'C'); // Move to the next line after the header

    // Table content
    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(10, 10, $row['ProductID'], 1, 0, 'C');
        $pdf->Cell(50, 10, $row['ProductName'], 1, 0, 'C');
        $pdf->Cell(40, 10, $row['CategoryName'], 1, 0, 'C');
        $pdf->Cell(20, 10, $row['StockAlert'], 1, 0, 'C');
        $pdf->Cell(15, 10, $row['Quantity'], 1, 0, 'C');
        $pdf->Cell(20, 10, $row['ProductCost'], 1, 0, 'C');
        $pdf->Cell(20, 10, $row['ProductPrice'], 1, 1, 'C'); // Move to the next line after each row
    }


} else {
    $pdf->Cell(0, 10, 'No Product records found.', 0, 1, 'C');
}

// Output the PDF (force download)
$pdf->Output('Product_records.pdf', 'D'); // 'D' will trigger download

// Close database connection
mysqli_close($connection);
?>
