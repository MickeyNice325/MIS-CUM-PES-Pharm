-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 30, 2024 at 11:42 AM
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
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `committee`
--

CREATE TABLE `committee` (
  `id` int(11) NOT NULL,
  `com_code` varchar(11) NOT NULL,
  `staff_code` varchar(11) NOT NULL,
  `staff_name` varchar(255) NOT NULL,
  `dep_name` varchar(255) NOT NULL,
  `unit_name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `committee`
--

INSERT INTO `committee` (`id`, `com_code`, `staff_code`, `staff_name`, `dep_name`, `unit_name`, `status`) VALUES
(2, 'E160020', '1517', 'นิวาศ วงค์คำ', 'สํานักงานคณะ', 'หน่วยอาคารสถานที่และยานพาหนะ', 'committee'),
(4, 'E160020', 'E160020', 'กุสุมา ภาคภูมิ', 'สํานักงานคณะ', 'หน่วยบริหารงานบุคคล', 'committee'),
(5, 'E160020', 'E160039', 'ประจวบ ไชยนาง', 'สํานักงานคณะ', 'หน่วยอาคารสถานที่และยานพาหนะ', 'committee'),
(6, 'E160020', 'A160020', 'สมพร พวงประทุม', 'สํานักงานคณะ', 'null', 'committee'),
(7, 'E160020', 'S4160010', 'ยงยุทธ ไชยน้อย', 'สํานักงานคณะ', 'หน่วยอาคารสถานที่และยานพาหนะ', 'committee'),
(20, 'E160039', '1517', 'นิวาศ วงค์คำ', 'สํานักงานคณะ', 'หน่วยอาคารสถานที่และยานพาหนะ', 'committee'),
(23, 'E160039', 'E160020', 'กุสุมา ภาคภูมิ', 'สํานักงานคณะ', 'หน่วยบริหารงานบุคคล', 'committee'),
(24, 'E160039', 'E160039', 'ประจวบ ไชยนาง', 'สํานักงานคณะ', 'หน่วยอาคารสถานที่และยานพาหนะ', 'committee'),
(25, 'E160039', 'A160020', 'สมพร พวงประทุม', 'สํานักงานคณะ', 'null', 'committee'),
(26, 'E160039', 'S4160010', 'ยงยุทธ ไชยน้อย', 'สํานักงานคณะ', 'หน่วยอาคารสถานที่และยานพาหนะ', 'committee'),
(39, 'A160020', 'E160020', 'กุสุมา ภาคภูมิ', 'สํานักงานคณะ', 'หน่วยบริหารงานบุคคล', 'committee'),
(40, 'A160020', 'E160039', 'ประจวบ ไชยนาง', 'สํานักงานคณะ', 'หน่วยอาคารสถานที่และยานพาหนะ', 'committee'),
(41, 'A160020', 'A160020', 'สมพร พวงประทุม', 'สํานักงานคณะ', 'null', 'committee'),
(42, 'A160020', 'S4160010', 'ยงยุทธ ไชยน้อย', 'สํานักงานคณะ', 'หน่วยอาคารสถานที่และยานพาหนะ', 'committee'),
(43, 'A160020', '1517', 'นิวาศ วงค์คำ', 'สํานักงานคณะ', 'หน่วยอาคารสถานที่และยานพาหนะ', 'committee'),
(44, 'E160020', '1529', 'จันทร์เพ็ญ ชัยชนะ', 'สํานักงานคณะ', 'หน่วยบริหารงานบุคคล', 'supervisor'),
(45, 'S4160010', '1517', 'นิวาศ วงค์คำ', 'สํานักงานคณะ', 'หน่วยอาคารสถานที่และยานพาหนะ', 'committee'),
(46, 'S4160010', 'E160020', 'กุสุมา ภาคภูมิ', 'สํานักงานคณะ', 'หน่วยบริหารงานบุคคล', 'committee'),
(47, 'S4160010', 'E160039', 'ประจวบ ไชยนาง', 'สํานักงานคณะ', 'หน่วยอาคารสถานที่และยานพาหนะ', 'committee'),
(48, 'S4160010', 'A160020', 'สมพร พวงประทุม', 'สํานักงานคณะ', 'null', 'committee'),
(49, 'S4160010', 'S4160010', 'ยงยุทธ ไชยน้อย', 'สํานักงานคณะ', 'หน่วยอาคารสถานที่และยานพาหนะ', 'committee'),
(50, '1517', '1517', 'นิวาศ วงค์คำ', 'สํานักงานคณะ', 'หน่วยอาคารสถานที่และยานพาหนะ', 'committee'),
(51, '1517', 'E160020', 'กุสุมา ภาคภูมิ', 'สํานักงานคณะ', 'หน่วยบริหารงานบุคคล', 'committee'),
(52, '1517', 'E160039', 'ประจวบ ไชยนาง', 'สํานักงานคณะ', 'หน่วยอาคารสถานที่และยานพาหนะ', 'committee'),
(53, '1517', 'A160020', 'สมพร พวงประทุม', 'สํานักงานคณะ', 'null', 'committee'),
(54, '1517', 'S4160010', 'ยงยุทธ ไชยน้อย', 'สํานักงานคณะ', 'หน่วยอาคารสถานที่และยานพาหนะ', 'committee'),
(56, '1536', 'E160020', 'กุสุมา ภาคภูมิ', 'สํานักงานคณะ', 'หน่วยบริหารงานบุคคล', 'committee');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `code` varchar(11) NOT NULL COMMENT 'รหัสแผนก',
  `name` varchar(255) DEFAULT NULL COMMENT 'ชื่อแผนก'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `code`, `name`) VALUES
(1, '1201', 'สํานักงานคณะ'),
(2, '1202', 'ภาควิชาบริบาลเภสัชกรรม'),
(3, '1203', 'ภาควิชาวิทยาศาสตร์เภสัชกรรม'),
(4, '1204', 'หน่วยงานอื่นๆ ในสังกัด'),
(5, '1205', 'ศูนย์ปฏิบัติการเภสัชชุมชน'),
(6, '1206', 'ศูนย์บริการเภสัชกรรม'),
(7, '1207', 'คณะเภสัชศาสตร์'),
(8, '1208', 'ศูนย์นวัตกรรมสมุนไพร'),
(9, '1209', 'ศูนย์ DermX');

-- --------------------------------------------------------

--
-- Table structure for table `duty`
--

CREATE TABLE `duty` (
  `id` int(11) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `name_sort` varchar(255) DEFAULT NULL,
  `groups_type` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `duty`
--

INSERT INTO `duty` (`id`, `code`, `name`, `name_sort`, `groups_type`, `type`) VALUES
(2, '1', 'ข้าราชการพลเรือนในมหาวิทยาลัย-สาย ก', 'ข้าราชการ', 1, 1),
(3, '2', 'ข้าราชการพลเรือนในมหาวิทยาลัย-สาย ข', 'ข้าราชการ', 0, 0),
(4, '3', 'ข้าราชการพลเรือนในมหาวิทยาลัย-สาย ค', 'ข้าราชการ', 0, 0),
(5, '4', 'พนักงานมหาวิทยาลัย-พนักงานวิชาการ', 'พนักงานมหาวิทยาลัย', 2, 1),
(6, '5', 'พนักงานมหาวิทยาลัย-พนักงานปฎิบัติการ', 'พนักงานมหาวิทยาลัย', 2, 2),
(7, '6', 'พนักงานมหาวิทยาลัยชั่วคราว(พนักงาน ส่วนงาน)', 'พนักงานมหาวิทยาลัยชั่วคราว(พนักงาน ส่วนงาน)', 3, 3),
(8, '7', 'ลูกจ้างประจำ', 'ลูกจ้างประจำ', 4, 2),
(9, '8', 'ลูกจ้างชั่วคราว', 'ลูกจ้างชั่วคราว', 0, 0),
(10, '9', 'ไม่ระบุ', NULL, 0, 0),
(11, '10', 'พนักงานโครงการของส่วนงาน', 'พนักงานโครงการของส่วนงาน', 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `estimate`
--

CREATE TABLE `estimate` (
  `id` int(11) NOT NULL,
  `est_year` int(4) DEFAULT NULL COMMENT 'ปีที่ประเมิน',
  `est_lvl` varchar(11) DEFAULT NULL COMMENT 'ระดับการประเมิน',
  `staff_code` varchar(11) DEFAULT NULL COMMENT 'รหัสพนักงาน',
  `est_opinion` varchar(255) DEFAULT NULL COMMENT 'ข้อคิดเห็น',
  `est_date` date DEFAULT NULL COMMENT 'วันที่',
  `assessor_code` varchar(255) DEFAULT NULL COMMENT 'รหัสผู้ประเมิน',
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `estimate_score`
--

CREATE TABLE `estimate_score` (
  `id` int(11) NOT NULL,
  `ests_year` int(4) DEFAULT NULL COMMENT 'ปีที่ประเมิน',
  `staff_code` varchar(11) DEFAULT NULL COMMENT 'รหัสพนักงาน',
  `performance` float(5,2) DEFAULT NULL,
  `behavior` float(5,2) DEFAULT NULL,
  `score` float(5,2) DEFAULT NULL COMMENT 'คะแนนประเมิน',
  `results` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `date_complete` date DEFAULT NULL,
  `dean_status` varchar(11) NOT NULL,
  `dean_date` date DEFAULT NULL,
  `round` int(11) NOT NULL,
  `duty_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `estimate_score`
--

INSERT INTO `estimate_score` (`id`, `ests_year`, `staff_code`, `performance`, `behavior`, `score`, `results`, `status`, `date_complete`, `dean_status`, `dean_date`, `round`, `duty_code`) VALUES
(8, 2566, '1536', 65.25, 28.75, 94.00, 'ดีเด่น', 'complete', NULL, '', '2024-08-02', 1, '8'),
(9, 2566, '1517', 66.25, 28.25, 94.00, 'ดีเด่น', 'complete', NULL, '', NULL, 1, '6'),
(10, 2566, '1529', 67.00, 28.00, 95.00, 'ดีเด่น', 'complete', NULL, '', NULL, 2, '7'),
(11, 2566, '1536', 65.20, 29.00, 94.00, 'ดีเด่น', 'complete', NULL, '', NULL, 2, '8'),
(12, 2566, '1517', 65.38, 28.89, 94.00, 'ดีเด่น', 'complete', NULL, '', NULL, 2, '6'),
(13, 2567, '1517', 66.50, 28.50, 95.00, 'ดีเด่น', 'complete', NULL, '', NULL, 1, '6'),
(14, 2567, '1536', 65.75, 29.25, 95.00, 'ดีเด่น', 'complete', NULL, 'complete', NULL, 1, '8'),
(15, 2567, '1529', 66.25, 28.00, 94.00, 'ดีเด่น', 'complete', NULL, 'complete', NULL, 1, '7'),
(16, 2567, '1536', 66.00, 29.00, 95.00, 'ดีเด่น', 'complete', NULL, 'complete', NULL, 2, '8'),
(17, 2567, '1517', 66.00, 28.75, 94.00, 'ดีเด่น', 'complete', NULL, 'complete', NULL, 2, '6'),
(18, 2567, '1529', 66.25, 28.00, 94.00, 'ดีเด่น', 'complete', NULL, 'complete', NULL, 2, '7'),
(19, 2567, 'S4160035', 68.02, 30.00, 98.02, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '1'),
(20, 2567, 'S4160010', 66.60, 30.00, 96.60, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '7'),
(21, 2567, 'S4160006', 66.70, 29.56, 96.26, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '6'),
(22, 2567, 'S4160021', 66.46, 29.78, 96.24, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '5'),
(23, 2567, 'S4160039', 66.16, 30.00, 96.16, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '4'),
(24, 2567, 'S4160017', 66.08, 30.00, 96.08, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '2'),
(25, 2567, 'S4160007', 66.10, 29.78, 95.88, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '7'),
(26, 2567, 'S4160012', 62.44, 29.56, 92.00, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '1'),
(27, 2566, 'S4160039', 66.68, 30.00, 96.68, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '4'),
(28, 2566, 'S4160010', 66.60, 30.00, 96.60, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '7'),
(29, 2566, 'S4160007', 66.66, 29.78, 96.44, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '7'),
(30, 2566, 'S4160017', 66.12, 29.78, 95.90, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '2'),
(31, 2566, 'S4160006', 66.06, 29.56, 95.62, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '6'),
(32, 2566, 'S4160021', 65.36, 29.78, 95.14, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '5'),
(33, 2566, 'S4160012', 63.96, 29.56, 93.52, 'ดีมาก', 'complete', NULL, '', NULL, 1, '1'),
(34, 2565, 'S4160021', 67.20, 29.78, 96.98, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '5'),
(35, 2565, 'S4160017', 66.64, 30.00, 96.64, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '2'),
(36, 2565, 'S4160006', 66.60, 30.00, 96.60, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '6'),
(37, 2565, 'S4160007', 66.48, 30.00, 96.48, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '7'),
(38, 2565, 'S4160039', 66.28, 30.00, 96.28, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '4'),
(39, 2565, 'S4160010', 66.24, 30.00, 96.24, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '7'),
(40, 2565, 'S4160012', 65.16, 29.33, 94.49, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '1'),
(41, 2565, 'E160020', 68.20, 29.78, 97.98, 'ดีมาก', 'waitcomplete', '2024-08-02', '', NULL, 1, '1'),
(42, 2565, 'A160020', 67.80, 29.87, 97.67, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '3'),
(43, 2565, 'E160039', 67.24, 29.78, 97.02, 'ดีมาก', 'complete', '2024-08-01', '', NULL, 1, '2'),
(44, 2565, 'B160003', 64.80, 29.33, 94.13, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '3'),
(45, 2566, 'E160020', 68.30, 30.00, 98.30, 'ดีมาก', 'complete', '2024-08-02', 'complete', NULL, 1, '1'),
(46, 2566, 'A160020', 67.90, 29.73, 97.63, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '3'),
(47, 2566, 'E160039', 67.52, 29.78, 97.30, 'ดีมาก', 'complete', '2024-08-01', 'complete', NULL, 1, '2'),
(48, 2566, 'B160003', 65.66, 29.56, 95.22, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '3'),
(49, 2567, 'A160020', 67.82, 30.00, 97.82, 'ดีมาก', 'complete', NULL, '', NULL, 1, '3'),
(50, 2567, 'E160020', 67.76, 30.00, 97.76, 'ดีมาก', 'complete', '2024-08-02', 'complete', NULL, 1, '1'),
(51, 2567, 'E160039', 67.20, 29.56, 96.76, 'ดีมาก', 'complete', '2024-08-01', 'complete', NULL, 1, '2'),
(52, 2567, 'B160003', 64.92, 29.56, 94.48, 'ดีมาก', 'complete', NULL, 'complete', NULL, 1, '3'),
(53, 2565, 'E160039', 99.99, 99.99, 99.90, 'Dmak', 'complete', '2024-08-01', 'complete', NULL, 2, '7');

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE `job` (
  `id` int(11) NOT NULL,
  `code` varchar(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `dep_code` varchar(255) DEFAULT NULL,
  `dean_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `job`
--

INSERT INTO `job` (`id`, `code`, `name`, `dep_code`, `dean_code`) VALUES
(1, '120101', 'งานบริหารทั่วไป', '1201', 'X003'),
(2, '120102', 'งานบริการการศึกษาและพัฒนาคุณภาพนักศึกษา', '1201', 'X002'),
(3, '120103', 'งานนโยบายและแผนและประกันคุณภาพการศึกษา', '1201', 'X005'),
(4, '120104', 'งานการเงิน การคลังและพัสดุ', '1201', 'X003'),
(5, '120105', 'งานบริหารงานวิจัย บริการวิชาการ และวิเทศสัมพันธ์', '1201', 'X004');

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `name_eng` varchar(100) NOT NULL,
  `dep_code` varchar(150) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`id`, `code`, `name`, `name_eng`, `dep_code`) VALUES
(1, '000000', 'ไม่ระบุ', '', 'ไม่ระบุ'),
(2, '100000', 'อาจารย์', '', 'อ.'),
(13, '204012', 'พนักงานรักษาความปลอดภัย', '', 'พนักงานรักษาความปลอดภัย'),
(20, '205015', 'นักวิทยาศาสตร์', '', 'นักวิทยาศาสตร์'),
(23, '206019', 'นักวิจัย', '', 'นักวิจัย'),
(30, '207015', 'พนักงานขับรถยนต์', '', 'พนักงานขับรถยนต์'),
(47, '214011', 'เภสัชกร', '', 'เภสัชกร'),
(57, '218026', 'นักวิชาการคอมพิวเตอร์', '', 'นักวิชาการคอมพิวเตอร์'),
(72, '301022', 'พนักงานธุรการ', '', 'พ.ธุรการ'),
(82, '302019', 'เจ้าหน้าที่บริหารงานทั่วไป (ชำนาญการพิเศษ)', '', 'จ.บริหารงานทั่วไป'),
(96, '308009', 'พนักงานบัญชี', '', 'พ.บัญชี'),
(99, '308035', 'นักวิชาการเงินและบัญชี', '', 'นักวิชาการเงินและบัญชี'),
(115, '314021', 'พนักงานวิทยาศาสตร์', '', 'พ.วิทยาศาสตร์'),
(125, '320025', 'พนักงานเข้าเล่ม', '', 'พนักงานเข้าเล่ม'),
(209, 'x67000', 'พนักงานเก็บเงิน', '', 'พนักงานเก็บเงิน'),
(210, 'x68000', 'พนักงานคลังและเวชภัณฑ์', '', 'พนักงานคลังและเวชภัณฑ์'),
(212, 'x70000', 'พนักงานบัญชีและธุรการ', '', 'พนักงานบัญชีและธุรการ'),
(213, 'x71000', 'พนักงานบริการเอกสารทั่วไป', '', 'พนักงานบริการเอกสารทั่วไป'),
(214, 'x71001', 'พนักงานสถานที่', '', 'พนักงานสถานที่'),
(216, 'x71003', 'พนักงานห้องปฎิบัติการ', '', 'พนักงานห้องปฎิบัติการ'),
(217, 'x71004', 'พนักงานพิมพ์', '', 'พนักงานพิมพ์'),
(218, 'x71005', 'เจ้าหน้าที่สำนักงาน', '', 'เจ้าหน้าที่สำนักงาน'),
(219, 'x71006', 'พนักงานบริการทั่วไป', '', 'พนักงานบริการทั่วไป'),
(220, 'x71007', 'พนักงานบริการฝีมือ(ด้านเทคนิคและเครื่องยนต์)', '', 'พนักงานบริการฝีมือ(ด้านเทคนิคและเครื่องยนต์)'),
(221, 'x71008', 'พนักงานปฏิบัติงาน', '', 'พนักงานปฏิบัติงาน'),
(224, 'x71011', 'นักการเงินและบัญชี', '', 'นักการเงินและบัญชี'),
(225, 'x71012', 'พนักงานบริการฝีมือ (ด้านวิทยาศาสตร์และการแพทย์)', '', 'พนักงานบริการฝีมือ (ด้านวิทยาศาสตร์และการแพทย์)'),
(226, 'x71013', 'พนักงานบริการฝีมือ (ด้านสำนักงาน)', '', 'พนักงานบริการฝีมือ (ด้านสำนักงาน)'),
(227, 'x71014', 'นักวิทยาศาสตร์เกษตร', '', 'นักวิทยาศาสตร์เกษตร'),
(228, 'x71015', 'พนักงานช่าง', '', 'พนักงานช่าง'),
(229, 'x71016', 'นักวิชาการศึกษา', '', 'นักวิชาการศึกษา'),
(230, 'x71017', 'ที่ปรึกษา', 'Counselor', 'ที่ปรึกษา'),
(231, 'x71018', 'ผู้มีความรู้ความสามารถพิเศษ', '', 'ผู้มีความรู้ความสามารถพิเศษ'),
(232, 'x71019', 'ผู้จัดการศูนย์ปฏิบัติการเภสัชชุมชน', '', 'ผู้จัดการศูนย์ปฏิบัติการเภสัชชุมชน'),
(233, 'x71020', 'นักจัดการงานทั่วไป', '', 'นักจัดการงานทั่วไป'),
(234, 'x71021', 'ผู้ช่วยเภสัชกร', '', 'ผู้ช่วยเภสัชกร'),
(235, '110000', 'อาจารย์ชาวต่างประเทศ (ผู้สอน)', '', 'อ.'),
(236, 'x71022', 'วิศวกร', '', 'วิศวกร'),
(237, 'x71023', 'เจ้าหน้าที่โครงการ', '', 'เจ้าหน้าที่โครงการ'),
(238, 'x71024', 'นักบริหารงานทั่วไป', '', 'นักบริหารงานทั่วไป'),
(239, 'x71025', 'นักบริการงานทั่วไป', '', 'นักบริการงานทั่วไป'),
(240, 'x71026', 'นักบริหารงานการศึกษา', '', 'นักบริหารงานการศึกษา'),
(241, 'x71027', 'นักวิเคราะห์นโยบายและแผน', '', 'นักวิเคราะห์นโยบายและแผน'),
(242, 'x71028', 'นักทรัพยากรบุคคล', '', 'นักทรัพยากรบุคคล'),
(243, 'x71029', 'นักบริหารงานพัสดุ', '', 'นักบริหารงานพัสดุ'),
(244, 'x71030', 'นักกิจการนักศึกษา', '', 'นักกิจการนักศึกษา'),
(245, 'x71031', 'นักบริหารงานวิจัย', '', 'นักบริหารงานวิจัย'),
(246, 'x71032', 'นักบริหารเทคโนโลยีการศึกษา', '', 'นักบริหารเทคโนโลยีการศึกษา'),
(247, 'x71033', 'นักวิเทศสัมพันธ์', '', 'นักวิเทศสัมพันธ์');

-- --------------------------------------------------------

--
-- Table structure for table `position_dean`
--

CREATE TABLE `position_dean` (
  `id` int(11) NOT NULL,
  `code` varchar(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `position_dean`
--

INSERT INTO `position_dean` (`id`, `code`, `name`, `level`) VALUES
(1, 'X001', 'คณบดี', 3),
(2, 'X002', 'รองคณบดีฝ่ายวิชาการ', 3),
(3, 'X003', 'รองคณบดีฝ่ายบริหารและศิษย์เก่าสัมพันธ์', 3),
(4, 'X004', 'รองคณบดีฝ่ายวิจัยและวิเทศสัมพันธ์', 3),
(5, 'X005', 'รองคณบดีฝ่ายแผนงานและพัฒนาคุณภาพ', 3),
(6, 'X006', 'ผู้ช่วยคณบดีฝ่ายบัณฑิตศึกษา', 3),
(7, 'X007', 'ผู้ช่วยคณบดีฝ่ายวิชาชีพและบริการวิชาการ', 3),
(8, 'X008', 'ผู้ช่วยคณบดีฝ่ายพัฒนานักศึกษา', 3),
(9, 'X009', 'หัวหน้าศูนย์บริการเภสัชกรรม', 2),
(10, 'X010', 'ผู้อำนวยการศูนย์ปฏิบัติการเภสัชชุมชน', 0),
(11, 'X011', 'หัวหน้าภาควิชาวิทยาศาสตร์เภสัชกรรม', 2),
(12, 'X012', 'หัวหน้าภาควิชาบริบาลเภสัชกรรม', 2),
(13, 'X013', 'รองหัวหน้าภาควิชาวิทยาศาสตร์เภสัชกรรม', 0),
(14, 'X014', 'รองหัวหน้าภาควิชาบริบาลเภสัชกรรม', 0),
(15, 'X015', 'เลขานุการคณะเภสัชศาสตร์', 0),
(16, 'X016', 'หัวหน้างานบริหารทั่วไป', 2),
(17, 'X017', 'หัวหน้างาน การเงิน การคลัง และพัสดุ', 2),
(18, 'X018', 'หัวหน้างานนโยบายและแผนและประกันคุณภาพการศึกษา', 2),
(19, 'X019', 'หัวหน้างานบริการการศึกษาและพัฒนาคุณภาพนักศึกษา', 2),
(20, 'X020', 'หัวหน้างานบริหารงานวิจัย บริการวิชาการ วิเทศสัมพันธ์', 2),
(21, 'X021', 'ผู้ช่วยคณบดีฝ่ายเทคโนโลยีสารสนเทศ', 3),
(22, 'X022', 'รองผู้อำนวยการศูนย์ปฏิบัติการเภสัชชุมชน', 0),
(23, 'X023', 'ผู้ช่วยคณบดีฝ่ายพัฒนางานด้านสมุนไพร', 0),
(24, 'X024', 'หัวหน้าศูนย์นวัตกรรมสมุนไพร', 2),
(25, 'X025', 'รักษาการแทนเลขานุการคณะเภสัชศาสตร์', 0),
(26, 'X026', 'รองคณบดี', 1),
(27, 'X027', 'ผู้ช่วยคณบดี', 1),
(28, 'X028', 'รักษาการแทนหัวหน้าภาควิชาวิทยาศาสตร์เภสัชกรรม', 1),
(29, 'X101', 'ผู้บริหารหน่วยงานนอกคณะฯ', 0),
(30, 'X102', 'ผู้จัดการศูนย์ปฏิบัติการเภสัชชุมชน', 1),
(31, 'X103', 'ปฏิบัติการแทนคณบดี', 1),
(32, 'X104', 'หัวหน้าศูนย์ DermX', 1),
(33, 'X200', 'รักษาการแทน', 0),
(34, 'X999', 'เจ้าหน้าที่งานนโยบายและแผนและประกันคุณภาพการศึกษา', 1);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `code` varchar(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) NOT NULL,
  `dep_code` varchar(11) DEFAULT NULL,
  `job_code` varchar(11) DEFAULT NULL,
  `unit_code` varchar(11) DEFAULT NULL,
  `position_dean` varchar(11) DEFAULT NULL,
  `duty_code` varchar(255) DEFAULT NULL,
  `position_code` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `level` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `code`, `username`, `password`, `fname`, `lname`, `dep_code`, `job_code`, `unit_code`, `position_dean`, `duty_code`, `position_code`, `photo`, `level`) VALUES
(1, 'E160020', 'a', 'a', 'กุสุมา', 'ภาคภูมิ', '1201', '120101', '12010102', NULL, '1', '100000', 'E160020024126.jpg', 'employee'),
(2, 'E160039', 'c', 'c', 'ประจวบ', 'ไชยนาง', '1201', '120101', '12010103', NULL, '2', 'x71001', '3501300306432.jpg', 'employee'),
(3, 'A160020', 'b', 'b', 'สมพร', 'พวงประทุม', '1201', '120101', NULL, 'X016', '3', '204012', '3509900012143.jpg', 'supervisor'),
(4, 'S4160007', 'admin', 'admin', 'พัชรี', 'ตรงเพียรเลิศ', '1201', '120101', '12010101', NULL, '7', '204012', '3509901476666.jpg', 'admin'),
(5, 'S4160010', 'n', 'n', 'ยงยุทธ', 'ไชยน้อย', '1201', '120101', '12010103', NULL, '7', '205015', '3500100160239.jpg', 'employee'),
(6, '1517', 'dean', 'dean', 'นิวาศ', 'วงค์คำ', '1201', '120101', '12010103', NULL, '6', '320025', '3500700079394.jpg', 'dean'),
(7, '1529', '2', '2', 'จันทร์เพ็ญ', 'ชัยชนะ', '1201', '120101', '12010102', NULL, '7', '308035', '3500100130356.jpg', 'supervisor'),
(8, '1536', '', '', 'สุรพล', 'วงศ์หาญ', '1201', '120101', '12010103', NULL, '8', 'x67000', '3500100315402.JPG', 'employee'),
(9, 'S4160012', '', '', 'ชานนท์', 'อ่ำศรี', '1201', '120101', '12010103', NULL, '1', 'x67000', '3250400141232.jpg', 'employee'),
(10, 'S4160017', '', '', 'อนุ', 'สุต๋า', '1201', '120101', '12010103', NULL, '2', 'x71000', '3501500016063.jpg', 'employee'),
(11, 'B160003', '', '', 'สิทธา', 'สภาวจิตร', '1201', '120101', '12010103', NULL, '3', 'x68000', 'B160003022717.jpg', 'employee'),
(12, 'S4160039', '', '', 'ศราวุธ', 'สร้อยฟ้า', '1201', '120101', '12010103', NULL, '4', 'x70000', 'S4160039110709.jpg', 'employee'),
(13, 'S4160021', '', '', 'พัชราภรณ์', 'กิตติญาปกรณ์', '1201', '120101', '12010101', NULL, '5', 'x68000', 'S4160021064632.jpg', 'employee'),
(14, 'S4160006', 'k', 'k', 'สมพงษ์', 'ศรีคำหน้อย', '1201', '120101', '12010103', NULL, '6', '302019', 'S4160006092609.jpg', 'employee'),
(15, 'S4160008-1', '', '', 'ธีรนันทน์', 'จันทร์เที่ยง', '1201', '120101', '12010103', NULL, '7', '100000', 'S4160008-1011958.jpg', 'employee'),
(16, 'S4160009-1', '', '', 'วิภพ', 'สิทธิเจริญ', '1201', '120101', '12010103', NULL, '8', '100000', 'S4160009-1072831.jpg', 'employee'),
(17, 'S4160035', '', '', 'สริยา', 'กำลูนเวสารัช', '1201', '120101', '12010102', NULL, '1', 'x67000', 'S4160035124807.jpg', 'employee'),
(18, 'S4160032-5', 'a1', 'a1', 'ธนวินท์', 'แสงรักษา', '1201', '120101', '12010103', NULL, '2', 'x67000', '', 'employee'),
(19, 'S4160048', 'a2', 'a2', 'ยศวีร์', 'วาณิชย์พุฒิกุล  ', '1201', '120101', NULL, NULL, '3', 'x68000', 'P160021085541.jpg', 'employee'),
(20, 'E160001', 'v', 'v', 'ไพศาล', 'ณ ลำปาง', '1201', '120101', '12010101', 'X002', '7', '308035', '', 'employee');

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `id` int(11) NOT NULL,
  `code` varchar(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `job_code` varchar(11) DEFAULT NULL,
  `dean_code` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `unit`
--

INSERT INTO `unit` (`id`, `code`, `name`, `job_code`, `dean_code`) VALUES
(1, '12010101', 'หน่วยธุรการและสารบรรณ', '120101', 'X003'),
(2, '12010102', 'หน่วยบริหารงานบุคคล', '120101', 'X003'),
(3, '12010103', 'หน่วยอาคารสถานที่และยานพาหนะ', '120101', 'X003'),
(4, '12010201', 'หน่วยหลักสูตรทะเบียนและพัฒนาวิชาการ', '120102', 'X002'),
(5, '12010202', 'หน่วยกิจการนักศึกษา', '120102', 'X008'),
(6, '12010203', 'หน่วยบัณฑิตศึกษา', '120102', 'X006'),
(7, '12010204', 'หน่วยฝีกงานและพัฒนาวิชาชีพ', '120102', 'X007'),
(8, '12010205', 'หน่วยนวัตกรรมการศึกษา', '120102', 'X002'),
(9, '12010301', 'หน่วยแผนและงบประมาณ', '120103', 'X005'),
(10, '12010302', 'หน่วยประกันคุณภาพการศึกษา', '120103', 'X005'),
(11, '12010304', 'หน่วยพัฒนาระบบสารสนเทศ', '120103', 'X021'),
(12, '12010401', 'หน่วยการเงินและบัญชี', '120104', 'X003'),
(13, '12010402', 'หน่วยพัสดุ', '120104', 'X003'),
(14, '12010501', 'หน่วยบริหารงานวิจัย', '120105', 'X004'),
(15, '12010502', 'หน่วยวิเทศสัมพันธ์', '120105', 'X004'),
(16, '12010503', 'หน่วยบริการเครื่องมือกลาง', '120105', 'X004'),
(17, '12010504', 'หน่วยบริการวิชาการ', '120105', 'X007');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `committee`
--
ALTER TABLE `committee`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_code` (`staff_code`),
  ADD KEY `com_code` (`com_code`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `duty`
--
ALTER TABLE `duty`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code_2` (`code`),
  ADD KEY `code` (`code`);

--
-- Indexes for table `estimate`
--
ALTER TABLE `estimate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_code` (`staff_code`);

--
-- Indexes for table `estimate_score`
--
ALTER TABLE `estimate_score`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_estimate` (`ests_year`,`staff_code`,`round`),
  ADD KEY `staff_code` (`staff_code`),
  ADD KEY `duty_code` (`duty_code`);

--
-- Indexes for table `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `dep_code` (`dep_code`),
  ADD KEY `dean_code` (`dean_code`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code_2` (`code`),
  ADD KEY `code` (`code`);

--
-- Indexes for table `position_dean`
--
ALTER TABLE `position_dean`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `dep_code` (`dep_code`),
  ADD KEY `job_code` (`job_code`),
  ADD KEY `unit_code` (`unit_code`),
  ADD KEY `dean_code` (`position_dean`),
  ADD KEY `duty_code` (`duty_code`),
  ADD KEY `position_code` (`position_code`),
  ADD KEY `position_dean` (`position_dean`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `job_code` (`job_code`),
  ADD KEY `dean_code` (`dean_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `committee`
--
ALTER TABLE `committee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `duty`
--
ALTER TABLE `duty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `estimate`
--
ALTER TABLE `estimate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `estimate_score`
--
ALTER TABLE `estimate_score`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `job`
--
ALTER TABLE `job`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `position`
--
ALTER TABLE `position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=248;

--
-- AUTO_INCREMENT for table `position_dean`
--
ALTER TABLE `position_dean`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `committee`
--
ALTER TABLE `committee`
  ADD CONSTRAINT `committee_ibfk_1` FOREIGN KEY (`com_code`) REFERENCES `staff` (`code`),
  ADD CONSTRAINT `committee_ibfk_2` FOREIGN KEY (`staff_code`) REFERENCES `staff` (`code`);

--
-- Constraints for table `estimate`
--
ALTER TABLE `estimate`
  ADD CONSTRAINT `estimate_ibfk_1` FOREIGN KEY (`staff_code`) REFERENCES `staff` (`code`);

--
-- Constraints for table `estimate_score`
--
ALTER TABLE `estimate_score`
  ADD CONSTRAINT `estimate_score_ibfk_1` FOREIGN KEY (`staff_code`) REFERENCES `staff` (`code`),
  ADD CONSTRAINT `estimate_score_ibfk_2` FOREIGN KEY (`duty_code`) REFERENCES `duty` (`code`);

--
-- Constraints for table `job`
--
ALTER TABLE `job`
  ADD CONSTRAINT `job_ibfk_1` FOREIGN KEY (`dep_code`) REFERENCES `department` (`code`),
  ADD CONSTRAINT `job_ibfk_2` FOREIGN KEY (`dean_code`) REFERENCES `position_dean` (`code`);

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`dep_code`) REFERENCES `department` (`code`),
  ADD CONSTRAINT `staff_ibfk_2` FOREIGN KEY (`job_code`) REFERENCES `job` (`code`),
  ADD CONSTRAINT `staff_ibfk_3` FOREIGN KEY (`unit_code`) REFERENCES `unit` (`code`),
  ADD CONSTRAINT `staff_ibfk_4` FOREIGN KEY (`position_dean`) REFERENCES `position_dean` (`code`),
  ADD CONSTRAINT `staff_ibfk_5` FOREIGN KEY (`duty_code`) REFERENCES `duty` (`code`);

--
-- Constraints for table `unit`
--
ALTER TABLE `unit`
  ADD CONSTRAINT `unit_ibfk_1` FOREIGN KEY (`dean_code`) REFERENCES `position_dean` (`code`),
  ADD CONSTRAINT `unit_ibfk_2` FOREIGN KEY (`job_code`) REFERENCES `job` (`code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
