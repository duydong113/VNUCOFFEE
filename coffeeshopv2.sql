-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2025 at 06:17 PM
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
-- Database: `coffeeshopv2`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `AddressID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `Province` varchar(100) DEFAULT NULL,
  `District` varchar(100) DEFAULT NULL,
  `Ward` varchar(100) DEFAULT NULL,
  `Detail` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`AddressID`, `CustomerID`, `Province`, `District`, `Ward`, `Detail`) VALUES
(12, 2, '22', '207', '7192', 'q'),
(13, 2, '22', '203', '7024', 'q'),
(14, 2, '22', '203', '7027', 'q'),
(15, 2, '22', '203', '7000', 'q'),
(16, 2, '22', '203', '7027', 'q'),
(17, 2, '22', '203', '7024', 'q'),
(18, 2, '22', '207', '7198', 'q'),
(19, 2, '22', '207', '7192', 'q'),
(20, 2, '22', '207', '7192', 'q'),
(21, 2, '22', '207', '7195', 'q');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `AdminID` int(11) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `Email` varchar(100) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `Role` enum('Manager','Employee') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`AdminID`, `Username`, `Password`, `CreatedAt`, `Email`, `Phone`, `FullName`, `Role`) VALUES
(6, 'admin', '$2y$10$ipEEOcZb/QjmYd3jwS.hrO/qeitIQEIYbS/0DWXc1D3UH7MHZwRtm', '2025-06-05 15:52:20', '', '', '', 'Manager'),
(7, 'adminkhanhduy', '$2y$10$Z1JFa3GMIGLFB2D3Jw5moeQQN7EoVj1YE4rbz1trGqjbpCtsVpZES', '2025-06-06 08:37:27', 'dongduy991@gmail.com', '0367228955', 'Duy Dong', 'Manager');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `CartID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`CartID`, `CustomerID`, `ProductID`, `Quantity`) VALUES
(19, 3, 9, 1),
(20, 3, 17, 1);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `CategoryID` int(11) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `AdminID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`CategoryID`, `Name`, `AdminID`) VALUES
(1, 'Whole Bean', NULL),
(2, 'Ground Coffee', NULL),
(3, 'Coffee Pods', NULL),
(4, 'Accessories', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `ContactID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `ContactMethod` enum('email','phone','chat') DEFAULT NULL,
  `ContactDetails` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CustomerID` int(11) NOT NULL,
  `Firstname` varchar(100) DEFAULT NULL,
  `Lastname` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Username` varchar(50) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `PhoneNum` varchar(20) DEFAULT NULL,
  `Status` enum('Active','Blocked') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CustomerID`, `Firstname`, `Lastname`, `Email`, `Username`, `Password`, `PhoneNum`, `Status`) VALUES
(1, 'John', 'Doe', 'john.doe@example.com', 'johndoe', '123456', '0123456789', 'Active'),
(2, 'Detina', 'Tran', '23070589@vnu.edu.vn', 'duong', '$2y$10$vfiBF.WF5tqAnhGqj1EK0.nsxDskpdmS3Qx1SdFwX7R52vgFdMIw.', '0327459598', 'Blocked'),
(3, 'Duy', 'Dong', 'dongduy991@gmail.com', 'admin', '$2y$10$9H08UjE5IZuOMdvts0nAzevNJo1cFJ/ri.ni9ET.qiWwgtkQ5dV8O', '0367228955', 'Blocked'),
(5, 'Hoàng', 'Thị Mai', 'hoang.thimai@gmail.com', 'maihoang', '$2y$10$lXG0QtfgveCacHh3cBKjiOWZwEJ5QNRp/lF2AdfxH7s/AfvpeSAEu', '0917654321', 'Active'),
(6, 'Vũ', 'Ngọc Sơn', 'vu.ngocson@gmail.com', 'Sonxys123', '$2y$10$zFx9A05XyyK.RN9BGGVmp.br3RkQ4312/LcM2r86803/KIpnoZBoW', '0327459598', 'Active'),
(7, 'Đỗ', 'Thanh Hà', 'do.thanhha@gmail.com', 'Hahihi123', '$2y$10$oIrJqUaAXndJ5LisWTNeauj0wNcEQzKF0Gd2ytPOijVVdztT8st2m', '0912345678', 'Active'),
(8, 'Nguyễn', 'Thị Kim', 'nguyen.thikim@gmail.com', 'kimkim1999', '$2y$10$Sa4Ewyxk0Jf6OSOxOhWxP.DlxkcSZ9hHRKGZD0RKB5/FiaOCvpmM.', '0917654321', 'Active'),
(9, 'Trần', 'Hùng Dũng', 'tran.hungdung@gmail.com', 'dungtrank4', '$2y$10$l4864kRW4aBxs72kMCPMTe4mRN3EQuNhU97mUSAkDXHj/0Jvi7Y9q', '0987654321', 'Active'),
(10, 'Lê', 'Minh Tâm', 'le.minhtam@gmail.com', 'tamleyahoo', '$2y$10$SHBBh/7Ge98WNo2QaS4wE.3YMelxU1lFkIDDD/IoNti4CbaDKuIl2', '0327459598', 'Active'),
(11, 'Phạm', 'Hương Giang', 'pham.huonggiang@hotmail.com', 'user11', 'qwerty123', '0917654321', 'Active'),
(12, 'Hoàng', 'Thanh Tú', 'hoang.thanhtu@gmail.com', 'user12', '123456789', '0912345678', 'Active'),
(14, 'Đỗ', 'Khánh Ly', 'do.khanhly@gmail.com', 'user14', 'admin123', '0987654321', 'Active'),
(15, 'Nguyễn', 'Hoài An', 'nguyen.hoaian@gmail.com', 'user15', '123456789', '0912345678', 'Active'),
(18, 'Phạm', 'Minh Đạo', 'pham.minhdao@gmail.com', 'user18', '123456789', '0912345678', 'Active'),
(20, 'Vũ', 'Anh Thi', 'vu.anhthi@gmail.com', 'user20', 'letmein123', '0917654321', 'Active'),
(21, 'Đỗ', 'Hồng Minh', 'do.hongminh@gmail.com', 'user21', 'qwerty123', '0987654321', 'Active'),
(22, 'Nguyễn', 'Trúc Ly', 'nguyen.trucly@yahoo.com', 'user22', 'admin123', '0327459598', 'Active'),
(23, 'Trần', 'Thanh Duy', 'tran.thanhduy@gmail.com', 'user23', '123456789', '0912345678', 'Active'),
(24, 'Lê', 'Thị Huyền', 'le.thihuyen@gmail.com', 'user24', 'letmein123', '0917654321', 'Active'),
(25, 'Phạm', 'Thành Nhân', 'pham.thanhnhan@yahoo.com', 'user25', 'qwerty123', '0327459598', 'Active'),
(26, 'Hoàng', 'Ngọc Vân', 'hoang.ngocvan@gmail.com', 'user26', 'admin123', '0912345678', 'Active'),
(27, 'Vũ', 'Lê Hòa', 'vu.lehoa@outlook.com', 'user27', 'letmein123', '0917654321', 'Active'),
(28, 'Đỗ', 'Bảo Trân', 'do.baotran@gmail.com', 'user28', '123456789', '0327459598', 'Active'),
(30, 'Trần', 'Duy Quang', 'tran.duyquang@gmail.com', 'user30', 'admin123', '0987654321', 'Active'),
(31, 'Lê', 'Thị Lan', 'le.thilan@gmail.com', 'user31', 'letmein123', '0917654321', 'Active'),
(33, 'Hoàng', 'Lâm Thủy', 'hoang.lamthuy@gmail.com', 'user33', 'admin123', '0912345678', 'Active'),
(34, 'Vũ', 'Tuyết Mai', 'vu.tuyetmai@gmail.com', 'user34', 'letmein123', '0917654321', 'Active'),
(36, 'Nguyễn', 'Tâm Bảo', 'nguyen.tambao@gmail.com', 'user36', 'qwerty123', '0912345678', 'Active'),
(38, 'Lê', 'Bảo An', 'le.baoan@yahoo.com', 'user38', 'letmein123', '0327459598', 'Active'),
(39, 'Phạm', 'Mỹ Linh', 'pham.mylinh@gmail.com', 'user39', '123456789', '0917654321', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `FeedbackID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `OrderID` int(11) DEFAULT NULL,
  `Rating` int(11) DEFAULT NULL,
  `Comment` varchar(255) DEFAULT NULL,
  `FeedbackDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `OrderID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `OrderDate` date DEFAULT NULL,
  `TotalPrice` decimal(10,2) DEFAULT NULL,
  `Status` enum('pending','confirmed','shipped','delivered','cancelled') DEFAULT NULL,
  `PhoneNum` varchar(20) DEFAULT NULL,
  `AddressID` int(11) DEFAULT NULL,
  `ProcessedByAdminID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`OrderID`, `CustomerID`, `OrderDate`, `TotalPrice`, `Status`, `PhoneNum`, `AddressID`, `ProcessedByAdminID`) VALUES
(1, 2, NULL, 66.98, 'pending', '0327459598', 12, NULL),
(2, 2, NULL, 17.99, 'pending', '0327459598', 13, NULL),
(3, 2, NULL, 35.98, 'pending', '0327459598', 14, NULL),
(4, 2, NULL, 35.98, 'pending', '0327459598', 15, NULL),
(5, 2, NULL, 15.00, 'pending', '0327459598', 16, NULL),
(6, 2, NULL, 29.50, 'pending', '0327459598', 17, NULL),
(7, 2, NULL, 17.99, 'pending', '0327459598', 18, NULL),
(8, 2, NULL, 17.99, 'pending', '0327459598', 19, NULL),
(9, 2, NULL, 35.98, 'pending', '0327459598', 20, NULL),
(10, 2, NULL, 17.99, 'pending', '0327459598', 21, NULL),
(11, 2, NULL, 29.50, 'pending', '0327459598', 12, NULL),
(12, 2, '2025-05-29', 17.99, 'pending', '0327459598', 12, NULL),
(13, 1, '2025-06-01', 150.00, 'pending', '123-456-7890', 12, NULL),
(14, 1, '2025-06-02', 250.50, 'confirmed', '234-567-8901', 12, NULL),
(15, 1, '2025-06-03', 100.75, 'shipped', '345-678-9012', 12, NULL),
(16, 1, '2025-06-04', 120.40, 'delivered', '456-789-0123', 12, NULL),
(17, 1, '2025-06-05', 200.00, 'cancelled', '567-890-1234', 12, NULL),
(18, 1, '2025-06-06', 310.00, 'pending', '678-901-2345', 12, NULL),
(19, 1, '2025-06-01', 180.90, 'confirmed', '789-012-3456', 12, NULL),
(20, 1, '2025-06-02', 220.80, 'shipped', '890-123-4567', 12, NULL),
(21, 1, '2025-06-03', 135.60, 'delivered', '901-234-5678', 12, NULL),
(22, 1, '2025-06-04', 175.30, 'pending', '012-345-6789', 12, NULL),
(23, 1, '2025-06-05', 160.00, 'confirmed', '123-456-7891', 12, NULL),
(24, 1, '2025-06-06', 145.20, 'shipped', '234-567-8902', 12, NULL),
(25, 1, '2025-06-01', 195.10, 'delivered', '345-678-9013', 12, NULL),
(26, 1, '2025-06-02', 210.50, 'cancelled', '456-789-0124', 12, NULL),
(27, 1, '2025-06-03', 275.00, 'pending', '567-890-1235', 12, NULL),
(28, 1, '2025-06-04', 325.20, 'confirmed', '678-901-2346', 12, NULL),
(29, 1, '2025-06-05', 400.50, 'shipped', '789-012-3457', 12, NULL),
(30, 1, '2025-06-06', 90.80, 'delivered', '890-123-4568', 12, NULL),
(31, 1, '2025-06-01', 250.00, 'pending', '901-234-5679', 12, NULL),
(32, 1, '2025-06-02', 210.40, 'confirmed', '012-345-6790', 12, NULL),
(33, 1, '2025-06-03', 280.60, 'shipped', '123-456-7892', 12, NULL),
(34, 1, '2025-06-04', 190.75, 'delivered', '234-567-8903', 12, NULL),
(35, 1, '2025-06-05', 140.50, 'pending', '345-678-9014', 12, NULL),
(36, 1, '2025-06-06', 220.00, 'confirmed', '456-789-0125', 12, NULL),
(37, 1, '2025-06-01', 160.90, 'shipped', '567-890-1236', 12, NULL),
(38, 1, '2025-06-02', 190.00, 'delivered', '678-901-2347', 12, NULL),
(39, 1, '2025-06-03', 230.60, 'cancelled', '789-012-3458', 12, NULL),
(40, 1, '2025-06-04', 185.40, 'pending', '890-123-4569', 12, NULL),
(41, 1, '2025-06-05', 275.30, 'confirmed', '901-234-5680', 12, NULL),
(42, 1, '2025-06-06', 320.70, 'shipped', '012-345-6791', 12, NULL),
(43, 1, '2025-06-01', 220.90, 'delivered', '123-456-7893', 12, NULL),
(44, 1, '2025-06-02', 270.50, 'pending', '234-567-8904', 12, NULL),
(45, 1, '2025-06-03', 200.00, 'confirmed', '345-678-9015', 12, NULL),
(46, 1, '2025-07-04', 150.00, 'pending', '123-456-7890', 12, NULL),
(47, 1, '2025-07-05', 250.50, 'confirmed', '234-567-8901', 12, NULL),
(48, 1, '2025-07-06', 100.75, 'shipped', '345-678-9012', 12, NULL),
(49, 1, '2025-07-07', 120.40, 'delivered', '456-789-0123', 12, NULL),
(50, 1, '2025-07-08', 200.00, 'cancelled', '567-890-1234', 12, NULL),
(51, 1, '2025-07-09', 310.00, 'pending', '678-901-2345', 12, NULL),
(52, 1, '2025-07-10', 180.90, 'confirmed', '789-012-3456', 12, NULL),
(53, 1, '2025-07-11', 220.80, 'shipped', '890-123-4567', 12, NULL),
(54, 1, '2025-07-12', 135.60, 'delivered', '901-234-5678', 12, NULL),
(55, 1, '2025-07-13', 175.30, 'pending', '012-345-6789', 12, NULL),
(56, 1, '2025-07-14', 160.00, 'confirmed', '123-456-7891', 12, NULL),
(57, 1, '2025-07-15', 145.20, 'shipped', '234-567-8902', 12, NULL),
(58, 1, '2025-07-16', 195.10, 'delivered', '345-678-9013', 12, NULL),
(59, 1, '2025-07-17', 210.50, 'cancelled', '456-789-0124', 12, NULL),
(60, 1, '2025-07-18', 275.00, 'pending', '567-890-1235', 12, NULL),
(61, 1, '2025-07-19', 325.20, 'confirmed', '678-901-2346', 12, NULL),
(62, 1, '2025-07-20', 400.50, 'shipped', '789-012-3457', 12, NULL),
(63, 1, '2025-07-21', 90.80, 'delivered', '890-123-4568', 12, NULL),
(64, 1, '2025-07-22', 250.00, 'pending', '901-234-5679', 12, NULL),
(65, 1, '2025-07-23', 210.40, 'confirmed', '012-345-6790', 12, NULL),
(66, 1, '2025-07-24', 280.60, 'shipped', '123-456-7892', 12, NULL),
(67, 1, '2025-07-25', 190.75, 'delivered', '234-567-8903', 12, NULL),
(68, 1, '2025-07-26', 140.50, 'pending', '345-678-9014', 12, NULL),
(69, 1, '2025-07-27', 220.00, 'confirmed', '456-789-0125', 12, NULL),
(70, 1, '2025-07-28', 160.90, 'shipped', '567-890-1236', 12, NULL),
(71, 1, '2025-07-29', 190.00, 'delivered', '678-901-2347', 12, NULL),
(72, 1, '2025-07-30', 230.60, 'cancelled', '789-012-3458', 12, NULL),
(73, 1, '2025-07-31', 185.40, 'pending', '890-123-4569', 12, NULL),
(74, 1, '2025-08-01', 275.30, 'confirmed', '901-234-5680', 12, NULL),
(75, 1, '2025-08-02', 320.70, 'shipped', '012-345-6791', 12, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orderdetail`
--

CREATE TABLE `orderdetail` (
  `OrderDetailID` int(11) NOT NULL,
  `OrderID` int(11) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `UnitPrice` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderdetail`
--

INSERT INTO `orderdetail` (`OrderDetailID`, `OrderID`, `ProductID`, `Quantity`, `UnitPrice`) VALUES
(1, 1, 9, 2, 17.99),
(2, 1, 2, 1, 15.50),
(3, 1, 6, 1, 15.50),
(4, 2, 9, 1, 17.99),
(5, 3, 9, 2, 17.99),
(6, 4, 9, 2, 17.99),
(7, 5, 20, 1, 15.00),
(8, 6, 18, 1, 29.50),
(9, 7, 9, 1, 17.99),
(10, 8, 9, 1, 17.99),
(11, 9, 9, 2, 17.99),
(12, 10, 9, 1, 17.99),
(13, 11, 18, 1, 29.50),
(14, 12, 9, 1, 17.99);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `ProductID` int(11) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `CategoryID` int(11) DEFAULT NULL,
  `StockQuantity` int(11) DEFAULT NULL,
  `ImageURL` varchar(255) DEFAULT NULL,
  `AdminID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`ProductID`, `Name`, `Description`, `Price`, `CategoryID`, `StockQuantity`, `ImageURL`, `AdminID`) VALUES
(1, 'Ethiopian Yirgacheffe', 'Bright, citrusy, and floral notes. Whole bean.', 19990.00, 1, 50, 'images/ethiopian_yirgacheffe.jpg', NULL),
(2, 'Colombian Supremo', 'Smooth, nutty, and chocolatey. Medium roast, ground.', 15500.00, 2, 29, 'images/colombian_supremo.jpg', NULL),
(4, 'French Press', 'Classic 3-cup French press coffee maker.', 25000.00, 4, 15, 'images/french_press.jpg', NULL),
(5, 'Ethiopian Yirgacheffe', 'Bright, citrusy, and floral notes. Whole bean.', 19990.00, 1, 50, 'images/ethiopian_yirgacheffe.jpg', NULL),
(6, 'Colombian Supremo', 'Smooth, nutty, and chocolatey. Medium roast, ground.', 15500.00, 2, 29, 'images/colombian_supremo.jpg', NULL),
(7, 'Dark Roast Espresso Pods', 'Intense and rich espresso pods. Pack of 20.', 12000.00, 3, 100, 'images/espresso_pods.jpg', NULL),
(8, 'French Press', 'Classic 3-cup French press coffee maker.', 25000.00, 4, 15, 'images/french_press.jpg', NULL),
(9, 'Brazilian Santos', 'A smooth, low-acidity coffee with nutty and sweet chocolate notes. Excellent for espresso. Whole bean.', 17990.00, 1, 47, 'images/brazilian_santos.jpg', NULL),
(10, 'Kenyan AA Washed', 'Bright acidity, full body, with notes of blackcurrant and citrus. Whole bean.', 21500.00, 1, 40, 'images/kenyan_aa.jpg', NULL),
(11, 'Sumatra Mandheling', 'Earthy, complex, and full-bodied with a syrupy richness. Dark roast, ground.', 18750.00, 2, 35, 'images/sumatra_mandheling.jpg', NULL),
(12, 'Costa Rican Tarrazu', 'Clean, crisp, with bright citrus notes and a hint of chocolate. Medium roast, ground.', 16900.00, 2, 55, 'images/costa_rican_tarrazu.jpg', NULL),
(13, 'Decaf Colombian Excelso', 'Full-flavored decaf using Swiss Water Process. Notes of caramel and nuts. Whole bean.', 19250.00, 1, 25, 'images/decaf_colombian.jpg', NULL),
(14, 'Hazelnut Flavored Coffee', 'Smooth Arabica beans infused with rich hazelnut flavor. Ground.', 14500.00, 2, 70, 'images/hazelnut_flavored.jpg', NULL),
(15, 'Variety Pack Coffee Pods', 'A mix of our most popular coffee pods. 30 count.', 22000.00, 3, 80, 'images/variety_pods.jpg', NULL),
(16, 'Reusable Coffee Filter', 'Eco-friendly stainless steel mesh filter for pour-over or drip machines.', 12990.00, 4, 45, 'images/reusable_filter.jpg', NULL),
(17, 'Gooseneck Kettle', 'Stainless steel gooseneck kettle for precise pour-over brewing. 1L capacity.', 39990.00, 4, 20, 'images/gooseneck_kettle.jpg', NULL),
(18, 'Coffee Grinder - Manual', 'Burr grinder for a consistent grind. Portable and perfect for travel.', 29500.00, 4, 28, 'images/manual_grinder.jpg', NULL),
(19, 'Espresso Blend - Dark Knight', 'Our signature dark roast espresso blend. Rich, intense, with notes of dark chocolate and smoke. Whole bean.', 20500.00, 1, 50, 'images/espresso_dark_knight.jpg', NULL),
(20, 'Breakfast Blend - Morning Light', 'A bright and cheerful medium roast to start your day. Ground.', 15000.00, 2, 64, 'images/breakfast_blend.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `returnpolicy`
--

CREATE TABLE `returnpolicy` (
  `ReturnPolicyID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `ReturnDate` date DEFAULT NULL,
  `Reason` varchar(255) DEFAULT NULL,
  `Status` enum('pending','approved','rejected') DEFAULT NULL,
  `ProcessedByAdminID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support`
--

CREATE TABLE `support` (
  `SupportID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `RequestType` enum('technical','billing','general') DEFAULT NULL,
  `SupportDate` date DEFAULT NULL,
  `IssueDescription` varchar(255) DEFAULT NULL,
  `AssignedAdminID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`AddressID`),
  ADD KEY `CustomerID` (`CustomerID`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`AdminID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`CartID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`CategoryID`),
  ADD KEY `idx_category_admin` (`AdminID`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`ContactID`),
  ADD KEY `CustomerID` (`CustomerID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CustomerID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`FeedbackID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `OrderID` (`OrderID`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `fk_AddressID` (`AddressID`),
  ADD KEY `idx_order_admin` (`ProcessedByAdminID`);

--
-- Indexes for table `orderdetail`
--
ALTER TABLE `orderdetail`
  ADD PRIMARY KEY (`OrderDetailID`),
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`ProductID`),
  ADD KEY `CategoryID` (`CategoryID`),
  ADD KEY `idx_product_admin` (`AdminID`);

--
-- Indexes for table `returnpolicy`
--
ALTER TABLE `returnpolicy`
  ADD PRIMARY KEY (`ReturnPolicyID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `idx_return_admin` (`ProcessedByAdminID`);

--
-- Indexes for table `support`
--
ALTER TABLE `support`
  ADD PRIMARY KEY (`SupportID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `idx_support_admin` (`AssignedAdminID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `AddressID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `AdminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `CartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `ContactID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `FeedbackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `orderdetail`
--
ALTER TABLE `orderdetail`
  MODIFY `OrderDetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `returnpolicy`
--
ALTER TABLE `returnpolicy`
  MODIFY `ReturnPolicyID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support`
--
ALTER TABLE `support`
  MODIFY `SupportID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`);

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `fk_category_admin` FOREIGN KEY (`AdminID`) REFERENCES `admin` (`AdminID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`OrderID`) REFERENCES `order` (`OrderID`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_AddressID` FOREIGN KEY (`AddressID`) REFERENCES `address` (`AddressID`),
  ADD CONSTRAINT `fk_order_processed_by_admin` FOREIGN KEY (`ProcessedByAdminID`) REFERENCES `admin` (`AdminID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`);

--
-- Constraints for table `orderdetail`
--
ALTER TABLE `orderdetail`
  ADD CONSTRAINT `orderdetail_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `order` (`OrderID`),
  ADD CONSTRAINT `orderdetail_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_admin` FOREIGN KEY (`AdminID`) REFERENCES `admin` (`AdminID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`);

--
-- Constraints for table `returnpolicy`
--
ALTER TABLE `returnpolicy`
  ADD CONSTRAINT `fk_return_processed_by_admin` FOREIGN KEY (`ProcessedByAdminID`) REFERENCES `admin` (`AdminID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `returnpolicy_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`);

--
-- Constraints for table `support`
--
ALTER TABLE `support`
  ADD CONSTRAINT `fk_support_assigned_admin` FOREIGN KEY (`AssignedAdminID`) REFERENCES `admin` (`AdminID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `support_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
