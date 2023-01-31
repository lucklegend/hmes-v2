-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2017 at 04:59 AM
-- Server version: 5.5.34
-- PHP Version: 5.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ulimsequipment`
--

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE IF NOT EXISTS `equipment` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `equipmentID` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `lab` int(11) NOT NULL,
  `classificationID` int(11) NOT NULL,
  `specification` varchar(500) NOT NULL,
  `date_received` date NOT NULL,
  `received_by` int(11) NOT NULL,
  `amount` double NOT NULL,
  `supplier` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `usagestatus` tinyint(1) NOT NULL,
  `lengthofuse` date NOT NULL,
  `remarks` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`ID`, `equipmentID`, `name`, `description`, `lab`, `classificationID`, `specification`, `date_received`, `received_by`, `amount`, `supplier`, `status`, `usagestatus`, `lengthofuse`, `remarks`) VALUES
(9, '99999', 'Host', 'aaaaaa', 2, 2, 'qwerty', '2016-01-02', 2, 2, 2, 2, 1, '0000-00-00', 'asdasdasd'),
(10, '23121', 'Test tube rock', 'bbbbb', 3, 1, 'qwerty', '2016-03-02', 2, 5, 5, 1, 0, '0000-00-00', 'asdasdsa');

-- --------------------------------------------------------

--
-- Table structure for table `equipmentcalibration`
--

CREATE TABLE IF NOT EXISTS `equipmentcalibration` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `equipmentID` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `isdone` tinyint(1) NOT NULL,
  `certificate` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `equipmentcalibration`
--

INSERT INTO `equipmentcalibration` (`ID`, `user_id`, `equipmentID`, `date`, `isdone`, `certificate`) VALUES
(2, 0, '23121', '2016-03-31', 0, 'hhh.pdf'),
(3, 0, '23121', '2016-06-22', 1, '2016-04-28-97-Participants_half.pdf'),
(4, 2, '23121', '2016-04-30', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `equipmentclassification`
--

CREATE TABLE IF NOT EXISTS `equipmentclassification` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(200) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `equipmentclassification`
--

INSERT INTO `equipmentclassification` (`ID`, `name`, `description`) VALUES
(1, 'Testing Equipment', 'for testing'),
(2, 'Peripheral', 'just Peripheral');

-- --------------------------------------------------------

--
-- Table structure for table `equipmentmaintenance`
--

CREATE TABLE IF NOT EXISTS `equipmentmaintenance` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `equipmentID` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `type` tinyint(1) NOT NULL,
  `isdone` tinyint(1) NOT NULL DEFAULT '0',
  `maintenancedata` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `equipmentmaintenance`
--

INSERT INTO `equipmentmaintenance` (`ID`, `user_id`, `equipmentID`, `date`, `type`, `isdone`, `maintenancedata`) VALUES
(1, 0, '99999', '2016-03-30', 0, 1, NULL),
(2, 0, '23121', '2016-03-30', 0, 1, NULL),
(3, 0, '23121', '2016-04-07', 0, 1, '2016-04-28-75-resource-management-team.pdf'),
(4, 0, '99999', '2016-04-07', 0, 1, '2016-04-08-Book1.pdf'),
(5, 2, '23121', '2016-04-30', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `equipmentstatus`
--

CREATE TABLE IF NOT EXISTS `equipmentstatus` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `equipmentstatus`
--

INSERT INTO `equipmentstatus` (`ID`, `name`, `description`) VALUES
(1, 'Available', 'Sample description here'),
(2, 'Not Available', 'Equipment didnt use');

-- --------------------------------------------------------

--
-- Table structure for table `equipmentusage`
--

CREATE TABLE IF NOT EXISTS `equipmentusage` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `equipmentID` varchar(50) NOT NULL,
  `status` int(11) NOT NULL,
  `startdate` datetime NOT NULL,
  `enddate` datetime NOT NULL,
  `remarks` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `equipmentusage`
--

INSERT INTO `equipmentusage` (`ID`, `user_id`, `equipmentID`, `status`, `startdate`, `enddate`, `remarks`) VALUES
(14, 2, '23121', 1, '2016-04-22 04:32:44', '2016-04-22 04:37:46', 'Start Usage: asdasdfasf\n End Usage: asdfsfdsfdsfs'),
(15, 2, '23121', 1, '2016-04-22 08:00:10', '2016-04-22 08:00:42', 'Start Usage: Hahahaha\n\n End Usage: Wew hehehe\n');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
