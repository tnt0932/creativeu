-- phpMyAdmin SQL Dump
-- version 3.3.10.4
-- http://www.phpmyadmin.net
--
-- Host: mysql.bytnt.com
-- Generation Time: Oct 14, 2014 at 10:48 PM
-- Server version: 5.1.56
-- PHP Version: 5.3.27

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `troyis_creativeu`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `AdminID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `AdminName` char(20) NOT NULL,
  `AdminEmail` varchar(255) NOT NULL,
  `AdminPassword` char(64) NOT NULL,
  `AdminSalt` char(30) NOT NULL,
  PRIMARY KEY (`AdminID`),
  UNIQUE KEY `AdminName` (`AdminName`),
  UNIQUE KEY `AdminEmail` (`AdminEmail`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`AdminID`, `AdminName`, `AdminEmail`, `AdminPassword`, `AdminSalt`) VALUES
(1, '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `creativetitle`
--

CREATE TABLE IF NOT EXISTS `creativetitle` (
  `CreativeTitleID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `CreativeTitle` char(255) NOT NULL,
  PRIMARY KEY (`CreativeTitleID`),
  UNIQUE KEY `CreativeTitle` (`CreativeTitle`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `creativetitle`
--

INSERT INTO `creativetitle` (`CreativeTitleID`, `CreativeTitle`) VALUES
(9, 'Creative'),
(1, 'Front-end Developer'),
(8, 'Graphic Designer'),
(7, 'Illustrator'),
(2, 'Information Architect'),
(3, 'User Experience Designer');

-- --------------------------------------------------------

--
-- Table structure for table `gradyear`
--

CREATE TABLE IF NOT EXISTS `gradyear` (
  `GradYearID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `GradYear` year(4) NOT NULL,
  PRIMARY KEY (`GradYearID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `gradyear`
--

INSERT INTO `gradyear` (`GradYearID`, `GradYear`) VALUES
(1, 2016),
(2, 2015),
(3, 2014),
(4, 2013),
(5, 2012),
(6, 2011),
(7, 2010),
(8, 2009),
(9, 2008),
(10, 2007),
(11, 2006),
(15, 2018);

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE IF NOT EXISTS `program` (
  `ProgramID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `ProgramName` char(80) NOT NULL DEFAULT '',
  `SchoolID` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`ProgramID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`ProgramID`, `ProgramName`, `SchoolID`) VALUES
(1, 'Interactive Design (INTE)', 2),
(2, 'Illustration/Design (IDEA)', 2),
(3, 'Digital Design', 3);

-- --------------------------------------------------------

--
-- Table structure for table `school`
--

CREATE TABLE IF NOT EXISTS `school` (
  `SchoolID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `SchoolName` char(255) NOT NULL,
  PRIMARY KEY (`SchoolID`),
  UNIQUE KEY `SchoolName` (`SchoolName`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `school`
--

INSERT INTO `school` (`SchoolID`, `SchoolName`) VALUES
(2, 'British Columbia Institue of Technology (BCIT)'),
(1, 'Capilano University'),
(4, 'Simon Frasier University'),
(3, 'Vancouver Film School');

-- --------------------------------------------------------

--
-- Table structure for table `snippet`
--

CREATE TABLE IF NOT EXISTS `snippet` (
  `SnippetID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `SnippetTitle` char(50) NOT NULL,
  `SnippetDescription` text,
  `SnippetURL` varchar(255) DEFAULT NULL,
  `SnippetLikes` mediumint(6) unsigned DEFAULT '0',
  `SnippetAddedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `SnippetPictureFile` varchar(255) NOT NULL,
  `SnippetThumbnailFile` varchar(255) NOT NULL,
  `SID` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`SnippetID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=146 ;

--
-- Dumping data for table `snippet`
--

INSERT INTO `snippet` (`SnippetID`, `SnippetTitle`, `SnippetDescription`, `SnippetURL`, `SnippetLikes`, `SnippetAddedDate`, `SnippetPictureFile`, `SnippetThumbnailFile`, `SID`) VALUES
(142, 'Creative U', 'I''m one of the people who built this site! My main tasks were HTML/JS/PHP/MySQL development. I hope you enjoy!', 'http://creativeu.ca', 2, '2014-09-01 23:58:49', 'creativeu_feature.jpg', 'cu.jpg', 1),

-- --------------------------------------------------------

--
-- Table structure for table `snippettag`
--

CREATE TABLE IF NOT EXISTS `snippettag` (
  `SnippetTagID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `SnippetID` smallint(5) NOT NULL,
  `TagID` tinyint(3) NOT NULL,
  PRIMARY KEY (`SnippetTagID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=85 ;

--
-- Dumping data for table `snippettag`
--

INSERT INTO `snippettag` (`SnippetTagID`, `SnippetID`, `TagID`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(50, 112, 1),
(51, 113, 1),
(52, 114, 5),
(59, 117, 7),
(60, 118, 2),
(61, 118, 1),
(62, 119, 3),
(63, 119, 7),
(64, 119, 1),
(65, 119, 6),
(66, 120, 5),
(67, 120, 1),
(68, 121, 8),
(69, 121, 1),
(70, 122, 3),
(71, 123, 7),
(75, 142, 6),
(79, 144, 3),
(80, 144, 1),
(81, 144, 6),
(82, 145, 8),
(83, 145, 1),
(84, 145, 6);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE IF NOT EXISTS `students` (
  `SID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `StudentFirstName` char(30) NOT NULL,
  `StudentLastName` char(50) NOT NULL,
  `StudentEmail` varchar(255) NOT NULL,
  `StudentPassword` char(80) NOT NULL,
  `StudentSalt` char(30) NOT NULL,
  `StudentWebsite` varchar(255) DEFAULT NULL,
  `StudentFacebook` varchar(255) DEFAULT NULL,
  `StudentTwitter` char(20) DEFAULT NULL,
  `StudentLinkedIn` varchar(255) DEFAULT NULL,
  `StudentDescription` text,
  `GradYearID` tinyint(3) unsigned NOT NULL,
  `StudentPicture` char(80) DEFAULT NULL,
  `SchoolID` tinyint(3) unsigned NOT NULL,
  `ProgramID` tinyint(3) unsigned NOT NULL,
  `CreativeTitleID` tinyint(3) unsigned NOT NULL,
  `StudentPublicEmail` varchar(255) DEFAULT NULL,
  `resetKey` char(255) DEFAULT NULL,
  `joined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`SID`),
  UNIQUE KEY `StudentEmail` (`StudentEmail`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`SID`, `StudentFirstName`, `StudentLastName`, `StudentEmail`, `StudentPassword`, `StudentSalt`, `StudentWebsite`, `StudentFacebook`, `StudentTwitter`, `StudentLinkedIn`, `StudentDescription`, `GradYearID`, `StudentPicture`, `SchoolID`, `ProgramID`, `CreativeTitleID`, `StudentPublicEmail`, `resetKey`, `joined`) VALUES
(1, 'Generic', 'Student', 'genericstudent@gmail.com', 'pword', 'salt', 'http://example.com', 'http://facebook.com/example', 'example', 'http://linkedin.com/in/example', 'example bio', 4, 'edwardscissorhands.jpg', 1, 1, 3, 'publicemail@gmail.com', NULL, '2013-08-17 19:16:34'),


-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `TagID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `TagName` char(255) NOT NULL,
  PRIMARY KEY (`TagID`),
  UNIQUE KEY `TagName` (`TagName`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`TagID`, `TagName`) VALUES
(9, ''),
(3, 'Information Architecture'),
(7, 'Mobile Design'),
(5, 'Photography'),
(2, 'Print Design'),
(8, 'Visual Design'),
(1, 'Web Design'),
(6, 'Web Development');
