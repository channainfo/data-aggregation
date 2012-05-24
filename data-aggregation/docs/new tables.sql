-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 06, 2012 at 11:15 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `site_new_oi_dbo`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbleimain`
--

CREATE TABLE IF NOT EXISTS `tbleimain` (
  `ClinicID` char(10) NOT NULL,
  `DateVisit` datetime DEFAULT NULL,
  `DOB` datetime DEFAULT NULL,
  `Sex` char(13) DEFAULT NULL,
  `AddGuardian` char(13) DEFAULT NULL,
  `House` char(4) DEFAULT NULL,
  `Street` char(5) DEFAULT NULL,
  `Grou` char(6) DEFAULT NULL,
  `Village` char(20) DEFAULT NULL,
  `Commune` char(20) DEFAULT NULL,
  `District` char(20) DEFAULT NULL,
  `Province` char(20) DEFAULT NULL,
  `NameContPs1` char(30) DEFAULT NULL,
  `ContAddress1` char(150) DEFAULT NULL,
  `ContPhone1` char(20) DEFAULT NULL,
  `Fage` int(10) DEFAULT NULL,
  `FHIV` int(10) DEFAULT NULL,
  `Fstatus` int(10) DEFAULT NULL,
  `Mage` int(10) DEFAULT NULL,
  `MOI` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `MART` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `Hospital` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `Mstatus` int(10) DEFAULT NULL,
  `PlaceDelivery` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `PMTCT` char(10) CHARACTER SET utf8 DEFAULT NULL,
  `DateDelivery` datetime DEFAULT NULL,
  `StatusDelivery` int(10) DEFAULT NULL,
  `Lenght` char(5) CHARACTER SET utf8 DEFAULT NULL,
  `Weight` char(5) CHARACTER SET utf8 DEFAULT NULL,
  `Bpregnancy` int(10) DEFAULT NULL,
  `Dtpregnancy` int(10) DEFAULT NULL,
  `Dpregnancy` int(10) DEFAULT NULL,
  `ART` int(10) DEFAULT NULL,
  `ARVpro` int(10) DEFAULT NULL,
  `None` int(10) DEFAULT NULL,
  `SyrupNVP` int(10) DEFAULT NULL,
  `Cotrim` int(10) DEFAULT NULL,
  `OffYesNo` int(10) DEFAULT NULL,
  `TransferIn` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `HIVTest` int(10) DEFAULT NULL,
  PRIMARY KEY (`ClinicID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblevarv`
--

CREATE TABLE IF NOT EXISTS `tblevarv` (
  `ARV` char(25) DEFAULT NULL,
  `Form` char(10) DEFAULT NULL,
  `Dose` char(10) DEFAULT NULL,
  `Freq` char(10) DEFAULT NULL,
  `TotalTable` char(10) DEFAULT NULL,
  `Status` char(2) CHARACTER SET utf8 DEFAULT NULL,
  `Dat` datetime DEFAULT NULL,
  `Reason` char(40) CHARACTER SET utf8 DEFAULT NULL,
  `Remark` char(6) CHARACTER SET utf8 DEFAULT NULL,
  `Eid` char(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblevlostdead`
--

CREATE TABLE IF NOT EXISTS `tblevlostdead` (
  `ClinicID` char(10) NOT NULL,
  `Status` char(50) NOT NULL,
  `LDdate` datetime DEFAULT NULL,
  `EID` char(15) NOT NULL,
  PRIMARY KEY (`ClinicID`,`Status`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblevmain`
--

CREATE TABLE IF NOT EXISTS `tblevmain` (
  `ClinicID` char(10) DEFAULT NULL,
  `DateVisit` datetime DEFAULT NULL,
  `TypeVisit` char(10) DEFAULT NULL,
  `Temperat` char(5) DEFAULT NULL,
  `Pulse` char(10) DEFAULT NULL,
  `Resp` char(10) DEFAULT NULL,
  `Weight` char(5) DEFAULT NULL,
  `Height` char(5) DEFAULT NULL,
  `Head` char(5) DEFAULT NULL,
  `Malnutrition` int(10) DEFAULT NULL,
  `Mild` char(10) DEFAULT NULL,
  `Moderate` char(10) DEFAULT NULL,
  `Severe` char(10) DEFAULT NULL,
  `BCG` int(10) DEFAULT NULL,
  `Poli` int(10) DEFAULT NULL,
  `Measies` int(10) DEFAULT NULL,
  `VaccinOther` char(10) CHARACTER SET utf8 DEFAULT NULL,
  `ReceivingNVP` int(10) DEFAULT NULL,
  `ReceivingNone` int(10) DEFAULT NULL,
  `ARVProOther` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `Breast` int(10) DEFAULT NULL,
  `Formula` int(10) DEFAULT NULL,
  `Mixed` int(10) DEFAULT NULL,
  `food` int(10) DEFAULT NULL,
  `Complementatry` int(10) DEFAULT NULL,
  `DNA1` char(10) DEFAULT NULL,
  `AnDNA1` char(10) DEFAULT NULL,
  `DNA2` char(10) DEFAULT NULL,
  `AnDNA2` char(10) DEFAULT NULL,
  `DNA` char(10) DEFAULT NULL,
  `AnDNA` char(10) DEFAULT NULL,
  `DateCollected` datetime DEFAULT NULL,
  `DateSent` datetime DEFAULT NULL,
  `DateReceived` datetime DEFAULT NULL,
  `Result` char(10) DEFAULT NULL,
  `DateDelivered` datetime DEFAULT NULL,
  `ResultAntibody` char(10) DEFAULT NULL,
  `DateResult` datetime DEFAULT NULL,
  `DateApp` datetime DEFAULT NULL,
  `EID` char(15) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`EID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
