-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2024 at 02:51 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `staffid` varchar(20) NOT NULL,
  `fname` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `email`, `password`, `staffid`, `fname`) VALUES
(1, 'staff1@gmail.com', '1234567', 'STF001', 'staff1'),
(2, 'staff2@gmail.com', '1234567', 'STF002', 'staff2');

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE `answer` (
  `qid` text NOT NULL,
  `ansid` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blacklist`
--

CREATE TABLE `blacklist` (
  `sid` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `classid` varchar(20) NOT NULL,
  `class` varchar(20) DEFAULT NULL,
  `arm` varchar(20) DEFAULT NULL,
  `staffid` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`classid`, `class`, `arm`, `staffid`) VALUES
('jss1a', 'jss1', 'a', 'STF001'),
('jss1b', 'jss1', 'b', ''),
('jss2a', 'jss2', 'a', ''),
('jss2b', 'jss2', 'b', ''),
('jss2c', 'jss2', 'c', ''),
('jss3a', 'jss3', 'a', ''),
('jss3b', 'jss3', 'b', ''),
('ss1a', 'ss1', 'a', ''),
('ss1b', 'ss1', 'b', ''),
('ss2a', 'ss2', 'a', ''),
('ss2b', 'ss2', 'b', ''),
('ss3a', 'ss3', 'a', '');

-- --------------------------------------------------------

--
-- Table structure for table `class_subject`
--

CREATE TABLE `class_subject` (
  `classid` varchar(20) NOT NULL,
  `sub_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_subject`
--

INSERT INTO `class_subject` (`classid`, `sub_id`) VALUES
('jss1a', 11),
('jss1a', 30);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `sid` varchar(20) NOT NULL,
  `term` varchar(10) NOT NULL,
  `session` varchar(30) NOT NULL,
  `status` text NOT NULL,
  `Guardian_Comments` text NOT NULL,
  `Criteria` text NOT NULL,
  `staffid` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `eid` text NOT NULL,
  `sub_id` int(11) NOT NULL,
  `classid` varchar(20) NOT NULL,
  `sahi` decimal(10,2) DEFAULT NULL,
  `waam` decimal(10,2) DEFAULT NULL,
  `timelimit` int(11) NOT NULL,
  `tnoq` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `instruction` text DEFAULT NULL,
  `termid` int(11) NOT NULL,
  `session` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `email` varchar(50) NOT NULL,
  `eid` text NOT NULL,
  `score` int(11) NOT NULL,
  `sahi` int(11) NOT NULL,
  `wrong` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tnoq` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `qid` varchar(50) NOT NULL,
  `option` text DEFAULT NULL,
  `optionid` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `eid` text NOT NULL,
  `qid` text NOT NULL,
  `qns` text DEFAULT NULL,
  `choice` int(10) NOT NULL,
  `sn` int(11) NOT NULL,
  `image` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `email` varchar(255) NOT NULL,
  `session` varchar(20) NOT NULL,
  `termid` int(11) NOT NULL,
  `classid` varchar(20) NOT NULL,
  `sub_id` int(11) NOT NULL,
  `ca` float DEFAULT NULL,
  `examobj` float DEFAULT NULL,
  `examtheory` float DEFAULT NULL,
  `noinclass` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staffid` varchar(20) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `midname` varchar(50) DEFAULT NULL,
  `lname` varchar(50) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `lga` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `jobtitle` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `qualifications` varchar(100) DEFAULT NULL,
  `schoolattended` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staffid`, `fname`, `midname`, `lname`, `address`, `gender`, `phone`, `state`, `lga`, `country`, `jobtitle`, `email`, `qualifications`, `schoolattended`, `password`) VALUES
('STF001', 'staff1', NULL, 'staff', NULL, 'Male', '26352762526', NULL, NULL, NULL, NULL, 'staff1@gmail.com', NULL, NULL, '1234567'),
('STF002', 'staff2', NULL, 'staff22', NULL, 'Female', '837363873826', NULL, NULL, NULL, NULL, 'staff2@gmail.com', NULL, NULL, '1234567');

-- --------------------------------------------------------

--
-- Table structure for table `staff_class_subject`
--

CREATE TABLE `staff_class_subject` (
  `staffid` varchar(20) NOT NULL,
  `classid` varchar(20) NOT NULL,
  `sub_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_class_subject`
--

INSERT INTO `staff_class_subject` (`staffid`, `classid`, `sub_id`) VALUES
('STF001', 'jss1a', 11),
('STF001', 'jss1a', 30),
('STF002', 'jss1b', 11),
('STF002', 'jss1b', 30);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `sid` varchar(20) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `midname` varchar(50) DEFAULT NULL,
  `lname` varchar(50) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `dob` date DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `lga` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `medicals` varchar(255) DEFAULT NULL,
  `classid` varchar(20) NOT NULL,
  `parentname` varchar(100) DEFAULT NULL,
  `parentoccupation` varchar(100) DEFAULT NULL,
  `parentmobile1` varchar(20) DEFAULT NULL,
  `parentmobile2` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`sid`, `fname`, `midname`, `lname`, `gender`, `dob`, `mobile`, `address`, `state`, `lga`, `country`, `email`, `medicals`, `classid`, `parentname`, `parentoccupation`, `parentmobile1`, `parentmobile2`, `password`) VALUES
('ID1234', 'Lamba', NULL, 'Waybad', '', NULL, '7777777777777', NULL, NULL, NULL, NULL, 'lamba@gmail.com', NULL, 'JSS1A', NULL, NULL, NULL, NULL, 'fcea920f7412b5da7be0cf42b8c93759');

-- --------------------------------------------------------

--
-- Table structure for table `student_class_subject`
--

CREATE TABLE `student_class_subject` (
  `sid` varchar(20) NOT NULL,
  `classid` varchar(20) NOT NULL,
  `sub_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_class_subject`
--

INSERT INTO `student_class_subject` (`sid`, `classid`, `sub_id`) VALUES
('ID1234', 'JSS1A', 11),
('ID1234', 'JSS1A', 30);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `sub_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`sub_id`, `subject`) VALUES
(11, 'mathematics'),
(30, 'english language');

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE `terms` (
  `termid` int(11) NOT NULL,
  `term` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `terms`
--

INSERT INTO `terms` (`termid`, `term`) VALUES
(1, 'First Term'),
(2, 'Second Term'),
(3, 'Third Term');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `staffid` (`staffid`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`classid`),
  ADD UNIQUE KEY `classid` (`classid`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`sid`,`term`,`session`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`email`,`session`,`termid`,`sub_id`),
  ADD KEY `termid` (`termid`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staffid`),
  ADD UNIQUE KEY `staffid` (`staffid`),
  ADD UNIQUE KEY `unique_staffid` (`staffid`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- Indexes for table `staff_class_subject`
--
ALTER TABLE `staff_class_subject`
  ADD PRIMARY KEY (`staffid`,`classid`,`sub_id`),
  ADD KEY `classid` (`classid`),
  ADD KEY `sub_id` (`sub_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD UNIQUE KEY `sid` (`sid`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`sub_id`);

--
-- Indexes for table `terms`
--
ALTER TABLE `terms`
  ADD PRIMARY KEY (`termid`),
  ADD UNIQUE KEY `termid` (`termid`),
  ADD UNIQUE KEY `term` (`term`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `sub_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `terms`
--
ALTER TABLE `terms`
  MODIFY `termid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`termid`) REFERENCES `terms` (`termid`);

--
-- Constraints for table `staff_class_subject`
--
ALTER TABLE `staff_class_subject`
  ADD CONSTRAINT `staff_class_subject_ibfk_1` FOREIGN KEY (`staffid`) REFERENCES `staff` (`staffid`),
  ADD CONSTRAINT `staff_class_subject_ibfk_2` FOREIGN KEY (`classid`) REFERENCES `class` (`classid`),
  ADD CONSTRAINT `staff_class_subject_ibfk_3` FOREIGN KEY (`sub_id`) REFERENCES `subjects` (`sub_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
