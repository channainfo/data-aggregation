-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 25, 2012 at 11:21 AM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `server_oi_dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblaiarvtreatment`
--

CREATE TABLE IF NOT EXISTS `tblaiarvtreatment` (
  `ClinicID` char(10) DEFAULT NULL,
  `Detaildrugtreat` char(16) DEFAULT NULL,
  `Clinic` char(20) DEFAULT NULL,
  `StartDate` datetime DEFAULT NULL,
  `StopDate` datetime DEFAULT NULL,
  `ReasonStop` char(40) DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblAIARVTreatment_tblAImain` (`ClinicID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblaiarvtreatment`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblaicotrimo`
--

CREATE TABLE IF NOT EXISTS `tblaicotrimo` (
  `ClinicID` char(10) NOT NULL,
  `StartDate` datetime DEFAULT NULL,
  `StopDate` datetime DEFAULT NULL,
  `ReasonStop` char(45) DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblAICotrimo_tblAImain` (`ClinicID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblaicotrimo`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblaidrugallergy`
--

CREATE TABLE IF NOT EXISTS `tblaidrugallergy` (
  `ClinicID` char(10) DEFAULT NULL,
  `DrugAllergy` char(15) DEFAULT NULL,
  `Allergy` char(23) DEFAULT NULL,
  `Dat` datetime DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblAIDrugAllergy_tblAImain` (`ClinicID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblaidrugallergy`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblaifamily`
--

CREATE TABLE IF NOT EXISTS `tblaifamily` (
  `ClinicID` char(10) NOT NULL,
  `RelativeSpoPart` char(12) DEFAULT NULL,
  `Age` int(10) DEFAULT NULL,
  `HivStatus` char(2) DEFAULT NULL,
  `Status` char(8) DEFAULT NULL,
  `Mother` char(10) DEFAULT NULL,
  `Child` char(12) DEFAULT NULL,
  `ARV` char(10) DEFAULT NULL,
  `OIART` char(10) DEFAULT NULL,
  `ReceiARV` char(8) DEFAULT NULL,
  `HostoryTB` char(8) DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblAIFamily_tblAImain` (`ClinicID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblaifamily`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblaifluconazole`
--

CREATE TABLE IF NOT EXISTS `tblaifluconazole` (
  `ClinicID` char(10) NOT NULL,
  `StartDate` datetime DEFAULT NULL,
  `StopDate` datetime DEFAULT NULL,
  `ReasonStop` char(45) DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblAIFluconazole_tblAImain` (`ClinicID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblaifluconazole`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblaiisoniazid`
--

CREATE TABLE IF NOT EXISTS `tblaiisoniazid` (
  `ClinicID` char(10) NOT NULL,
  `StartDate` datetime DEFAULT NULL,
  `StopDate` datetime DEFAULT NULL,
  `ReasonStop` char(45) DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblAIIsoniazid_tblAImain` (`ClinicID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblaiisoniazid`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblaimain`
--

CREATE TABLE IF NOT EXISTS `tblaimain` (
  `CLinicID` char(10) NOT NULL,
  `DateFirstVisit` datetime DEFAULT NULL,
  `Age` int(10) NOT NULL,
  `Sex` char(13) NOT NULL,
  `House` char(10) DEFAULT NULL,
  `Street` char(10) DEFAULT NULL,
  `Grou` char(10) DEFAULT NULL,
  `Village` char(50) DEFAULT NULL,
  `Commune` char(50) DEFAULT NULL,
  `District` char(50) DEFAULT NULL,
  `Province` char(50) DEFAULT NULL,
  `Phone` char(20) DEFAULT NULL,
  `NameContPs1` char(60) CHARACTER SET utf8 DEFAULT NULL,
  `ContAddress1` longtext,
  `ContPhone1` char(20) DEFAULT NULL,
  `NameContPs2` char(60) DEFAULT NULL,
  `ContAddress2` longtext,
  `ContPhone2` char(20) DEFAULT NULL,
  `MaritalStatus` char(25) DEFAULT NULL,
  `Occupation` char(60) DEFAULT NULL,
  `Education` char(12) DEFAULT NULL,
  `Rea` char(5) DEFAULT NULL,
  `Writ` char(5) DEFAULT NULL,
  `Referredfrom` char(16) DEFAULT NULL,
  `NameLocationHBC` char(25) DEFAULT NULL,
  `DateConfPosHIV` datetime DEFAULT NULL,
  `DateLastNegHIV` datetime DEFAULT NULL,
  `OffYesNo` char(4) DEFAULT NULL,
  `OffTransferin` char(45) DEFAULT NULL,
  `DateStaART` datetime DEFAULT NULL,
  `ArtNumber` char(10) DEFAULT NULL,
  `TbPasMediHis` char(8) DEFAULT NULL,
  `Gravida` char(3) DEFAULT NULL,
  `Para` char(3) DEFAULT NULL,
  `DailyAlcohol` char(8) DEFAULT NULL,
  `Tabacco` char(8) DEFAULT NULL,
  `Idu` char(8) DEFAULT NULL,
  `Yama` char(8) DEFAULT NULL,
  `PreviousARV` char(8) DEFAULT NULL,
  `Precontrimoxazole` char(8) DEFAULT NULL,
  `Prefluconzazole` char(8) DEFAULT NULL,
  `PreIsoniazid` char(8) DEFAULT NULL,
  `PreTranditional` char(8) DEFAULT NULL,
  `DrugAllergy` char(8) DEFAULT NULL,
  `Vct` char(50) DEFAULT NULL,
  `PClinicID` char(10) DEFAULT NULL,
  `ID` char(4) NOT NULL,
  PRIMARY KEY (`CLinicID`,`ID`),
  KEY `FK_tblAImain_tblClinic` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblaimain`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblaiothermedical`
--

CREATE TABLE IF NOT EXISTS `tblaiothermedical` (
  `ClinicID` char(10) NOT NULL,
  `Detaildrugtreat` char(45) DEFAULT NULL,
  `Clinic` char(16) DEFAULT NULL,
  `StartDate` datetime NOT NULL,
  `StopDate` datetime DEFAULT NULL,
  `ReasonStop` char(50) DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblAIOtherMedical_tblAImain` (`ClinicID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblaiothermedical`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblaiothpasmedical`
--

CREATE TABLE IF NOT EXISTS `tblaiothpasmedical` (
  `ClinicID` char(10) DEFAULT NULL,
  `HIVRelatill` char(70) DEFAULT NULL,
  `DateOn` datetime DEFAULT NULL,
  `OthNotHIV` char(23) DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblAIOthPasMedical_tblAImain` (`ClinicID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblaiothpasmedical`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblaitbpastmedical`
--

CREATE TABLE IF NOT EXISTS `tblaitbpastmedical` (
  `ClinicID` char(10) NOT NULL,
  `Ptb` char(4) DEFAULT NULL,
  `EPTB` char(10) DEFAULT NULL,
  `DateOnSick` datetime DEFAULT NULL,
  `TbTreatment` char(8) DEFAULT NULL,
  `DoTreatment` datetime DEFAULT NULL,
  `TreatmentOut` char(20) DEFAULT NULL,
  `Datecomptreat` datetime DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblAITBPastMedical_tblAImain` (`ClinicID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblaitbpastmedical`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblaitraditional`
--

CREATE TABLE IF NOT EXISTS `tblaitraditional` (
  `ClinicID` char(10) NOT NULL,
  `StartDate` datetime DEFAULT NULL,
  `StopDate` datetime DEFAULT NULL,
  `ReasonStop` char(45) DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblAITraditional_tblAImain` (`ClinicID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblaitraditional`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblappoint`
--

CREATE TABLE IF NOT EXISTS `tblappoint` (
  `AV_ID` char(15) NOT NULL,
  `Atime` char(2) DEFAULT NULL,
  `Tosee` char(17) DEFAULT NULL,
  `Doct` char(21) DEFAULT NULL,
  `Att` char(1) DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblAppoint_tblAVmain` (`AV_ID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblappoint`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblart`
--

CREATE TABLE IF NOT EXISTS `tblart` (
  `ClinicID` char(10) NOT NULL,
  `ART` char(12) DEFAULT NULL,
  `ARTDate` datetime DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblART_tblAImain` (`ClinicID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblart`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblavarv`
--

CREATE TABLE IF NOT EXISTS `tblavarv` (
  `ARV` char(16) DEFAULT NULL,
  `Dose` char(20) DEFAULT NULL,
  `Quantity` char(10) DEFAULT NULL,
  `Freq` char(4) DEFAULT NULL,
  `Form` char(10) DEFAULT NULL,
  `Status` char(2) DEFAULT NULL,
  `Dat` datetime DEFAULT NULL,
  `Reason` char(40) DEFAULT NULL,
  `Remark` char(6) DEFAULT NULL,
  `AV_ID` char(15) NOT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblAVARV_tblAVmain` (`AV_ID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblavarv`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblavlostdead`
--

CREATE TABLE IF NOT EXISTS `tblavlostdead` (
  `ClinicID` char(10) NOT NULL,
  `Status` char(50) NOT NULL,
  `LDdate` datetime DEFAULT NULL,
  `AV_ID` char(15) NOT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblAVLostDead_tblAVmain` (`AV_ID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblavlostdead`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblavmain`
--

CREATE TABLE IF NOT EXISTS `tblavmain` (
  `ClinicID` char(10) NOT NULL,
  `DateVisit` datetime DEFAULT NULL,
  `TypeVisit` char(10) DEFAULT NULL,
  `Weight` char(5) DEFAULT NULL,
  `Height` char(5) DEFAULT NULL,
  `Pulse` char(4) DEFAULT NULL,
  `Resp` char(4) DEFAULT NULL,
  `Blood` char(8) DEFAULT NULL,
  `Temperature` char(5) DEFAULT NULL,
  `STIPrevent` char(2) DEFAULT NULL,
  `ARTAdherence` char(2) DEFAULT NULL,
  `BirthSpacing` char(2) DEFAULT NULL,
  `TBinfection` char(2) DEFAULT NULL,
  `Partner` char(2) DEFAULT NULL,
  `Advice` char(2) DEFAULT NULL,
  `Cough` char(2) DEFAULT NULL,
  `Fever` char(2) DEFAULT NULL,
  `Drenching` char(2) DEFAULT NULL,
  `FamilyNoYes` char(3) DEFAULT NULL,
  `FamilyPlan` char(10) DEFAULT NULL,
  `Hivpreven` char(16) DEFAULT NULL,
  `HospitlastVisit` char(3) DEFAULT NULL,
  `NumberDay` char(3) DEFAULT NULL,
  `CauseHispital` char(10) DEFAULT NULL,
  `MissARV` char(4) DEFAULT NULL,
  `Mtime` char(3) DEFAULT NULL,
  `Condom` char(2) DEFAULT NULL,
  `Ncondom` char(3) DEFAULT NULL,
  `Asymptomatic` char(8) DEFAULT NULL,
  `PGeneralised` char(8) DEFAULT NULL,
  `weightLoss` char(8) DEFAULT NULL,
  `Minor` char(8) DEFAULT NULL,
  `HerpesZ` char(8) DEFAULT NULL,
  `Recurrent` char(8) DEFAULT NULL,
  `Angular` char(8) DEFAULT NULL,
  `Seborrhoeic` char(8) DEFAULT NULL,
  `Oral` char(8) DEFAULT NULL,
  `Papular` char(8) DEFAULT NULL,
  `Fungal` char(8) DEFAULT NULL,
  `ULow` char(8) DEFAULT NULL,
  `Uchronic` char(8) DEFAULT NULL,
  `Ufever` char(8) DEFAULT NULL,
  `Ocandidiasis` char(8) DEFAULT NULL,
  `Ohairy` char(8) DEFAULT NULL,
  `Pulmonary` char(8) DEFAULT NULL,
  `Bacterial` char(8) DEFAULT NULL,
  `Severe` char(8) DEFAULT NULL,
  `Acute` char(8) DEFAULT NULL,
  `Anaemia` char(8) DEFAULT NULL,
  `HIVwast` char(8) DEFAULT NULL,
  `Pneumocystis` char(8) DEFAULT NULL,
  `Pneumonia` char(8) DEFAULT NULL,
  `Cryptococcal` char(8) DEFAULT NULL,
  `Atypical` char(8) DEFAULT NULL,
  `Extrapulmonary` char(8) DEFAULT NULL,
  `Candidiasis` char(8) DEFAULT NULL,
  `HSV` char(8) DEFAULT NULL,
  `Disseminated` char(8) DEFAULT NULL,
  `Cryptonsoridiosis` char(8) DEFAULT NULL,
  `Cytome` char(8) DEFAULT NULL,
  `tuberculous` char(8) DEFAULT NULL,
  `Kaposi` char(8) DEFAULT NULL,
  `Lymphoma` char(8) DEFAULT NULL,
  `HIV` char(8) DEFAULT NULL,
  `Progressive` char(8) DEFAULT NULL,
  `Toxoplasmosis` char(8) DEFAULT NULL,
  `Typhoid` char(8) DEFAULT NULL,
  `Associated` char(8) DEFAULT NULL,
  `Chronic` char(8) DEFAULT NULL,
  `Mycosis` char(8) DEFAULT NULL,
  `Isoporiasis` char(8) DEFAULT NULL,
  `Invasive` char(8) DEFAULT NULL,
  `Cryptosporidiosis` char(8) DEFAULT NULL,
  `Who` char(1) DEFAULT NULL,
  `TST` char(2) DEFAULT NULL,
  `NTST` char(4) DEFAULT NULL,
  `TestID` char(15) DEFAULT NULL,
  `EligibleART` char(3) DEFAULT NULL,
  `funct` char(15) DEFAULT NULL,
  `Pregnancy` char(12) DEFAULT NULL,
  `PregnantStatus` char(2) DEFAULT NULL,
  `EDDate` datetime DEFAULT NULL,
  `EligiblePro` char(2) DEFAULT NULL,
  `Refer` char(20) DEFAULT NULL,
  `NextApp` datetime DEFAULT NULL,
  `AV_ID` char(15) NOT NULL,
  `ARTNum` char(11) DEFAULT NULL,
  `ID` char(4) NOT NULL,
  PRIMARY KEY (`AV_ID`,`ID`),
  KEY `FK_tblAVmain_tblAImain` (`ClinicID`,`ID`),
  KEY `FK_tblAVmain_tblPatientTest` (`TestID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblavmain`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblavoidrugs`
--

CREATE TABLE IF NOT EXISTS `tblavoidrugs` (
  `ARV` char(15) DEFAULT NULL,
  `Dose` char(5) DEFAULT NULL,
  `Quantity` char(4) DEFAULT NULL,
  `Freq` char(3) DEFAULT NULL,
  `Form` char(10) DEFAULT NULL,
  `Status` char(2) DEFAULT NULL,
  `Dat` datetime DEFAULT NULL,
  `Reason` char(15) DEFAULT NULL,
  `Remark` char(5) DEFAULT NULL,
  `AV_ID` char(15) NOT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblAVOIdrugs_tblAVmain` (`AV_ID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblavoidrugs`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblavtb`
--

CREATE TABLE IF NOT EXISTS `tblavtb` (
  `IFPTB` char(5) DEFAULT NULL,
  `PTB` char(3) DEFAULT NULL,
  `Sbx` char(5) DEFAULT NULL,
  `IfEPTB` char(5) DEFAULT NULL,
  `EPTB` char(27) DEFAULT NULL,
  `TBTreatment` char(2) DEFAULT NULL,
  `TBdate` datetime DEFAULT NULL,
  `AV_ID` char(15) NOT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblAVTB_tblAVmain` (`AV_ID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblavtb`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblavtbdrugs`
--

CREATE TABLE IF NOT EXISTS `tblavtbdrugs` (
  `ARV` char(14) DEFAULT NULL,
  `Dose` char(10) DEFAULT NULL,
  `Quantity` char(10) DEFAULT NULL,
  `Freq` char(4) DEFAULT NULL,
  `Form` char(10) DEFAULT NULL,
  `Status` char(2) DEFAULT NULL,
  `Dat` datetime NOT NULL,
  `Reason` char(40) DEFAULT NULL,
  `Remark` char(10) DEFAULT NULL,
  `AV_ID` char(15) NOT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblAVTBdrugs_tblAVmain` (`AV_ID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblavtbdrugs`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblcart`
--

CREATE TABLE IF NOT EXISTS `tblcart` (
  `ClinicID` char(10) NOT NULL,
  `ART` char(12) DEFAULT NULL,
  `ARTDate` datetime DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblCART_tblCIMain` (`ClinicID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcart`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblciarvtreatment`
--

CREATE TABLE IF NOT EXISTS `tblciarvtreatment` (
  `ClinicID` char(10) DEFAULT NULL,
  `Detaildrugtreat` char(15) DEFAULT NULL,
  `Clinic` char(16) DEFAULT NULL,
  `StartDate` datetime DEFAULT NULL,
  `StopDate` datetime DEFAULT NULL,
  `ReasonStop` char(36) DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblCIARVTreatment_tblCIMain` (`ClinicID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblciarvtreatment`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblcicotrimo`
--

CREATE TABLE IF NOT EXISTS `tblcicotrimo` (
  `ClinicID` char(10) NOT NULL,
  `StartDate` datetime DEFAULT NULL,
  `StopDate` datetime DEFAULT NULL,
  `ReasonStop` char(36) DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblCICotrimo_tblCIMain` (`ClinicID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcicotrimo`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblcidrugallergy`
--

CREATE TABLE IF NOT EXISTS `tblcidrugallergy` (
  `ClinicID` char(10) DEFAULT NULL,
  `DrugAllergy` char(15) DEFAULT NULL,
  `Allergy` char(23) DEFAULT NULL,
  `Dat` datetime DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblCIDrugAllergy_tblCIMain` (`ClinicID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcidrugallergy`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblcifamily`
--

CREATE TABLE IF NOT EXISTS `tblcifamily` (
  `ClinicID` char(10) NOT NULL,
  `RelativeSpoPart` char(7) DEFAULT NULL,
  `Age` int(10) DEFAULT NULL,
  `HivStatus` char(2) DEFAULT NULL,
  `Status` char(5) DEFAULT NULL,
  `Mother` char(8) DEFAULT NULL,
  `Child` char(13) DEFAULT NULL,
  `ARV` char(7) DEFAULT NULL,
  `OIART` char(5) DEFAULT NULL,
  `ReceiARV` char(7) DEFAULT NULL,
  `HostoryTB` char(7) DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblCIFamily_tblCIMain` (`ClinicID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcifamily`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblcifluconazole`
--

CREATE TABLE IF NOT EXISTS `tblcifluconazole` (
  `ClinicID` char(10) NOT NULL,
  `StartDate` datetime DEFAULT NULL,
  `StopDate` datetime DEFAULT NULL,
  `ReasonStop` char(36) DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblCIFluconazole_tblCIMain` (`ClinicID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcifluconazole`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblcimain`
--

CREATE TABLE IF NOT EXISTS `tblcimain` (
  `ClinicID` char(10) NOT NULL,
  `DateVisit` datetime DEFAULT NULL,
  `DOB` datetime DEFAULT NULL,
  `Sex` char(13) DEFAULT NULL,
  `AddGuardian` char(13) DEFAULT NULL,
  `House` char(10) DEFAULT NULL,
  `Street` char(10) DEFAULT NULL,
  `Grou` char(6) DEFAULT NULL,
  `Village` char(60) DEFAULT NULL,
  `Commune` char(60) DEFAULT NULL,
  `District` char(60) DEFAULT NULL,
  `Province` char(25) DEFAULT NULL,
  `Phone` char(19) DEFAULT NULL,
  `NameContPs1` char(60) DEFAULT NULL,
  `ContAddress1` char(60) DEFAULT NULL,
  `ContPhone1` char(30) DEFAULT NULL,
  `NameContPs2` char(20) DEFAULT NULL,
  `ContAddress2` char(40) DEFAULT NULL,
  `ContPhone2` char(30) DEFAULT NULL,
  `ChildStatus` char(23) DEFAULT NULL,
  `FatherStatus` char(29) DEFAULT NULL,
  `MotherStatus` char(29) DEFAULT NULL,
  `Education` char(12) DEFAULT NULL,
  `Refer` char(14) DEFAULT NULL,
  `HBCTeam` char(19) DEFAULT NULL,
  `FTesDate` datetime DEFAULT NULL,
  `FAge` int(10) DEFAULT NULL,
  `FOption` char(13) DEFAULT NULL,
  `FResult` char(8) DEFAULT NULL,
  `STestDate` datetime DEFAULT NULL,
  `SAge` int(10) DEFAULT NULL,
  `SOption` char(13) DEFAULT NULL,
  `SResult` char(13) DEFAULT NULL,
  `OffYesNo` char(3) DEFAULT NULL,
  `OfficeIn` char(45) DEFAULT NULL,
  `DateARV` datetime DEFAULT NULL,
  `ARVNumber` char(10) DEFAULT NULL,
  `TBPastMedical` char(7) DEFAULT NULL,
  `Vaccinat` char(36) DEFAULT NULL,
  `InfantNutrit` char(28) DEFAULT NULL,
  `PreviousARV` char(7) DEFAULT NULL,
  `Precontrimoxazole` char(7) DEFAULT NULL,
  `Prefluconzazole` char(7) DEFAULT NULL,
  `PreTranditional` char(7) DEFAULT NULL,
  `DrugAllergy` char(7) NOT NULL,
  `EClinicId` char(10) DEFAULT NULL,
  `ID` char(4) NOT NULL,
  PRIMARY KEY (`ClinicID`,`ID`),
  KEY `FK_tblCIMain_tblClinic` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcimain`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblciothpastmedical`
--

CREATE TABLE IF NOT EXISTS `tblciothpastmedical` (
  `ClinicID` char(10) DEFAULT NULL,
  `HIV` char(148) DEFAULT NULL,
  `DateOnset` datetime DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblCIOthPastMedical_tblCIMain` (`ClinicID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblciothpastmedical`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblcitbpastmedical`
--

CREATE TABLE IF NOT EXISTS `tblcitbpastmedical` (
  `ClinicID` char(10) DEFAULT NULL,
  `TypePTB` char(3) DEFAULT NULL,
  `EPTB` char(5) DEFAULT NULL,
  `DateOnSick` datetime DEFAULT NULL,
  `TBTreat` char(7) DEFAULT NULL,
  `Treatment` char(22) DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblCITBPastMedical_tblCIMain` (`ClinicID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcitbpastmedical`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblcitraditional`
--

CREATE TABLE IF NOT EXISTS `tblcitraditional` (
  `ClinicID` char(10) NOT NULL,
  `StartDate` datetime DEFAULT NULL,
  `StopDate` datetime DEFAULT NULL,
  `ReasonStop` char(36) DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblCITraditional_tblCIMain` (`ClinicID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcitraditional`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblclinic`
--

CREATE TABLE IF NOT EXISTS `tblclinic` (
  `Clinic` char(50) DEFAULT NULL,
  `ClinicKh` char(60) DEFAULT NULL,
  `ART` char(4) NOT NULL,
  `District` char(40) DEFAULT NULL,
  `OD` char(60) DEFAULT NULL,
  `Province` char(20) DEFAULT NULL,
  PRIMARY KEY (`ART`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblclinic`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblcommune`
--

CREATE TABLE IF NOT EXISTS `tblcommune` (
  `IDCommune` int(10) NOT NULL,
  `IDDistrict` int(10) NOT NULL,
  `CommuneEN` varchar(100) NOT NULL,
  PRIMARY KEY (`IDCommune`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcommune`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblcvarv`
--

CREATE TABLE IF NOT EXISTS `tblcvarv` (
  `ARV` char(20) DEFAULT NULL,
  `Form` char(10) DEFAULT NULL,
  `Dose` char(10) DEFAULT NULL,
  `Freq` char(10) DEFAULT NULL,
  `TotalTable` char(10) DEFAULT NULL,
  `Status` char(2) CHARACTER SET utf8 DEFAULT NULL,
  `Dat` datetime DEFAULT NULL,
  `Reason` char(40) CHARACTER SET utf8 DEFAULT NULL,
  `Remark` char(6) CHARACTER SET utf8 DEFAULT NULL,
  `Cid` char(15) DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblCVARV_tblCVMain` (`Cid`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcvarv`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblcvarvoi`
--

CREATE TABLE IF NOT EXISTS `tblcvarvoi` (
  `ARVOI` char(25) DEFAULT NULL,
  `Status` char(5) DEFAULT NULL,
  `Dat` datetime DEFAULT NULL,
  `Reason` char(15) DEFAULT NULL,
  `Cid` char(15) DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblCVARVOI_tblCVMain` (`Cid`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcvarvoi`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblcvlostdead`
--

CREATE TABLE IF NOT EXISTS `tblcvlostdead` (
  `ClinicID` char(10) NOT NULL,
  `Status` char(50) NOT NULL,
  `LDdate` datetime DEFAULT NULL,
  `Cid` char(15) NOT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblCVLostDead_tblCVMain` (`Cid`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcvlostdead`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblcvmain`
--

CREATE TABLE IF NOT EXISTS `tblcvmain` (
  `ClinicID` char(10) NOT NULL,
  `DateVisit` datetime DEFAULT NULL,
  `TypeVisit` char(10) DEFAULT NULL,
  `Weight` char(5) DEFAULT NULL,
  `Height` char(5) DEFAULT NULL,
  `Pulse` char(10) DEFAULT NULL,
  `Resp` char(10) DEFAULT NULL,
  `Blood` char(10) DEFAULT NULL,
  `Temperat` char(5) DEFAULT NULL,
  `Malnutrition` char(3) DEFAULT NULL,
  `WH` char(22) DEFAULT NULL,
  `HospitalLastVisit` char(3) DEFAULT NULL,
  `NumDay` int(10) DEFAULT NULL,
  `Causses` char(30) DEFAULT NULL,
  `MissMonth` char(3) DEFAULT NULL,
  `MTime` int(10) DEFAULT NULL,
  `MissDay` char(3) DEFAULT NULL,
  `DTime` int(10) DEFAULT NULL,
  `Eye` char(2) DEFAULT NULL,
  `Ear` char(2) DEFAULT NULL,
  `Mouth` char(2) DEFAULT NULL,
  `Skin` char(2) DEFAULT NULL,
  `LN4` char(2) DEFAULT NULL,
  `Heart` char(2) DEFAULT NULL,
  `Lung` char(2) DEFAULT NULL,
  `Abdomen` char(2) DEFAULT NULL,
  `Genital` char(2) DEFAULT NULL,
  `Neurology` char(2) DEFAULT NULL,
  `Psychological` char(2) DEFAULT NULL,
  `Asymptomatic` char(7) DEFAULT NULL,
  `Persistent` char(7) DEFAULT NULL,
  `Hepatos` char(7) DEFAULT NULL,
  `Papula` char(7) DEFAULT NULL,
  `Seborrheic` char(7) DEFAULT NULL,
  `Fungal` char(7) DEFAULT NULL,
  `Angular` char(7) DEFAULT NULL,
  `Lineal` char(7) DEFAULT NULL,
  `Molluscum` char(7) DEFAULT NULL,
  `Human` char(7) DEFAULT NULL,
  `RecurrentOral` char(7) DEFAULT NULL,
  `Parotid` char(7) DEFAULT NULL,
  `Herpes` char(7) DEFAULT NULL,
  `RecurrentRespiratory` char(7) DEFAULT NULL,
  `UnModerate` char(7) DEFAULT NULL,
  `UnPersistent` char(7) DEFAULT NULL,
  `UnPersistentFever` char(7) DEFAULT NULL,
  `OralCandidiasis` char(7) DEFAULT NULL,
  `OralHairy` char(7) DEFAULT NULL,
  `Pulmonary` char(7) DEFAULT NULL,
  `Severe` char(7) DEFAULT NULL,
  `AcuteNecrotizing` char(7) DEFAULT NULL,
  `Lymphoid` char(7) DEFAULT NULL,
  `ChronicHIV` char(7) DEFAULT NULL,
  `UnAnemia` char(7) DEFAULT NULL,
  `UnSevere` char(7) DEFAULT NULL,
  `Pneumocystis` char(7) DEFAULT NULL,
  `RecurrentSevere` char(7) DEFAULT NULL,
  `ChronicHerpes` char(7) DEFAULT NULL,
  `Oesophageal` char(7) DEFAULT NULL,
  `Extrapulmonary` char(7) DEFAULT NULL,
  `Kaposi` char(7) DEFAULT NULL,
  `CMVRetinitis` char(7) DEFAULT NULL,
  `CNSToxoplasmosis` char(7) DEFAULT NULL,
  `Cryptococcal` char(7) DEFAULT NULL,
  `HIVEncephalopathy` char(7) DEFAULT NULL,
  `Progressive` char(7) DEFAULT NULL,
  `AnyDisseminat` char(7) DEFAULT NULL,
  `Candida` char(7) DEFAULT NULL,
  `Disseminated` char(7) DEFAULT NULL,
  `Cryptosporidiosis` char(7) DEFAULT NULL,
  `Cerebral` char(7) DEFAULT NULL,
  `AcquiredHIV` char(7) DEFAULT NULL,
  `HIVAssociat` char(7) DEFAULT NULL,
  `WHO` char(2) DEFAULT NULL,
  `TestID` char(15) DEFAULT NULL,
  `ART` char(3) DEFAULT NULL,
  `Funct` char(13) DEFAULT NULL,
  `TBdrugs` char(13) DEFAULT NULL,
  `Refer` char(30) DEFAULT NULL,
  `NexApp` datetime DEFAULT NULL,
  `Cid` char(15) NOT NULL,
  `ARTNum` char(11) DEFAULT NULL,
  `ID` char(4) NOT NULL,
  PRIMARY KEY (`Cid`,`ID`),
  KEY `FK_tblCVMain_tblCIMain` (`ClinicID`,`ID`),
  KEY `FK_tblCVMain_tblPatientTest` (`TestID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcvmain`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblcvoi`
--

CREATE TABLE IF NOT EXISTS `tblcvoi` (
  `OI` char(20) DEFAULT NULL,
  `Form` char(10) DEFAULT NULL,
  `Dose` char(10) DEFAULT NULL,
  `Freq` char(10) DEFAULT NULL,
  `TotalTable` char(10) DEFAULT NULL,
  `Status` char(2) CHARACTER SET utf8 DEFAULT NULL,
  `Dat` datetime DEFAULT NULL,
  `Reason` char(40) CHARACTER SET utf8 DEFAULT NULL,
  `Remark` char(6) CHARACTER SET utf8 DEFAULT NULL,
  `Cid` char(15) DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblCVOI_tblCVMain` (`Cid`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcvoi`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblcvtb`
--

CREATE TABLE IF NOT EXISTS `tblcvtb` (
  `IFPTB` char(5) DEFAULT NULL,
  `PTB` char(3) DEFAULT NULL,
  `Sbx` char(5) DEFAULT NULL,
  `IfEPTB` char(5) DEFAULT NULL,
  `EPTB` char(16) DEFAULT NULL,
  `Cid` char(15) DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tblCVTB_tblCVMain` (`Cid`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcvtb`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblcvtbdrugs`
--

CREATE TABLE IF NOT EXISTS `tblcvtbdrugs` (
  `ARV` char(14) DEFAULT NULL,
  `Dose` char(10) DEFAULT NULL,
  `Quantity` char(10) DEFAULT NULL,
  `Freq` char(4) DEFAULT NULL,
  `Form` char(10) DEFAULT NULL,
  `Status` char(2) DEFAULT NULL,
  `Dat` datetime NOT NULL,
  `Reason` char(40) DEFAULT NULL,
  `Remark` char(10) DEFAULT NULL,
  `Cid` char(15) NOT NULL,
  `ID` char(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcvtbdrugs`
--


-- --------------------------------------------------------

--
-- Table structure for table `tbldistrict`
--

CREATE TABLE IF NOT EXISTS `tbldistrict` (
  `IDDistrict` int(10) NOT NULL,
  `IDProvince` int(10) NOT NULL,
  `DistrictEN` varchar(100) NOT NULL,
  PRIMARY KEY (`IDDistrict`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbldistrict`
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
  `ID` char(4) NOT NULL,
  PRIMARY KEY (`ClinicID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbleimain`
--


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
  `Eid` char(15) NOT NULL,
  `ID` char(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblevarv`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblevlostdead`
--

CREATE TABLE IF NOT EXISTS `tblevlostdead` (
  `ClinicID` char(10) NOT NULL,
  `Status` char(50) NOT NULL,
  `LDdate` datetime DEFAULT NULL,
  `EID` char(15) NOT NULL,
  `ID` char(4) NOT NULL,
  PRIMARY KEY (`ClinicID`,`Status`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblevlostdead`
--


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
  `ID` char(4) NOT NULL,
  PRIMARY KEY (`EID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblevmain`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblpatienttest`
--

CREATE TABLE IF NOT EXISTS `tblpatienttest` (
  `TestID` char(15) NOT NULL,
  `ClinicID` char(10) DEFAULT NULL,
  `Dat` datetime DEFAULT NULL,
  `CD4` char(6) DEFAULT NULL,
  `CD` char(6) DEFAULT NULL,
  `CD8` char(6) DEFAULT NULL,
  `HIVLoad` char(10) DEFAULT NULL,
  `HIVLog` char(6) DEFAULT NULL,
  `HIVAb` char(13) DEFAULT NULL,
  `HBsAg` char(9) DEFAULT NULL,
  `HCVPCR` char(9) DEFAULT NULL,
  `HBeAg` char(9) DEFAULT NULL,
  `TPHA` char(9) DEFAULT NULL,
  `AntiHBcAb` char(9) DEFAULT NULL,
  `RPR` char(9) DEFAULT NULL,
  `AntiHBeAb` char(9) DEFAULT NULL,
  `RPRab` char(12) DEFAULT NULL,
  `HCVAb` char(9) DEFAULT NULL,
  `HBsAb` char(9) DEFAULT NULL,
  `WBC` char(9) DEFAULT NULL,
  `Neutrophils` char(9) DEFAULT NULL,
  `HGB` char(9) DEFAULT NULL,
  `Eosinophis` char(9) DEFAULT NULL,
  `HCT` char(9) DEFAULT NULL,
  `Lymphocyte` char(9) DEFAULT NULL,
  `MCV` char(9) DEFAULT NULL,
  `Monocyte` char(9) DEFAULT NULL,
  `PLT` char(9) DEFAULT NULL,
  `Reticulocte` char(9) DEFAULT NULL,
  `Prothrombin` char(9) DEFAULT NULL,
  `ProReticulocyte` char(9) DEFAULT NULL,
  `Creatinine` char(9) DEFAULT NULL,
  `HDL` char(9) DEFAULT NULL,
  `Bilirubin` char(9) DEFAULT NULL,
  `Glucose` char(9) DEFAULT NULL,
  `Sodium` char(9) DEFAULT NULL,
  `AlPhosphate` char(9) DEFAULT NULL,
  `GotASAT` char(9) DEFAULT NULL,
  `Potassium` char(9) DEFAULT NULL,
  `Amylase` char(9) DEFAULT NULL,
  `GPTALAT` char(9) DEFAULT NULL,
  `Chloride` char(9) DEFAULT NULL,
  `CK` char(9) DEFAULT NULL,
  `CHOL` char(9) DEFAULT NULL,
  `Bicarbonate` char(9) DEFAULT NULL,
  `Lactate` char(9) DEFAULT NULL,
  `Triglyceride` char(9) DEFAULT NULL,
  `Urea` char(9) DEFAULT NULL,
  `Magnesium` char(9) DEFAULT NULL,
  `Phosphorus` char(9) DEFAULT NULL,
  `Calcium` char(9) DEFAULT NULL,
  `BHCG` char(9) DEFAULT NULL,
  `SputumAFB` char(9) DEFAULT NULL,
  `AFBCulture` char(9) DEFAULT NULL,
  `AFBCulture1` char(12) DEFAULT NULL,
  `UrineMicroscopy` char(18) DEFAULT NULL,
  `UrineComment` char(18) DEFAULT NULL,
  `CSFCell` char(6) DEFAULT NULL,
  `CSFGram` char(6) DEFAULT NULL,
  `CSFAFB` char(6) DEFAULT NULL,
  `CSFIndian` char(6) DEFAULT NULL,
  `CSFCCag` char(6) DEFAULT NULL,
  `CSFProtein` char(6) DEFAULT NULL,
  `CSFGlucose` char(6) DEFAULT NULL,
  `BloodCulture` char(6) DEFAULT NULL,
  `BloodCulture0` char(35) DEFAULT NULL,
  `BloodCulture1` char(9) DEFAULT NULL,
  `BloodCulture10` char(30) DEFAULT NULL,
  `CTNA` char(9) DEFAULT NULL,
  `GCNA` char(9) DEFAULT NULL,
  `CXR` char(10) DEFAULT NULL,
  `Abdominal` char(10) DEFAULT NULL,
  `ID` char(4) NOT NULL,
  PRIMARY KEY (`TestID`,`ID`),
  KEY `FK_tblPatientTest_tblAImain` (`ClinicID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblpatienttest`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblprovince`
--

CREATE TABLE IF NOT EXISTS `tblprovince` (
  `IDProvince` int(10) NOT NULL,
  `ProvinceEN` varchar(100) NOT NULL,
  `ProvinceKH` varchar(100) NOT NULL,
  `Distance` varchar(50) NOT NULL,
  PRIMARY KEY (`IDProvince`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblprovince`
--


-- --------------------------------------------------------

--
-- Table structure for table `tbltestabdominal`
--

CREATE TABLE IF NOT EXISTS `tbltestabdominal` (
  `TestID` char(15) NOT NULL,
  `Abdo` char(50) DEFAULT NULL,
  `Abdo1` char(50) DEFAULT NULL,
  `Abdo2` char(40) NOT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `FK_tbltestAbdominal_tblPatientTest` (`TestID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbltestabdominal`
--


-- --------------------------------------------------------

--
-- Table structure for table `tbltestcxr`
--

CREATE TABLE IF NOT EXISTS `tbltestcxr` (
  `TestID` char(15) DEFAULT NULL,
  `CXR` char(50) DEFAULT NULL,
  `CXR1` char(50) DEFAULT NULL,
  `CXR2` char(40) DEFAULT NULL,
  `ID` char(4) DEFAULT NULL,
  KEY `IX_tblTestCXR` (`TestID`),
  KEY `FK_tblTestCXR_tblPatientTest` (`TestID`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbltestcxr`
--


-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE IF NOT EXISTS `tbluser` (
  `UserName` char(10) NOT NULL,
  `Password` char(20) NOT NULL,
  `Child` char(1) NOT NULL,
  `Adult` char(1) NOT NULL,
  `Report` char(1) NOT NULL,
  `Exp` char(1) NOT NULL,
  `Back` char(1) NOT NULL,
  `Muser` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbluser`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblvillage`
--

CREATE TABLE IF NOT EXISTS `tblvillage` (
  `IDVillage` int(10) NOT NULL,
  `IDCommune` int(10) NOT NULL,
  `VillageEN` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblvillage`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblvisitreason`
--

CREATE TABLE IF NOT EXISTS `tblvisitreason` (
  `VisitReasonEN` varchar(100) NOT NULL,
  `IDVisitReasonType` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblvisitreason`
--


-- --------------------------------------------------------

--
-- Table structure for table `tbl_migration`
--

CREATE TABLE IF NOT EXISTS `tbl_migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_migration`
--

INSERT INTO `tbl_migration` (`version`, `apply_time`) VALUES
('m120206_061319_create_users_table', 1343186036),
('m120209_094658_add_active_column_to_tbl_users', 1343186036),
('m120210_041210_create_user_group_table', 1343186036),
('m120213_030304_create_da_site_config_tables', 1343186036),
('m120215_074836_create_backup', 1343186036),
('m120217_073957_create_da_dj_jobs_table', 1343186036),
('m120223_032951_add_columns_to_tbl_tables', 1343186036),
('m120223_080443_add_foreigkey_conttraint_to_da_backups_table', 1343186036),
('m120228_044345_create_drug_control_table', 1343186036),
('m120229_075950_create_import_site_histories_table', 1343186036),
('m120328_040315_create_table_reject_pationt', 1343186037),
('m120427_041800_add_job_id_to_import_history', 1343186037),
('m120430_041142_export_history', 1343186037),
('m120507_041808_create_table_conversion', 1343186037),
('m120608_030812_add_column_status_to_siteconfig', 1343186037),
('m120608_062807_add_status_import_table_to_import_site_history', 1343186038),
('m120703_103931_add_group_to_export_site_group', 1343186038),
('m120704_152929_create_site_code_and_name_for_import_site_histories', 1343186038),
('m120720_181805_add_reject_type_column_to_da_reject_patient', 1343186038);

-- --------------------------------------------------------

--
-- Table structure for table `temp`
--

CREATE TABLE IF NOT EXISTS `temp` (
  `ClinicID` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `temp`
--


-- --------------------------------------------------------

--
-- Table structure for table `tempc`
--

CREATE TABLE IF NOT EXISTS `tempc` (
  `ClinicID` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tempc`
--


-- --------------------------------------------------------

--
-- Table structure for table `tempcd4`
--

CREATE TABLE IF NOT EXISTS `tempcd4` (
  `ClinicID` char(15) NOT NULL,
  `Sex` char(25) DEFAULT NULL,
  `CD4` char(10) DEFAULT NULL,
  `Dat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tempcd4`
--


-- --------------------------------------------------------

--
-- Table structure for table `tempcdrug`
--

CREATE TABLE IF NOT EXISTS `tempcdrug` (
  `ClinicID` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tempcdrug`
--


-- --------------------------------------------------------

--
-- Table structure for table `tempdrug`
--

CREATE TABLE IF NOT EXISTS `tempdrug` (
  `ClinicID` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tempdrug`
--


-- --------------------------------------------------------

--
-- Table structure for table `tempegli`
--

CREATE TABLE IF NOT EXISTS `tempegli` (
  `ClinicID` char(10) NOT NULL,
  `Sex` char(20) DEFAULT NULL,
  `Egli` char(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tempegli`
--


-- --------------------------------------------------------

--
-- Table structure for table `tempvcct`
--

CREATE TABLE IF NOT EXISTS `tempvcct` (
  `Female` char(10) DEFAULT NULL,
  `Male` char(10) DEFAULT NULL,
  `VCCT` char(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tempvcct`
--


--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblaiarvtreatment`
--
ALTER TABLE `tblaiarvtreatment`
  ADD CONSTRAINT `FK_tblAIARVTreatment_tblAImain` FOREIGN KEY (`ClinicID`, `ID`) REFERENCES `tblaimain` (`CLinicID`, `ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tblaicotrimo`
--
ALTER TABLE `tblaicotrimo`
  ADD CONSTRAINT `FK_tblAICotrimo_tblAImain` FOREIGN KEY (`ClinicID`, `ID`) REFERENCES `tblaimain` (`CLinicID`, `ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tblaidrugallergy`
--
ALTER TABLE `tblaidrugallergy`
  ADD CONSTRAINT `FK_tblAIDrugAllergy_tblAImain` FOREIGN KEY (`ClinicID`, `ID`) REFERENCES `tblaimain` (`CLinicID`, `ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tblaifamily`
--
ALTER TABLE `tblaifamily`
  ADD CONSTRAINT `FK_tblAIFamily_tblAImain` FOREIGN KEY (`ClinicID`, `ID`) REFERENCES `tblaimain` (`CLinicID`, `ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tblaifluconazole`
--
ALTER TABLE `tblaifluconazole`
  ADD CONSTRAINT `FK_tblAIFluconazole_tblAImain` FOREIGN KEY (`ClinicID`, `ID`) REFERENCES `tblaimain` (`CLinicID`, `ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tblaiisoniazid`
--
ALTER TABLE `tblaiisoniazid`
  ADD CONSTRAINT `FK_tblAIIsoniazid_tblAImain` FOREIGN KEY (`ClinicID`, `ID`) REFERENCES `tblaimain` (`CLinicID`, `ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tblaimain`
--
ALTER TABLE `tblaimain`
  ADD CONSTRAINT `FK_tblAImain_tblClinic` FOREIGN KEY (`ID`) REFERENCES `tblclinic` (`ART`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tblaiothermedical`
--
ALTER TABLE `tblaiothermedical`
  ADD CONSTRAINT `FK_tblAIOtherMedical_tblAImain` FOREIGN KEY (`ClinicID`, `ID`) REFERENCES `tblaimain` (`CLinicID`, `ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tblaiothpasmedical`
--
ALTER TABLE `tblaiothpasmedical`
  ADD CONSTRAINT `FK_tblAIOthPasMedical_tblAImain` FOREIGN KEY (`ClinicID`, `ID`) REFERENCES `tblaimain` (`CLinicID`, `ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tblaitbpastmedical`
--
ALTER TABLE `tblaitbpastmedical`
  ADD CONSTRAINT `FK_tblAITBPastMedical_tblAImain` FOREIGN KEY (`ClinicID`, `ID`) REFERENCES `tblaimain` (`CLinicID`, `ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tblaitraditional`
--
ALTER TABLE `tblaitraditional`
  ADD CONSTRAINT `FK_tblAITraditional_tblAImain` FOREIGN KEY (`ClinicID`, `ID`) REFERENCES `tblaimain` (`CLinicID`, `ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tblappoint`
--
ALTER TABLE `tblappoint`
  ADD CONSTRAINT `FK_tblAppoint_tblAVmain` FOREIGN KEY (`AV_ID`, `ID`) REFERENCES `tblavmain` (`AV_ID`, `ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tblart`
--
ALTER TABLE `tblart`
  ADD CONSTRAINT `FK_tblART_tblAImain` FOREIGN KEY (`ClinicID`, `ID`) REFERENCES `tblaimain` (`CLinicID`, `ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tblavarv`
--
ALTER TABLE `tblavarv`
  ADD CONSTRAINT `FK_tblAVARV_tblAVmain` FOREIGN KEY (`AV_ID`, `ID`) REFERENCES `tblavmain` (`AV_ID`, `ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tblavlostdead`
--
ALTER TABLE `tblavlostdead`
  ADD CONSTRAINT `FK_tblAVLostDead_tblAVmain` FOREIGN KEY (`AV_ID`, `ID`) REFERENCES `tblavmain` (`AV_ID`, `ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
