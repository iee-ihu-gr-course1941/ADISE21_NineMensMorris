-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2021 at 06:33 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nmm`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `clean_board` ()  BEGIN 
REPLACE INTO board SELECT * FROM boardempty;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `board`
--

CREATE TABLE `board` (
  `X` tinyint(1) NOT NULL,
  `Y` tinyint(1) NOT NULL,
  `COLOR` enum('w','b') COLLATE utf8_bin DEFAULT NULL,
  `Bcolor` enum('g','r') COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `board`
--

INSERT INTO `board` (`X`, `Y`, `COLOR`, `Bcolor`) VALUES
(1, 1, NULL, 'g'),
(1, 2, NULL, 'r'),
(1, 3, NULL, 'r'),
(1, 4, NULL, 'g'),
(1, 5, NULL, 'r'),
(1, 6, NULL, 'r'),
(1, 7, NULL, 'g'),
(2, 1, NULL, 'r'),
(2, 2, NULL, 'g'),
(2, 3, NULL, 'r'),
(2, 4, NULL, 'g'),
(2, 5, NULL, 'r'),
(2, 6, NULL, 'g'),
(2, 7, NULL, 'r'),
(3, 1, NULL, 'r'),
(3, 2, NULL, 'r'),
(3, 3, NULL, 'g'),
(3, 4, NULL, 'g'),
(3, 5, NULL, 'g'),
(3, 6, NULL, 'r'),
(3, 7, NULL, 'r'),
(4, 1, NULL, 'g'),
(4, 2, NULL, 'g'),
(4, 3, NULL, 'g'),
(4, 4, NULL, 'r'),
(4, 5, NULL, 'g'),
(4, 6, NULL, 'g'),
(4, 7, NULL, 'g'),
(5, 1, NULL, 'r'),
(5, 2, NULL, 'r'),
(5, 3, NULL, 'g'),
(5, 4, NULL, 'g'),
(5, 5, NULL, 'g'),
(5, 6, NULL, 'r'),
(5, 7, NULL, 'r'),
(6, 1, NULL, 'r'),
(6, 2, NULL, 'g'),
(6, 3, NULL, 'r'),
(6, 4, NULL, 'g'),
(6, 5, NULL, 'r'),
(6, 6, NULL, 'g'),
(6, 7, NULL, 'r'),
(7, 1, NULL, 'g'),
(7, 2, NULL, 'r'),
(7, 3, NULL, 'r'),
(7, 4, NULL, 'g'),
(7, 5, NULL, 'r'),
(7, 6, NULL, 'r'),
(7, 7, NULL, 'g');

-- --------------------------------------------------------

--
-- Table structure for table `boardempty`
--

CREATE TABLE `boardempty` (
  `x` tinyint(1) NOT NULL,
  `y` tinyint(1) NOT NULL,
  `color` enum('w','b') COLLATE utf8_bin DEFAULT NULL,
  `Bcolor` enum('g','r') COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `boardempty`
--

INSERT INTO `boardempty` (`x`, `y`, `color`, `Bcolor`) VALUES
(1, 1, NULL, 'g'),
(1, 2, NULL, 'r'),
(1, 3, NULL, 'r'),
(1, 4, NULL, 'g'),
(1, 5, NULL, 'r'),
(1, 6, NULL, 'r'),
(1, 7, NULL, 'g'),
(2, 1, NULL, 'r'),
(2, 2, NULL, 'g'),
(2, 3, NULL, 'r'),
(2, 4, NULL, 'g'),
(2, 5, NULL, 'r'),
(2, 6, NULL, 'g'),
(2, 7, NULL, 'r'),
(3, 1, NULL, 'r'),
(3, 2, NULL, 'r'),
(3, 3, NULL, 'g'),
(3, 4, NULL, 'g'),
(3, 5, NULL, 'g'),
(3, 6, NULL, 'r'),
(3, 7, NULL, 'r'),
(4, 1, NULL, 'g'),
(4, 2, NULL, 'g'),
(4, 3, NULL, 'g'),
(4, 4, NULL, 'r'),
(4, 5, NULL, 'g'),
(4, 6, NULL, 'g'),
(4, 7, NULL, 'g'),
(5, 1, NULL, 'r'),
(5, 2, NULL, 'r'),
(5, 3, NULL, 'g'),
(5, 4, NULL, 'g'),
(5, 5, NULL, 'g'),
(5, 6, NULL, 'r'),
(5, 7, NULL, 'r'),
(6, 1, NULL, 'r'),
(6, 2, NULL, 'g'),
(6, 3, NULL, 'r'),
(6, 4, NULL, 'g'),
(6, 5, NULL, 'r'),
(6, 6, NULL, 'g'),
(6, 7, NULL, 'r'),
(7, 1, NULL, 'g'),
(7, 2, NULL, 'r'),
(7, 3, NULL, 'r'),
(7, 4, NULL, 'g'),
(7, 5, NULL, 'r'),
(7, 6, NULL, 'r'),
(7, 7, NULL, 'g');

-- --------------------------------------------------------

--
-- Table structure for table `game_status`
--

CREATE TABLE `game_status` (
  `gamestatus` enum('not_active','initialized','started','ended','aborded') COLLATE utf8_bin NOT NULL DEFAULT 'not_active',
  `p_turn` enum('w','b') COLLATE utf8_bin DEFAULT NULL,
  `result` enum('b','w','d') COLLATE utf8_bin DEFAULT NULL,
  `last_change` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Triggers `game_status`
--
DELIMITER $$
CREATE TRIGGER `game_status_update` BEFORE UPDATE ON `game_status` FOR EACH ROW BEGIN 
SET NEW.last_change = NOW();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `username` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `color` enum('b','w') COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `board`
--
ALTER TABLE `board`
  ADD PRIMARY KEY (`X`,`Y`);

--
-- Indexes for table `boardempty`
--
ALTER TABLE `boardempty`
  ADD PRIMARY KEY (`x`,`y`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`color`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
