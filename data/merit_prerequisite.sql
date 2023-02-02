-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 01, 2023 at 06:49 PM
-- Server version: 8.0.31
-- PHP Version: 8.1.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `darklib`
--

-- --------------------------------------------------------

--
-- Table structure for table `merit_prerequisite`
--

CREATE TABLE `merit_prerequisite` (
  `merit_id` int NOT NULL,
  `prerequisite_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `merit_prerequisite`
--

INSERT INTO `merit_prerequisite` (`merit_id`, `prerequisite_id`) VALUES
(8, 67),
(10, 8),
(10, 9),
(12, 17),
(12, 18),
(13, 23),
(15, 5),
(15, 6),
(15, 7),
(16, 35),
(16, 36),
(16, 37),
(16, 38),
(17, 64),
(17, 65),
(18, 24),
(19, 25),
(21, 29),
(21, 30),
(22, 32),
(22, 33),
(23, 34),
(24, 42),
(25, 43),
(26, 44),
(27, 56),
(28, 57),
(29, 58),
(30, 63),
(31, 68),
(31, 69),
(36, 31),
(45, 20),
(45, 21),
(45, 22),
(47, 2),
(47, 3),
(47, 4),
(48, 10),
(48, 11),
(48, 12),
(49, 13),
(49, 14),
(49, 15),
(49, 16),
(52, 46),
(52, 47),
(52, 48),
(52, 49),
(53, 50),
(53, 51),
(54, 53),
(54, 54),
(54, 55),
(58, 45),
(59, 52),
(60, 66),
(62, 19),
(63, 26),
(63, 27),
(63, 28),
(64, 60),
(64, 61),
(64, 62),
(67, 59),
(81, 39),
(81, 40),
(81, 41);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `merit_prerequisite`
--
ALTER TABLE `merit_prerequisite`
  ADD PRIMARY KEY (`merit_id`,`prerequisite_id`),
  ADD KEY `IDX_7BC20DCF58D79B5E` (`merit_id`),
  ADD KEY `IDX_7BC20DCF276AF86B` (`prerequisite_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `merit_prerequisite`
--
ALTER TABLE `merit_prerequisite`
  ADD CONSTRAINT `FK_7BC20DCF276AF86B` FOREIGN KEY (`prerequisite_id`) REFERENCES `prerequisite` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_7BC20DCF58D79B5E` FOREIGN KEY (`merit_id`) REFERENCES `merits` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
