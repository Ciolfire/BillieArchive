-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 01, 2023 at 06:29 PM
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
-- Table structure for table `prerequisite`
--

CREATE TABLE `prerequisite` (
  `id` int NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` int NOT NULL,
  `value` smallint NOT NULL,
  `choice_group` smallint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prerequisite`
--

INSERT INTO `prerequisite` (`id`, `type`, `entity_id`, `value`, `choice_group`) VALUES
(1, 'App\\Entity\\Attribute', 5, 3, NULL),
(2, 'App\\Entity\\Skill', 9, 2, NULL),
(3, 'App\\Entity\\Attribute', 5, 2, NULL),
(4, 'App\\Entity\\Attribute', 4, 2, NULL),
(5, 'App\\Entity\\Skill', 10, 2, NULL),
(6, 'App\\Entity\\Attribute', 4, 3, NULL),
(7, 'App\\Entity\\Attribute', 6, 2, NULL),
(8, 'App\\Entity\\Skill', 10, 1, NULL),
(9, 'App\\Entity\\Attribute', 4, 2, NULL),
(10, 'App\\Entity\\Skill', 16, 3, NULL),
(11, 'App\\Entity\\Attribute', 5, 3, NULL),
(12, 'App\\Entity\\Attribute', 4, 2, NULL),
(13, 'App\\Entity\\Skill', 12, 2, NULL),
(14, 'App\\Entity\\Attribute', 5, 2, NULL),
(15, 'App\\Entity\\Attribute', 4, 2, NULL),
(16, 'App\\Entity\\Attribute', 9, 3, NULL),
(17, 'App\\Entity\\Skill', 16, 2, NULL),
(18, 'App\\Entity\\Attribute', 5, 3, NULL),
(19, 'App\\Entity\\Attribute', 3, 2, NULL),
(20, 'App\\Entity\\Attribute', 2, 3, 1),
(21, 'App\\Entity\\Attribute', 5, 3, 1),
(22, 'App\\Entity\\Skill', 3, 3, NULL),
(23, 'App\\Entity\\Attribute', 5, 3, NULL),
(24, 'App\\Entity\\Attribute', 4, 2, NULL),
(25, 'App\\Entity\\Merit', 13, 2, NULL),
(26, 'App\\Entity\\Skill', 1, 2, 1),
(27, 'App\\Entity\\Skill', 5, 2, 1),
(28, 'App\\Entity\\Skill', 8, 2, 1),
(29, 'App\\Entity\\Attribute', 5, 3, NULL),
(30, 'App\\Entity\\Skill', 12, 3, NULL),
(31, 'App\\Entity\\Attribute', 7, 4, NULL),
(32, 'App\\Entity\\Attribute', 6, 3, NULL),
(33, 'App\\Entity\\Attribute', 3, 3, NULL),
(34, 'App\\Entity\\Attribute', 6, 2, NULL),
(35, 'App\\Entity\\Attribute', 4, 2, NULL),
(36, 'App\\Entity\\Attribute', 5, 2, NULL),
(37, 'App\\Entity\\Attribute', 6, 2, NULL),
(38, 'App\\Entity\\Skill', 10, 2, NULL),
(39, 'App\\Entity\\Attribute', 4, 3, NULL),
(40, 'App\\Entity\\Attribute', 6, 2, NULL),
(41, 'App\\Entity\\Skill', 10, 2, NULL),
(42, 'App\\Entity\\Attribute', 6, 2, NULL),
(43, 'App\\Entity\\Attribute', 5, 3, NULL),
(44, 'App\\Entity\\Attribute', 6, 3, NULL),
(45, 'App\\Entity\\Skill', 18, 3, NULL),
(46, 'App\\Entity\\Attribute', 5, 3, NULL),
(47, 'App\\Entity\\Attribute', 3, 3, NULL),
(48, 'App\\Entity\\Skill', 12, 3, NULL),
(49, 'App\\Entity\\Skill', 14, 2, NULL),
(50, 'App\\Entity\\Attribute', 5, 3, NULL),
(51, 'App\\Entity\\Skill', 16, 2, NULL),
(52, 'App\\Entity\\Merit', 38, 1, NULL),
(53, 'App\\Entity\\Attribute', 4, 3, NULL),
(54, 'App\\Entity\\Attribute', 5, 2, NULL),
(55, 'App\\Entity\\Skill', 16, 2, NULL),
(56, 'App\\Entity\\Attribute', 4, 2, NULL),
(57, 'App\\Entity\\Skill', 9, 3, NULL),
(58, 'App\\Entity\\Attribute', 5, 3, NULL),
(59, 'App\\Entity\\Merit', 40, 1, NULL),
(60, 'App\\Entity\\Attribute', 3, 2, 1),
(61, 'App\\Entity\\Attribute', 6, 2, 1),
(62, 'App\\Entity\\Attribute', 9, 2, 1),
(63, 'App\\Entity\\Attribute', 6, 3, NULL),
(64, 'App\\Entity\\Attribute', 5, 3, NULL),
(65, 'App\\Entity\\Skill', 16, 3, NULL),
(66, 'App\\Entity\\Skill', 14, 2, NULL),
(67, 'App\\Entity\\Attribute', 2, 2, NULL),
(68, 'App\\Entity\\Attribute', 4, 2, NULL),
(69, 'App\\Entity\\Skill', 16, 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `prerequisite`
--
ALTER TABLE `prerequisite`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `prerequisite`
--
ALTER TABLE `prerequisite`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
