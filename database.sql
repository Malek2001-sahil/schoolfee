-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 04, 2023 at 07:01 AM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 5.6.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sfms`
--

-- --------------------------------------------------------

--
-- Table structure for table `sfms_acedemic_standard`
--

CREATE TABLE `sfms_acedemic_standard` (
  `acedemic_standard_id` int(11) NOT NULL,
  `acedemic_standard_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `acedemic_standard_division` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `acedemic_standard_status` enum('Enable','Disable') COLLATE utf8_unicode_ci NOT NULL,
  `acedemic_standard_added_on` int(16) NOT NULL,
  `acedemic_standard_updated_on` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sfms_acedemic_standard`
--

INSERT INTO `sfms_acedemic_standard` (`acedemic_standard_id`, `acedemic_standard_name`, `acedemic_standard_division`, `acedemic_standard_status`, `acedemic_standard_added_on`, `acedemic_standard_updated_on`) VALUES
(1, 'Junior KG', 'A', 'Enable', 1667819450, 1667819511),
(2, 'Senior KG', 'A', 'Enable', 1668596148, 0),
(3, '1', 'A', 'Enable', 1668596157, 0),
(4, '2', 'A', 'Enable', 1668596166, 0),
(5, '3', 'A', 'Enable', 1668596175, 0),
(6, '2', 'B', 'Enable', 1672207282, 1672294503);

-- --------------------------------------------------------

--
-- Table structure for table `sfms_acedemic_year`
--

CREATE TABLE `sfms_acedemic_year` (
  `acedemic_year_id` int(11) NOT NULL,
  `acedemic_start_year` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `acedemic_start_month` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `acedemic_end_year` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `acedemic_end_month` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `acedemic_year_status` enum('Enable','Disable') COLLATE utf8_unicode_ci NOT NULL,
  `acedemic_added_on` int(16) NOT NULL,
  `acedemic_updated_on` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sfms_acedemic_year`
--

INSERT INTO `sfms_acedemic_year` (`acedemic_year_id`, `acedemic_start_year`, `acedemic_start_month`, `acedemic_end_year`, `acedemic_end_month`, `acedemic_year_status`, `acedemic_added_on`, `acedemic_updated_on`) VALUES
(1, '2022', 'June', '2023', 'May', 'Enable', 1667815686, 1667815712),
(2, '2023', 'June', '2024', 'May', 'Enable', 1670664849, 1671001833);

-- --------------------------------------------------------

--
-- Table structure for table `sfms_admin`
--

CREATE TABLE `sfms_admin` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `admin_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `admin_password` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `admin_type` enum('Master','User') COLLATE utf8_unicode_ci NOT NULL,
  `admin_status` enum('Enable','Disable') COLLATE utf8_unicode_ci NOT NULL,
  `admin_added_on` int(16) NOT NULL,
  `admin_updated_on` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sfms_admin`
--

INSERT INTO `sfms_admin` (`admin_id`, `admin_name`, `admin_email`, `admin_password`, `admin_type`, `admin_status`, `admin_added_on`, `admin_updated_on`) VALUES
(1, 'Johny Smith', 'admin@sfms.com', 'password', 'Master', 'Enable', 1667307101, 0),
(2, 'Peter Parker', 'user@sfms.com', 'password123', 'User', 'Enable', 1667385979, 0),
(3, 'Diana Hubber', 'diana.huber@sfms.com', 'password', 'User', 'Enable', 1670069079, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sfms_fees`
--

CREATE TABLE `sfms_fees` (
  `fees_id` int(11) NOT NULL,
  `acedemic_year_id` int(11) NOT NULL,
  `acedemic_standard_id` int(11) NOT NULL,
  `fees_month` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `fees_data` text COLLATE utf8_unicode_ci NOT NULL,
  `fees_status` enum('Enable','Disable') COLLATE utf8_unicode_ci NOT NULL,
  `fees_added_on` int(16) NOT NULL,
  `fees_updated_on` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sfms_fees`
--

INSERT INTO `sfms_fees` (`fees_id`, `acedemic_year_id`, `acedemic_standard_id`, `fees_month`, `fees_data`, `fees_status`, `fees_added_on`, `fees_updated_on`) VALUES
(1, 1, 1, 'June - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1000\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"500\"},{\"fees_name\":\"Term Fee\",\"fees_value\":\"250\"}]', 'Enable', 1667913480, 1667994941),
(2, 1, 1, 'July - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1000\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"500\"}]', 'Enable', 1668165189, 0),
(3, 1, 1, 'August - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1000\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"500\"}]', 'Enable', 1668601735, 0),
(4, 1, 1, 'September - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1000\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"500\"}]', 'Enable', 1668601763, 0),
(5, 1, 1, 'October - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1000\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"500\"}]', 'Enable', 1668601819, 0),
(6, 1, 1, 'November - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1000\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"500\"}]', 'Enable', 1668601845, 0),
(7, 1, 1, 'December - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1000\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"500\"},{\"fees_name\":\"Term Fee\",\"fees_value\":\"250\"}]', 'Enable', 1668601899, 0),
(8, 1, 1, 'January - 2023', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1000\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"500\"}]', 'Enable', 1668601929, 0),
(9, 1, 1, 'February - 2023', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1000\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"500\"}]', 'Enable', 1668601955, 0),
(10, 1, 1, 'March - 2023', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1000\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"500\"}]', 'Enable', 1668602044, 0),
(11, 1, 1, 'April - 2023', '[{\"fees_name\":\"Tution Fee - April\",\"fees_value\":\"1000\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"500\"},{\"fees_name\":\"Tution Fee - May\",\"fees_value\":\"1000\"}]', 'Enable', 1668602085, 0),
(12, 1, 2, 'June - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1100\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"550\"},{\"fees_name\":\"Term Fee\",\"fees_value\":\"300\"}]', 'Enable', 1668602135, 1668602171),
(13, 1, 2, 'July - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1100\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"550\"}]', 'Enable', 1668602164, 0),
(14, 1, 2, 'August - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1100\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"550\"}]', 'Enable', 1668602197, 0),
(15, 1, 2, 'September - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1100\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"550\"}]', 'Enable', 1668602218, 0),
(16, 1, 2, 'October - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1100\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"550\"}]', 'Enable', 1668602249, 0),
(17, 1, 2, 'November - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1100\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"550\"}]', 'Enable', 1668602271, 0),
(18, 1, 2, 'December - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1100\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"550\"},{\"fees_name\":\"Term Fee\",\"fees_value\":\"300\"}]', 'Enable', 1668602309, 0),
(19, 1, 2, 'January - 2023', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1100\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"550\"}]', 'Enable', 1668602333, 0),
(20, 1, 2, 'February - 2023', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1100\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"550\"}]', 'Enable', 1668602354, 0),
(21, 1, 2, 'March - 2023', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1100\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"550\"}]', 'Enable', 1668602379, 0),
(22, 1, 2, 'April - 2023', '[{\"fees_name\":\"Tution Fee - April\",\"fees_value\":\"1100\"},{\"fees_name\":\"Tution Fee - May\",\"fees_value\":\"1100\"},{\"fees_name\":\"Food Fee - April\",\"fees_value\":\"550\"}]', 'Enable', 1668602450, 0),
(23, 1, 3, 'June - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1200\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"600\"},{\"fees_name\":\"Term Fee\",\"fees_value\":\"350\"}]', 'Enable', 1668602493, 0),
(24, 1, 3, 'July - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1200\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"600\"}]', 'Enable', 1668602520, 0),
(25, 1, 3, 'August - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1200\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"600\"}]', 'Enable', 1668602545, 0),
(26, 1, 3, 'September - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1200\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"600\"}]', 'Enable', 1668602566, 0),
(27, 1, 3, 'October - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1200\"},{\"fees_name\":\"Food\",\"fees_value\":\"600\"}]', 'Enable', 1668602590, 0),
(28, 1, 3, 'November - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1200\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"600\"}]', 'Enable', 1668602617, 0),
(29, 1, 3, 'December - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1200\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"600\"},{\"fees_name\":\"Term Fee\",\"fees_value\":\"350\"}]', 'Enable', 1668602652, 0),
(30, 1, 3, 'January - 2023', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1200\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"600\"}]', 'Enable', 1668602673, 0),
(31, 1, 3, 'February - 2023', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1200\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"600\"}]', 'Enable', 1668602695, 0),
(32, 1, 3, 'March - 2023', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1200\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"600\"}]', 'Enable', 1668602719, 0),
(33, 1, 3, 'April - 2023', '[{\"fees_name\":\"Tution Fee - April\",\"fees_value\":\"1200\"},{\"fees_name\":\"Tution Fee - May\",\"fees_value\":\"1200\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"600\"}]', 'Enable', 1668602754, 0),
(34, 1, 4, 'June - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1300\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"650\"},{\"fees_name\":\"Term Fee\",\"fees_value\":\"400\"}]', 'Enable', 1668602791, 0),
(35, 1, 4, 'July - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1300\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"650\"}]', 'Enable', 1668602821, 0),
(36, 1, 4, 'August - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1300\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"650\"}]', 'Enable', 1668602843, 0),
(37, 1, 4, 'September - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1300\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"650\"}]', 'Enable', 1668602864, 0),
(38, 1, 4, 'October - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1300\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"650\"}]', 'Enable', 1668602900, 0),
(39, 1, 4, 'November - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1300\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"650\"}]', 'Enable', 1668602928, 0),
(40, 1, 4, 'December - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1300\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"650\"},{\"fees_name\":\"Term Fee\",\"fees_value\":\"400\"}]', 'Enable', 1668602957, 0),
(41, 1, 4, 'January - 2023', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1300\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"650\"}]', 'Enable', 1668602981, 0),
(42, 1, 4, 'February - 2023', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1300\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"650\"}]', 'Enable', 1668603005, 0),
(43, 1, 4, 'March - 2023', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1300\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"650\"}]', 'Enable', 1668603027, 0),
(44, 1, 4, 'April - 2023', '[{\"fees_name\":\"Tution Fee - April\",\"fees_value\":\"1300\"},{\"fees_name\":\"Tution Fee - May\",\"fees_value\":\"1300\"},{\"fees_name\":\"Food Fee - April\",\"fees_value\":\"650\"}]', 'Enable', 1668603071, 0),
(45, 1, 5, 'June - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1400\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"700\"},{\"fees_name\":\"Term Fee\",\"fees_value\":\"400\"}]', 'Enable', 1668603105, 1668603121),
(46, 1, 5, 'July - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1400\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"700\"}]', 'Enable', 1668603147, 0),
(47, 1, 5, 'August - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1400\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"700\"}]', 'Enable', 1668603171, 0),
(48, 1, 5, 'September - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1400\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"700\"}]', 'Enable', 1668603193, 0),
(49, 1, 5, 'October - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1400\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"700\"}]', 'Enable', 1668603241, 0),
(50, 1, 5, 'November - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1400\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"700\"}]', 'Enable', 1668603265, 0),
(51, 1, 5, 'December - 2022', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1400\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"700\"},{\"fees_name\":\"Term Fee\",\"fees_value\":\"400\"}]', 'Enable', 1668603291, 1668603305),
(52, 1, 5, 'January - 2023', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1400\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"700\"}]', 'Enable', 1668603338, 0),
(53, 1, 5, 'February - 2023', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1400\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"700\"}]', 'Enable', 1668603361, 0),
(54, 1, 5, 'March - 2023', '[{\"fees_name\":\"Tution Fee\",\"fees_value\":\"1400\"},{\"fees_name\":\"Food Fee\",\"fees_value\":\"700\"}]', 'Enable', 1668603386, 0),
(55, 1, 5, 'April - 2023', '[{\"fees_name\":\"Tution Fee - April\",\"fees_value\":\"1400\"},{\"fees_name\":\"Tution Fee - May\",\"fees_value\":\"1400\"},{\"fees_name\":\"Food Fee - April\",\"fees_value\":\"700\"}]', 'Enable', 1668603431, 1677907724);

-- --------------------------------------------------------

--
-- Table structure for table `sfms_fees_paid`
--

CREATE TABLE `sfms_fees_paid` (
  `fees_paid_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `fees_id` int(11) NOT NULL,
  `acedemic_year_id` int(11) NOT NULL,
  `acedemic_standard_id` int(11) NOT NULL,
  `fees_received_by` int(11) NOT NULL,
  `fees_paid_on` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sfms_fees_paid`
--

INSERT INTO `sfms_fees_paid` (`fees_paid_id`, `student_id`, `fees_id`, `acedemic_year_id`, `acedemic_standard_id`, `fees_received_by`, `fees_paid_on`) VALUES
(2, 1, 1, 1, 1, 1, 1668169735),
(3, 1, 2, 1, 1, 1, 1668603465),
(4, 2, 1, 1, 1, 1, 1668603515),
(5, 2, 2, 1, 1, 1, 1668603532),
(6, 3, 1, 1, 1, 1, 1668603609),
(7, 3, 2, 1, 1, 1, 1668603625),
(8, 4, 1, 1, 1, 1, 1668603660),
(9, 5, 1, 1, 1, 1, 1668603680),
(10, 6, 1, 1, 1, 1, 1668603694),
(11, 7, 1, 1, 1, 2, 1668603763),
(12, 8, 1, 1, 1, 1, 1668603827),
(13, 9, 1, 1, 1, 2, 1668603841),
(14, 10, 1, 1, 1, 1, 1668603861),
(15, 11, 12, 1, 2, 2, 1668604197),
(16, 12, 12, 1, 2, 1, 1668604217),
(17, 13, 12, 1, 2, 2, 1668604970),
(18, 14, 12, 1, 2, 1, 1668604985),
(19, 15, 12, 1, 2, 2, 1668605001),
(20, 16, 12, 1, 2, 1, 1668605020),
(21, 17, 12, 1, 2, 2, 1668605035),
(22, 18, 12, 1, 2, 1, 1668605052),
(23, 19, 12, 1, 2, 1, 1668605067),
(24, 20, 12, 1, 2, 2, 1668605081),
(25, 21, 23, 1, 3, 1, 1668605095),
(26, 22, 23, 1, 3, 2, 1668605109),
(27, 23, 23, 1, 3, 1, 1668605122),
(28, 24, 23, 1, 3, 2, 1668605135),
(29, 25, 23, 1, 3, 1, 1668605148),
(30, 26, 23, 1, 3, 2, 1668605162),
(31, 27, 23, 1, 3, 1, 1668605177),
(32, 28, 23, 1, 3, 2, 1668605191),
(33, 29, 23, 1, 3, 1, 1668605207),
(34, 30, 23, 1, 3, 2, 1668605222),
(35, 31, 34, 1, 4, 1, 1668605237),
(36, 32, 34, 1, 4, 2, 1668605250),
(37, 33, 34, 1, 4, 1, 1668605264),
(38, 34, 34, 1, 4, 2, 1668605281),
(39, 35, 34, 1, 4, 1, 1668605292),
(40, 36, 34, 1, 4, 2, 1668605305),
(41, 38, 34, 1, 4, 1, 1668605333),
(42, 39, 34, 1, 4, 2, 1668605347),
(43, 40, 34, 1, 4, 1, 1668605360),
(44, 41, 45, 1, 5, 2, 1668605379),
(45, 1, 3, 1, 1, 1, 1669027631),
(46, 1, 4, 1, 1, 1, 1677908827);

-- --------------------------------------------------------

--
-- Table structure for table `sfms_setting`
--

CREATE TABLE `sfms_setting` (
  `setting_id` int(2) NOT NULL,
  `school_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `school_address` text COLLATE utf8_unicode_ci NOT NULL,
  `school_email_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `school_website` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `school_contact_number` varchar(15) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sfms_setting`
--

INSERT INTO `sfms_setting` (`setting_id`, `school_name`, `school_address`, `school_email_address`, `school_website`, `school_contact_number`) VALUES
(1, 'Testing School', 'Testing School Address, 7522525', 'testing_school123@gmail.com', 'https://www.testingschool.org/', '7412589632');

-- --------------------------------------------------------

--
-- Table structure for table `sfms_student`
--

CREATE TABLE `sfms_student` (
  `student_id` int(11) NOT NULL,
  `student_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `student_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `student_father_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `student_mother_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `student_date_of_birth` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `student_address` text COLLATE utf8_unicode_ci NOT NULL,
  `student_date_of_addmission` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `student_contact_number1` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `student_contact_number2` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `student_image` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `student_added_on` int(16) NOT NULL,
  `student_updated_on` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sfms_student`
--

INSERT INTO `sfms_student` (`student_id`, `student_number`, `student_name`, `student_father_name`, `student_mother_name`, `student_date_of_birth`, `student_address`, `student_date_of_addmission`, `student_contact_number1`, `student_contact_number2`, `student_image`, `student_added_on`, `student_updated_on`) VALUES
(1, 'S000001', 'Rahul Mihir Shah', 'Mihir Ganpat Shah', 'Veena Mihir Shah', '10/11/2014', 'Testing Address', '05/12/2018', '9874563210', '8523697410', '1667477828-29161.png', 1667477828, 1667478354),
(2, 'S000002', 'Richa Manoj Brahmbhatt', 'Manoj Brahmbhatt', 'Swati Manoj Brahmbhatt', '04/16/2015', '48, Jamshed Apartments, Munaf Nagar', '05/04/2022', '6173879064', '', '1668431489-1385.png', 1668431489, 0),
(3, 'S000003', 'Ankita Sunil Kakar', 'Sunil Kakar', 'Madhuri Kakar', '08/16/2014', '36, Sodala', '06/09/2022', '8684129849', '', '1668431637-30634.png', 1668431637, 0),
(4, 'S000004', 'Aishwarya Satish Pathak', 'Satish Pathak', 'Mamta Pathak', '03/12/2015', '12, Qadim Society', '04/13/2022', '7923991982', '', '1668431910-31726.png', 1668431910, 0),
(5, 'S000005', 'Kalyani Rajeev Guha', 'Rajeev Guha', 'Manisha Guha', '10/28/2014', '17, EmranPur', '07/04/2022', '7179847815', '', '1668432079-17405.png', 1668432079, 0),
(6, 'S000006', 'Rosey Ghalib Chauhan', 'Ghalib Chauhan', 'Anjali Chauhan', '03/06/2015', '30, Hadapsar', '04/22/2022', '8974615872', '', '1668432184-3065.png', 1668432184, 0),
(7, 'S000007', 'Ram Gopal Sule', 'Gopal Sule', 'Tejasvi Sule', '12/17/2014', '43, Naina Apartments, Deccan Gymkhana', '04/05/2022', '6991282151', '', '1668432283-30863.png', 1668432283, 0),
(8, 'S000008', 'Dipti Omar Ahuja', 'Omar Ahuja', 'Ketki Ahuja', '02/13/2015', '78, Urmi Heights, Ricky Nagar', '05/13/2022', '6475690895', '', '1668432414-5916.png', 1668432414, 0),
(9, 'S000009', 'Lata Jay Mehta', 'Jay Mehta', 'Teena Mehta', '08/19/2014', '36, Peter Heights, Bhanupriya Chowk', '04/15/2022', '6515359987', '', '1668432695-18847.png', 1668432695, 0),
(10, 'S000010', 'Shashank Murali Sridhar', 'Murali Sridhar', 'Gayatri Sridhar', '11/27/2014', '36, Sodala', '06/04/2022', '7842632744', '', '1668432802-14578.png', 1668432802, 0),
(11, 'S000011', 'Astha Dhawan', 'Pratik Dhawan', 'Disha Dhawan', '12/28/2013', '12, Fardeen Heights, Vikhroli', '05/13/2022', '8755593159', '', '1668514751-1028.png', 1668514751, 0),
(12, 'S000012', 'Nilam Dubey', 'Abhay Dubey', 'Meera Dubey', '11/26/2014', '21, Borivali', '05/13/2022', '5816758411', '', '1668514850-7661.png', 1668514850, 0),
(13, 'S000013', 'Rehman Divan', 'Imran Divan', 'Mariyam Divan', '09/23/2014', '79, Brock Society, EkbalPur', '06/25/2022', '9808902219', '', '1668514949-11614.png', 1668514949, 0),
(14, 'S000014', 'Manjari Reddy', 'Rahul Reddy', 'Sameera Reddy', '12/31/2014', '12, Ragini Villas, Malad', '06/15/2022', '9704405123', '', '1668515060-9535.png', 1668515060, 0),
(15, 'S000015', 'Parveen Pingle', 'Radhav Pingle', 'Laxmi Pingle', '01/31/2013', '43, Cyber City', '05/23/2022', '9981512407', '', '1668515176-10070.png', 1668515176, 0),
(16, 'S000016', 'Veena Bhardwaj', 'Suraj Bhardwaj', 'Sakshi Bhardwaj', '12/30/2014', '70, SunitaGarh', '06/23/2022', '8286627150', '', '1668515284-9250.png', 1668515284, 0),
(17, 'S000017', 'Maherbanu Sahid Khan Pathan', 'Sahid Khan Pathan', 'Rumana Pathan', '09/25/2014', 'Amraiwadi', '05/05/2022', '8557445263', '', '1668515733-26025.png', 1668515733, 0),
(18, 'S000018', 'Sisodiya Prashant Kumar Arun Bhai', 'Sisodiya Arun Bhai', 'Sisodiya Madhu', '03/18/2015', 'Bhat B.O', '06/12/2022', '9985574555', '', '1668516131-3468.png', 1668516131, 0),
(19, 'S000019', 'Kapadiya Krunal Chandresh Kumar', 'Kapadiya Chandresh Kumar', 'Kapadiya Mahi Ben', '02/17/2015', 'Bodakdev', '06/13/2022', '9854745526', '', '1668516241-28339.png', 1668516241, 0),
(20, 'S000020', 'Patel Pallaviben Bharatkumar', 'Patel Bharatkumar', 'Patel Bharati', '08/26/2014', '64, Deccan Gymkhana', '06/07/2022', '948215774', '', '1668517552-30510.png', 1668517552, 0),
(21, 'S000021', 'Vihol Devrajsinh Rakeshkumar', 'Vihol Rakeshkumar', 'Vihol Vaibhavi', '08/21/2014', '57, Naseer Apartments, Hinjewadi', '04/13/2022', '9893530348', '', '1668517669-15884.png', 1668517669, 0),
(22, 'S000022', 'Gohil Avaniben Ashokkumar', 'Gohil Ashokkumar', 'Gohil Ashaben', '10/28/2014', '42, Aundh', '05/11/2022', '5132373222', '', '1668517776-7381.png', 1668517776, 0),
(23, 'S000023', 'Parmar Girishkumar Rameshbhai', 'Parmar Rameshbhai', 'Parmar Ramila', '11/27/2013', '27, NakulGunj', '05/10/2022', '5170409803', '', '1668517894-17237.png', 1668517894, 0),
(24, 'S000024', 'Damor Kinjalben Umeshbhai', 'Damor Umeshbhai', 'Damor Vinya', '11/15/2022', '89, Cyber City', '06/21/2022', '9856325415', '', '1668518020-16746.png', 1668518020, 0),
(25, 'S000025', 'Mori Darshitkumar Maheshbhai', 'Mori Maheshbhai', 'Mori Meenaben', '08/19/2014', '95, Kirti Society', '07/12/2022', '5349629504', '', '1668518151-23786.png', 1668518151, 0),
(26, 'S000026', 'Paghadar Darshan Dineshbhai', 'Paghadar Dineshbhai', 'Paghadar Rashmika', '12/18/2013', '84, Falguni Nagar', '05/18/2022', '606474714', '', '1668518329-3035.png', 1668518329, 0),
(27, 'S000027', 'Rakholiya Darpitkumar Rajanikant', 'Rakholiya Rajanikant', 'Rakholiya Reemaben', '09/23/2014', '13, Sabina Heights, Malad', '06/16/2022', '8814006764', '', '1668518423-23664.png', 1668518423, 0),
(28, 'S000028', 'Zaver Darshan Vijaykumar', 'Zaver Vijaykumar', 'Zaver Praptiben', '11/18/2014', '93, Ragini Villas,', '05/18/2022', '9127613750', '', '1668518527-29391.png', 1668518527, 0),
(29, 'S000029', 'Makwana Pinalben Alpeshbhai', 'Makwana Alpeshbhai', 'Makwana Alpaben', '10/15/2014', '48, Amolika Heights', '05/20/2022', '4812995242', '', '1668518625-11241.png', 1668518625, 0),
(30, 'S000030', 'Parmar Devangiben Ashokbhai', 'Parmar Ashokbhai', 'Parmar Ashaben', '09/24/2014', '82, Ruchi Apartments', '05/12/2022', '9028447135', '', '1668518719-22107.png', 1668518719, 0),
(31, 'S000031', 'Malkiya Vaibhav Nandlalbhai', 'Malkiya Nandlalbhai', 'Malkiya Nandniben', '11/16/2022', '86, Mukti Society, Navami Chowk', '05/19/2022', '7637981022', '', '1668597227-6821.png', 1668597227, 0),
(32, 'S000032', 'Pathak Nirav Pradipbhai', 'Pathak Pradipbhai', 'Pathak Chaya', '03/21/2013', '74, Malad', '05/11/2022', '898420763', '', '1668597334-31404.png', 1668597334, 0),
(33, 'S000033', 'Padhiyar Arpitaben Kesarisinh', 'Padhiyar Kesarisinh', 'Padhiyar Jyotiben', '11/14/2012', '58, Radha Heights, Model Town', '03/24/2022', '6797971757', '', '1668597457-20090.png', 1668597457, 0),
(34, 'S000034', 'Tadvi Riteshkumar Navalsinh', 'Tadvi Navalsinh', 'Tadvi Rekhaben', '09/17/2013', '52, JulieGunj', '05/04/2022', '5513116557', '', '1668597563-9167.png', 1668597563, 0),
(35, 'S000035', 'Thakkar Mitali Jayeshkumar', 'Thakkar Jayeshkumar', 'Thakkar Manshi', '09/25/2013', '37, Veena Society, RakhiGunj', '07/06/2022', '8167436131', '', '1668597660-17963.png', 1668597660, 0),
(36, 'S000036', 'Suthar Vijaykumar Velaji', 'Suthar Velaji', 'Suthar Aditi', '09/19/2013', '12, Yadu Heights, Harmada', '05/19/2022', '5236264913', '', '1668597955-20721.png', 1668597955, 0),
(37, 'S000037', 'Suthar Vijaykumar Velaji', 'Suthar Velaji', 'Suthar Aditi', '09/19/2013', '12, Yadu Heights, Harmada', '05/19/2022', '5236264913', '', '1668597956-10888.png', 1668597956, 0),
(38, 'S000038', 'Prajapati Sahil Mukeshbhai', 'Prajapati Mukeshbhai', 'Prajapati Minxiben', '11/26/2013', '89, Darpan Apartments, Yerwada', '05/18/2022', '9256476409', '', '1668598382-10815.png', 1668598382, 0),
(39, 'S000039', 'Prajapati Khushbu Girishbhai', 'Prajapati Girishbhai', 'Prajapati Kaminiben', '08/13/2013', '18, Radhe Villas, DevendraGunj', '06/09/2022', '6449290491', '', '1668598483-15393.png', 1668598483, 0),
(40, 'S000040', 'Chaudhary Janakkumar Bhurabhai', 'Chaudhary Bhurabhai', 'Chaudhary Jankiben', '10/26/2013', '33, Akshay Apartments', '04/06/2022', '6745893226', '', '1668598596-348.png', 1668598596, 0),
(41, 'S000041', 'Chauhan Bhavaji Babaji', 'Chauhan Babaji', 'Chauhan Bharati', '10/15/2013', '47, Yeshwanthpura', '04/14/2022', '7504698839', '', '1668598703-22498.png', 1668598703, 0),
(42, 'S000042', 'Patel Hetviben Umakantbhai', 'Patel Umakantbhai', 'Patel Urmilaben', '07/10/2013', '61, Gaurav Villas, Aundh', '06/15/2022', '9549321592', '', '1668598810-24332.png', 1668598810, 0),
(43, 'S000043', 'Joshi Shrirambhai Prabhurambhai', 'Joshi Prabhurambhai', 'Joshi Hemaben', '10/31/2012', '91, Zahir Apartments, Harmada', '05/11/2022', '7308884650', '', '1668599245-19557.png', 1668599245, 0),
(44, 'S000044', 'Jangid Harshita Shreeshyamlal', 'Jangid Shreeshyamlal', 'Jangid Sakshiben', '08/13/2014', '45, Peter Apartments', '04/21/2022', '8171626515', '', '1668599357-21365.png', 1668599357, 0),
(45, 'S000045', 'Solanki Pruthvirajsinh Vikramsinh', 'Solanki Vikramsinh', 'Solanki Veenaben', '12/28/2012', '22, Kalpana Society, Sodala', '05/17/2022', '6528182517', '', '1668599467-24330.png', 1668599467, 0),
(46, 'S000046', 'Pandya Kushal Bhadreshkumar', 'Pandya Bhadreshkumar', 'Pandya Bhumika', '05/10/2013', '17, Ajeet Apartments, SameedhaGarh', '06/08/2022', '9731507766', '', '1668599578-13179.png', 1668599578, 0),
(47, 'S000047', 'Chaudhary Vijaykumar Bhudarbhai', 'Chaudhary Bhudarbhai', 'Chaudhary Seemaben', '08/29/2013', '29, Veena Society, Vikhroli', '05/10/2022', '9143261360', '', '1668599690-5528.png', 1668599690, 0),
(48, 'S000048', 'Lakhavara Vaibhavkumar Vinodbhai', 'Lakhavara Vinodbhai', 'Lakhavara Abhaben', '10/29/2013', '57, Priyanka Apartments, Mohan Chowk', '05/11/2022', '4924642303', '', '1668599810-5334.png', 1668599810, 0),
(49, 'S000049', 'Prajapati Jaiminkumar Khushaldas', 'Prajapati Khushaldas', 'Prajapati Padmini', '10/30/2012', '92, Saurabh Society, ManjariPur', '04/14/2022', '8756161745', '', '1668599919-30579.png', 1668599919, 0),
(50, 'S000050', 'Pandya Mandipkumar Bharatbhai', 'Pandya Bharatbhai', 'Pandya Sejalben', '11/10/2014', '20, Ram Gopal Apartments, Virar', '05/20/2022', '9705823293', '', '1668600085-12785.png', 1668600085, 0),
(51, 'S000051', 'Ayushi Gupte', 'Rahul Gupta', 'Mahi Gupta', '07/09/2015', 'Testing Address', '08/24/2022', '8523697411', '8523697412', '1673593502-32613.jpg', 1672923655, 1673593546);

-- --------------------------------------------------------

--
-- Table structure for table `sfms_student_standard`
--

CREATE TABLE `sfms_student_standard` (
  `student_standard_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `acedemic_year_id` int(11) NOT NULL,
  `acedemic_standard_id` int(11) NOT NULL,
  `student_standard_status` enum('Enable','Disable') COLLATE utf8_unicode_ci NOT NULL,
  `student_standard_added_on` int(16) NOT NULL,
  `student_standard_updated_on` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sfms_student_standard`
--

INSERT INTO `sfms_student_standard` (`student_standard_id`, `student_id`, `acedemic_year_id`, `acedemic_standard_id`, `student_standard_status`, `student_standard_added_on`, `student_standard_updated_on`) VALUES
(1, 1, 1, 1, 'Enable', 1667826557, 1667826760),
(2, 2, 1, 1, 'Enable', 1668600307, 0),
(3, 3, 1, 1, 'Enable', 1668600319, 0),
(4, 4, 1, 1, 'Enable', 1668600335, 0),
(5, 5, 1, 1, 'Enable', 1668600346, 0),
(6, 6, 1, 1, 'Enable', 1668600357, 0),
(7, 7, 1, 1, 'Enable', 1668600370, 0),
(8, 8, 1, 1, 'Enable', 1668600381, 0),
(9, 9, 1, 1, 'Enable', 1668600391, 0),
(10, 10, 1, 1, 'Enable', 1668600401, 0),
(11, 11, 1, 2, 'Enable', 1668600415, 0),
(12, 12, 1, 2, 'Enable', 1668600427, 0),
(13, 13, 1, 2, 'Enable', 1668600438, 0),
(14, 14, 1, 2, 'Enable', 1668600453, 0),
(15, 15, 1, 2, 'Enable', 1668600982, 0),
(16, 16, 1, 2, 'Enable', 1668600991, 0),
(17, 17, 1, 2, 'Enable', 1668601001, 0),
(18, 18, 1, 2, 'Enable', 1668601013, 0),
(19, 19, 1, 2, 'Enable', 1668601022, 0),
(20, 20, 1, 2, 'Enable', 1668601034, 0),
(21, 21, 1, 3, 'Enable', 1668601045, 0),
(22, 22, 1, 3, 'Enable', 1668601059, 0),
(23, 23, 1, 3, 'Enable', 1668601070, 0),
(24, 24, 1, 3, 'Enable', 1668601085, 0),
(25, 25, 1, 3, 'Enable', 1668601097, 0),
(26, 26, 1, 3, 'Enable', 1668601110, 0),
(27, 27, 1, 3, 'Enable', 1668601120, 0),
(28, 28, 1, 3, 'Enable', 1668601131, 0),
(29, 29, 1, 3, 'Enable', 1668601141, 0),
(30, 30, 1, 3, 'Enable', 1668601152, 0),
(31, 31, 1, 4, 'Enable', 1668601166, 0),
(32, 32, 1, 4, 'Enable', 1668601181, 0),
(33, 33, 1, 4, 'Enable', 1668601195, 0),
(34, 34, 1, 4, 'Enable', 1668601205, 0),
(35, 35, 1, 4, 'Enable', 1668601217, 0),
(36, 36, 1, 4, 'Enable', 1668601227, 0),
(37, 38, 1, 4, 'Enable', 1668601237, 1668601251),
(38, 39, 1, 4, 'Enable', 1668601403, 0),
(39, 40, 1, 4, 'Enable', 1668601417, 0),
(40, 41, 1, 5, 'Enable', 1668601429, 0),
(41, 42, 1, 5, 'Enable', 1668601441, 0),
(42, 43, 1, 5, 'Enable', 1668601453, 0),
(43, 44, 1, 5, 'Enable', 1668601473, 0),
(44, 45, 1, 5, 'Enable', 1668601485, 0),
(45, 46, 1, 5, 'Enable', 1668601497, 0),
(46, 47, 1, 5, 'Enable', 1668601508, 0),
(47, 48, 1, 5, 'Enable', 1668601531, 0),
(48, 49, 1, 5, 'Enable', 1668601545, 0),
(49, 50, 1, 5, 'Enable', 1668601557, 0),
(50, 51, 2, 1, 'Enable', 1674647608, 1674904620);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sfms_acedemic_standard`
--
ALTER TABLE `sfms_acedemic_standard`
  ADD PRIMARY KEY (`acedemic_standard_id`);

--
-- Indexes for table `sfms_acedemic_year`
--
ALTER TABLE `sfms_acedemic_year`
  ADD PRIMARY KEY (`acedemic_year_id`);

--
-- Indexes for table `sfms_admin`
--
ALTER TABLE `sfms_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `sfms_fees`
--
ALTER TABLE `sfms_fees`
  ADD PRIMARY KEY (`fees_id`);

--
-- Indexes for table `sfms_fees_paid`
--
ALTER TABLE `sfms_fees_paid`
  ADD PRIMARY KEY (`fees_paid_id`);

--
-- Indexes for table `sfms_setting`
--
ALTER TABLE `sfms_setting`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `sfms_student`
--
ALTER TABLE `sfms_student`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `sfms_student_standard`
--
ALTER TABLE `sfms_student_standard`
  ADD PRIMARY KEY (`student_standard_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sfms_acedemic_standard`
--
ALTER TABLE `sfms_acedemic_standard`
  MODIFY `acedemic_standard_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sfms_acedemic_year`
--
ALTER TABLE `sfms_acedemic_year`
  MODIFY `acedemic_year_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sfms_admin`
--
ALTER TABLE `sfms_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sfms_fees`
--
ALTER TABLE `sfms_fees`
  MODIFY `fees_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `sfms_fees_paid`
--
ALTER TABLE `sfms_fees_paid`
  MODIFY `fees_paid_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `sfms_setting`
--
ALTER TABLE `sfms_setting`
  MODIFY `setting_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sfms_student`
--
ALTER TABLE `sfms_student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `sfms_student_standard`
--
ALTER TABLE `sfms_student_standard`
  MODIFY `student_standard_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
