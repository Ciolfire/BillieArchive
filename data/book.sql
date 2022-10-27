-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Oct 27, 2022 at 09:02 PM
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
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruleset` smallint NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `released_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `setting` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`id`, `name`, `ruleset`, `type`, `short`, `released_at`, `setting`, `cover`) VALUES
(1, 'The World of Darkness', 1, 'Core', 'WoD', '2004-08-08 00:00:00', 'human', 'index-6359e1469c866.jpg'),
(2, 'Vampire: The Requiem', 1, 'Core', 'VtR', '2004-08-01 00:00:00', 'vampire', '123898b-6359e28ce5a33.webp'),
(3, 'Armory', 1, 'Equipment', 'Arm', '2006-01-01 00:00:00', 'human', 'Wodarmory-6359e20c3bc52.webp'),
(4, 'Ancient Bloodlines', 1, 'Bloodlines', 'AB', '2009-05-01 00:00:00', 'vampire', 'Vtrancientbloodlines-6359e29a1ce3f.webp'),
(5, 'Antagonists', 1, NULL, 'Ant', '2004-10-01 00:00:00', 'human', 'Wodantagonists-635ad5c8115d7.webp'),
(6, 'Armory Reloaded', 1, 'Equipment', 'Reload', '2009-04-01 00:00:00', 'human', 'Wodarmouryreloaded-635ad5ae9a62b.webp'),
(7, 'Asylum', 1, NULL, 'Asy', '2007-08-01 00:00:00', 'human', 'Wodasylum-635acfe611917.webp'),
(8, 'Book of Spirits', 1, NULL, 'BoS', '2004-01-01 00:00:00', 'human', 'Wodbookofspirits-635ad01c91ab4.webp'),
(9, 'Changing Breeds', 1, NULL, NULL, '2007-12-01 00:00:00', 'human', 'Wodchangingbreeds-635ad08be7215.webp'),
(10, 'Chicago', 1, 'Location', 'Chi', '2005-12-01 00:00:00', 'human', 's-l1600-635ad27789385.jpg'),
(11, 'Dogs of War', 1, NULL, 'DoW', '2008-06-01 00:00:00', 'human', 'Woddogsofwar-635ad2c271b24.webp'),
(12, 'Ghost Stories', 1, NULL, 'GS', '2004-11-01 00:00:00', 'human', 'Wodghoststories-635ad328645df.webp'),
(13, 'Immortals', 1, NULL, 'Imm', '2009-05-01 00:00:00', 'human', 'Wodimmortals-635ad3694843a.webp'),
(14, 'Inferno', 1, NULL, 'Inf', '2009-01-01 00:00:00', 'human', 'Wodinferno-635ad56e8be3e.webp'),
(15, 'Innocents', 1, NULL, 'Inn', '2008-04-01 00:00:00', 'human', 'Wodinnocents-635ad3ee8f653.webp'),
(16, 'Midnight Roads', 1, NULL, 'MR', '2008-02-01 00:00:00', 'human', 'Wodmidnightroads-635ad41167928.webp'),
(17, 'Mirrors', 1, 'Alternate Setting', 'Mir', '2010-07-01 00:00:00', 'human', 'Wodmirrors-635ad478a680d.webp'),
(18, 'Mysterious Places', 1, NULL, 'MP', '2005-06-01 00:00:00', 'human', 'Wodmysteriousplaces-635ad4c18d95a.webp'),
(19, 'Reliquary', 1, NULL, 'Rel', '2007-08-01 00:00:00', 'human', 'Wodreliquary-635ad4df70b6d.webp'),
(20, 'Second Sight', 1, NULL, 'SS', '2006-04-01 00:00:00', 'human', 'Wodsecondsight-635ad500e38a8.webp'),
(21, 'Shadows of Mexico', 1, 'Location', 'SoM', '2006-10-01 00:00:00', 'human', 'Wodshadowsofmexico-635ad5389c632.webp'),
(22, 'Shadows of the UK', 1, 'Location', 'SotUK', '2006-06-01 00:00:00', 'human', 'Wodshadowsoftheuk-635ad756c7335.webp'),
(23, 'Skinchangers', 1, NULL, 'Skin', '2006-07-01 00:00:00', 'human', 'Wodskinchangers-635ad77ee339b.webp'),
(24, 'Tales from the 13th Precinct', 1, NULL, '13th', '2006-07-01 00:00:00', 'human', 'Wodtalesfromthe13thprecinct-635ad7b1a3bc8.webp'),
(25, 'Urban Legends', 1, NULL, 'UL', '2007-04-01 00:00:00', 'human', 'Wodurbanlegends-635ad7d78f3b8.webp'),
(26, 'Dark Eras', 1, 'Alternate Setting', 'DE', '2016-05-01 00:00:00', 'human', 'Dark-Eras-635ad9ae08a4e.webp'),
(27, 'Dark Eras: Three Kingdoms of Darkness', 1, 'Alternate Setting', NULL, '2017-06-01 00:00:00', 'human', 'Darkerasthreekingdomsofdarkness-635ad9e1cdf0f.webp'),
(28, 'Dark Eras: The Wolf and the Rave', 1, 'Alternate Setting', NULL, '2017-01-01 00:00:00', 'human', 'WolfRaven-635ada17bddb2.webp'),
(29, 'Mirrors: Bleeding Edge', 1, 'Alternate Setting', 'MirBE', '2011-01-01 00:00:00', 'human', 'Wodmirrorsbleedingedge-635adb9c1b69d.webp'),
(30, 'Mirrors: Infinite Macabre', 1, 'Alternate Setting', 'MirIM', '2011-01-01 00:00:00', 'human', 'Wodmirrorsinfinitemacabre-635adbde683b6.webp'),
(31, 'Proverbial Monsters', 1, NULL, 'PM', '2009-10-01 00:00:00', 'human', 'Wodproverbialmonsters-635adc1156a4e.webp'),
(32, 'Shadows of Iceland', 1, 'Joke', NULL, '2008-04-01 00:00:00', 'human', 'Wodshadowsoficeland-635adc58b641e.webp'),
(33, 'Ancient Mysteries', 1, NULL, 'AM', '2009-04-01 00:00:00', 'vampire', 'Vtrancientmysteries-635adcae5eaa0.webp'),
(34, 'The Beast That Haunts the Blood: Nosferatu', 1, NULL, NULL, '2009-03-01 00:00:00', 'vampire', 'Vtrthebeastthathauntsthebloodnosferatu-635adcf359fb3.webp'),
(35, 'Belial\'s Brood', 1, NULL, 'BB', '2007-01-01 00:00:00', 'vampire', 'Vtrbelialsbrood-635add2449f15.webp'),
(36, 'The Blood', 1, NULL, 'Blood', '2007-05-01 00:00:00', 'vampire', 'Vtrtheblood-635add5fd5ec5.webp'),
(37, 'Bloodlines: the Chosen', 1, 'Bloodlines', 'BtC', '2007-07-01 00:00:00', 'vampire', '51iRmtVNpiL-635adda4d30ce.jpg'),
(38, 'Bloodlines: the Hidden', 1, 'Bloodlines', 'BtH', '2005-02-01 00:00:00', 'vampire', '25102-635ade598d9b9.webp'),
(39, 'Bloodlines: the Legendary', 1, 'Bloodlines', 'BtL', '2006-01-01 00:00:00', 'vampire', 'Vtrbloodlinesthelegendary-635ade91b0292.webp'),
(40, 'Carthians', 1, NULL, 'Cart', '2006-04-01 00:00:00', 'vampire', 'Vtrcarthians-635adeb869cae.webp'),
(41, 'Circle of the Crone', 1, NULL, 'Crone', '2006-08-01 00:00:00', 'vampire', 'Vtrcircleofthecrone-635adedaad97f.webp'),
(42, 'City of the Damned: New Orleans', 1, 'Location', 'NOrl', '2005-05-01 00:00:00', 'vampire', 'Vtrcityofthedamnedneworleans-635adf0938d43.webp'),
(43, 'Coteries', 1, NULL, 'Cote', '2004-10-01 00:00:00', 'vampire', 'Vtrcoteries-635adf2cd2f21.webp'),
(44, 'Damnation City', 1, NULL, 'DC', '2007-08-01 00:00:00', 'vampire', 'Vtrdamnationcity-635adf5724e0b.webp'),
(45, 'The Danse Macabre', 1, NULL, 'DM', '2011-03-01 00:00:00', 'vampire', 'Vtrthedansemacabre-635adf7c9069c.webp'),
(46, 'Requiem for Rome', 1, 'Alternate Setting', 'RfR', '2007-11-01 00:00:00', 'vampire', 'Vtrrequiemforrome-635adfc8c7223.webp'),
(47, 'Requiem for Rome: Fall of the Camarilla', 1, 'Alternate Setting', 'FotC', '2008-01-01 00:00:00', 'vampire', 'Fotccover-635adfe80de58.webp'),
(48, 'Ghouls', 1, NULL, 'Ghou', '2005-05-01 00:00:00', 'vampire', 'Vtrghouls-635ae0256be3a.webp'),
(49, 'Invictus', 1, NULL, 'Inv', '2005-10-01 00:00:00', 'vampire', 'Vtrinvictus-635ae04eb84ed.webp'),
(50, 'Kiss of the Succubus: Daeva', 1, NULL, 'Daeva', '2008-05-01 00:00:00', 'vampire', 'Vtrkissofthesuccubusdaeva-635ae08077bf4.webp'),
(51, 'Lancea Sanctum', 1, NULL, 'Lancea', '2005-03-01 00:00:00', 'vampire', 'Vtrlanceasanctum-635ae0b87fc8c.webp'),
(52, 'Lords Over the Damned: Ventrue', 1, NULL, 'Vent', '2008-04-01 00:00:00', 'vampire', 'Vtrlordsoverthedamnedventrue-635ae0da3395b.webp'),
(53, 'Mythologies', 1, NULL, 'Myth', '2006-06-01 00:00:00', 'vampire', 'Vtrmythologies-635ae109cbc21.webp'),
(54, 'Night Horrors: Immortal Sinners', 1, NULL, 'NH-IS', '2009-03-01 00:00:00', 'vampire', 'Vtrnighhorrorsimmortalsinners-635ae13eb6a75.webp'),
(55, 'Night Horrors: Wicked Dead', 1, NULL, 'NH-WD', '2009-09-01 00:00:00', 'vampire', 'Vtrnighthorrorswickeddead-635ae16aa4baf.webp'),
(56, 'Nomads', 1, NULL, 'Noma', '2004-11-01 00:00:00', 'vampire', 'Vtrnomads-635ae18ee90d8.webp'),
(57, 'Ordo Dracul', 1, NULL, 'Ordo', '2005-07-01 00:00:00', 'vampire', 'Vtrordodracul-635ae1d5dfd3c.webp'),
(58, 'Requiem Chroniclers Guide', 1, NULL, 'RCG', '2006-02-01 00:00:00', 'vampire', 'Vtrrequiemchroniclersguide-635ae20a731e2.webp'),
(59, 'Rites of the Dragon', 1, NULL, NULL, '2004-11-01 00:00:00', 'vampire', 'Vtrritesofthedragon-635ae2486ef74.webp'),
(60, 'Savage and Macabre: Gangrel', 1, NULL, 'Gan', '2008-09-01 00:00:00', 'vampire', 'Vtrsavageandmacabregangrel-635ae2784c284.webp'),
(61, 'Shadows in the Dark: Mekhet', 1, NULL, 'Mekh', '2009-02-01 00:00:00', 'vampire', 'Vtrshadowsinthedarkmekhet-635ae2a22f6d4.webp'),
(62, 'VII', 1, NULL, 'VII', '2005-08-01 00:00:00', 'vampire', 'Vtrvii-635ae2d4da8c5.webp'),
(63, 'Blood Sorcery: Sacraments and Blasphemies', 1, NULL, 'BSorc', '2012-09-01 00:00:00', 'vampire', 'Vtrbloodsorcery-635ae2fdde9c6.webp'),
(64, 'Invite Only', 1, NULL, 'InvO', '2010-07-01 00:00:00', 'vampire', 'Vtrinviteonly-635ae33dc713e.webp'),
(65, 'New Wave Requiem', 1, 'Alternate Setting', 'NWR', '2009-02-01 00:00:00', 'vampire', 'Vtrnewwaverequiem-635ae57d35c92.webp'),
(66, 'Strange, Dead Love', 1, 'Alternate Setting', 'SDL', '2011-12-01 00:00:00', 'vampire', 'Vtrstrangedeadlove-635ae5bc44304.webp'),
(67, 'Vampire Translation Guide', 1, 'Translation', 'VTG', '2010-11-01 00:00:00', 'vampire', 'Vampiretranslationguide-635ae5e29bc60.webp');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
