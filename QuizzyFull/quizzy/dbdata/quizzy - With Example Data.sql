-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 08, 2013 at 09:33 AM
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1007 ;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `answerDate`, `patID`, `quizID`, `questionID`, `questionType`, `questionAnswer`) VALUES
(1001, '2013-09-08 08:37:43', 1000001, 1, 1, '?', 0x49742773206120686f727269626c6520616e64207665727920736572696f757320697373756520776869636820636f6e6365726e7320757320616e64206f7572206368696c6472656e),
(1002, '2013-09-08 08:37:43', 1000001, 1, 2, '?', 0x5375736869),
(1003, '2013-09-08 08:37:43', 1000001, 1, 3, '?', 0x46656d616c65),
(1004, '2013-09-08 08:37:43', 1000001, 1, 4, '?', 0x34312d3630),
(1005, '2013-09-08 08:37:43', 1000001, 1, 5, '?', 0x4e6f74206d756368),
(1006, '2013-09-08 08:37:43', 1000001, 1, 6, '?', 0x56657279206d756368);

-- --------------------------------------------------------

--
-- Table structure for table `assistant`
--

CREATE TABLE IF NOT EXISTS `assistant` (
  `assistantID` int(8) NOT NULL AUTO_INCREMENT,
  `assistantName` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`assistantID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1001 ;

--
-- Dumping data for table `assistant`
--

INSERT INTO `assistant` (`assistantID`, `assistantName`) VALUES
(1000, 0x466972737420417373697374616e742045766572);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1000003 ;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`patID`, `patFirstname`, `patLastname`, `patAge`, `patGender`, `patStatus`, `patAddress`, `patPhone`) VALUES
(1000000, 'Jeff', 'Albertson', 36, 'male', 'single', 'Comic Book Store, Springfield', '555-0001'),
(1000001, 'Edna', 'Krabappel', 41, 'female', 'divorced', '4th Grade, Springfield Elementary School, Springfield', '555-6666'),
(1000002, 'Apu', 'Nahasapeemapetilon', 27, 'male', 'married', 'Kwik-E-Mart, Springfield', '555-1234');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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

--
-- Dumping data for table `patientquiz`
--

INSERT INTO `patientquiz` (`id`, `patID`, `quizID`, `quizTaken`) VALUES
(1, 1000000, 1, 0),
(2, 1000001, 1, 1),
(3, 1000002, 1, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`questionID`, `quizID`, `questionText`, `questionType`, `questionImage`, `questionData`) VALUES
(1, 1, 0x5768617420697320796f7572206f70696e696f6e2061626f757420676c6f62616c207761726d696e673f, 'TEXT', '', 0x54455854),
(2, 1, 0x57686174206b696e64206f6620666f6f6420646f20796f75206c696b653f, 'CHK', '', 0x4974616c69616e40404661737420466f6f64404053757368694040),
(3, 1, 0x5768617420697320796f75722067656e6465723f, 'RADIO', '', 0x4d616c65404046656d616c654040),
(4, 1, 0x486f77206f6c642061726520796f753f, 'SELECT', '', 0x302d3137404031382d3235404032362d3430404034312d3630404036302b4040),
(5, 1, 0x446f20796f7520656e6a6f792073706f7274733f, 'MATRIX', '', 0x4d41545249584f3d4e6f74206d75636840404d41545249584f3d536f20736f40404d41545249584f3d56657279206d756368),
(6, 1, 0x446f20796f7520656e6a6f7920636f6f6b696e673f, 'MATRIX', '', 0x4d41545249584f3d4e6f74206d75636840404d41545249584f3d536f20736f40404d41545249584f3d56657279206d756368);

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE IF NOT EXISTS `quiz` (
  `quizID` int(11) NOT NULL AUTO_INCREMENT,
  `quizTitle` varchar(128) COLLATE utf8_bin NOT NULL,
  `researchID` int(11) NOT NULL,
  PRIMARY KEY (`quizID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`quizID`, `quizTitle`, `researchID`) VALUES
(1, 'Demo Quiz', 1);

-- --------------------------------------------------------

--
-- Table structure for table `research`
--

CREATE TABLE IF NOT EXISTS `research` (
  `researchID` int(11) NOT NULL AUTO_INCREMENT,
  `researchName` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`researchID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `research`
--

INSERT INTO `research` (`researchID`, `researchName`) VALUES
(1, 0x5468652066697273742072657365617263682065766572);

-- --------------------------------------------------------

--
-- Table structure for table `researchassistants`
--

CREATE TABLE IF NOT EXISTS `researchassistants` (
  `researchID` int(11) NOT NULL,
  `assistantID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `researchassistants`
--

INSERT INTO `researchassistants` (`researchID`, `assistantID`) VALUES
(1, 1000);

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

--
-- Dumping data for table `researchpatients`
--

INSERT INTO `researchpatients` (`researchID`, `patID`) VALUES
(1, 1000000),
(1, 1000001),
(1, 1000002),
(8, 1000000),
(8, 1000001),
(8, 1000002);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1000003 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created`, `modified`) VALUES
(1, 'admin', 'cbc15667ede5cf6feb73603a8dc9c19043a3ca57', 'admin', '2013-01-01 00:00:00', '2013-01-24 00:00:00'),
(1000, '1000', 'cbc15667ede5cf6feb73603a8dc9c19043a3ca57', 'assistant', '2013-01-01 00:00:00', '2013-01-01 00:00:00'),
(1000000, '1000000', 'cbc15667ede5cf6feb73603a8dc9c19043a3ca57', 'patient', NULL, NULL),
(1000001, '1000001', 'cbc15667ede5cf6feb73603a8dc9c19043a3ca57', 'patient', NULL, NULL),
(1000002, '1000002', 'cbc15667ede5cf6feb73603a8dc9c19043a3ca57', 'patient', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
