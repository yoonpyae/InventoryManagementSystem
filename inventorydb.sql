-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2025 at 10:08 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventorydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`CategoryID`, `CategoryName`) VALUES
(1, 'Shwe Bo'),
(2, 'Paw San Hmwe'),
(3, 'Sin Thwe'),
(5, 'Emata'),
(6, 'Loonzain');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CustomerID` int(11) NOT NULL,
  `CustomerName` varchar(50) NOT NULL,
  `Address` varchar(150) NOT NULL,
  `PhoneNumber` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CustomerID`, `CustomerName`, `Address`, `PhoneNumber`) VALUES
(1, 'Khin Khin', 'No.24, Aw Br rd, Ygn', '0945794385 '),
(2, 'Kyaw Soe', 'No.42, Inn wa st, Yg', '0964357243 '),
(7, 'Aye Aye', 'No.67, Kaba Aye Pagoda Rd', '09567894567'),
(8, 'Soe Win', 'No.58, Sule Pagoda Rd', '09567891234'),
(9, 'Than Than', 'No.15, Shwe Taung Nyung St', '09456782345 '),
(10, 'Aung Aung', 'No.527, Hledan Rd, Kamaryut Ts', '09751234567'),
(11, 'Hla Aung', 'No.102, Strand Rd, Pazundaung', '09567896789 ');

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `DeliveryID` varchar(30) NOT NULL,
  `DeliveryDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Status` varchar(30) NOT NULL,
  `EmployeeID` int(11) NOT NULL,
  `TotalQuantity` int(11) NOT NULL,
  `TotalAmount` int(11) NOT NULL,
  `Note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`DeliveryID`, `DeliveryDate`, `Status`, `EmployeeID`, `TotalQuantity`, `TotalAmount`, `Note`) VALUES
('D-0001', '2024-12-20 11:58:10', 'Completed', 6, 10, 10000, ''),
('D-0002', '2024-12-25 17:30:00', 'Pending', 10, 40, 28000, ''),
('D-0003', '2024-12-27 17:30:00', 'Send', 8, 90, 54000, '');

-- --------------------------------------------------------

--
-- Table structure for table `deliverydetail`
--

CREATE TABLE `deliverydetail` (
  `DeliveryID` varchar(30) NOT NULL,
  `SaleID` varchar(30) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `DeliveryFee` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deliverydetail`
--

INSERT INTO `deliverydetail` (`DeliveryID`, `SaleID`, `Quantity`, `DeliveryFee`) VALUES
('D-0001', 'S-000001', 10, 1000),
('D-0002', 'S-000004', 20, 700),
('D-0002', 'S-000005', 20, 700),
('D-0003', 'S-000001', 20, 600),
('D-0003', 'S-000002', 70, 600);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `EmployeeID` int(11) NOT NULL,
  `EmployeeName` varchar(30) NOT NULL,
  `Position` varchar(30) NOT NULL,
  `Address` varchar(100) NOT NULL,
  `PhoneNumber` varchar(20) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `ProfilePicture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`EmployeeID`, `EmployeeName`, `Position`, `Address`, `PhoneNumber`, `Email`, `Username`, `Password`, `ProfilePicture`) VALUES
(4, 'Khin Wah Wah', 'Finance Admin', 'No.23, Kan Nar Str, YGN', '0959384657', 'wahwah1@gamil.com', 'wahlay', '345456', 'UserPhoto/_femaleprofile.png'),
(6, 'Kyaw Swe', 'Delivery driver', 'No.1, Law Ka Rd, YGN', '0934957384', 'kyawswe1@gmail.com', '-', '', 'UserPhoto/_maleprofile.png'),
(7, 'Thein Zaw', 'Super Admin', 'No.123, Inn Wa Streert, YGN', '0925088632', 'Tz4352@gmail.com', 'tz4352', '234567', 'UserPhoto/_maleprofile.png'),
(8, 'Win Naing', 'Delivery driver', 'No.102, Strand Rd, Pazundaung', '0943216547', 'win.naing@example.com', '-', '-', 'UserPhoto/_maleprofile.png'),
(10, 'Moe Moe', 'Delivery driver', 'No.23, Anawrahta Rd, Botahtaung', '0945678345', 'moe.moe@example.com', '-', '-', 'UserPhoto/_femaleprofile.png'),
(11, 'Kyaw Ko', 'Finance Admin', 'No.643, Yay Kyaw St, ShewPyiThar', '0945369966', 'kyaw.ko@example.com', 'kyawko', '$2y$10$gOFEp9240YBoV0ZeS4TJrO0jZRJTZ5KoJGk8XEk5NDF', 'UserPhoto/_maleprofile.png');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(50) NOT NULL,
  `CategoryID` int(11) NOT NULL,
  `StockAlert` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `ProductCost` int(10) NOT NULL,
  `ProductPrice` int(11) NOT NULL,
  `WarehouseID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`ProductID`, `ProductName`, `CategoryID`, `StockAlert`, `Quantity`, `ProductCost`, `ProductPrice`, `WarehouseID`) VALUES
(2, 'Ayeyar Shwe Bo', 1, 30, 180, 154000, 163200, 0),
(3, 'Pyapon Paw san', 2, 30, 125, 136000, 144000, 0),
(4, 'Myaungmya Paw San', 2, 30, 90, 160000, 175000, 0),
(5, 'Pathein Paw San', 2, 30, 60, 120000, 129600, 0),
(6, 'Kyar Pyan Paw San', 2, 30, 290, 150000, 160000, 0),
(7, 'An Nyar Thar Sin Thwe', 3, 30, 200, 100000, 115000, 0),
(8, 'Zeera Sin Thwe', 3, 30, 20, 90000, 96000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `PurchaseID` varchar(30) NOT NULL,
  `PurchaseDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `SupplierID` int(11) NOT NULL,
  `GrandTotal` int(11) NOT NULL,
  `TotalQuantity` int(11) NOT NULL,
  `Status` varchar(30) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `WarehouseID` int(11) NOT NULL,
  `EmployeeID` int(11) NOT NULL,
  `VAT` int(11) NOT NULL,
  `TotalAmount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`PurchaseID`, `PurchaseDate`, `SupplierID`, `GrandTotal`, `TotalQuantity`, `Status`, `Description`, `WarehouseID`, `EmployeeID`, `VAT`, `TotalAmount`) VALUES
('P-000001', '2024-12-10 16:52:22', 2, 1616000, 10, 'Received', '', 1, 7, 16000, 1600000),
('P-000002', '2024-12-09 17:30:00', 9, 13837000, 100, 'Pending', '', 2, 7, 137000, 13700000),
('P-000003', '2024-12-11 17:30:00', 7, 3939000, 40, 'Received', 'COD', 2, 7, 39000, 3900000),
('P-000004', '2024-12-25 17:30:00', 8, 10261600, 85, 'Ordered', '', 2, 7, 101600, 10160000),
('P-000005', '2024-12-25 17:30:00', 7, 5332800, 40, 'Received', '', 2, 7, 52800, 5280000),
('P-000006', '2025-01-01 17:30:00', 7, 4545000, 30, 'Received', '', 1, 7, 45000, 4500000);

-- --------------------------------------------------------

--
-- Table structure for table `purchasedetail`
--

CREATE TABLE `purchasedetail` (
  `PurchaseID` varchar(30) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchasedetail`
--

INSERT INTO `purchasedetail` (`PurchaseID`, `ProductID`, `Quantity`) VALUES
('P-000001', 4, 10),
('P-000002', 4, 50),
('P-000002', 5, 40),
('P-000002', 8, 10),
('P-000003', 5, 10),
('P-000003', 8, 30),
('P-000004', 3, 35),
('P-000004', 5, 20),
('P-000004', 7, 30),
('P-000005', 3, 30),
('P-000005', 5, 10),
('P-000006', 6, 30);

-- --------------------------------------------------------

--
-- Table structure for table `sale`
--

CREATE TABLE `sale` (
  `SaleID` varchar(30) NOT NULL,
  `SaleDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `WarehouseID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `GrandTotal` int(11) NOT NULL,
  `TotalQuantity` int(11) NOT NULL,
  `Status` varchar(30) NOT NULL,
  `EmployeeID` int(11) NOT NULL,
  `TotalAmount` int(11) NOT NULL,
  `VAT` int(11) NOT NULL,
  `Note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale`
--

INSERT INTO `sale` (`SaleID`, `SaleDate`, `WarehouseID`, `CustomerID`, `GrandTotal`, `TotalQuantity`, `Status`, `EmployeeID`, `TotalAmount`, `VAT`, `Note`) VALUES
('S-000001', '2024-12-18 23:04:09', 1, 2, 2617920, 20, 'In Progress', 7, 2592000, 25920, ''),
('S-000002', '2024-12-20 10:54:18', 5, 2, 8720340, 70, 'Pending', 7, 8634000, 86340, ''),
('S-000003', '2024-12-19 17:30:00', 2, 1, 9999000, 60, 'Completed', 7, 9900000, 99000, ''),
('S-000004', '2024-12-27 17:30:00', 5, 8, 3296640, 20, 'Pending', 7, 3264000, 32640, ''),
('S-000005', '2024-12-27 17:30:00', 2, 2, 3535000, 20, 'Completed', 7, 3500000, 35000, 'COD'),
('S-000006', '2025-01-01 17:30:00', 2, 9, 9687920, 60, 'Completed', 7, 9592000, 95920, '');

-- --------------------------------------------------------

--
-- Table structure for table `saledetail`
--

CREATE TABLE `saledetail` (
  `SaleID` varchar(30) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saledetail`
--

INSERT INTO `saledetail` (`SaleID`, `ProductID`, `Quantity`) VALUES
('S-000001', 5, 20),
('S-000002', 5, 40),
('S-000002', 7, 30),
('S-000003', 4, 20),
('S-000003', 6, 40),
('S-000004', 2, 20),
('S-000005', 4, 20),
('S-000006', 4, 40),
('S-000006', 5, 20);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `SupplierID` int(11) NOT NULL,
  `SupplierName` varchar(50) NOT NULL,
  `ContactPerson` varchar(30) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `PhoneNumber` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`SupplierID`, `SupplierName`, `ContactPerson`, `Address`, `PhoneNumber`) VALUES
(1, 'Aung Mingalar Rice Mill', 'U Kan San', '27, Dana Theiddi St., Kannar (West) Ward, Insein Ts, Ygn', '09250333222'),
(2, 'Ayar Tagon Rice Paddy ', 'U Ayar', '105, 1st Flr, Bayint Naung Rd., Zeyar Mon Housing, Mayangone Ts, Ygn', '09444253111'),
(3, 'Phone Pyae Zaw Trading', 'U Yan Chin', 'Kyaung Taw Yar Village, Pwint Phyu Ts, Minbhuu, Magway Region', '09450299998'),
(7, 'Shwe Poe Hlwar Family', 'U Aung Thu, Daw Aye Aye Than', 'Nyaung Kine Village, Myahoetsait Ts, Taungtwinkyi Region', '09450299992'),
(8, 'Aye Htike San Co.,Ltd', 'Daw Nang Htike Honey, Daw Nang', 'No.1/110, Aung Myae(2) Village, Myit Kyi Na -Bamaw Highway Rd,Waing Maw Ts, Kachin ', '09265697897'),
(9, 'Very Good Flower Mining', 'U Kyaw Moe Thu', 'Nyaung Gone Quarter , Nan Mar , Moe Nyin Township.', '09886111155'),
(10, 'Myanmar Agribusiness Public', 'Daw Zar Zar Myint', 'Plot No. 1669, Mezligon Village , Pyinmana Ts, Nay Pyi Taw.', '094230127384');

-- --------------------------------------------------------

--
-- Table structure for table `transfer`
--

CREATE TABLE `transfer` (
  `TransferID` varchar(30) NOT NULL,
  `fromWarehouseID` int(11) DEFAULT NULL,
  `toWarehouseID` int(11) DEFAULT NULL,
  `TotalQuantity` int(11) DEFAULT NULL,
  `TransferDate` datetime DEFAULT NULL,
  `EmployeeID` int(11) NOT NULL,
  `Note` text NOT NULL,
  `TotalAmount` int(11) NOT NULL,
  `Status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transfer`
--

INSERT INTO `transfer` (`TransferID`, `fromWarehouseID`, `toWarehouseID`, `TotalQuantity`, `TransferDate`, `EmployeeID`, `Note`, `TotalAmount`, `Status`) VALUES
('T-0001', 2, 1, 50, '2024-12-10 00:00:00', 7, '', 15000, 'Completed'),
('T-0002', 2, 5, 130, '2024-12-22 00:00:00', 7, '', 65000, 'Send'),
('T-0003', 1, 5, 100, '2024-12-17 00:00:00', 7, '', 40000, 'Send'),
('T-0004', 5, 2, 30, '2024-12-27 00:00:00', 7, '', 9000, 'Pending'),
('T-0005', 2, 5, 130, '2024-12-27 00:00:00', 7, '', 39000, 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `transferdetail`
--

CREATE TABLE `transferdetail` (
  `TransferID` varchar(30) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transferdetail`
--

INSERT INTO `transferdetail` (`TransferID`, `ProductID`, `Quantity`, `Price`) VALUES
('T-0001', 5, 50, 300),
('T-0002', 6, 50, 500),
('T-0002', 8, 80, 500),
('T-0003', 4, 100, 400),
('T-0004', 3, 30, 300),
('T-0005', 2, 30, 300),
('T-0005', 8, 100, 300);

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

CREATE TABLE `warehouse` (
  `WarehouseID` int(11) NOT NULL,
  `WarehouseName` varchar(30) NOT NULL,
  `Location` varchar(150) NOT NULL,
  `Status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warehouse`
--

INSERT INTO `warehouse` (`WarehouseID`, `WarehouseName`, `Location`, `Status`) VALUES
(1, 'Gandamar ', '150, Dhammazedi Road, Bahan Township', 'Active'),
(2, 'Padauk ', 'No. 204,205, Kin Wun Min Gyi Road, Dagon Seikkan Industry City Zone 1, Dagon Seikkan Township, Yangon', 'Active'),
(4, 'Gant Gaw', 'No.139, Insein Rd, Hlaing', 'Inactive'),
(5, 'Yin Mar', 'Thilawa Special Economic Zone (SEZ), Thanlyin Township, Yangon', 'Active'),
(6, 'Zalat', 'No. 68, Hlaing Thar Yar Industrial Zone, Hlaing Thar Yar Ts', 'Inactive');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CustomerID`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`DeliveryID`);

--
-- Indexes for table `deliverydetail`
--
ALTER TABLE `deliverydetail`
  ADD PRIMARY KEY (`DeliveryID`,`SaleID`),
  ADD KEY `SaleID` (`SaleID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`EmployeeID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`ProductID`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`PurchaseID`);

--
-- Indexes for table `purchasedetail`
--
ALTER TABLE `purchasedetail`
  ADD PRIMARY KEY (`PurchaseID`,`ProductID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `sale`
--
ALTER TABLE `sale`
  ADD PRIMARY KEY (`SaleID`);

--
-- Indexes for table `saledetail`
--
ALTER TABLE `saledetail`
  ADD PRIMARY KEY (`SaleID`,`ProductID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`SupplierID`);

--
-- Indexes for table `transfer`
--
ALTER TABLE `transfer`
  ADD PRIMARY KEY (`TransferID`),
  ADD KEY `fromWarehouseID` (`fromWarehouseID`),
  ADD KEY `toWarehouseID` (`toWarehouseID`);

--
-- Indexes for table `transferdetail`
--
ALTER TABLE `transferdetail`
  ADD PRIMARY KEY (`TransferID`,`ProductID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`WarehouseID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `EmployeeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `SupplierID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `warehouse`
--
ALTER TABLE `warehouse`
  MODIFY `WarehouseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deliverydetail`
--
ALTER TABLE `deliverydetail`
  ADD CONSTRAINT `deliverydetail_ibfk_1` FOREIGN KEY (`DeliveryID`) REFERENCES `delivery` (`DeliveryID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `deliverydetail_ibfk_2` FOREIGN KEY (`SaleID`) REFERENCES `sale` (`SaleID`) ON UPDATE CASCADE;

--
-- Constraints for table `purchasedetail`
--
ALTER TABLE `purchasedetail`
  ADD CONSTRAINT `purchasedetail_ibfk_1` FOREIGN KEY (`PurchaseID`) REFERENCES `purchase` (`PurchaseID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchasedetail_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`) ON UPDATE CASCADE;

--
-- Constraints for table `saledetail`
--
ALTER TABLE `saledetail`
  ADD CONSTRAINT `saledetail_ibfk_1` FOREIGN KEY (`SaleID`) REFERENCES `sale` (`SaleID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `saledetail_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`) ON UPDATE CASCADE;

--
-- Constraints for table `transfer`
--
ALTER TABLE `transfer`
  ADD CONSTRAINT `transfer_ibfk_1` FOREIGN KEY (`fromWarehouseID`) REFERENCES `warehouse` (`WarehouseID`),
  ADD CONSTRAINT `transfer_ibfk_2` FOREIGN KEY (`toWarehouseID`) REFERENCES `warehouse` (`WarehouseID`);

--
-- Constraints for table `transferdetail`
--
ALTER TABLE `transferdetail`
  ADD CONSTRAINT `transferdetail_ibfk_1` FOREIGN KEY (`TransferID`) REFERENCES `transfer` (`TransferID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transferdetail_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
