-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 25, 2012 at 11:22 AM
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
-- Table structure for table `da_backups`
--

CREATE TABLE IF NOT EXISTS `da_backups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `status` int(4) DEFAULT '0',
  `siteconfig_id` int(11) NOT NULL,
  `duration` float DEFAULT NULL,
  `reason` text,
  `modified_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_da_bakups_siteconfig_id` (`siteconfig_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `da_backups`
--


-- --------------------------------------------------------

--
-- Table structure for table `da_conversion`
--

CREATE TABLE IF NOT EXISTS `da_conversion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `status` int(4) DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `src` varchar(255) DEFAULT NULL,
  `des` varchar(255) DEFAULT NULL,
  `message` text,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `da_conversion`
--


-- --------------------------------------------------------

--
-- Table structure for table `da_djjobs`
--

CREATE TABLE IF NOT EXISTS `da_djjobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `handler` text NOT NULL,
  `queue` varchar(255) DEFAULT 'default',
  `attempts` int(11) DEFAULT '0',
  `run_at` datetime DEFAULT NULL,
  `locked_at` datetime DEFAULT NULL,
  `locked_by` varchar(255) DEFAULT NULL,
  `failed_at` datetime DEFAULT NULL,
  `error` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `da_djjobs`
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `da_drug_controls`
--


-- --------------------------------------------------------

--
-- Table structure for table `da_export_history`
--

CREATE TABLE IF NOT EXISTS `da_export_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `reversable` int(1) DEFAULT NULL,
  `sites` text,
  `status` int(4) DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `table_list` text,
  `file` varchar(255) DEFAULT NULL,
  `message` text,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `all_site` int(1) DEFAULT NULL,
  `all_table` int(1) DEFAULT NULL,
  `site_text` text,
  `group` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `da_export_history`
--


-- --------------------------------------------------------

--
-- Table structure for table `da_groups`
--

CREATE TABLE IF NOT EXISTS `da_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `da_groups`
--


-- --------------------------------------------------------

--
-- Table structure for table `da_import_site_histories`
--

CREATE TABLE IF NOT EXISTS `da_import_site_histories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(4) DEFAULT '0',
  `siteconfig_id` int(11) NOT NULL,
  `duration` float DEFAULT NULL,
  `reason` text,
  `modified_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `info` text,
  `importing_table` varchar(255) DEFAULT NULL,
  `total_record` int(10) DEFAULT NULL,
  `current_record` int(10) DEFAULT NULL,
  `importing_record` text,
  `site_code` varchar(255) DEFAULT NULL,
  `site_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_da_import_site_histories_siteconfig_id` (`siteconfig_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `da_import_site_histories`
--


-- --------------------------------------------------------

--
-- Table structure for table `da_import_tables`
--

CREATE TABLE IF NOT EXISTS `da_import_tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(255) DEFAULT NULL,
  `cols` text,
  `created_at` datetime DEFAULT NULL,
  `priority` int(4) DEFAULT '0',
  `type` varchar(255) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `da_import_tables`
--


-- --------------------------------------------------------

--
-- Table structure for table `da_reject_patients`
--

CREATE TABLE IF NOT EXISTS `da_reject_patients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tableName` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` int(4) DEFAULT '0',
  `message` text,
  `record` text,
  `err_records` text,
  `import_site_history_id` int(11) NOT NULL,
  `modified_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `reject_type` int(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `da_reject_patients`
--


-- --------------------------------------------------------

--
-- Table structure for table `da_siteconfigs`
--

CREATE TABLE IF NOT EXISTS `da_siteconfigs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `host` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `db` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `status` int(4) DEFAULT '10',
  `last_imported` datetime DEFAULT NULL,
  `last_restored` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `da_siteconfigs`
--


-- --------------------------------------------------------

--
-- Table structure for table `da_users`
--

CREATE TABLE IF NOT EXISTS `da_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `salt` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `da_users`
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
