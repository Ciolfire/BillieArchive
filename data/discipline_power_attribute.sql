-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 30, 2023 at 01:02 AM
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
-- Table structure for table `discipline_power_attribute`
--

CREATE TABLE `discipline_power_attribute` (
  `discipline_power_id` int NOT NULL,
  `attribute_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discipline_power_attribute`
--

INSERT INTO `discipline_power_attribute` (`discipline_power_id`, `attribute_id`) VALUES
(1, 8),
(2, 7),
(3, 7),
(4, 8),
(5, 8),
(7, 1),
(8, 2),
(9, 1),
(10, 1),
(12, 1),
(13, 1),
(14, 2),
(15, 2),
(16, 1),
(17, 7),
(18, 8),
(19, 8),
(20, 8),
(21, 7),
(22, 7),
(23, 8),
(24, 7),
(25, 8),
(26, 7),
(27, 2),
(29, 1),
(30, 2),
(31, 1),
(39, 7),
(41, 6),
(42, 3),
(47, 1),
(49, 2),
(50, 1),
(51, 2),
(52, 9),
(53, 2),
(54, 6),
(55, 5),
(56, 1),
(57, 5),
(60, 4),
(62, 6),
(63, 6),
(67, 2),
(68, 7),
(69, 2),
(70, 2),
(71, 8),
(72, 1),
(73, 1),
(74, 3),
(75, 1),
(78, 3),
(79, 1),
(80, 7),
(81, 3),
(83, 1),
(85, 7),
(88, 4),
(90, 7),
(94, 3),
(94, 9),
(95, 3),
(96, 3),
(97, 3),
(97, 9),
(98, 7),
(99, 8),
(100, 8),
(101, 7),
(102, 7);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `discipline_power_attribute`
--
ALTER TABLE `discipline_power_attribute`
  ADD PRIMARY KEY (`discipline_power_id`,`attribute_id`),
  ADD KEY `IDX_299417ABC9F8163B` (`discipline_power_id`),
  ADD KEY `IDX_299417ABB6E62EFA` (`attribute_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `discipline_power_attribute`
--
ALTER TABLE `discipline_power_attribute`
  ADD CONSTRAINT `FK_299417ABB6E62EFA` FOREIGN KEY (`attribute_id`) REFERENCES `attribute` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_299417ABC9F8163B` FOREIGN KEY (`discipline_power_id`) REFERENCES `discipline_power` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
