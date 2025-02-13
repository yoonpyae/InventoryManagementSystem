<?php
require 'C:\xampp\htdocs\AungBiWinRiceTradingHouse\vendor\vendor\autoload.php'; // Include PHPSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include('connect.php'); // Database connection

// Query to fetch all product records
$query = "SELECT p.*, c.CategoryName 
                        FROM product p
                        LEFT JOIN category c ON p.CategoryID = c.CategoryID";
$result = mysqli_query($connection, $query);

// Create a new Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();



// Set header row
$sheet->setCellValue('A1', 'Product Code');
$sheet->setCellValue('B1', 'Product Name');
$sheet->setCellValue('C1', 'Category');
$sheet->setCellValue('D1', 'Stock Alert (bag)');
$sheet->setCellValue('E1', 'Quantity');
$sheet->setCellValue('F1', 'ProductCost');
$sheet->setCellValue('G1', 'ProductPrice');
// Add data rows
$rowNumber = 2; // Start from the second row
while ($row = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $rowNumber, $row['ProductID']);
    $sheet->setCellValue('B' . $rowNumber, $row['ProductName']);
    $sheet->setCellValue('C' . $rowNumber, $row['CategoryName']);
    $sheet->setCellValue('D' . $rowNumber, $row['StockAlert']);
    $sheet->setCellValue('E' . $rowNumber, $row['Quantity']);
    $sheet->setCellValue('F' . $rowNumber, $row['ProductCost']);
    $sheet->setCellValue('G' . $rowNumber, $row['ProductPrice']);
    $rowNumber++;
}

// Set headers for the browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="product.xlsx"');
header('Cache-Control: max-age=0');

// Write the file and send to browser
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
?>
