<?php
require 'C:\xampp\htdocs\AungBiWinRiceTradingHouse\vendor\vendor\autoload.php'; // Include PHPSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include('connect.php'); // Database connection

// Query to fetch all transfer records
$query = "SELECT ta.*, w1.WarehouseName AS FromWarehouseName, w2.WarehouseName AS ToWarehouseName
                        FROM transfer ta
                        INNER JOIN warehouse w1 ON ta.fromWarehouseID = w1.WarehouseID
                        INNER JOIN warehouse w2 ON ta.toWarehouseID = w2.WarehouseID";
$result = mysqli_query($connection, $query);

// Create a new Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();



// Set header row
$sheet->setCellValue('A1', 'TransferID');
$sheet->setCellValue('B1', 'Date');
$sheet->setCellValue('C1', 'From Warehouse');
$sheet->setCellValue('D1', 'To Warehouse');
$sheet->setCellValue('E1', 'Total Quantity');
$sheet->setCellValue('F1', 'Total Amount');
$sheet->setCellValue('G1', 'Status');
// Add data rows
$rowNumber = 2; // Start from the second row
while ($row = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $rowNumber, $row['TransferID']);
    $sheet->setCellValue('B' . $rowNumber, $row['TransferDate']);
    $sheet->setCellValue('C' . $rowNumber, $row['FromWarehouseName']);
    $sheet->setCellValue('D' . $rowNumber, $row['ToWarehouseName']);
    $sheet->setCellValue('E' . $rowNumber, $row['TotalQuantity']);
    $sheet->setCellValue('F' . $rowNumber, $row['TotalAmount']);
    $sheet->setCellValue('G' . $rowNumber, $row['Status']);
    $rowNumber++;
}

// Set headers for the browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="transfers.xlsx"');
header('Cache-Control: max-age=0');

// Write the file and send to browser
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
?>

