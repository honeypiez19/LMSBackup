-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 28, 2024 at 04:34 PM
-- Server version: 10.3.38-MariaDB-0ubuntu0.20.04.1
-- PHP Version: 7.4.3-4ubuntu2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `LMS`
--

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `d_id` int(11) NOT NULL,
  `d_department` varchar(30) DEFAULT NULL,
  `d_remark` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`d_id`, `d_department`, `d_remark`) VALUES
(1, 'Management', 'Management'),
(2, 'Office', 'Office'),
(3, 'CAD1', 'CAD1'),
(4, 'CAD2', 'CAD2'),
(5, 'CAM', 'CAM'),
(6, 'RD', 'RD'),
(7, 'PC', 'Production'),
(8, 'QC', 'QC'),
(9, 'MC', 'Machine'),
(10, 'FN', 'Finishing');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `e_id` int(11) NOT NULL,
  `e_usercode` varchar(10) DEFAULT NULL,
  `e_username` varchar(10) DEFAULT NULL,
  `e_password` varchar(10) DEFAULT NULL,
  `e_name` varchar(255) DEFAULT NULL,
  `e_department` varchar(15) DEFAULT NULL,
  `e_sub_department` varchar(15) DEFAULT NULL,
  `e_sub_department2` varchar(15) DEFAULT NULL,
  `e_sub_department3` varchar(15) DEFAULT NULL,
  `e_sub_department4` varchar(15) DEFAULT NULL,
  `e_sub_department5` varchar(15) DEFAULT NULL,
  `e_work_start_date` date DEFAULT NULL,
  `e_yearexp` varchar(5) DEFAULT NULL,
  `e_level` varchar(15) DEFAULT NULL,
  `e_workplace` varchar(10) DEFAULT NULL,
  `e_remark` varchar(50) DEFAULT NULL,
  `e_email` varchar(50) DEFAULT NULL,
  `e_phone` varchar(12) DEFAULT NULL,
  `e_status` varchar(2) DEFAULT NULL,
  `e_token` varchar(255) DEFAULT NULL,
  `e_leave_personal` varchar(5) DEFAULT NULL,
  `e_leave_personal_no` varchar(5) DEFAULT NULL,
  `e_leave_sick` varchar(5) DEFAULT NULL,
  `e_leave_sick_work` varchar(5) DEFAULT NULL,
  `e_leave_annual` varchar(5) DEFAULT NULL,
  `e_stop_work` varchar(5) DEFAULT NULL,
  `e_late` varchar(5) DEFAULT NULL,
  `e_other` varchar(5) DEFAULT NULL,
  `e_add_datetime` datetime DEFAULT NULL,
  `e_add_name` varchar(10) DEFAULT NULL,
  `e_upd_datetime` datetime DEFAULT NULL,
  `e_upd_name` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`e_id`, `e_usercode`, `e_username`, `e_password`, `e_name`, `e_department`, `e_sub_department`, `e_sub_department2`, `e_sub_department3`, `e_sub_department4`, `e_sub_department5`, `e_work_start_date`, `e_yearexp`, `e_level`, `e_workplace`, `e_remark`, `e_email`, `e_phone`, `e_status`, `e_token`, `e_leave_personal`, `e_leave_personal_no`, `e_leave_sick`, `e_leave_sick_work`, `e_leave_annual`, `e_stop_work`, `e_late`, `e_other`, `e_add_datetime`, `e_add_name`, `e_upd_datetime`, `e_upd_name`) VALUES
(1, '3505004', 'Anchana', '3505004', 'Anchana Assawaphimjinda', 'Management', 'Office', NULL, NULL, NULL, NULL, '1992-05-07', '32.4', 'manager', 'Bang Phli', NULL, NULL, '081-6465557', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', NULL, NULL, '365', '2024-09-23 17:40:49', 'Kanyapa', NULL, NULL),
(2, '3802006', 'Theeraphat', '3802006', 'Theeraphat Thongprasom\r\n', 'MC', '', NULL, NULL, NULL, NULL, '1995-02-06', '29.7', 'user', 'Bang Phli', NULL, NULL, '081-7889811', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', NULL, NULL, '365', '2024-09-23 17:40:49', 'Kanyapa', NULL, NULL),
(3, '3805009', 'Sarawut', '3805009', 'Sarawut Sekunnumtieng\r\n', 'MC', '', NULL, NULL, NULL, NULL, '1995-05-16', '29.4', 'leader', 'Bang Phli', NULL, NULL, '089-0340601', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', NULL, NULL, '365', '2024-09-23 17:40:49', 'Kanyapa', NULL, NULL),
(4, '3904011', 'Rachen', '3904011', 'Rachen Kanpookeaw', 'FN', '', NULL, NULL, NULL, NULL, '1996-04-25', '28.4', 'user', 'Bang Phli', NULL, '', '080-4995966', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(5, '3906013', 'Thongmai', '3906013', 'Thongmai Chinchat', 'MC', '', NULL, NULL, NULL, NULL, '1996-06-19', '28.3', 'user', 'Bang Phli', NULL, '', '099-2566446', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(6, '4001017', 'Somchart', '4001017', 'Somchart Kladkhempetch', 'CAD1', 'Modeling', NULL, NULL, NULL, NULL, '1997-01-24', '27.7', 'user', 'Bang Phli', NULL, '', '083-0199273', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(7, '4108024', 'Nathaphon', '4108024', 'Nathaphon Kaewkanha', 'FN', '', NULL, NULL, NULL, NULL, '1998-08-06', '26.1', 'user', 'Bang Phli', NULL, '', ' 086-5557209', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(8, '4302029', 'Chalad', '4302029', 'Chalad Seesamea', 'MC', '', NULL, NULL, NULL, NULL, '2000-02-10', '24.7', 'user', 'Bang Phli', NULL, '', '085-1281795', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(9, '4403044', 'Wirat', '4403044', 'Wirat Manangan', 'Office', '', NULL, NULL, NULL, NULL, '2001-03-16', '23.6', 'user', 'Bang Phli', NULL, '', '081-9086764', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(10, '4404047', 'Suriya', '4404047', 'Suriya Pimol', 'Management', 'MC', 'PC', 'QC', 'FN', NULL, '2001-04-02', '23.5', 'chief', 'Bang Phli', NULL, '', '089-9276917', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(11, '4404049', 'Wiriya', '4404049', 'Wiriya Houychae', 'Office', 'Store', NULL, NULL, NULL, NULL, '2001-04-23', '23.5', 'leader', 'Bang Phli', NULL, '', '089-1465339', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(12, '4410058', 'Chaikorn', '4410058', 'Chaikorn Suriyasa', 'Management', 'CAD1', 'CAD2', 'CAM', NULL, NULL, '2001-10-01', '22.11', 'assisManager', 'Bang Phli', NULL, '', '092-2677716', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(13, '4502068', 'Preecha', '4502068', 'Preecha Thaboonrueng', 'MC', '', NULL, NULL, NULL, NULL, '2002-02-01', '22.7', 'leader', 'Bang Phli', NULL, '', '085-2350197', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(14, '4505071', 'Suwannee', '4505071', 'Suwannee Khunchamnong', 'Office', 'Store', NULL, NULL, NULL, NULL, '2002-05-07', '22.4', 'user', 'Bang Phli', NULL, '', '087-9261470', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(15, '4506073', 'Thepparit', '4506073', 'Thepparit Pasee', 'CAD1', 'Modeling', NULL, NULL, NULL, NULL, '2002-06-18', '22.3', 'leader', 'Bang Phli', NULL, '', '090-9540979', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(16, '4512084', 'Niran', '4512084', 'Niran Sea-Or', 'QC', '', NULL, NULL, NULL, NULL, '2002-12-02', '21.9', 'leader', 'Bang Phli', NULL, '', '081-6328529', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(17, '4512085', 'Anon', '4512085', 'Anon Maneechot', 'MC', '', NULL, NULL, NULL, NULL, '2002-12-23', '21.9', 'leader', 'Bang Phli', NULL, '', '095-8482879', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(18, '4604093', 'A-Nan', '4604093', 'A-Nan Sangyos', 'MC', '', NULL, NULL, NULL, NULL, '2003-04-16', '21.5', 'user', 'Bang Phli', NULL, '', '084-1324290', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(19, '4606097', 'Chaiyaporn', '4606097', 'Chaiyaporn Poukpoon', 'CAM', '', NULL, NULL, NULL, NULL, '2003-06-02', '21.3', 'user', 'Bang Phli', NULL, '', '089-4988729', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(20, '4606098', 'Thawatyuty', '4606098', 'Thawatyutyotha Photirat', 'FN', '', NULL, NULL, NULL, NULL, '2003-06-02', '21.3', 'user', 'Bang Phli', NULL, '', '081-2681346', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(21, '4610104', 'U-Thai', '4610104', 'U-Thai Chaiyaocha', 'MC', '', NULL, NULL, NULL, NULL, '2003-10-16', '20.11', 'user', 'Bang Phli', NULL, '', '087-9895429', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(22, '4704110', 'Siriporn', '4704110', 'Siriporn Chuaikhoksung', 'Management', 'Sales', NULL, NULL, NULL, NULL, '2004-04-01', '20.5', 'chief', 'Bang Phli', NULL, '', '089-8149638', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(23, '4708116', 'Thanyaboon', '4708116', 'Thanyaboon Phokead', 'MC', '', NULL, NULL, NULL, NULL, '2004-08-09', '20.1', 'user', 'Bang Phli', NULL, '', '080-2853863', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(24, '4803121', 'Prasert', '4803121', 'Prasert Kaweebua', 'CAD2', 'Design', NULL, NULL, NULL, NULL, '2005-03-01', '19.6', 'leader', 'Bang Phli', NULL, '', '083-8395030', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(25, '4804123', 'Sathiya', '4804123', 'Sathiya Suksanguanthai', 'CAM', '', NULL, NULL, NULL, NULL, '2005-04-01', '19.5', 'user', 'Bang Phli', NULL, '', '086-7519662', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(26, '4804124', 'Pamonrut', '4804124', 'Pamonrut Kaewkhiaw', 'CAM', '', NULL, NULL, NULL, NULL, '2005-04-01', '19.5', 'leader', 'Bang Phli', NULL, '', '089-0524307', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(27, '4805126', 'Boonjan', '4805126', 'Boonjan Ahntarit', 'Office', '', NULL, NULL, NULL, NULL, '2005-05-05', '19.4', 'user', 'Bang Phli', NULL, '', '081-5529143', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(28, '4810128', 'Kunchit', '4810128', 'Kunchit Whan-Ngoen', 'CAD1', 'Design', NULL, NULL, NULL, NULL, '2005-10-01', '18.11', 'leader', 'Bang Phli', NULL, '', '089-1278700', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(29, '4902133', 'Kanjana', '4902133', 'Kanjana Sangyos', 'CAD1', '', NULL, NULL, NULL, NULL, '2006-02-06', '18.7', 'user', 'Bang Phli', NULL, '', '086-5361510', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(30, '4906145', 'Wichan', '4906145', 'Wichan Botmoon', 'CAD1', 'Modeling', NULL, NULL, NULL, NULL, '2006-06-19', '18.3', 'user', 'Bang Phli', NULL, '', '095-9239114', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(31, '5003153', 'Mesa', '5003153', 'Mesa Puktalae', 'CAD1', 'Modeling', NULL, NULL, NULL, NULL, '2007-03-01', '17.6', 'user', 'Bang Phli', NULL, '', '098-1027309', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(32, '5006160', 'Manachai', '5006160', 'Manachai Lapootama', 'CAD1', 'Design', NULL, NULL, NULL, NULL, '2007-06-01', '17.3', 'user', 'Bang Phli', NULL, '', '085-1501836', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(33, '5009165', 'Boworn', '5009165', 'Boworn Sorsomboon', 'MC', '', NULL, NULL, NULL, NULL, '2007-09-10', '17', 'user', 'Bang Phli', NULL, '', '086-0612319', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(34, '5102168', 'Piphat', '5102168', 'Piphat Thawkamlea', 'QC', '', NULL, NULL, NULL, NULL, '2008-02-25', '16.6', 'user', 'Bang Phli', NULL, '', '086-1833646 ', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(35, '5102169', 'Ratchasak', '5102169', 'Ratchasak Deeha', 'QC', '', NULL, NULL, NULL, NULL, '2008-02-27', '16.6', 'user', 'Bang Phli', NULL, '', '089-7742941', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(36, '5109177', 'Danupon', '5109177', 'Danupon Kaewkun', 'CAD2', 'Cam-Work NC', NULL, NULL, NULL, NULL, '2008-09-22', '16', 'user', 'Bang Phli', NULL, '', '086-9785120', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(37, '5111178', 'Yongwit', '5111178', 'Yongwit Chasanthia', 'CAD1', 'Design', NULL, NULL, NULL, NULL, '2008-11-17', '15.1', 'user', 'Bang Phli', NULL, '', '087-2031600', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(38, '5303180', 'Pongsak', '5303180', 'Pongsak Yodkhammee', 'CAD2', 'Modeling', NULL, NULL, NULL, NULL, '2010-03-01', '14.6', 'user', 'Bang Phli', NULL, '', '084-7717035', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(39, '5303181', 'Thanu', '5303181', 'Thanu Kunying', 'CAD1', 'Design', NULL, NULL, NULL, NULL, '2010-03-16', '14.6', 'user', 'Bang Phli', NULL, '', '086-6358615', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(40, '5303183', 'Natnicha', '5303183', 'Natnicha Maneechot', 'Office', 'All', NULL, NULL, NULL, NULL, '2010-03-16', '14.6', 'admin', 'Bang Phli', NULL, NULL, '085-2062793', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', NULL, NULL, '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, NULL),
(41, '5307186', 'Jeerasak', '5307186', 'Jeerasak Chaydam', 'CAM', '', NULL, NULL, NULL, NULL, '2010-07-01', '14.2', 'user', 'Bang Phli', NULL, '', '084-5598845', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(42, '5311196', 'Tussanee', '5311196', 'Tussanee Soparut', 'Office', 'Store', NULL, NULL, NULL, NULL, '2010-11-22', '13.1', 'user', 'Bang Phli', NULL, '', '081-3005389', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(43, '5505228', 'Wasitporn', '5505228', 'Wasitporn Nuekthuek', 'Management', 'Sales', NULL, NULL, NULL, NULL, '2012-05-02', '12.4', 'chief', 'Bang Phli', NULL, '', '085-0440790', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(44, '5506235', 'Saeksan', '5506235', 'Saeksan Dungsungnoen', 'PC', '', NULL, NULL, NULL, NULL, '2012-06-14', '12.3', 'leader', 'Bang Phli', NULL, '', '092-9731967', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(45, '5602249', 'Chatchawan', '5602249', 'Chatchawan Einnoy', 'CAM', '', NULL, NULL, NULL, NULL, '2013-02-01', '11.7', 'user', 'Bang Phli', NULL, '', '097-1426524', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(46, '5602251', 'Waewdow', '5602251', 'Waewdow Boonsawai', 'Management', 'AC', NULL, NULL, NULL, NULL, '2013-02-16', '11.7', 'chief', 'Bang Phli', NULL, '', '089-1254542', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(47, '5603257', 'Wongthawat', '5603257', 'Wongthawat Phupanna', 'FN', '', NULL, NULL, NULL, NULL, '2013-03-16', '11.6', 'leader', 'Bang Phli', NULL, '', '085-2549428', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(48, '5608269', 'Umapan', '5608269', 'Umapan Pinitkul', 'CAD1', 'Modeling', NULL, NULL, NULL, NULL, '2013-08-19', '11.1', 'user', 'Bang Phli', NULL, '', '081-4914272', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(49, '5810302', 'Pornsuk', '5810302', 'Pornsuk Tantivisanusopit', 'Management', 'All', NULL, NULL, NULL, NULL, '2015-10-01', '8.11', 'manager', 'Bang Phli', NULL, '', '081-3748146', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(50, '5907312', 'Umaporn', '5907312', 'Umaporn Klinmueang', 'Office', 'Sales', NULL, NULL, NULL, NULL, '2016-07-25', '8.1', 'user', 'Bang Phli', NULL, '', '089-7029722', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(51, '5909317', 'Suphatsorn', '5909317', 'Suphatsorn Thiabphim', 'FN', '', NULL, NULL, NULL, NULL, '2016-09-19', '8', 'user', 'Bang Phli', NULL, '', '096-1699735', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(52, '6002318', 'Siriruch', '6002318', 'Siriruch Chainrum', 'MC', '', NULL, NULL, NULL, NULL, '2017-02-13', '7.7', 'user', 'Bang Phli', NULL, '', '092-6205632', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(53, '6005323', 'Aphisit', '6005323', 'Aphisit Vichangern', 'FN', '', NULL, NULL, NULL, NULL, '2017-05-16', '7.4', 'user', 'Bang Phli', NULL, '', '095-9655694', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(54, '6006326', 'Sura', '6006326', 'Sura Srimongkhon', 'FN', '', NULL, NULL, NULL, NULL, '2017-06-01', '7.3', 'user', 'Bang Phli', NULL, '', '061-1576173', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(55, '6006327', 'Wattanacha', '6006327', 'Wattanachai Chuenmuang', 'QC', '', NULL, NULL, NULL, NULL, '2017-06-01', '7.3', 'user', 'Bang Phli', NULL, '', '084-6152261', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(56, '6006331', 'Nakarin', '6006331', 'Nakarin Phinyapan', 'MC', '', NULL, NULL, NULL, NULL, '2017-06-05', '7.3', 'leader', 'Bang Phli', NULL, '', '080-1654618', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(57, '6008333', 'Kamolwan', '6008333', 'Kamolwan Kaewkomol', 'QC', '', NULL, NULL, NULL, NULL, '2017-08-01', '7.1', 'user', 'Bang Phli', NULL, '', '081-9172480', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(58, '6101338', 'Chanachai', '6101338', 'Chanachai Thammawong', 'FN', '', NULL, NULL, NULL, NULL, '2018-01-16', '6.8', 'user', 'Bang Phli', NULL, '', '061-0370691', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(59, '6103347', 'Theeraphat', '6103347', 'Theeraphat Khrueakaew', 'CAM', '', NULL, NULL, NULL, NULL, '2018-03-19', '6.6', 'user', 'Bang Phli', NULL, '', '087-3583293', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(60, '6105352', 'Seksan', '6105352', 'Seksan Srisukha', 'MC', '', NULL, NULL, NULL, NULL, '2018-05-03', '6.4', 'user', 'Bang Phli', NULL, '', '092-8697387', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(61, '6107357', 'Sureephan', '6107357', 'Sureephan Phongsab', 'Office', 'AC', NULL, NULL, NULL, NULL, '2018-07-09', '6.2', 'user', 'Bang Phli', NULL, '', '081-5671047', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(62, '6109361', 'Chompoo', '6109361', 'Chompoo Wannupratham', 'CAD2', 'Modeling', NULL, NULL, NULL, NULL, '2018-09-01', '6', 'user', 'Bang Phli', NULL, '', '095-4756858', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(63, '6109364', 'Chalermpho', '6109364', 'Chalermphon Khamfu', 'MC', '', NULL, NULL, NULL, NULL, '2018-09-24', '5.11', 'user', 'Bang Phli', NULL, '', '083-0507318', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(64, '6202371', 'Nipat', '6202371', 'Nipat Pattamasukhon', 'MC', '', NULL, NULL, NULL, NULL, '2019-02-06', '5.7', 'user', 'Bang Phli', NULL, '', '092-4791794', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(65, '6203372', 'Arthit', '6203372', 'Arthit Phawarit', 'MC', '', NULL, NULL, NULL, NULL, '2019-03-18', '5.6', 'user', 'Bang Phli', NULL, '', '061-8324141', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(66, '6208381', 'Sopon', '6208381', 'Sopon Nanthamongkhonchai', 'CAD1', 'Design', NULL, NULL, NULL, NULL, '2019-08-01', '5.1', 'user', 'Bang Phli', NULL, '', '088-2571630', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '10', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(67, '6210386', 'Phongphat', '6210386', 'Phongphat Theannoo', 'CAD2', 'Design', NULL, NULL, NULL, NULL, '2019-10-03', '4.11', 'user', 'Bang Phli', NULL, '', '095-8626993', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '9', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(68, '6301387', 'Kiyoka', '6301387', 'Kiyoka Horita', 'Management', 'RD', NULL, NULL, NULL, NULL, '2020-01-03', '4.8', 'manager', 'Bang Phli', NULL, '', '089-2024189', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '9', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(69, '6302388', 'Ketsada', '6302388', 'Ketsada Pumpukwan', 'Office', 'Sales', NULL, NULL, NULL, NULL, '2020-02-10', '4.7', 'user', 'Bang Phli', NULL, '', '062-7260365', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '9', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(70, '6303390', 'Nantawat', '6303390', 'Nantawat Nampa', 'CAD1', 'Design', NULL, NULL, NULL, NULL, '2020-03-16', '4.6', 'user', 'Bang Phli', NULL, '', '064-4640222', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '9', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(71, '6307391', 'Burinphot', '6307391', 'Burinphot Ketkaew', 'Office', '', NULL, NULL, NULL, NULL, '2020-07-01', '4.2', 'user', 'Bang Phli', NULL, '', '095-2477315', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '9', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(72, '6309394', 'Pasakorn', '6309394', 'Pasakorn Tantivisanusopit', 'CAM', '', NULL, NULL, NULL, NULL, '2020-09-16', '4', 'user', 'Bang Phli', NULL, '', '064-5905000', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '9', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(73, '6406398', 'Wanvipa', '6406398', 'Wanvipa Thongratsamee', 'PC', '', NULL, NULL, NULL, NULL, '2021-06-07', '3.3', 'user', 'Bang Phli', NULL, '', '094-3343233', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '8', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(74, '6505405', 'Prateep', '6505405', 'Prateep Hunthong', 'MC', '', NULL, NULL, NULL, NULL, '2022-05-03', '2.4', 'user', 'Bang Phli', NULL, '', '099-4259594', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '7', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(75, '6604411', 'Supanida', '6604411', 'Supanida Khosaphongsa', 'RD', '', NULL, NULL, NULL, NULL, '2023-04-01', '1.5', 'user', 'Bang Phli', NULL, '', '087-3664097', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '6', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(76, '6604412', 'Waewdao', '6604412', 'Waewdao Raksa-Ngam', 'QC', '', NULL, NULL, NULL, NULL, '2023-04-03', '1.5', 'user', 'Bang Phli', NULL, '', '061-7540551', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '6', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(77, '6604413', 'Chaiwat', '6604413', 'Chaiwat Poonsanoi', 'MC', '', NULL, NULL, NULL, NULL, '2023-04-17', '1.5', 'user', 'Bang Phli', NULL, '', '061-2175250', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '6', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(78, '6604414', 'Nichapa', '6604414', 'Nichapa Chantarayothakul', 'RD', '', NULL, NULL, NULL, NULL, '2023-04-24', '1.4', 'user', 'Bang Phli', NULL, '', '084-1569444', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '6', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(79, '6608418', 'Kanyapa', '6608418', 'Kanyapa Saetiew', 'RD', '', NULL, NULL, NULL, NULL, '2023-08-07', '1.1', 'user', 'Bang Phli', NULL, '', '092-1484363', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '6', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(80, '6608420', 'Sarawut', '6608420', 'Sarawut Champathip', 'MC', '', NULL, NULL, NULL, NULL, '2023-08-21', '1.1', 'user', 'Bang Phli', NULL, '', '063-9891414', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '6', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(81, '6611422', 'Anattapong', '6611422', 'Anattapong Wongsuwan', 'CAM', '', NULL, NULL, NULL, NULL, '2023-11-02', '0.1', 'user', 'Bang Phli', NULL, '', '084-3720826', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '0', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(82, '6611423', 'Saichon', '6611423', 'Saichon Bunsamret', 'MC', '', NULL, NULL, NULL, NULL, '2023-11-20', '0.1', 'user', 'Bang Phli', NULL, '', '088-0476018', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '0', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(83, '6701424', 'Tipphawan', '6701424', 'Tipphawan Baokham', 'FN', '', NULL, NULL, NULL, NULL, '2024-01-05', '0.8', 'user', 'Bang Phli', NULL, '', '061-3731190', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '0', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(84, '6703425', 'Pimlapas', '6703425', 'Pimlapas Yasawut', 'RD', '', NULL, NULL, NULL, NULL, '2024-03-04', '0.6', 'user', 'Bang Phli', NULL, '', '082-3252414', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '0', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(85, '6703426', 'Wilawan', '6703426', 'Wilawan Sarapo', 'Office', 'AC', NULL, NULL, NULL, NULL, '2024-03-11', '0.6', 'user', 'Bang Phli', NULL, '', '065-6881604', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '0', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(86, '6706430', 'Chawalit', '6706430', 'Chawalit Hadsa', 'MC', '', NULL, NULL, NULL, NULL, '2024-06-04', '0.3', 'user', 'Bang Phli', NULL, '', '092-0359277', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '0', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, ''),
(87, '6707432', 'Charan', '6707432', 'Charan Saengchot', 'MC', '', NULL, NULL, NULL, NULL, '2024-07-17', '0.2', 'user', 'Bang Phli', NULL, '', '062-4096384', '0', 'nqfsvavrOb6KwxeK666SINY6Z7u80WOI6DdzlCh6nK6', '5', '365', '30', '365', '0', '', '', '365', '2023-09-24 14:31:00', 'Kanyapa', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `holiday`
--

CREATE TABLE `holiday` (
  `h_id` int(11) NOT NULL,
  `h_name` varchar(100) DEFAULT NULL,
  `h_start_date` date DEFAULT NULL,
  `h_end_date` date DEFAULT NULL,
  `h_holiday_status` varchar(10) DEFAULT NULL,
  `h_status` varchar(2) DEFAULT NULL,
  `h_hr_name` varchar(10) DEFAULT NULL,
  `h_hr_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `holiday`
--

INSERT INTO `holiday` (`h_id`, `h_name`, `h_start_date`, `h_end_date`, `h_holiday_status`, `h_status`, `h_hr_name`, `h_hr_datetime`) VALUES
(1487, 'วันสงกรานต์', '2024-04-13', '2024-04-13', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1488, 'วันสงกรานต์', '2024-04-14', '2024-04-14', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1489, 'วันสงกรานต์', '2024-04-15', '2024-04-15', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1490, 'วันหยุดวันอาทิตย์', '2024-01-07', '2024-01-07', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1491, 'วันหยุดวันอาทิตย์', '2024-01-14', '2024-01-14', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1492, 'วันหยุดวันอาทิตย์', '2024-01-21', '2024-01-21', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1493, 'วันหยุดวันอาทิตย์', '2024-01-28', '2024-01-28', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1494, 'วันหยุดวันอาทิตย์', '2024-02-04', '2024-02-04', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1495, 'วันหยุดวันอาทิตย์', '2024-02-11', '2024-02-11', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1496, 'วันหยุดวันอาทิตย์', '2024-02-18', '2024-02-18', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1497, 'วันหยุดวันอาทิตย์', '2024-02-25', '2024-02-25', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1498, 'วันหยุดวันอาทิตย์', '2024-03-03', '2024-03-03', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1499, 'วันหยุดวันอาทิตย์', '2024-03-10', '2024-03-10', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1500, 'วันหยุดวันอาทิตย์', '2024-03-17', '2024-03-17', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1501, 'วันหยุดวันอาทิตย์', '2024-03-24', '2024-03-24', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1502, 'วันหยุดวันอาทิตย์', '2024-03-31', '2024-03-31', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1503, 'วันหยุดวันอาทิตย์', '2024-04-07', '2024-04-07', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1504, 'วันหยุดวันอาทิตย์', '2024-04-14', '2024-04-14', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1505, 'วันหยุดวันอาทิตย์', '2024-04-21', '2024-04-21', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1506, 'วันหยุดวันอาทิตย์', '2024-04-28', '2024-04-28', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1507, 'วันหยุดวันอาทิตย์', '2024-05-05', '2024-05-05', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1508, 'วันหยุดวันอาทิตย์', '2024-05-12', '2024-05-12', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1509, 'วันหยุดวันอาทิตย์', '2024-05-19', '2024-05-19', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1510, 'วันหยุดวันอาทิตย์', '2024-05-26', '2024-05-26', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1511, 'วันหยุดวันอาทิตย์', '2024-06-02', '2024-06-02', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1512, 'วันหยุดวันอาทิตย์', '2024-06-09', '2024-06-09', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1513, 'วันหยุดวันอาทิตย์', '2024-06-16', '2024-06-16', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1514, 'วันหยุดวันอาทิตย์', '2024-06-23', '2024-06-23', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1515, 'วันหยุดวันอาทิตย์', '2024-06-30', '2024-06-30', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1516, 'วันหยุดวันอาทิตย์', '2024-07-07', '2024-07-07', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1517, 'วันหยุดวันอาทิตย์', '2024-07-14', '2024-07-14', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1518, 'วันหยุดวันอาทิตย์', '2024-07-21', '2024-07-21', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1519, 'วันหยุดวันอาทิตย์', '2024-07-28', '2024-07-28', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1520, 'วันหยุดวันอาทิตย์', '2024-08-04', '2024-08-04', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1521, 'วันหยุดวันอาทิตย์', '2024-08-11', '2024-08-11', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1522, 'วันหยุดวันอาทิตย์', '2024-08-18', '2024-08-18', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1523, 'วันหยุดวันอาทิตย์', '2024-08-25', '2024-08-25', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1524, 'วันหยุดวันอาทิตย์', '2024-09-01', '2024-09-01', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1525, 'วันหยุดวันอาทิตย์', '2024-09-08', '2024-09-08', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1526, 'วันหยุดวันอาทิตย์', '2024-09-15', '2024-09-15', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1527, 'วันหยุดวันอาทิตย์', '2024-09-22', '2024-09-22', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1528, 'วันหยุดวันอาทิตย์', '2024-09-29', '2024-09-29', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1529, 'วันหยุดวันอาทิตย์', '2024-10-06', '2024-10-06', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1530, 'วันหยุดวันอาทิตย์', '2024-10-13', '2024-10-13', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1531, 'วันหยุดวันอาทิตย์', '2024-10-20', '2024-10-20', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1532, 'วันหยุดวันอาทิตย์', '2024-10-27', '2024-10-27', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1533, 'วันหยุดวันอาทิตย์', '2024-11-03', '2024-11-03', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1534, 'วันหยุดวันอาทิตย์', '2024-11-10', '2024-11-10', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1535, 'วันหยุดวันอาทิตย์', '2024-11-17', '2024-11-17', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1536, 'วันหยุดวันอาทิตย์', '2024-11-24', '2024-11-24', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1537, 'วันหยุดวันอาทิตย์', '2024-12-01', '2024-12-01', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1538, 'วันหยุดวันอาทิตย์', '2024-12-08', '2024-12-08', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1539, 'วันหยุดวันอาทิตย์', '2024-12-15', '2024-12-15', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1540, 'วันหยุดวันอาทิตย์', '2024-12-22', '2024-12-22', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1541, 'วันหยุดวันอาทิตย์', '2024-12-29', '2024-12-29', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1542, 'วันแรงงาน', '2024-05-01', '2024-05-01', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1543, 'วันเฉลิมพระชนมพรรษา สมเด็จพระนางเจ้าฯ พระบรมราชินี', '2024-06-03', '2024-06-03', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1544, 'วันเฉลิมพระชนมพรรษา พระบาทสมเด็จพระเจ้าอยู่หัว', '2024-07-29', '2024-07-29', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1545, 'วันเฉลิมพระชนมพรรษา สมเด็จพระนางเจ้าสิริกิติ์ฯ', '2024-08-12', '2024-08-12', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1546, 'วันคล้ายวันสวรรคต ร.9', '2024-10-13', '2024-10-13', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1547, 'วันพ่อแห่งชาติ', '2024-12-05', '2024-12-05', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1548, 'วันสิ้นปี', '2024-12-31', '2024-12-31', 'วันหยุด', '0', 'admin', '2024-09-12 11:03:14'),
(1549, 'วันหยุดวันเสาร์', '2024-09-21', '2024-09-21', 'วันหยุด', '0', 'admin', '2024-09-19 11:06:58');

-- --------------------------------------------------------

--
-- Table structure for table `leave_list`
--

CREATE TABLE `leave_list` (
  `l_list_id` int(11) NOT NULL,
  `l_usercode` varchar(10) DEFAULT NULL COMMENT 'รหัสพนักงาน',
  `l_username` varchar(10) DEFAULT NULL COMMENT 'ชื่อผู้ใช้',
  `l_name` varchar(255) DEFAULT NULL COMMENT 'ชื่อพนักงาน',
  `l_department` varchar(15) DEFAULT NULL COMMENT 'แผนก',
  `l_level` varchar(15) DEFAULT NULL COMMENT 'ระดับเช่น พนักงานทั่วไป / หัวหน้า / ผจก / แอดมิน',
  `l_workplace` varchar(10) DEFAULT NULL,
  `l_phone` varchar(11) DEFAULT NULL COMMENT 'เบอร์โทรศัพท์',
  `l_leave_id` int(11) DEFAULT NULL COMMENT 'ID ของประเภทการลา อิงจาก Table leave_type',
  `l_leave_reason` varchar(255) DEFAULT NULL COMMENT 'เหตุผลการลา',
  `l_leave_start_date` date DEFAULT NULL COMMENT 'วันที่ลา (เริ่มต้น)',
  `l_leave_start_time` time DEFAULT NULL COMMENT 'เวลาที่ลา (เริ่มต้น)',
  `l_leave_end_date` date DEFAULT NULL COMMENT 'วันที่ลา (สิ้นสุด)',
  `l_leave_end_time` time DEFAULT NULL COMMENT 'เวลาที่ลา (สิ้นสุด)',
  `l_leave_status` varchar(2) DEFAULT NULL COMMENT 'สถานะใบลา',
  `l_create_datetime` datetime DEFAULT NULL COMMENT 'วันเวลาที่ยื่นใบลา',
  `l_cancel_datetime` datetime DEFAULT NULL COMMENT 'วันเวลาที่ยกเลิกใบลา',
  `l_late_datetime` datetime DEFAULT NULL,
  `l_file` varchar(255) DEFAULT NULL COMMENT 'ไฟล์แนบ ',
  `l_remark` varchar(255) DEFAULT NULL COMMENT 'หมายเหตุ',
  `l_approve_status` varchar(10) DEFAULT NULL COMMENT 'สถานะอนุมัติของหัวหน้า\n0 = รอหัวหน้าอนุมัติ\n2 = หัวหน้าอนุมัติ\n3 = หัวหน้าไม่อนุมัติ',
  `l_approve_name` varchar(10) DEFAULT NULL COMMENT 'ชื่อหัวหน้า (ใช้ชื่อ Username)',
  `l_approve_datetime` datetime DEFAULT NULL COMMENT 'วันเวลาที่อนุมัติ',
  `l_reason` varchar(255) DEFAULT NULL COMMENT 'เหตุผลอนุมัติ',
  `l_approve_status2` varchar(10) DEFAULT NULL COMMENT 'สถานะอนุมัติของ ผจก\n1 = รอ ผจก อนุมัติ\n4 = ผจก อนุมัติ\n5 = ผจก ไม่อนุมัติ',
  `l_approve_name2` varchar(10) DEFAULT NULL COMMENT 'ชื่อ ผจก  (ใช้ชื่อ Username)',
  `l_approve_datetime2` datetime DEFAULT NULL COMMENT 'วันเวลาที่อนุมัติ',
  `l_reason2` varchar(255) DEFAULT NULL COMMENT 'เหตุผลอนุมัติ',
  `l_hr_status` varchar(2) DEFAULT NULL COMMENT 'สถานะตรวจสอบของ HR\n0 = รอตรวจสอบ\n1 = ผ่าน\n2 = ไม่ผ่าน',
  `l_hr_name` varchar(10) DEFAULT NULL COMMENT 'ชื่อ HR (ใช้ชื่อ Username)',
  `l_hr_datetime` datetime DEFAULT NULL COMMENT 'วันเวลาที่ตรวจสอบ',
  `l_hr_reason` varchar(255) DEFAULT NULL,
  `l_hr_create_name` varchar(10) DEFAULT NULL COMMENT 'ชื่อ HR ที่สร้างรายการ (ใช้ชื่อ Username)',
  `l_hr_create_datetime` datetime DEFAULT NULL COMMENT 'วันเวลาที่สร้างรายการ (รายการมาสาย)',
  `l_hr_cancel_name` varchar(10) DEFAULT NULL COMMENT 'ชื่อ HR ที่ยกเลิกรายการ (ใช้ชื่อ Username)',
  `l_hr_cancel_datetime` datetime DEFAULT NULL COMMENT 'วันเวลาที่ยกเลิกใบลา'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `leave_list`
--

INSERT INTO `leave_list` (`l_list_id`, `l_usercode`, `l_username`, `l_name`, `l_department`, `l_level`, `l_workplace`, `l_phone`, `l_leave_id`, `l_leave_reason`, `l_leave_start_date`, `l_leave_start_time`, `l_leave_end_date`, `l_leave_end_time`, `l_leave_status`, `l_create_datetime`, `l_cancel_datetime`, `l_late_datetime`, `l_file`, `l_remark`, `l_approve_status`, `l_approve_name`, `l_approve_datetime`, `l_reason`, `l_approve_status2`, `l_approve_name2`, `l_approve_datetime2`, `l_reason2`, `l_hr_status`, `l_hr_name`, `l_hr_datetime`, `l_hr_reason`, `l_hr_create_name`, `l_hr_create_datetime`, `l_hr_cancel_name`, `l_hr_cancel_datetime`) VALUES
(233, '4505071', 'Suwannee', 'Suwannee Khunchamnong', 'Office', 'user', 'Bang Phli', '087-9261470', 1, 'กิจส่วนตัว', '2024-09-28', '08:00:00', '2024-09-28', '16:40:00', '0', '2024-09-28 15:09:54', NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(234, '4505071', 'Suwannee', 'Suwannee Khunchamnong', 'Office', 'user', 'Bang Phli', '087-9261470', 1, 'กิจส่วนตัว', '2024-09-28', '08:00:00', '2024-09-28', '16:40:00', '0', '2024-09-28 15:27:50', NULL, NULL, NULL, 'ลาฉุกเฉิน', '0', NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(235, '4505071', 'Suwannee', 'Suwannee Khunchamnong', 'Office', 'user', 'Bang Phli', '087-9261470', 1, 'กิจส่วนตัว', '2024-09-28', '08:00:00', '2024-09-28', '16:40:00', '1', '2024-09-28 15:28:50', '2024-09-28 15:49:26', NULL, NULL, 'ลาฉุกเฉิน', '0', '', NULL, '', '1', '', NULL, '', '0', '', NULL, NULL, NULL, NULL, NULL, NULL),
(236, '3505004', 'Anchana', 'Anchana Assawaphimjinda', 'Management', 'manager', 'Bang Phli', '081-6465557', 1, 'กิจส่วนตัว', '2024-09-28', '08:00:00', '2024-09-28', '16:40:00', '1', '2024-09-28 15:29:48', '2024-09-28 15:56:27', NULL, NULL, 'ลาฉุกเฉิน', '6', '', NULL, '', '4', 'Anchana', '2024-09-28 15:56:27', '', '0', '', NULL, NULL, NULL, NULL, NULL, NULL),
(237, '4404049', 'Wiriya', 'Wiriya Houychae', 'Office', 'leader', 'Bang Phli', '089-1465339', 1, 'กิจส่วนตัว', '2024-09-28', '08:00:00', '2024-09-28', '16:40:00', '1', '2024-09-28 15:30:13', '2024-09-28 15:52:33', NULL, NULL, 'ลาฉุกเฉิน', '2', 'Wiriya', '2024-09-28 15:52:33', '', '1', '', NULL, '', '0', '', NULL, NULL, NULL, NULL, NULL, NULL),
(238, '5602251', 'Waewdow', 'Waewdow Boonsawai', 'Management', 'chief', 'Bang Phli', '089-1254542', 1, 'กิจส่วนตัว', '2024-09-28', '08:00:00', '2024-09-28', '16:40:00', '1', '2024-09-28 15:38:28', '2024-09-28 15:53:50', NULL, NULL, NULL, '2', 'Waewdow', '2024-09-28 15:53:50', '', '1', '', NULL, '', '0', '', NULL, NULL, NULL, NULL, NULL, NULL),
(239, '4505071', 'Suwannee', 'Suwannee Khunchamnong', 'Office', 'user', 'Bang Phli', '087-9261470', 7, 'มาสาย', '2024-09-28', '08:01:00', '2024-09-28', '08:02:00', '0', '2024-09-28 16:01:10', NULL, '2024-09-28 11:01:28', NULL, 'มาสายครั้งที่ 1', '2', 'Wiriya', '2024-09-28 11:18:27', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Natnicha', '2024-09-28 16:01:10', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `leave_type`
--

CREATE TABLE `leave_type` (
  `lt_id` int(11) NOT NULL,
  `lt_leave_type` varchar(50) DEFAULT NULL,
  `lt_leave_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `leave_type`
--

INSERT INTO `leave_type` (`lt_id`, `lt_leave_type`, `lt_leave_name`) VALUES
(1, 'Leave_personal', 'ลากิจได้รับค่าจ้าง'),
(2, 'Leave_personal_no', 'ลากิจไม่ได้รับค่าจ้าง'),
(3, 'Leave_sick', 'ลาป่วย'),
(4, 'Leave_sick_work', 'ลาป่วยจากงาน'),
(5, 'Leave_annual', 'ลาพักร้อน'),
(6, 'Stop_work', 'หยุดงาน'),
(7, 'Late', 'มาสาย'),
(8, 'Other', 'อื่น ๆ');

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

CREATE TABLE `level` (
  `l_id` int(11) NOT NULL,
  `l_level` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`l_id`, `l_level`) VALUES
(1, 'user'),
(2, 'leader'),
(3, 'chief'),
(4, 'assisManager'),
(5, 'manager'),
(6, 'manager2');

-- --------------------------------------------------------

--
-- Table structure for table `notification_log`
--

CREATE TABLE `notification_log` (
  `n_id` int(11) NOT NULL,
  `n_leave_id` int(11) DEFAULT NULL,
  `n_name` varchar(255) DEFAULT NULL,
  `n_department` varchar(15) DEFAULT NULL,
  `n_workplace` varchar(10) DEFAULT NULL,
  `n_leave_start_date` date DEFAULT NULL,
  `n_leave_start_time` time DEFAULT NULL,
  `n_leave_end_date` date DEFAULT NULL,
  `n_leave_end_time` time DEFAULT NULL,
  `n_send_name` varchar(10) DEFAULT NULL,
  `n_send_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `s_id` int(11) NOT NULL,
  `s_usercode` varchar(10) DEFAULT NULL,
  `s_username` varchar(10) DEFAULT NULL,
  `s_password` varchar(10) DEFAULT NULL,
  `s_name` varchar(255) DEFAULT NULL,
  `s_department` varchar(15) DEFAULT NULL,
  `s_level` varchar(10) DEFAULT NULL,
  `s_workplace` varchar(10) DEFAULT NULL,
  `s_status` varchar(2) DEFAULT NULL,
  `s_log_status` varchar(2) DEFAULT NULL,
  `s_login_datetime` datetime DEFAULT NULL,
  `s_logout_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`s_id`, `s_usercode`, `s_username`, `s_password`, `s_name`, `s_department`, `s_level`, `s_workplace`, `s_status`, `s_log_status`, `s_login_datetime`, `s_logout_datetime`) VALUES
(1, '3505004', 'Anchana', '3505004', 'Anchana Assawaphimjinda', 'Management', 'manager', 'Bang Phli', '0', '0', '2024-09-28 15:53:58', '2024-09-28 15:58:35'),
(2, '3802006', 'Theeraphat', '3802006', 'Theeraphat Thongprasom', 'MC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(3, '3805009', 'Sarawut', '3805009', 'Sarawut Sekunnumtieng', 'MC', 'leader', 'Bang Phli', '0', '0', '2024-09-23 15:44:55', '2024-09-23 15:59:27'),
(4, '3904011', 'Rachen', '3904011', 'Rachen Kanpookeaw', 'FN', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(5, '3906013', 'Thongmai', '3906013', 'Thongmai Chinchat', 'MC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(6, '4001017', 'Somchart', '4001017', 'Somchart Kladkhempetch', 'CAD1', 'user', 'Bang Phli', '0', '0', '2024-09-25 15:16:42', '2024-09-25 16:36:12'),
(7, '4108024', 'Nathaphon', '4108024', 'Nathaphon Kaewkanha', 'FN', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(8, '4302029', 'Chalad', '4302029', 'Chalad Seesamea', 'MC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(9, '4403044', 'Wirat', '4403044', 'Wirat Manangan', 'Office', 'user', 'Bang Phli', '0', '0', '2024-09-28 15:32:23', '2024-09-28 15:32:30'),
(10, '4404047', 'Suriya', '4404047', 'Suriya Pimol', 'Management', 'chief', 'Bang Phli', '0', '0', '2024-09-24 16:26:18', '2024-09-24 16:27:32'),
(11, '4404049', 'Wiriya', '4404049', 'Wiriya Houychae', 'Office', 'leader', 'Bang Phli', '0', '1', '2024-09-28 16:09:16', '2024-09-28 15:53:41'),
(12, '4410058', 'Chaikorn', '4410058', 'Chaikorn Suriyasa', 'Management', 'assisManag', 'Bang Phli', '0', '0', NULL, NULL),
(13, '4502068', 'Preecha', '4502068', 'Preecha Thaboonrueng', 'MC', 'leader', 'Bang Phli', '0', '0', NULL, NULL),
(14, '4505071', 'Suwannee', '4505071', 'Suwannee Khunchamnong\r\n', 'Office', 'user', 'Bang Phli', '0', '0', '2024-09-28 16:01:24', '2024-09-28 16:09:03'),
(15, '4506073', 'Thepparit', '4506073', 'Thepparit Pasee\r\n', 'CAD1', 'leader', 'Bang Phli', '0', '0', NULL, NULL),
(16, '4512084', 'Niran', '4512084', 'Niran Sea-Or', 'QC', 'leader', 'Bang Phli', '0', '0', '2024-09-24 17:13:10', '2024-09-24 17:41:25'),
(17, '4512085', 'Anon', '4512085', 'Anon Maneechot', 'MC', 'leader', 'Bang Phli', '0', '0', '2024-09-26 15:01:04', '2024-09-26 15:01:10'),
(18, '4604093', 'A-Nan', '4604093', 'A-Nan Sangyos', 'MC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(19, '4606097', 'Chaiyaporn', '4606097', 'Chaiyaporn Poukpoon', 'CAM', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(20, '4606098', 'Thawatyuty', '4606098', 'Thawatyutyotha Photirat', 'FN', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(21, '4610104', 'U-Thai', '4610104', 'U-Thai Chaiyaocha', 'MC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(22, '4704110', 'Siriporn', '4704110', 'Siriporn Chuaikhoksung', 'Management', 'chief', 'Bang Phli', '0', '0', '2024-09-28 15:02:28', '2024-09-28 15:07:04'),
(23, '4708116', 'Thanyaboon', '4708116', 'Thanyaboon Phokead', 'MC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(24, '4803121', 'Prasert', '4803121', 'Prasert Kaweebua', 'CAD2', 'leader', 'Bang Phli', '0', '0', NULL, NULL),
(25, '4804123', 'Sathiya', '4804123', 'Sathiya Suksanguanthai', 'CAM', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(26, '4804124', 'Pamonrut', '4804124', 'Pamonrut Kaewkhiaw', 'CAM', 'leader', 'Bang Phli', '0', '0', NULL, NULL),
(27, '4805126', 'Boonjan', '4805126', 'Boonjan Ahntarit', 'Office', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(28, '4810128', 'Kunchit', '4810128', 'Kunchit Whan-Ngoen', 'CAD1', 'leader', 'Bang Phli', '0', '0', '2024-09-26 17:23:12', '2024-09-26 17:24:40'),
(29, '4902133', 'Kanjana', '4902133', 'Kanjana Sangyos', 'CAD1', 'user', 'Bang Phli', '0', '0', '2024-09-26 14:29:12', '2024-09-26 14:30:51'),
(30, '4906145', 'Wichan', '4906145', 'Wichan Botmoon', 'CAD1', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(31, '5003153', 'Mesa', '5003153', 'Mesa Puktalae', 'CAD1', 'user', 'Bang Phli', '0', '1', '2024-09-25 17:47:51', NULL),
(32, '5006160', 'Manachai', '5006160', 'Manachai Lapootama', 'CAD1', 'user', 'Bang Phli', '0', '0', '2024-09-26 16:04:12', '2024-09-26 16:08:14'),
(33, '5009165', 'Boworn', '5009165', 'Boworn Sorsomboon', 'MC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(34, '5102168', 'Piphat', '5102168', 'Piphat Thawkamlea', 'QC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(35, '5102169', 'Ratchasak', '5102169', 'Ratchasak Deeha', 'QC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(36, '5109177', 'Danupon', '5109177', 'Danupon Kaewkun', 'CAD2', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(37, '5111178', 'Yongwit', '5111178', 'Yongwit Chasanthia', 'CAD1', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(38, '5303180', 'Pongsak', '5303180', 'Pongsak Yodkhammee', 'CAD2', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(39, '5303181', 'Thanu', '5303181', 'Thanu Kunying', 'CAD1', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(40, '5303183', 'Natnicha', '5303183', 'Natnicha Maneechot', 'Office', 'admin', 'Bang Phli', '0', '0', '2024-09-28 16:00:51', '2024-09-28 16:01:20'),
(41, '5307186', 'Jeerasak', '5307186', 'Jeerasak Chaydam', 'CAM', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(42, '5311196', 'Tussanee', '5311196', 'Tussanee Soparut', 'Office', 'user', 'Bang Phli', '0', '0', '2024-09-27 08:39:19', '2024-09-27 08:50:32'),
(43, '5505228', 'Wasitporn', '5505228', 'Wasitporn Nuekthuek', 'Management', 'chief', 'Bang Phli', '0', '0', NULL, NULL),
(44, '5506235', 'Saeksan', '5506235', 'Saeksan Dungsungnoen', 'PC', 'leader', 'Bang Phli', '0', '0', NULL, NULL),
(45, '5602249', 'Chatchawan', '5602249', 'Chatchawan Einnoy', 'CAM', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(46, '5602251', 'Waewdow', '5602251', 'Waewdow Boonsawai', 'Management', 'chief', 'Bang Phli', '0', '0', '2024-09-28 15:53:46', '2024-09-28 15:53:54'),
(47, '5603257', 'Wongthawat', '5603257', 'Wongthawat Phupanna', 'FN', 'leader', 'Bang Phli', '0', '0', NULL, NULL),
(48, '5608269', 'Umapan', '5608269', 'Umapan Pinitkul', 'CAD1', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(49, '5810302', 'Pornsuk', '5810302', 'Pornsuk Tantivisanusopit', 'Management', 'manager', 'Bang Phli', '0', '0', '2024-09-26 15:20:57', '2024-09-26 15:21:03'),
(50, '5907312', 'Umaporn', '5907312', 'Umaporn Klinmueang', 'Office', 'user', 'Bang Phli', '0', '0', '2024-09-28 13:39:18', '2024-09-28 13:39:38'),
(51, '5909317', 'Suphatsorn', '5909317', 'Suphatsorn Thiabphim', 'FN', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(52, '6002318', 'Siriruch', '6002318', 'Siriruch Chainrum', 'MC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(53, '6005323', 'Aphisit', '6005323', 'Aphisit Vichangern', 'FN', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(54, '6006326', 'Sura', '6006326', 'Sura Srimongkhon', 'FN', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(55, '6006327', 'Wattanacha', '6006327', 'Wattanachai Chuenmuang', 'QC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(56, '6006331', 'Nakarin', '6006331', 'Nakarin Phinyapan', 'MC', 'leader', 'Bang Phli', '0', '0', NULL, NULL),
(57, '6008333', 'Kamolwan', '6008333', 'Kamolwan Kaewkomol', 'QC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(58, '6101338', 'Chanachai', '6101338', 'Chanachai Thammawong', 'FN', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(59, '6103347', 'Theeraphat', '6103347', 'Theeraphat Khrueakaew', 'CAM', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(60, '6105352', 'Seksan', '6105352', 'Seksan Srisukha', 'MC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(61, '6107357', 'Sureephan', '6107357', 'Sureephan Phongsab', 'Office', 'user', 'Bang Phli', '0', '0', '2024-09-24 10:44:25', '2024-09-24 11:19:46'),
(62, '6109361', 'Chompoo', '6109361', 'Chompoo Wannupratham', 'CAD2', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(63, '6109364', 'Chalermpho', '6109364', 'Chalermphon Khamfu', 'MC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(64, '6202371', 'Nipat', '6202371', 'Nipat Pattamasukhon', 'MC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(65, '6203372', 'Arthit', '6203372', 'Arthit Phawarit', 'MC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(66, '6208381', 'Sopon', '6208381', 'Sopon Nanthamongkhonchai', 'CAD1', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(67, '6210386', 'Phongphat', '6210386', 'Phongphat Theannoo', 'CAD2', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(68, '6301387', 'Kiyoka', '6301387', 'Kiyoka Horita', 'Management', 'manager', 'Bang Phli', '0', '0', '2024-09-26 15:19:59', '2024-09-26 15:20:53'),
(69, '6302388', 'Ketsada', '6302388', 'Ketsada Pumpukwan', 'Office', 'user', 'Bang Phli', '0', '0', '2024-09-26 16:32:17', '2024-09-26 16:32:35'),
(70, '6303390', 'Nantawat', '6303390', 'Nantawat Nampa', 'CAD1', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(71, '6307391', 'Burinphot', '6307391', 'Burinphot Ketkaew', 'Office', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(72, '6309394', 'Pasakorn', '6309394', 'Pasakorn Tantivisanusopit', 'CAM', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(73, '6406398', 'Wanvipa', '6406398', 'Wanvipa Thongratsamee', 'PC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(74, '6505405', 'Prateep', '6505405', 'Prateep Hunthong', 'MC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(75, '6604411', 'Supanida', '6604411', 'Supanida Khosaphongsa', 'RD', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(76, '6604412', 'Waewdao', '6604412', 'Waewdao Raksa-Ngam', 'QC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(77, '6604413', 'Chaiwat', '6604413', 'Chaiwat Poonsanoi', 'MC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(78, '6604414', 'Nichapa', '6604414', 'Nichapa Chantarayothakul', 'RD', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(79, '6608418', 'Kanyapa', '6608418', 'Kanyapa Saetiew', 'RD', 'user', 'Bang Phli', '0', '1', '2024-09-27 18:01:05', '2024-09-26 15:59:06'),
(80, '6608420', 'Sarawut', '6608420', 'Sarawut Champathip', 'MC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(81, '6611422', 'Anattapong', '6611422', 'Anattapong Wongsuwan', 'CAM', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(82, '6611423', 'Saichon', '6611423', 'Saichon Bunsamret', 'MC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(83, '6701424', 'Tipphawan', '6701424', 'Tipphawan Baokham', 'FN', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(84, '6703425', 'Pimlapas', '6703425', 'Pimlapas Yasawut', 'RD', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(85, '6703426', 'Wilawan', '6703426', 'Wilawan Sarapo', 'Office', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(86, '6706430', 'Chawalit', '6706430', 'Chawalit Hadsa', 'MC', 'user', 'Bang Phli', '0', '0', NULL, NULL),
(87, '6707432', 'Charan', '6707432', 'Charan Saengchot', 'MC', 'user', 'Bang Phli', '0', '0', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workplace`
--

CREATE TABLE `workplace` (
  `w_id` int(11) NOT NULL,
  `w_name` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `workplace`
--

INSERT INTO `workplace` (`w_id`, `w_name`) VALUES
(1, 'Korat'),
(2, 'Bang Phli');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`d_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`e_id`);

--
-- Indexes for table `holiday`
--
ALTER TABLE `holiday`
  ADD PRIMARY KEY (`h_id`);

--
-- Indexes for table `leave_list`
--
ALTER TABLE `leave_list`
  ADD PRIMARY KEY (`l_list_id`);

--
-- Indexes for table `leave_type`
--
ALTER TABLE `leave_type`
  ADD PRIMARY KEY (`lt_id`);

--
-- Indexes for table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`l_id`);

--
-- Indexes for table `notification_log`
--
ALTER TABLE `notification_log`
  ADD PRIMARY KEY (`n_id`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`s_id`);

--
-- Indexes for table `workplace`
--
ALTER TABLE `workplace`
  ADD PRIMARY KEY (`w_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `d_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `e_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `holiday`
--
ALTER TABLE `holiday`
  MODIFY `h_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1550;

--
-- AUTO_INCREMENT for table `leave_list`
--
ALTER TABLE `leave_list`
  MODIFY `l_list_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240;

--
-- AUTO_INCREMENT for table `level`
--
ALTER TABLE `level`
  MODIFY `l_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notification_log`
--
ALTER TABLE `notification_log`
  MODIFY `n_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=580;

--
-- AUTO_INCREMENT for table `session`
--
ALTER TABLE `session`
  MODIFY `s_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `workplace`
--
ALTER TABLE `workplace`
  MODIFY `w_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
