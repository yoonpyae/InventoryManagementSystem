<?php
require 'C:\xampp\htdocs\AungBiWinRiceTradingHouse\vendor\vendor\autoload.php'; // Include PHPSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include('connect.php'); // Database connection

// Query to fetch all purchase records
$query = "SELECT pu.*, s.SupplierName, w.WarehouseName
                        FROM purchase pu
                        INNER JOIN supplier s ON pu.SupplierID = s.SupplierID
                        INNER JOIN warehouse w ON pu.WarehouseID = w.WarehouseID";
$result = mysqli_query($connection, $query);

// Create a new Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();



// Set header row
$sheet->setCellValue('A1', 'Purchase ID');
$sheet->setCellValue('B1', 'Purchase Date');
$sheet->setCellValue('C1', 'Supplier');
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
    $sheet->setCellValue('A' . $rowNumber, $row['PurchaseID']);
    $sheet->setCellValue('B' . $rowNumber, $row['PurchaseDate']);
    $sheet->setCellValue('C' . $rowNumber, $row['SupplierName']);
    $sheet->setCellValue('D' . $rowNumber, $row['GrandTotal']);
    $sheet->setCellValue('E' . $rowNumber, $row['TotalQuantity']);
    $sheet->setCellValue('F' . $rowNumber, $row['Status']);
    $sheet->setCellValue('G' . $rowNumber, $row['Description']);
    $sheet->setCellValue('H' . $rowNumber, $row['WarehouseName']);
    $sheet->setCellValue('I' . $rowNumber, $row['VAT']);
    $sheet->setCellValue('J' . $rowNumber, $row['TotalAmount']);
    $rowNumber++;
}

// Set headers for the browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="purchases.xlsx"');
header('Cache-Control: max-age=0');

// Write the file and send to browser
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
?>
