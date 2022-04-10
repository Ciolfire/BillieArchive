-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Apr 10, 2022 at 11:24 AM
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
-- Table structure for table `skill`
--

CREATE TABLE `skill` (
  `id` int NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fluff` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skill`
--

INSERT INTO `skill` (`id`, `name`, `category`, `fluff`, `description`) VALUES
(1, 'academics', 'mental', NULL, NULL),
(2, 'computer', 'mental', NULL, NULL),
(3, 'crafts', 'mental', NULL, NULL),
(4, 'investigation', 'mental', NULL, NULL),
(5, 'medecine', 'mental', NULL, NULL),
(6, 'occult', 'mental', NULL, NULL),
(7, 'politics', 'mental', NULL, NULL),
(8, 'science', 'mental', NULL, NULL),
(9, 'athletics', 'physical', NULL, NULL),
(10, 'brawl', 'physical', NULL, NULL),
(11, 'drive', 'physical', NULL, NULL),
(12, 'firearms', 'physical', NULL, NULL),
(13, 'larceny', 'physical', NULL, NULL),
(14, 'stealth', 'physical', NULL, NULL),
(15, 'survival', 'physical', NULL, NULL),
(16, 'weaponry', 'physical', NULL, NULL),
(17, 'animalKen', 'social', NULL, NULL),
(18, 'empathy', 'social', NULL, NULL),
(19, 'expression', 'social', NULL, NULL),
(20, 'intimidation', 'social', NULL, NULL),
(21, 'persuasion', 'social', NULL, NULL),
(22, 'socialize', 'social', NULL, NULL),
(23, 'streetwise', 'social', NULL, NULL),
(24, 'subterfuge', 'social', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `skill`
--
ALTER TABLE `skill`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `skill`
--
ALTER TABLE `skill`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
