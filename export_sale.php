<?php
require 'C:\xampp\htdocs\AungBiWinRiceTradingHouse\vendor\vendor\autoload.php'; // Include PHPSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include('connect.php'); // Database connection

// Prevent output issues
ob_start();
ob_clean();
ini_set('display_errors', 0);
error_reporting(0);

// Query to fetch all sale records
$query = "SELECT sa.*, c.CustomerName, w.WarehouseName
          FROM sale sa
          INNER JOIN customer c ON sa.CustomerID = c.CustomerID
          INNER JOIN warehouse w ON sa.WarehouseID = w.WarehouseID";
$result = mysqli_query($connection, $query);

// Create a new Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header row
$sheet->setCellValue('A1', 'SaleID');
$sheet->setCellValue('B1', 'SaleDate');
$sheet->setCellValue('C1', 'Customer');
$sheet->setCellValue('D1', 'Grand Total');
$sheet->setCellValue('E1', 'Total Quantity');
$sheet->setCellValue('F1', 'Status');
$sheet->setCellValue('G1', 'Description');
$sheet->setCellValue('H1', 'Warehouse');
$sheet->setCellValue('I1', 'VAT');
$sheet->setCellValue('J1', 'Total Amount');

// Add data rows
$rowNumber = 2; // Start from the second row
while ($row = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $rowNumber, $row['SaleID']);
    $sheet->setCellValue('B' . $rowNumber, $row['SaleDate']);
    $sheet->setCellValue('C' . $rowNumber, $row['CustomerName']);
    $sheet->setCellValue('D' . $rowNumber, $row['GrandTotal']);
    $sheet->setCellValue('E' . $rowNumber, $row['TotalQuantity']);
    $sheet->setCellValue('F' . $rowNumber, $row['Status']);
    $sheet->setCellValue('G' . $rowNumber, $row['Description']);
    $sheet->setCellValue('H' . $rowNumber, $row['WarehouseName']);
    $sheet->setCellValue('I' . $rowNumber, $row['VAT']);
    $sheet->setCellValue('J' . $rowNumber, $row['TotalAmount']);
    $rowNumber++;
}

// Save the file to disk for debugging
$writer = new Xlsx($spreadsheet);
$writer->save('sales_debug.xlsx'); // Save a debug file on the server

// Send the file to the browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="sales.xlsx"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit();
?>
