-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 10, 2019 at 07:41 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.2.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `first_transport`
--

-- --------------------------------------------------------

--
-- Table structure for table `business`
--

CREATE TABLE `business` (
  `business_id` int(11) NOT NULL,
  `business_name` varchar(150) NOT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT '0' COMMENT '0=>Default, 1=>Deleted',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `business`
--

INSERT INTO `business` (`business_id`, `business_name`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'Test Business', 0, '2019-04-03 11:22:11', '2019-04-08 08:39:42'),
(2, 'Dummy Business', 0, '2019-04-03 11:22:11', '2019-04-08 08:42:53'),
(3, 'Apple Soft', 0, '2019-04-04 06:19:59', NULL),
(7, 'XYZ LTD', 1, '2019-04-04 07:48:08', NULL),
(12, 'ABC LTD', 1, '2019-04-04 09:10:26', NULL),
(13, 'D Company', 1, '2019-04-04 10:10:23', NULL),
(18, 'Uriah Welch', 1, '2019-04-08 11:26:16', NULL),
(19, 'Jasmine Baxter', 0, '2019-04-08 11:27:35', NULL),
(20, 'Rosalyn LTD', 1, '2019-04-08 12:15:23', '2019-04-08 08:45:40'),
(21, 'Brynne\'s Lee', 0, '2019-04-09 09:43:03', '2019-04-09 06:15:03');

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE `levels` (
  `Levelid` int(11) NOT NULL,
  `levelname` varchar(50) NOT NULL,
  `levelnumber` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`Levelid`, `levelname`, `levelnumber`) VALUES
(1, 'Super Admin', 1),
(2, 'Admin', 2),
(3, 'Driver', 3);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `paymentid` int(11) NOT NULL,
  `paymenttype` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`paymentid`, `paymenttype`) VALUES
(1, 'Kontant'),
(3, 'Konto'),
(2, 'Kort');

-- --------------------------------------------------------

--
-- Table structure for table `rates`
--

CREATE TABLE `rates` (
  `rateid` int(11) NOT NULL,
  `business_id` int(11) NOT NULL,
  `ratetype` int(11) NOT NULL,
  `startprice` decimal(10,2) NOT NULL,
  `waitprice` decimal(10,2) NOT NULL,
  `kmprice` decimal(10,2) NOT NULL,
  `halfhourprice` decimal(10,2) NOT NULL,
  `minimumprice` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rates`
--

INSERT INTO `rates` (`rateid`, `business_id`, `ratetype`, `startprice`, `waitprice`, `kmprice`, `halfhourprice`, `minimumprice`, `created_at`) VALUES
(3, 1, 1, '60.00', '6.00', '11.00', '50.00', '300.00', '2019-04-09 11:09:04'),
(4, 1, 1, '60.00', '6.40', '10.60', '0.00', '340.00', '2019-04-09 11:09:04'),
(5, 1, 1, '0.00', '0.00', '6.00', '240.00', '500.00', '2019-04-09 11:09:04'),
(6, 1, 2, '0.00', '0.00', '6.80', '256.00', '768.00', '2019-04-09 11:09:04'),
(7, 1, 2, '0.00', '0.00', '8.20', '284.00', '852.00', '2019-04-09 11:09:04'),
(8, 1, 2, '0.00', '0.00', '10.40', '352.00', '1408.00', '2019-04-09 11:09:04'),
(9, 1, 2, '0.00', '0.00', '0.00', '0.00', '0.00', '2019-04-09 11:09:04'),
(10, 1, 1, '0.00', '0.00', '0.00', '368.00', '736.00', '2019-04-09 11:09:04'),
(11, 1, 2, '0.00', '0.00', '12.00', '392.00', '784.00', '2019-04-09 11:09:04'),
(12, 1, 2, '0.00', '0.00', '12.00', '452.00', '1356.00', '2019-04-09 11:09:04'),
(13, 1, 1, '50.00', '5.00', '9.00', '0.00', '290.00', '2019-04-09 11:09:04'),
(14, 1, 2, '566.00', '525.00', '974.00', '393.00', '477.00', '2019-04-09 11:09:04'),
(16, 21, 1, '58.00', '238.00', '211.00', '721.00', '962.55', '2019-04-09 11:11:50'),
(17, 21, 1, '628.55', '596.55', '162.02', '721.01', '26.00', '2019-04-09 11:12:13'),
(18, 21, 1, '962.00', '271.00', '924.00', '247.00', '264.55', '2019-04-09 11:15:04'),
(21, 1, 1, '22.00', '33.00', '44.00', '55.00', '66.00', '2019-04-09 13:34:15'),
(22, 21, 2, '222.00', '218.00', '378.00', '447.00', '554.00', '2019-04-09 13:46:58');

-- --------------------------------------------------------

--
-- Table structure for table `ratetype`
--

CREATE TABLE `ratetype` (
  `rateid` int(11) NOT NULL,
  `ratetype` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ratetype`
--

INSERT INTO `ratetype` (`rateid`, `ratetype`) VALUES
(2, 'big'),
(1, 'small');

-- --------------------------------------------------------

--
-- Table structure for table `trip`
--

CREATE TABLE `trip` (
  `tripid` int(11) NOT NULL,
  `business_id` int(11) DEFAULT NULL,
  `customertype` int(8) NOT NULL,
  `tdate` date NOT NULL,
  `starttime` time NOT NULL,
  `endtime` time NOT NULL,
  `distance` varchar(20) NOT NULL,
  `waittime` varchar(20) NOT NULL,
  `ratetype` int(11) NOT NULL,
  `ratesid` int(11) NOT NULL,
  `tripprice` decimal(65,2) NOT NULL,
  `tdriverid` int(11) NOT NULL,
  `tripnumber` varchar(15) NOT NULL,
  `paymenttype` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `trip`
--

INSERT INTO `trip` (`tripid`, `business_id`, `customertype`, `tdate`, `starttime`, `endtime`, `distance`, `waittime`, `ratetype`, `ratesid`, `tripprice`, `tdriverid`, `tripnumber`, `paymenttype`, `created_at`) VALUES
(968, 1, 1, '2017-06-29', '10:18:44', '10:19:18', '0,1', '15.0', 1, 10, '736.00', 24, '25888', 3, '2019-04-09 10:54:53'),
(969, 1, 0, '2017-07-01', '12:07:32', '12:09:20', '0,0', '90.0', 1, 4, '425.00', 24, '38619', 1, '2019-04-09 10:54:53'),
(970, 1, 1, '2017-07-01', '15:35:13', '15:38:02', '0,4', '135.0', 1, 3, '300.00', 24, '9999', 3, '2019-04-09 10:54:53'),
(971, 1, 1, '2017-07-01', '14:56:57', '16:12:54', '0,6', '4550.0', 1, 3, '529.00', 24, '22222222', 3, '2019-04-09 10:54:53'),
(972, 1, 1, '2017-07-01', '16:13:06', '16:46:01', '33,8', '530.0', 1, 4, '475.00', 24, '1', 3, '2019-04-09 10:54:53'),
(973, 1, 1, '2017-07-01', '15:38:12', '17:26:57', '12,2', '5880.0', 1, 4, '817.00', 24, '9999.1', 1, '2019-04-09 10:54:53'),
(974, 1, 1, '2017-07-01', '17:27:58', '17:50:46', '11,8', '525.0', 2, 5, '480.00', 24, '999.2', 1, '2019-04-09 10:54:53'),
(975, 1, 1, '2017-07-01', '18:05:52', '18:06:09', '0,1', '10.0', 1, 5, '500.00', 24, '999.3', 1, '2019-04-09 10:54:53'),
(976, 1, 0, '2017-07-01', '18:20:56', '18:21:52', '0,0', '45.0', 2, 12, '1695.00', 24, '999.4', 2, '2019-04-09 10:54:53'),
(977, 1, 1, '2017-07-02', '07:20:04', '07:38:11', '28,2', '80.0', 2, 6, '768.00', 24, '2.2', 3, '2019-04-09 10:54:53'),
(978, 1, 1, '2017-07-02', '08:05:21', '08:07:03', '0,1', '95.0', 2, 7, '852.00', 24, '3.1', 3, '2019-04-09 10:54:53'),
(979, 1, 1, '2017-07-02', '08:28:59', '13:03:05', '188,0', '7790.0', 2, 7, '4382.00', 24, '32', 3, '2019-04-09 10:54:53'),
(980, 1, 1, '2017-07-02', '09:41:18', '13:03:25', '18,4', '9375.0', 2, 7, '1855.00', 24, '38711', 3, '2019-04-09 10:54:53'),
(981, 1, 1, '2017-07-02', '13:57:30', '15:23:47', '38,0', '3380.0', 1, 4, '823.00', 24, '38604', 3, '2019-04-09 10:54:53'),
(982, 1, 1, '2017-07-03', '08:36:32', '08:36:53', '0,0', '15.0', 1, 3, '300.00', 24, '4', 3, '2019-04-09 10:54:53'),
(983, 1, 1, '2017-07-03', '10:24:07', '11:41:53', '56,7', '2200.0', 1, 4, '895.00', 24, '38681', 3, '2019-04-09 10:54:53'),
(984, 1, 1, '2017-07-03', '12:40:43', '14:37:36', '105,3', '2340.0', 2, 6, '1740.00', 24, '38744', 3, '2019-04-09 10:54:53'),
(987, 1, 1, '2017-07-04', '08:40:16', '13:11:10', '75,6', '12245.0', 2, 7, '3176.00', 24, '38749', 3, '2019-04-09 10:54:53'),
(988, 1, 1, '2017-07-04', '14:22:39', '15:26:38', '18,1', '1945.0', 1, 4, '459.00', 24, '24318', 3, '2019-04-09 10:54:53'),
(989, 1, 1, '2017-07-05', '08:41:51', '08:43:04', '0,0', '60.0', 2, 7, '852.00', 24, '25250', 3, '2019-04-09 10:54:53'),
(990, 1, 1, '2017-07-06', '06:23:41', '06:41:02', '13,6', '415.0', 1, 3, '300.00', 24, '999.5', 1, '2019-04-09 10:54:53'),
(991, 1, 1, '2017-07-06', '08:35:20', '08:35:34', '0,0', '0.0', 2, 9, '0.00', 24, '333', 3, '2019-04-09 10:54:53'),
(992, 1, 1, '2017-07-06', '08:35:43', '10:36:52', '61,8', '6280.0', 2, 8, '2403.00', 24, '38782', 3, '2019-04-09 10:54:53'),
(993, 1, 1, '2017-07-06', '11:15:58', '12:02:39', '45,1', '770.0', 1, 4, '621.00', 24, '38784', 3, '2019-04-09 10:54:53'),
(994, 1, 1, '2017-07-06', '10:57:33', '18:31:57', '755,6', '22395.0', 2, 8, '13491.00', 24, '9999.2', 3, '2019-04-09 10:54:53'),
(995, 1, 0, '2017-07-06', '18:32:07', '18:35:11', '0,6', '150.0', 1, 3, '375.00', 24, '9999.3', 2, '2019-04-09 10:54:53'),
(996, 1, 1, '2017-07-06', '18:35:52', '19:07:37', '62,3', '1385.0', 1, 3, '836.00', 24, '76350680', 3, '2019-04-09 10:54:53'),
(997, 1, 1, '2017-07-06', '19:18:20', '21:56:13', '0,1', '9460.0', 1, 3, '1022.00', 24, '0', 3, '2019-04-09 10:54:53'),
(998, 1, 1, '2017-07-07', '12:26:03', '16:56:19', '106,1', '11090.0', 2, 8, '4272.00', 24, '3879700052', 3, '2019-04-09 10:54:53'),
(999, 1, 1, '2017-07-08', '06:27:09', '09:58:35', '65,8', '9445.0', 2, 8, '3501.00', 24, '9000825', 3, '2019-04-09 10:54:53'),
(1000, 1, 1, '2017-07-08', '10:21:47', '18:51:34', '272,9', '16750.0', 2, 6, '6208.00', 24, '57', 3, '2019-04-09 10:54:53'),
(1001, 1, 1, '2017-07-10', '07:18:52', '08:33:21', '20,0', '3405.0', 2, 6, '904.00', 24, '38810', 3, '2019-04-09 10:54:53'),
(1002, 1, 1, '2017-07-10', '08:33:36', '10:14:31', '23,4', '4805.0', 2, 6, '1183.00', 24, '38820', 3, '2019-04-09 10:54:53'),
(1003, 1, 1, '2017-07-10', '13:40:34', '14:12:26', '26,1', '745.0', 1, 4, '416.00', 24, '38828', 3, '2019-04-09 10:54:53'),
(1004, 1, 1, '2017-07-10', '14:49:59', '16:18:20', '14,2', '4255.0', 2, 6, '865.00', 24, '38830', 2, '2019-04-09 10:54:53'),
(1006, 1, 1, '2017-07-10', '19:15:00', '23:18:06', '395,8', '1015.0', 1, 4, '4364.00', 24, '00000', 3, '2019-04-09 10:54:53'),
(1007, 1, 1, '2017-07-16', '11:54:10', '13:08:04', '13,9', '3725.0', 2, 7, '966.00', 24, '1608201701308', 3, '2019-04-09 10:54:53'),
(1008, 1, 1, '2017-07-16', '13:52:25', '14:52:09', '17,8', '2620.0', 2, 6, '768.00', 24, '15081701452', 3, '2019-04-09 10:54:53'),
(1012, 1, 0, '2017-07-25', '15:41:39', '15:41:39', '0.00', '0.0', 1, 3, '375.00', 24, '1555', 1, '2019-04-09 10:54:53'),
(1013, 1, 0, '2017-07-25', '15:44:03', '15:44:03', '0.00', '0.0', 1, 3, '375.00', 24, '11115', 1, '2019-04-09 10:54:53'),
(1014, 1, 1, '2017-07-27', '07:01:35', '08:12:39', '17,9', '3425.0', 1, 5, '827.00', 24, '38992', 3, '2019-04-09 10:54:53'),
(1016, 1, 0, '2017-07-27', '15:28:22', '15:28:22', '0.00', '0.0', 1, 3, '375.00', 24, '2555', 1, '2019-04-09 10:54:53'),
(1017, 1, 1, '2017-07-27', '16:50:05', '16:50:05', '0.00', '0.0', 1, 5, '500.00', 24, '25003', 3, '2019-04-09 10:54:53'),
(1018, 1, 1, '2017-07-27', '16:50:23', '16:50:24', '0.00', '0.0', 1, 5, '500.00', 24, '3333', 2, '2019-04-09 10:54:53'),
(1019, 1, 1, '2017-07-27', '16:50:54', '16:50:55', '0.00', '0.0', 1, 3, '300.00', 24, '3566', 1, '2019-04-09 10:54:53'),
(1020, 1, 0, '2017-07-27', '16:51:12', '16:51:13', '0.00', '0.0', 1, 3, '375.00', 24, '5855', 2, '2019-04-09 10:54:53'),
(1021, 1, 1, '2017-07-27', '08:27:44', '17:01:54', '168,0', '22810.0', 2, 6, '5751.00', 24, '38986', 3, '2019-04-09 10:54:53'),
(1022, 1, 1, '2017-07-28', '10:51:55', '13:57:30', '232,7', '1465.0', 1, 4, '2683.00', 24, '39012', 3, '2019-04-09 10:54:53'),
(1023, 1, 1, '2017-07-28', '03:00:00', '03:02:51', '152,0', '24205.0', 2, 8, '7917.00', 24, '9999999', 3, '2019-04-09 10:54:53'),
(1024, 1, 1, '2017-08-02', '11:41:20', '07:25:14', '290,8', '14345.0', 2, 8, '8656.00', 24, '39049', 3, '2019-04-09 10:54:53'),
(1025, 1, 1, '2017-08-03', '08:05:16', '08:46:22', '29,1', '1140.0', 1, 4, '490.00', 24, '39045', 3, '2019-04-09 10:54:53'),
(1026, 1, 1, '2017-08-04', '10:31:14', '02:51:49', '164,9', '17135.0', 2, 8, '6995.00', 24, '62172', 3, '2019-04-09 10:54:53'),
(1027, 1, 1, '2017-08-05', '03:00:03', '10:04:56', '82,3', '21130.0', 2, 8, '6136.00', 24, '39072', 3, '2019-04-09 10:54:53'),
(1028, 1, 1, '2019-04-12', '16:30:00', '16:30:00', '350', '04', 1, 3, '120.00', 24, '123456', 3, '2019-04-09 10:54:53'),
(1029, 1, 0, '2019-01-10', '20:15:00', '20:15:00', '700', '7 pm', 2, 6, '130.00', 24, '4567896', 2, '2019-04-09 10:54:53'),
(1030, 1, 0, '2019-04-05', '22:15:00', '06:15:00', '500', '09;30 pm', 1, 5, '500.00', 24, '3', 1, '2019-04-09 10:54:53'),
(1032, 1, 1, '2019-04-05', '21:15:00', '19:15:00', '750', '9', 2, 8, '1200.00', 24, '5', 2, '2019-04-09 10:54:53'),
(1033, 1, 0, '2019-04-05', '21:30:00', '21:30:00', '700', '9', 1, 3, '2000.00', 24, '6', 1, '2019-04-09 10:54:53'),
(1034, 1, 0, '2019-04-05', '20:30:00', '08:30:00', '700', '8', 1, 3, '1000.00', 24, '7', 2, '2019-04-09 10:54:53'),
(1035, 1, 1, '1975-03-23', '00:00:00', '00:00:00', 'Eiusmod est dolores ', 'Ex aute impedit et ', 2, 8, '973.00', 24, '243', 2, '2019-04-09 10:54:53'),
(1036, 1, 1, '1971-05-27', '00:00:00', '00:00:00', 'Eligendi odit dolor ', 'Excepteur dolore ani', 2, 9, '557.00', 24, '745', 2, '2019-04-09 10:54:53'),
(1037, 1, 1, '1977-10-04', '00:00:00', '00:00:00', 'Non earum deleniti l', 'Quod architecto laud', 2, 8, '143.00', 24, '756', 2, '2019-04-09 10:54:53'),
(1038, 1, 1, '2000-10-24', '00:00:00', '00:00:00', 'Velit aspernatur et', 'Ut laudantium aut n', 2, 5, '97.00', 24, '823', 2, '2019-04-09 10:54:53'),
(1039, 1, 0, '2019-04-09', '22:00:00', '00:00:00', 'test', 'asdf', 2, 5, '101.00', 22, '415', 2, '2019-04-09 10:56:00'),
(1047, 21, 0, '2019-04-09', '21:00:00', '00:00:00', 'Consequatur Nesciun', 'Molestiae duis sit e', 1, 17, '100.00', 34, '107', 3, '2019-04-09 13:45:15'),
(1048, 21, 1, '2019-04-09', '21:00:00', '23:00:00', 'Iste cupidatat ipsa', 'Et totam est volupt', 1, 18, '100.00', 35, '680', 3, '2019-04-09 13:46:18');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userid` int(11) NOT NULL,
  `business_id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `phone_no` varchar(10) NOT NULL,
  `password` varchar(100) NOT NULL,
  `profile_pic` varchar(250) NOT NULL,
  `userlevel` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userid`, `business_id`, `fname`, `lname`, `email`, `address`, `phone_no`, `password`, `profile_pic`, `userlevel`, `created_at`, `updated_at`) VALUES
(14, 0, 'Super', 'Admin', 'superadmin@gmail.com', '', '8866688666', '25f9e794323b453885f5181f1b624d0b', '', 1, '2019-04-03 11:16:29', NULL),
(18, 1, 'Vogn1', 'user', 'vogn1@first-transport.dk', '', '28141414', '25f9e794323b453885f5181f1b624d0b', '', 2, '2019-04-03 11:16:29', NULL),
(19, 2, 'Sander', 'Jensen', 'sander@first-transport.dk', 'address', '40602030', '25f9e794323b453885f5181f1b624d0b', 'avatar5.png', 2, '2019-04-03 11:16:29', NULL),
(20, 1, 'Ased', 'user', 'am@contrasoft.dk', 'address', '26844540', '25f9e794323b453885f5181f1b624d0b', 'user1-128x128.jpg', 3, '2019-04-03 11:16:29', '2019-04-09 07:28:17'),
(21, 1, 'Allan', 'Bengtson', 'bengtson@first-transport.dk', 'address', '28141414', '25f9e794323b453885f5181f1b624d0b', 'user1-128x128.jpg', 2, '2019-04-03 11:16:29', '2019-04-08 02:41:02'),
(22, 2, 'Julian', 'Winters', 'zanac@mailinator.net', 'Doloribus in ut mole', '123456798', '25f9e794323b453885f5181f1b624d0b', 'avatar2.png', 3, '2019-04-05 10:16:23', NULL),
(23, 2, 'Alex', 'Zender', 'alex123@mailnetor.com', 'Address line', '456798161', '25f9e794323b453885f5181f1b624d0b', 'user1-128x128.jpg', 3, '2019-04-05 10:17:30', '2019-04-05 10:15:01'),
(24, 1, 'Barclay', 'Olson', 'citifety@mailinator.net', 'Totam qui do tempore', '12345678', '25f9e794323b453885f5181f1b624d0b', 'user7-128x128.jpg', 3, '2019-04-05 12:18:18', NULL),
(27, 18, 'Colleen Gill', 'August Leon', 'wavab@mailinator.net', 'Sunt velit labore c', '+1 (233) 6', '8aa85d8e474bec306b333143ab5e30b7', 'avatar2.png', 0, '2019-04-08 11:26:16', NULL),
(28, 19, 'Larissa Ward', 'Jorden Brennan', 'myvyfar@mailinator.net', 'Laudantium aspernat', '+1 (978) 6', 'ee8ce4a1e977aaf426eceaa194186248', 'avatar2.png', 2, '2019-04-08 11:27:35', NULL),
(29, 20, 'Romeroa', 'Johnsona', 'jifyla@mailinator.com', 'A Tempora dolorem temp', '8899988999', '25f9e794323b453885f5181f1b624d0b', 'avatar04.png', 2, '2019-04-08 12:15:23', '2019-04-09 05:54:24'),
(31, 3, 'Jonah Alford', 'Keegan Patel', 'vydesihy@mailinator.net', 'Ea sit autem beatae ', '+1 (574) 2', '25f9e794323b453885f5181f1b624d0b', 'avatar.png', 2, '2019-04-09 06:11:42', NULL),
(32, 3, 'Tad Reese', 'Flynn Lancaster', 'befij@mailinator.com', 'Voluptatem atque odi', '+1 (824) 3', '25f9e794323b453885f5181f1b624d0b', 'avatar3.png', 3, '2019-04-09 06:11:57', NULL),
(33, 21, 'Brenda', 'Wilkins', 'dixoguvo@mailinator.com', 'Culpa rerum ut disti', '4567897945', '25f9e794323b453885f5181f1b624d0b', 'avatar04.png', 2, '2019-04-09 09:43:03', NULL),
(34, 21, 'Porter', 'Leon', 'dujibek@mailinator.net', 'Ex quibusdam iusto i', '4567894567', '25f9e794323b453885f5181f1b624d0b', 'avatar2.png', 3, '2019-04-09 11:02:51', NULL),
(35, 21, 'Valentine', 'Hammond', 'hezyve@mailinator.net', 'Atque minim laborios', '92798456', '25f9e794323b453885f5181f1b624d0b', 'avatar5.png', 3, '2019-04-09 13:02:10', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `business`
--
ALTER TABLE `business`
  ADD PRIMARY KEY (`business_id`);

--
-- Indexes for table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`Levelid`),
  ADD UNIQUE KEY `Levelid` (`Levelid`),
  ADD KEY `levelnumber` (`levelnumber`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`paymentid`),
  ADD KEY `paymenttype` (`paymenttype`);

--
-- Indexes for table `rates`
--
ALTER TABLE `rates`
  ADD PRIMARY KEY (`rateid`),
  ADD KEY `ratetype` (`ratetype`),
  ADD KEY `rateid` (`rateid`);

--
-- Indexes for table `ratetype`
--
ALTER TABLE `ratetype`
  ADD PRIMARY KEY (`rateid`),
  ADD KEY `rateid` (`rateid`),
  ADD KEY `ratetype` (`ratetype`);

--
-- Indexes for table `trip`
--
ALTER TABLE `trip`
  ADD PRIMARY KEY (`tripid`),
  ADD UNIQUE KEY `tripnumber` (`tripnumber`),
  ADD UNIQUE KEY `tripid` (`tripid`),
  ADD KEY `paymenttype` (`paymenttype`),
  ADD KEY `tdriverid` (`tdriverid`),
  ADD KEY `ratetype` (`ratetype`),
  ADD KEY `ratesid` (`ratesid`),
  ADD KEY `ratesid_2` (`ratesid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `userid` (`userid`),
  ADD KEY `userlevel` (`userlevel`),
  ADD KEY `userid_2` (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `business`
--
ALTER TABLE `business`
  MODIFY `business_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `levels`
--
ALTER TABLE `levels`
  MODIFY `Levelid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `paymentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rates`
--
ALTER TABLE `rates`
  MODIFY `rateid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `ratetype`
--
ALTER TABLE `ratetype`
  MODIFY `rateid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `trip`
--
ALTER TABLE `trip`
  MODIFY `tripid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1049;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rates`
--
ALTER TABLE `rates`
  ADD CONSTRAINT `rates_ibfk_1` FOREIGN KEY (`ratetype`) REFERENCES `ratetype` (`rateid`) ON UPDATE CASCADE;

--
-- Constraints for table `trip`
--
ALTER TABLE `trip`
  ADD CONSTRAINT `payment_paymenttype_fk` FOREIGN KEY (`paymenttype`) REFERENCES `payment` (`paymentid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `rateid_rates_fk` FOREIGN KEY (`ratesid`) REFERENCES `rates` (`rateid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ratetype_rates_fk` FOREIGN KEY (`ratetype`) REFERENCES `ratetype` (`rateid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `trip_user_fk` FOREIGN KEY (`tdriverid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
