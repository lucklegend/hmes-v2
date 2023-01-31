-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2017 at 08:31 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ulimslab`
--

-- --------------------------------------------------------

--
-- Table structure for table `tagging`
--

CREATE TABLE IF NOT EXISTS `tagging` (
`id` int(11) NOT NULL,
  `analysisId` int(11) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `user_id` varchar(20) NOT NULL,
  `cancelled` int(1) NOT NULL,
  `cancelDate` date NOT NULL,
  `reason` varchar(50) NOT NULL,
  `cancelledBy` varbinary(20) NOT NULL,
  `disposedDate` date NOT NULL,
  `isoAccredited` int(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tagging`
--

INSERT INTO `tagging` (`id`, `analysisId`, `startDate`, `endDate`, `status`, `user_id`, `cancelled`, `cancelDate`, `reason`, `cancelledBy`, `disposedDate`, `isoAccredited`) VALUES
(136, 30888, '2017-06-11', '2017-06-11', 2, '39', 0, '0000-00-00', '', '', '0000-00-00', 1),
(137, 30889, '0000-00-00', '0000-00-00', 5, '40', 0, '0000-00-00', '', '', '0000-00-00', 0),
(138, 30890, '2017-06-11', '0000-00-00', 1, '44', 0, '0000-00-00', '', '', '0000-00-00', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tagging`
--
ALTER TABLE `tagging`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tagging`
--
ALTER TABLE `tagging`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=139;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
