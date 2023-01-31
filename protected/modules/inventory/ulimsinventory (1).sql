-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2017 at 04:58 AM
-- Server version: 5.5.34
-- PHP Version: 5.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ulimsinventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `consumptions`
--

CREATE TABLE IF NOT EXISTS `consumptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stockID` varchar(250) NOT NULL,
  `balance` double NOT NULL,
  `amountused` double NOT NULL,
  `dateconsumed` date NOT NULL,
  `withdrawnby` int(11) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `consumptions`
--

INSERT INTO `consumptions` (`id`, `stockID`, `balance`, `amountused`, `dateconsumed`, `withdrawnby`, `remarks`) VALUES
(7, 'aabbcc', 99, 1, '2017-03-28', 2, ''),
(8, 'aabbce', 29, 1, '2017-03-28', 2, ''),
(9, 'aabbcc', 0, 99, '2017-03-29', 2, ''),
(10, 'aabbce', 28, 1, '2017-03-29', 2, ''),
(11, 'aabbce', 26, 2, '2017-04-12', 2, ''),
(12, 'aabbcd', 47, 3, '2017-04-12', 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `reorderstocks`
--

CREATE TABLE IF NOT EXISTS `reorderstocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplyID` int(11) NOT NULL,
  `reorderdate` date NOT NULL,
  `daterequested` date NOT NULL,
  `datereceived` date NOT NULL,
  `supplierID` int(11) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE IF NOT EXISTS `stocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stockCode` varchar(50) NOT NULL,
  `supplyID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `manufacturer` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `quantity` double NOT NULL,
  `daterecieved` date NOT NULL,
  `dateopened` datetime NOT NULL,
  `expiry_date` date NOT NULL,
  `recieved_by` int(11) NOT NULL,
  `threshold_limit` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `batch_number` varchar(255) NOT NULL,
  `supplierID` int(11) NOT NULL,
  `amount` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `stockCode`, `supplyID`, `name`, `description`, `manufacturer`, `unit`, `quantity`, `daterecieved`, `dateopened`, `expiry_date`, `recieved_by`, `threshold_limit`, `location`, `batch_number`, `supplierID`, `amount`) VALUES
(15, 'aabbcc', 1, 'Hbw', 'sample description', 'abc incorporation', 'pcs', 0, '0000-00-00', '0000-00-00 00:00:00', '2043-10-05', 1, 20, 'side AB deck', '1', 1, 10),
(16, 'aabbcd', 1, 'Mongol pen', 'sample description', 'yinzhang inc', 'pcs', 47, '0000-00-00', '0000-00-00 00:00:00', '2043-10-05', 1, 6, 'side A deck', '1', 1, 5),
(17, 'aabbce', 2, 'Tissue paper', 'peper for mapapel', 'yun zhue inc', 'roll', 26, '0000-00-00', '0000-00-00 00:00:00', '2017-03-08', 1, 10, '', '1', 1, 8),
(18, 'hghghg', 1, 'asdasd', 'sfdsf', '1', 'pc', 1, '0000-00-00', '0000-00-00 00:00:00', '0000-00-00', 1, 5, '', '2', 1, 50);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `fax_number` varchar(255) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `description`, `address`, `contact_person`, `phone_number`, `fax_number`, `email_address`) VALUES
(1, 'printpapers', 'oers blah blah', 'dgdgfdgdd', 'fghfgh', '911-1024', '991-4024', 'fvghfh@gmail.com'),
(2, 'PNG inc', 'sample description', 'tumaga ZC', 'Ms. Marilou lao', '09171063708', 'none', 'mar.lou@yahoo.com.ph');

-- --------------------------------------------------------

--
-- Table structure for table `supplies`
--

CREATE TABLE IF NOT EXISTS `supplies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `lab` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `supplies`
--

INSERT INTO `supplies` (`id`, `name`, `lab`, `description`) VALUES
(1, 'ballpen', 1, 'Normal ballpen for daily office use'),
(2, 'paper', 3, '32rwerwe');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
