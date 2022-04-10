-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Apr 10, 2022 at 11:23 AM
-- Server version: 8.0.23
-- PHP Version: 8.0.16

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
-- Table structure for table `clan_attribute`
--

CREATE TABLE `clan_attribute` (
  `clan_id` int NOT NULL,
  `attribute_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clan_attribute`
--

INSERT INTO `clan_attribute` (`clan_id`, `attribute_id`) VALUES
(1, 5),
(1, 8),
(2, 6),
(2, 9),
(3, 1),
(3, 2),
(4, 4),
(4, 9),
(5, 3),
(5, 7);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clan_attribute`
--
ALTER TABLE `clan_attribute`
  ADD PRIMARY KEY (`clan_id`,`attribute_id`),
  ADD KEY `IDX_50770B59BEAF84C8` (`clan_id`),
  ADD KEY `IDX_50770B59B6E62EFA` (`attribute_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clan_attribute`
--
ALTER TABLE `clan_attribute`
  ADD CONSTRAINT `FK_50770B59B6E62EFA` FOREIGN KEY (`attribute_id`) REFERENCES `attribute` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_50770B59BEAF84C8` FOREIGN KEY (`clan_id`) REFERENCES `clan` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
