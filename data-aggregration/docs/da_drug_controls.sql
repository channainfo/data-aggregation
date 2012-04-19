-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 19, 2012 at 10:09 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `server_oi_dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `da_drug_controls`
--

CREATE TABLE IF NOT EXISTS `da_drug_controls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `da_drug_controls`
--

INSERT INTO `da_drug_controls` (`id`, `name`, `description`, `created_at`, `modified_at`) VALUES
(1, '3TC', '3TC', '2012-04-06 10:00:25', '2012-04-06 10:00:25'),
(2, 'ABC', 'ABC', '2012-04-06 10:00:25', '2012-04-06 10:00:25'),
(3, 'AZT', 'AZT', '2012-04-06 10:00:25', '2012-04-06 10:00:25'),
(4, 'd4T', 'd4T', '2012-04-06 10:00:25', '2012-04-06 10:00:25'),
(5, 'ddl', 'ddI', '2012-04-06 10:00:25', '2012-04-06 10:00:25'),
(6, 'EFV', 'EFV', '2012-04-06 10:00:25', '2012-04-06 10:00:25'),
(7, 'IDV', 'IDV', '2012-04-06 10:00:25', '2012-04-06 10:00:25'),
(8, 'Kaletra(LPV/r)', 'Kaletra(LPV/r)', '2012-04-06 10:00:25', '2012-04-06 10:00:25'),
(9, 'LPV', 'LPV', '2012-04-06 10:00:25', '2012-04-06 10:00:25'),
(10, 'NFV', 'NFV', '2012-04-06 10:00:25', '2012-04-06 10:00:25'),
(11, 'NVP', 'NVP', '2012-04-06 10:00:25', '2012-04-06 10:00:25'),
(12, 'RTV', 'RTV', '2012-04-06 10:00:25', '2012-04-06 10:00:25'),
(13, 'SQV', 'SQV', '2012-04-06 10:00:25', '2012-04-06 10:00:25'),
(14, 'TDF', 'TDF', '2012-04-06 10:00:25', '2012-04-06 10:00:25');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
