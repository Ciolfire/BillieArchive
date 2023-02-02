-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 30, 2023 at 01:03 AM
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
-- Table structure for table `discipline_power_skill`
--

CREATE TABLE `discipline_power_skill` (
  `discipline_power_id` int NOT NULL,
  `skill_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discipline_power_skill`
--

INSERT INTO `discipline_power_skill` (`discipline_power_id`, `skill_id`) VALUES
(1, 17),
(2, 17),
(3, 17),
(4, 17),
(5, 18),
(7, 18),
(8, 6),
(9, 22),
(10, 6),
(12, 20),
(13, 19),
(14, 21),
(15, 24),
(16, 20),
(17, 19),
(18, 21),
(19, 18),
(20, 21),
(21, 20),
(22, 20),
(23, 18),
(24, 18),
(25, 18),
(26, 20),
(27, 13),
(29, 14),
(30, 24),
(31, 14),
(39, 6),
(41, 6),
(42, 6),
(47, 5),
(49, 15),
(50, 5),
(51, 15),
(52, 5),
(53, 4),
(54, 9),
(55, 3),
(56, 6),
(57, 5),
(60, 8),
(62, 6),
(63, 16),
(67, 21),
(68, 13),
(69, 3),
(70, 6),
(71, 6),
(72, 4),
(73, 8),
(74, 6),
(75, 8),
(78, 4),
(79, 18),
(80, 6),
(81, 6),
(83, 6),
(85, 18),
(88, 15),
(90, 15),
(95, 19),
(96, 6),
(98, 21),
(99, 21),
(100, 21),
(101, 21),
(102, 21);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `discipline_power_skill`
--
ALTER TABLE `discipline_power_skill`
  ADD PRIMARY KEY (`discipline_power_id`,`skill_id`),
  ADD KEY `IDX_C0F87A51C9F8163B` (`discipline_power_id`),
  ADD KEY `IDX_C0F87A515585C142` (`skill_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `discipline_power_skill`
--
ALTER TABLE `discipline_power_skill`
  ADD CONSTRAINT `FK_C0F87A515585C142` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_C0F87A51C9F8163B` FOREIGN KEY (`discipline_power_id`) REFERENCES `discipline_power` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
