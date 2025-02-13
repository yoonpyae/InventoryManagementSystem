<?php
require 'C:\xampp\htdocs\AungBiWinRiceTradingHouse\vendor\vendor\autoload.php'; // Include PHPSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include('connect.php'); // Database connection

// Query to fetch all delivery records
$query = "SELECT da.*, e.EmployeeName
                        FROM delivery da
                        INNER JOIN employee e ON da.EmployeeID = e.EmployeeID";
$result = mysqli_query($connection, $query);

// Create a new Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();



// Set header row
$sheet->setCellValue('A1', 'DeliveryID');
$sheet->setCellValue('B1', 'Date');
$sheet->setCellValue('C1', 'Employee');
$sheet->setCellValue('D1', 'Total Quantity');
$sheet->setCellValue('E1', 'Total Amount');
$sheet->setCellValue('F1', 'Status');
// Add data rows
$rowNumber = 2; // Start from the second row
while ($row = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $rowNumber, $row['DeliveryID']);
    $sheet->setCellValue('B' . $rowNumber, $row['DeliveryDate']);
    $sheet->setCellValue('C' . $rowNumber, $row['EmployeeName']);
    $sheet->setCellValue('D' . $rowNumber, $row['TotalQuantity']);
    $sheet->setCellValue('E' . $rowNumber, $row['TotalAmount']);
    $sheet->setCellValue('F' . $rowNumber, $row['Status']);
    $rowNumber++;
}

// Set headers for the browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="deliveries.xlsx"');
header('Cache-Control: max-age=0');

// Write the file and send to browser
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
?>

