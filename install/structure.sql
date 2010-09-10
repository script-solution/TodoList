-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 09, 2010 at 09:24 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.2-1ubuntu4.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `todolist_recover`
--

-- --------------------------------------------------------

--
-- Table structure for table `tl_categories`
--

CREATE TABLE IF NOT EXISTS `tl_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned NOT NULL DEFAULT '0',
  `category_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tl_config`
--

CREATE TABLE IF NOT EXISTS `tl_config` (
  `selected_project` int(10) unsigned NOT NULL DEFAULT '0',
  `last_start_version` int(10) unsigned NOT NULL DEFAULT '0',
  `last_fixed_version` int(10) unsigned NOT NULL DEFAULT '0',
  `last_category` int(10) unsigned NOT NULL DEFAULT '0',
  `last_type` enum('bug','feature','improvement') COLLATE latin1_general_ci NOT NULL DEFAULT 'bug',
  `last_priority` enum('current','next','anytime') COLLATE latin1_general_ci NOT NULL DEFAULT 'current',
  `last_status` enum('open','running','fixed') COLLATE latin1_general_ci NOT NULL DEFAULT 'open',
  `is_selected` tinyint(1) NOT NULL,
  `project_id` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tl_entries`
--

CREATE TABLE IF NOT EXISTS `tl_entries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned NOT NULL DEFAULT '0',
  `entry_title` text CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `entry_category` int(10) unsigned NOT NULL DEFAULT '0',
  `entry_type` enum('bug','feature','improvement','test') COLLATE latin1_general_ci NOT NULL DEFAULT 'bug',
  `entry_priority` enum('current','next','anytime') COLLATE latin1_general_ci NOT NULL DEFAULT 'anytime',
  `entry_description` text CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `entry_info_link` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `entry_start_date` int(10) unsigned NOT NULL DEFAULT '0',
  `entry_start_version` int(10) unsigned NOT NULL DEFAULT '0',
  `entry_fixed_date` int(10) unsigned NOT NULL DEFAULT '0',
  `entry_fixed_version` int(10) unsigned NOT NULL DEFAULT '0',
  `entry_changed_date` int(10) unsigned NOT NULL DEFAULT '0',
  `entry_status` enum('open','running','not_tested','fixed','not_reproducable','need_info') CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL DEFAULT 'open',
  PRIMARY KEY (`id`),
  KEY `entry_category` (`entry_category`),
  KEY `project_id` (`project_id`),
  KEY `entry_start_version` (`entry_start_version`),
  KEY `entry_fixed_version` (`entry_fixed_version`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Table structure for table `tl_projects`
--

CREATE TABLE IF NOT EXISTS `tl_projects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL DEFAULT '',
  `project_name_short` varchar(10) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL DEFAULT '',
  `project_start` int(10) unsigned NOT NULL DEFAULT '0',
  `is_selected` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Table structure for table `tl_project_versions`
--

CREATE TABLE IF NOT EXISTS `tl_project_versions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned NOT NULL DEFAULT '0',
  `version_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
