-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 08, 2013 at 06:24 AM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `quizzy`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE IF NOT EXISTS `answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `answerDate` datetime NOT NULL,
  `patID` int(11) NOT NULL,
  `quizID` int(11) NOT NULL,
  `questionID` int(11) NOT NULL,
  `questionType` varchar(16) COLLATE utf8_bin NOT NULL,
  `questionAnswer` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1001 ;

-- --------------------------------------------------------

--
-- Table structure for table `assistant`
--

CREATE TABLE IF NOT EXISTS `assistant` (
  `assistantID` int(8) NOT NULL AUTO_INCREMENT,
  `assistantName` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`assistantID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001 ;

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE IF NOT EXISTS `patient` (
  `patID` int(11) NOT NULL AUTO_INCREMENT,
  `patFirstname` varchar(32) COLLATE utf8_bin NOT NULL,
  `patLastname` varchar(32) COLLATE utf8_bin NOT NULL,
  `patAge` int(2) NOT NULL,
  `patGender` varchar(6) COLLATE utf8_bin NOT NULL,
  `patStatus` varchar(10) COLLATE utf8_bin NOT NULL,
  `patAddress` varchar(64) COLLATE utf8_bin NOT NULL,
  `patPhone` varchar(10) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`patID`),
  UNIQUE KEY `patID_2` (`patID`),
  KEY `patID` (`patID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1000000 ;

-- --------------------------------------------------------

--
-- Table structure for table `patientfiles`
--

CREATE TABLE IF NOT EXISTS `patientfiles` (
  `fileID` int(11) NOT NULL AUTO_INCREMENT,
  `patID` int(11) NOT NULL,
  `dateAdded` datetime NOT NULL,
  `fileName` varchar(256) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `fileDescription` varchar(256) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`fileID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `patientquiz`
--

CREATE TABLE IF NOT EXISTS `patientquiz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patID` int(11) NOT NULL,
  `quizID` int(11) NOT NULL,
  `quizTaken` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `questionID` int(11) NOT NULL AUTO_INCREMENT,
  `quizID` int(11) NOT NULL,
  `questionText` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `questionType` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `questionImage` varchar(256) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `questionData` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`questionID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE IF NOT EXISTS `quiz` (
  `quizID` int(11) NOT NULL AUTO_INCREMENT,
  `quizTitle` varchar(128) COLLATE utf8_bin NOT NULL,
  `researchID` int(11) NOT NULL,
  PRIMARY KEY (`quizID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `research`
--

CREATE TABLE IF NOT EXISTS `research` (
  `researchID` int(11) NOT NULL AUTO_INCREMENT,
  `researchName` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`researchID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `researchassistants`
--

CREATE TABLE IF NOT EXISTS `researchassistants` (
  `researchID` int(11) NOT NULL,
  `assistantID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `researchpatients`
--

CREATE TABLE IF NOT EXISTS `researchpatients` (
  `researchID` int(11) NOT NULL,
  `patID` int(11) NOT NULL,
  UNIQUE KEY `researchID` (`researchID`,`patID`),
  UNIQUE KEY `researchID_2` (`researchID`,`patID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `role` enum('admin','patient','assistant') CHARACTER SET utf8 COLLATE utf8_bin DEFAULT 'patient',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1000000 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created`, `modified`) VALUES
(1, '1', 'cbc15667ede5cf6feb73603a8dc9c19043a3ca57', 'admin', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
