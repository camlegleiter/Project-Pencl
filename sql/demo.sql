-- phpMyAdmin SQL Dump
-- version 3.4.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 01, 2011 at 09:19 PM
-- Server version: 5.5.15
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pencl`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
  `userid` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`userid`, `level`, `created`) VALUES
(1, 0, '2011-11-27 00:00:00'),
(2, 2, '2011-11-27 23:16:58'),
(11, 2, '2011-11-27 23:43:19');

-- --------------------------------------------------------

--
-- Table structure for table `classbooks`
--

CREATE TABLE IF NOT EXISTS `classbooks` (
  `notebookid` int(11) NOT NULL,
  `classid` int(11) NOT NULL,
  KEY `classid` (`classid`),
  KEY `notebookid` (`notebookid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `classbooks`
--

INSERT INTO `classbooks` (`notebookid`, `classid`) VALUES
(2, 1),
(6, 6),
(4, 4),
(5, 5),
(7, 6),
(8, 8),
(9, 9),
(10, 9),
(11, 5),
(12, 6),
(12, 8),
(14, 1),
(18, 5),
(17, 4),
(16, 3),
(19, 4),
(20, 8),
(21, 8),
(22, 9),
(23, 9),
(25, 10),
(24, 3),
(24, 1),
(27, 10),
(28, 4),
(29, 3),
(30, 2),
(31, 4),
(31, 3),
(32, 3),
(32, 1),
(33, 5),
(34, 10);

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE IF NOT EXISTS `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(31) NOT NULL,
  `description` varchar(256) NOT NULL,
  `owner` int(11) NOT NULL,
  `password` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `name`, `description`, `owner`, `password`) VALUES
(1, 'COM S 229', 'Java and data structures!!', 1, ''),
(2, 'Project Pencl', '', 1, 'eraser'),
(3, 'COM S 228', 'Java programming basics', 1, ''),
(4, 'CprE 381', 'Computer Architecture', 2, ''),
(5, 'Engl 101', '', 2, ''),
(6, 'Soc 331', '', 2, ''),
(7, 'Stat 330', '', 2, ''),
(8, 'JLMC 201', '', 2, ''),
(9, 'Hist 221', '', 2, ''),
(10, 'Com S 319', 'The best class around for UI learning!', 11, '');

-- --------------------------------------------------------

--
-- Table structure for table `classmates`
--

CREATE TABLE IF NOT EXISTS `classmates` (
  `userid` int(11) NOT NULL,
  `classid` int(11) NOT NULL,
  KEY `classid` (`classid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `classmates`
--

INSERT INTO `classmates` (`userid`, `classid`) VALUES
(1, 6),
(10, 4),
(10, 6),
(6, 8),
(6, 9),
(10, 9),
(7, 5),
(7, 6),
(7, 1),
(7, 8),
(11, 1),
(4, 4),
(4, 3),
(4, 5),
(5, 4),
(11, 2),
(5, 8),
(5, 9),
(5, 1),
(2, 3),
(2, 1),
(6, 10),
(6, 4),
(6, 3),
(3, 1),
(6, 1),
(11, 5),
(9, 4),
(9, 3),
(9, 1),
(9, 5),
(9, 10),
(2, 10);

-- --------------------------------------------------------

--
-- Table structure for table `coming_soon_emails`
--

CREATE TABLE IF NOT EXISTS `coming_soon_emails` (
  `email` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notebooks`
--

CREATE TABLE IF NOT EXISTS `notebooks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

--
-- Dumping data for table `notebooks`
--

INSERT INTO `notebooks` (`id`, `userid`, `name`, `description`, `created`, `modified`) VALUES
(2, 1, 'Syllabus', 'Please read!', '2011-11-27 22:49:12', '2011-11-27 23:16:31'),
(3, 3, 'Test', 'Testing', '2011-11-27 22:49:49', '2011-11-27 22:50:00'),
(4, 2, 'Intro to Computers', '', '2011-11-27 23:20:27', '2011-11-29 13:40:34'),
(5, 2, 'What is english', '', '2011-11-27 23:20:59', '2011-11-27 23:20:59'),
(6, 1, 'SOC 331 Meeting Notes (11/28)', '', '2011-11-27 23:21:16', '2011-11-27 23:21:26'),
(7, 2, 'Class & inequality', '', '2011-11-27 23:21:20', '2011-11-27 23:21:20'),
(8, 2, 'Journalism & why its useless', '', '2011-11-27 23:21:39', '2011-11-27 23:21:39'),
(9, 2, 'Wars you never heard of', '', '2011-11-27 23:21:56', '2011-11-28 14:25:28'),
(10, 10, 'pinkmans 221 notes', '', '2011-11-27 23:30:15', '2011-11-27 23:30:15'),
(11, 7, '101 Syllabus', '', '2011-11-27 23:33:57', '2011-11-27 23:33:57'),
(12, 7, 'Krostal''s 331 notes', '', '2011-11-27 23:34:44', '2011-11-27 23:34:44'),
(13, 11, 'CS 103, Day 1', 'This class is a joke!', '2011-11-27 23:35:20', '2011-11-27 23:36:17'),
(14, 7, 'quick note''s', '', '2011-11-27 23:35:27', '2011-11-27 23:50:28'),
(16, 4, 'Data Structures', '', '2011-11-27 23:37:37', '2011-11-27 23:37:37'),
(17, 4, 'Pipelined Processors', '', '2011-11-27 23:37:48', '2011-11-27 23:37:48'),
(18, 4, 'When to use commas', '', '2011-11-27 23:38:07', '2011-11-27 23:38:07'),
(19, 5, 'All about cache', '', '2011-11-27 23:41:52', '2011-11-27 23:41:52'),
(20, 5, 'Mass communication', '', '2011-11-27 23:43:12', '2011-11-27 23:43:12'),
(21, 5, 'Digital Communication', '', '2011-11-27 23:43:35', '2011-11-27 23:43:35'),
(22, 5, 'The civil war', '', '2011-11-27 23:44:16', '2011-11-27 23:44:16'),
(23, 5, 'WWII', '', '2011-11-27 23:44:30', '2011-11-27 23:44:30'),
(24, 2, 'arrays', '', '2011-11-27 23:45:09', '2011-11-30 13:23:49'),
(25, 11, 'Com S 319 Day 1 - Hello World!', 'Welcome to Com S 319!', '2011-11-27 23:45:13', '2011-11-27 23:45:22'),
(27, 6, 'I love javascript', '', '2011-11-27 23:47:34', '2011-11-27 23:47:34'),
(28, 6, 'assembly 101', '', '2011-11-27 23:48:22', '2011-11-27 23:48:22'),
(29, 6, 'the if statement', '', '2011-11-27 23:49:37', '2011-11-30 13:24:57'),
(30, 1, 'The Easter Egg', 'Surprise!', '2011-11-27 23:51:11', '2011-11-28 00:06:39'),
(31, 9, 'why computers are humans too', '', '2011-11-27 23:52:35', '2011-11-30 13:24:46'),
(32, 9, 'programming for dummies', '', '2011-11-27 23:53:41', '2011-11-27 23:53:41'),
(33, 9, 'EXCLAMATION POINTS!!11!1', '', '2011-11-27 23:54:35', '2011-11-27 23:54:35'),
(34, 9, 'using php', '', '2011-11-27 23:56:18', '2011-11-27 23:56:18');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(64) NOT NULL,
  `salt` varchar(40) NOT NULL,
  `email` varchar(128) NOT NULL,
  `created` datetime NOT NULL,
  `ip` varchar(40) NOT NULL,
  `token` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `username`, `password`, `salt`, `email`, `created`, `ip`, `token`) VALUES
(1, 'justderb', '1f1ee024f1279e812f732d90b5dcea3d4f210fae', '7e2ed2f385b4113ea955ba47fa23e8de28c8dcc7', 'testAccount@localhost', '2011-11-27 22:46:15', '192.168.1.107', '7c2e10c6b559a56910071c17ed163655154162f0'),
(2, 'tbouvin', '843053a6d0db84a454a542900cfa8cb9c59fb082', '3c3b5f508fad741ef393a2e831d7cc56336cd62a', 'testAccount@localhost', '2011-11-27 22:47:51', '64.113.91.177', '1073b699ce1f864cf0c5fc14f1272a0ad03bcc4b'),
(3, 'mpkinsella', 'a6b71afdda11f8f1245645e2d3ca8d13eab172e3', '9e5b881abb9c952f3f8262978cce3f178bcaf218', 'testAccount@localhost', '2011-11-27 22:47:55', '69.5.154.153', '903646bc1ff78f906f51be23766b823555d4e32a'),
(4, 'smithJR', '8ea8411ad7279fa202cd0310a8619003140643c2', 'b8e0301ce53bf147686cb452a2fd065f0c217697', 'testAccount@localhost', '2011-11-27 23:22:31', '64.113.91.177', '4261554d7021073ed10f41a0345bf2ef2a20a411'),
(5, 'smithSR', '856c5058d0cbe07998572d3c4180007f091119b5', '4bf7877fe06084be6ffba6789b18b61266c3ac33', 'testAccount@localhost', '2011-11-27 23:22:48', '64.113.91.177', '203cc796665b9d2e1bcd7186367ec32adbb45095'),
(6, 'anderson123', '31528e8605edf42c1dd2ac138504c10cfef1310a', 'a9e02e8450cc54cc40629c1db4b5347c57a6f61b', 'testAccount@localhost', '2011-11-27 23:23:09', '64.113.91.177', 'd8958267fd8afc9ee1d99527a74365401a4a5268'),
(7, 'Krostal', '1a2cf70117a335f03e76f00603d93c7eea4dc8ba', '3a7030c5ff60e7c73b46949f3d56323a32647ac5', 'testAccount@localhost', '2011-11-27 23:23:29', '64.113.91.177', '1df8a61b98626a440eb81282b9f226283b4e1338'),
(8, 'Thomas', '7fe6d152d61b673e0b9a149bf172565eec2c5415', '309a64f0ac73136f392b96927a9d0c637f823d2c', 'testAccount@localhost', '2011-11-27 23:23:43', '64.113.91.177', NULL),
(9, 'white', 'bcda1de30f44a647d49a140c71d6f32225e779eb', 'd5cf2c24321b2369d35b2d4d410269f20b8b09b6', 'testAccount@localhost', '2011-11-27 23:24:35', '64.113.91.177', 'db5c554845dfad80fd18cc7e530fa93d7bde052f'),
(10, 'pinkman', 'b3bd91405bc9a62da9f6e56c13901d5fab436263', '40ad598f08325cb6795ff9c1778c1a95e5dea9e5', 'testAccount@localhost', '2011-11-27 23:24:50', '64.113.91.177', 'da268d5ee0c4321e02c093640f9086c9f045761d'),
(11, 'camlegleiter', 'e6a2459a2d0218cda8bb0c27275ed27795bcc8c3', '42a12be7dd7377d59fdfe9ecdedbb2364350d816', 'testAccount@localhost', '2011-11-27 23:34:28', '173.26.197.206', 'fe8a538c25cd8311a2ab161e70d05034e38caa2e'),
(12, 'deleteMe', 'd0f08683c549e7b7548d3a03ddbac27485781994', '09601d5515c6bbc632b58bc86e5c7336612d6f07', 'testAccount@localhost', '2011-11-28 00:06:28', '173.26.197.206', '77a340deb353ed881535e8c548e8b35a249fa4fa');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `classbooks`
--
ALTER TABLE `classbooks`
  ADD CONSTRAINT `classbooks_ibfk_1` FOREIGN KEY (`classid`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `classbooks_ibfk_2` FOREIGN KEY (`notebookid`) REFERENCES `notebooks` (`id`);

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `users` (`userid`);

--
-- Constraints for table `classmates`
--
ALTER TABLE `classmates`
  ADD CONSTRAINT `classmates_ibfk_1` FOREIGN KEY (`classid`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `classmates_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `notebooks`
--
ALTER TABLE `notebooks`
  ADD CONSTRAINT `notebooks_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;