-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2021 at 07:12 PM
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
DROP PROCEDURE IF EXISTS `reset_board`;
DELIMITER $$
--
-- Procedures
--

CREATE DEFINER=`root`@`localhost` PROCEDURE `reset_board` ()  BEGIN 
  REPLACE INTO board SELECT * FROM boardempty;
  update players set username=null, token=null, playerNumber=0;
  update game_status set status='not active', p_turn=null, result=null;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `board`
--
DROP TABLE IF EXISTS `board`;

CREATE TABLE `board` (
  `X` tinyint(1) NOT NULL,
  `Y` tinyint(1) NOT NULL,
  `piece` enum('1','2','3','4','5','6') COLLATE utf8_bin DEFAULT NULL,
  `piece_color` enum('W','B') COLLATE utf8_bin DEFAULT NULL,
  `Bcolor` enum('g','r') COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`X`,`Y`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `board`
--

INSERT INTO `board` (`X`, `Y`,`piece`, `piece_color`, `Bcolor`) VALUES
(1, 1, NULL, NULL , 'g'),
(1, 2, NULL, NULL , 'r'),
(1, 3, NULL, NULL , 'r'),
(1, 4, NULL, NULL , 'g'),
(1, 5, NULL, NULL , 'r'),
(1, 6, NULL, NULL , 'r'),
(1, 7, NULL, NULL , 'g'),
(2, 1, NULL, NULL , 'r'),
(2, 2, NULL, NULL , 'g'),
(2, 3, NULL, NULL , 'r'),
(2, 4, NULL, NULL , 'g'),
(2, 5, NULL, NULL , 'r'),
(2, 6, NULL, NULL , 'g'),
(2, 7, NULL, NULL , 'r'),
(3, 1, NULL, NULL , 'r'),
(3, 2, NULL, NULL , 'r'),
(3, 3, NULL, NULL , 'g'),
(3, 4, NULL, NULL , 'g'),
(3, 5, NULL, NULL , 'g'),
(3, 6, NULL, NULL , 'r'),
(3, 7, NULL, NULL , 'r'),
(4, 1, NULL, NULL , 'g'),
(4, 2, NULL, NULL , 'g'),
(4, 3, NULL, NULL , 'g'),
(4, 4, NULL, NULL , 'r'),
(4, 5, NULL, NULL , 'g'),
(4, 6, NULL, NULL , 'g'),
(4, 7, NULL, NULL , 'g'),
(5, 1, NULL, NULL , 'r'),
(5, 2, NULL, NULL , 'r'),
(5, 3, NULL, NULL , 'g'),
(5, 4, NULL, NULL , 'g'),
(5, 5, NULL, NULL , 'g'),
(5, 6, NULL, NULL , 'r'),
(5, 7, NULL, NULL , 'r'),
(6, 1, NULL, NULL , 'r'),
(6, 2, NULL, NULL , 'g'),
(6, 3, NULL, NULL , 'r'),
(6, 4, NULL, NULL , 'g'),
(6, 5, NULL, NULL , 'r'),
(6, 6, NULL, NULL , 'g'),
(6, 7, NULL, NULL , 'r'),
(7, 1, NULL, NULL , 'g'),
(7, 2, NULL, NULL , 'r'),
(7, 3, NULL, NULL , 'r'),
(7, 4, NULL, NULL , 'g'),
(7, 5, NULL, NULL , 'r'),
(7, 6, NULL, NULL , 'r'),
(7, 7, NULL, NULL , 'g');

-- --------------------------------------------------------

--
-- Table structure for table `boardempty`
--

DROP TABLE IF EXISTS `boardempty`;

CREATE TABLE `boardempty` (
  `X` tinyint(1) NOT NULL,
  `Y` tinyint(1) NOT NULL,
  `piece` enum('1','2','3','4','5','6') COLLATE utf8_bin DEFAULT NULL,
  `piece_color` enum('W','B') COLLATE utf8_bin DEFAULT NULL,
  `Bcolor` enum('g','r') COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`X`,`Y`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `boardempty`
--

INSERT INTO `boardempty` (`X`, `Y`, `piece`, `piece_color`, `Bcolor`) VALUES
(1, 1, NULL, NULL , 'g'),
(1, 2, NULL, NULL , 'r'),
(1, 3, NULL, NULL , 'r'),
(1, 4, NULL, NULL , 'g'),
(1, 5, NULL, NULL , 'r'),
(1, 6, NULL, NULL , 'r'),
(1, 7, NULL, NULL , 'g'),
(2, 1, NULL, NULL , 'r'),
(2, 2, NULL, NULL , 'g'),
(2, 3, NULL, NULL , 'r'),
(2, 4, NULL, NULL , 'g'),
(2, 5, NULL, NULL , 'r'),
(2, 6, NULL, NULL , 'g'),
(2, 7, NULL, NULL , 'r'),
(3, 1, NULL, NULL , 'r'),
(3, 2, NULL, NULL , 'r'),
(3, 3, NULL, NULL , 'g'),
(3, 4, NULL, NULL , 'g'),
(3, 5, NULL, NULL , 'g'),
(3, 6, NULL, NULL , 'r'),
(3, 7, NULL, NULL , 'r'),
(4, 1, NULL, NULL , 'g'),
(4, 2, NULL, NULL , 'g'),
(4, 3, NULL, NULL , 'g'),
(4, 4, NULL, NULL , 'r'),
(4, 5, NULL, NULL , 'g'),
(4, 6, NULL, NULL , 'g'),
(4, 7, NULL, NULL , 'g'),
(5, 1, NULL, NULL , 'r'),
(5, 2, NULL, NULL , 'r'),
(5, 3, NULL, NULL , 'g'),
(5, 4, NULL, NULL , 'g'),
(5, 5, NULL, NULL , 'g'),
(5, 6, NULL, NULL , 'r'),
(5, 7, NULL, NULL , 'r'),
(6, 1, NULL, NULL , 'r'),
(6, 2, NULL, NULL , 'g'),
(6, 3, NULL, NULL , 'r'),
(6, 4, NULL, NULL , 'g'),
(6, 5, NULL, NULL , 'r'),
(6, 6, NULL, NULL , 'g'),
(6, 7, NULL, NULL , 'r'),
(7, 1, NULL, NULL , 'g'),
(7, 2, NULL, NULL , 'r'),
(7, 3, NULL, NULL , 'r'),
(7, 4, NULL, NULL , 'g'),
(7, 5, NULL, NULL , 'r'),
(7, 6, NULL, NULL , 'r'),
(7, 7, NULL, NULL , 'g');

-- --------------------------------------------------------

--
-- Table structure for table `game_status`
--
--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;

CREATE TABLE `players` (
  `username` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `token` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `last_action` timestamp NULL DEFAULT NULL,
  `piece_color` enum('W','B') COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`piece_color`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`username`, `token`, `last_action`, `piece_color`) VALUES
(NULL, NULL, NULL, 'W'),
(NULL, NULL, NULL, 'B');


DROP TABLE IF EXISTS `game_status`;

CREATE TABLE `game_status` (
  `status` enum('not_active','initialized','started','ended','aborded') COLLATE utf8_bin NOT NULL DEFAULT 'not_active',
  `p_turn` enum('W','B') COLLATE utf8_bin DEFAULT NULL,
  `result` enum('W','B','D') COLLATE utf8_bin DEFAULT NULL,
  `last_change` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `game_status`
--

INSERT INTO `game_status` (`status`, `p_turn`, `result`, `last_change`) VALUES
('started', 'W', NULL, '2021-12-10 18:11:02');

--
-- Triggers `game_status`
--
DROP TRIGGER IF EXISTS`game_status_update`;

DELIMITER ;;
CREATE DEFINER=`root`@`localhost` TRIGGER `game_status_update` BEFORE UPDATE ON `game_status`
FOR EACH ROW BEGIN 
  SET NEW.last_change = NOW();
END;;
DELIMITER ;




DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `move_piece`(x1 tinyint,y1 tinyint)
BEGIN
	declare  p, p_color char;
	
	select  piece, piece_color into p, p_color FROM `board` WHERE X=x1 AND Y=y1;
	
	update board
	set piece=p, piece_color=p_color
	where x=x2 and y=y2;

	update game_status set p_turn=if(p_color='W','B','W');
	
    END ;;
DELIMITER ;

-- --------------------------------------------------------


--
-- Indexes for dumped tables
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
