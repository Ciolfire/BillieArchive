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
-- Table structure for table `vice`
--

CREATE TABLE `vice` (
  `id` int NOT NULL,
  `name` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vice`
--

INSERT INTO `vice` (`id`, `name`, `details`) VALUES
(1, 'Envy', 'An envious person is never satisfied with what she has.\r\n\r\nNo matter her wealth, status or accomplishments, there is always someone else who seems to have more, and it’s coveted. Envious characters are never secure or content with their place in life. They always measure themselves against their rivals and look for ways to get what they deserve. They might be considered paranoid or just consumed by a self-loathing that they project onto others.\r\n\r\nYour character regains one Willpower point whenever she gains something important from a rival or has a hand in harming that rival’s wellbeing.\r\n\r\n**Example:** *Hughes watched the reporters, sponsors and women flock to Montgomery like moths to a flame. One-tenth of a second in the 40 meter and a couple slick moves on the field were all that made Hughes the low-paid fullback and Montgomery the star tailback. Now it was the big Monday-night game and the attention was all on Montgomery.*\r\n\r\n*“Drink this and you’ll be MVP tonight.”*\r\n\r\n*At first, the voice seemed to come out of nowhere, but then there he was, one of the ugliest guys Hughes had ever seen, sitting right there in the locker room, grinning like the Cheshire Cat.*\r\n\r\n*Who the hell are you? How’d you get in here?”*\r\n\r\n*“Kick-off is in five minutes, Hughes. You want to be the star tonight? Then drink up. You want Montgomery to get the headlines tomorrow, then call security.”*\r\n\r\n*Hughes considered a moment, then took the vial and downed it. Salty, thick, warm and powerful — so very powerful. Screw the consequences, he was going to have the game of his life.*\r\n\r\n*By taking the drink, Hughes indulges his defining Vice and regains one point of spent Willpower.*\r\n\r\n**Other Names:** Covetousness, jealousy, paranoia\r\n\r\n**Possessed by:** Celebrities, executives, politicians'),
(2, 'Gluttony', 'Gluttony is about indulging appetites to the exclusion of everything else. It’s about dedicating oneself to sensual pleasures or chasing the next high. A glutton makes any sacrifice to feed his insatiable appetite for pleasure, regardless of the cost to himself or those around him. He might be considered a junky or even a kleptomaniac (he steals things he doesn’t need just for the thrill of it).\r\n\r\nYour character regains one spent Willpower point whenever he indulges in his addiction or appetites at some risk to himself or a loved one.\r\n\r\n**Example:** *They’d come for his dad. They’d hauled his ass into the bedroom, brought in the power tools, and then the screaming started. James thought about protesting, but what good would it do? He decided to drown it all out, instead. He snorted some coke and tipped back the whiskey. Sure, he’d gotten involved with them, and maybe that was a mistake, but he told his dad to keep out of it. James took another drink. The whiskey trailed fire down his throat and dulled his senses. They didn’t tolerate people interfering in their business. And so, James took another drink.* \r\n\r\n*By overwhelming his senses with drugs and booze rather than helping his father, James indulges in his defining Vice and regains a point of spent Willpower.*\r\n\r\n**Other Names:** Addictive personality, conspicuous consumer, epicurean \r\n\r\n**Possessed by:** Celebrities, junkies, thieves'),
(3, 'Greed', 'Like the envious, the greedy are never satisfied with what they have. They want more — more money, a bigger house, more status or influence — no matter that they may already have more than they can possibly handle. Everything is taken to excess. To the greedy, there is no such thing as having too much. If that means snatching someone else’s hard-earned reward just to feather one’s own nest, well, that’s the way it goes.\r\n\r\nYour character regains one Willpower point whenever he acquires something at the expense of another. Gaining it must come at some potential risk (of assault, arrest or simple loss of peer respect).\r\n\r\n**Example:** *Gregor scanned down the list of names. It read like a small-town telephone book. He signed the cover sheet, ending the employment of several hundred people. “Such is the way of capitalism,” he mused. The press\r\nwouldn’t believe there was synergy to the merger unless the two companies’ duplicated human resources weren’t eliminated. Progress had its price.*\r\n\r\n*He once again skimmed the magazine list of the world’s 500 wealthiest men, and eyed the meager difference between his fortune at number 20 and that of number 19. Then he imagined next year’s poll. Progress had its rewards, too.*\r\n\r\n*By engineering the hostile takeover that costs hundreds of jobs, all for petty personal gain, Gregor indulges his defining Vice and regains a point of spent Willpower.*\r\n\r\n**Other Names:** Avarice, parsimony\r\n**Possessed by:** CEOs, lawyers, stock brokers'),
(4, 'Lust', 'The Vice of Lust is the sin of uncontrolled desire. A lusty individual is driven by a passion for something (usually sex, but it can be a craving for virtually any experience or activity) that he acts upon without consideration for the needs or feelings of others. A lusty individual uses any means at his disposal to indulge his desires, from deception to manipulation to acts of violence.\r\n\r\nYour character is consumed by a passion for something. He regains one Willpower point whenever he satisfies his lust or compulsion in a way that victimizes others.\r\n\r\n**Example:** *For two weeks, Aaron had been holed up with the star witness, “protecting” her. That she was also suspected as an accomplice in the crime hadn’t stopped Aaron from banging her seven ways to Sunday, ever since she’d come on to him on the second day. It might have meant compromising the witness and his career, but this chick was worth it. Aaron didn’t care that her last four husbands had died or that the precinct had labeled her “Black Widow.” The sex just kept getting better. In fact, he was exhausted for hours afterward. If he’d stopped to think about it, the blackouts might have worried him, but he didn’t want to think about it.*\r\n\r\n*By using his position and influence to get sexual favors, Aaron indulges in his defining Vice and regains a point of spent Willpower.*\r\n\r\n**Other Names:** Lasciviousness, impatience, impetuousness \r\n\r\n**Possessed by:** Movie producers, politicians, rock stars\r\n'),
(5, 'Pride', 'Pride is the Vice of self-confidence run amok. It is the belief that one’s every action is inherently right, even when it should be obvious that it is anything but. A prideful person refuses to back down when his decision or reputation is called into question, even when the evidence is clear that he is in the wrong. His ego does not accept any outcome that suggests fallibility, and he is willing to see others suffer rather than admit that he’s wrong.\r\n\r\nYour character regains one Willpower point whenever he exerts his own wants (not needs) over others at some potential risk to himself. This is most commonly the desire for adulation, but it could be the desire to make others do as he commands.\r\n\r\n**Example:** *Fabrice stepped out of his car and faced the old mansion. Four centuries of French weather had taken its toll on the once regal place. The setting sun stretched shadows across the façade, highlighting every flaw and crack, and throwing a distorted shadow over the front door like an evil omen.*\r\n\r\nHaunted indeed. \r\n\r\n*When the unkempt, ill-mannered student had shown up to his lecture and publicly challenged the professor to spend one night in the mansion, how could the foremost debunker of mystic nonsense decline?*\r\n\r\n*Fabrice was sure he had more to fear from the house collapsing than from evil spirits. Yes, he was quite sure.*\r\n\r\n*By refusing to back down to the challenge, and reveling in his own self-assurance, Fabrice indulges his defining Vice and regains a point of spent Willpower.*\r\n\r\n**Other Names:** Arrogance, ego complex, vanity\r\n**Possessed by:** Corporate executives, movie stars, street thugs\r\n'),
(6, 'Sloth', 'The Vice of Sloth is about avoiding work until someone else has to step in to get the job done. Rather than put in the effort — and possibly risk failure — in a difficult situation, the slothful person simply refuses to do anything, knowing that someone else will step in and fix the problem sooner or later. The fact that people might needlessly suffer while the slothful person sits on his thumbs doesn’t matter one bit.\r\n\r\nYour character regains one Willpower point whenever he successfully avoids a difficult task but achieves the same goal nonetheless.\r\n\r\n**Example:** *Catherine pretended to listen as the fourth tenant that day called to tell her, the superintendent, that the security lights were out. Some asshole had gone and broken all the lights around the apartment building.*\r\n\r\n*Sure, she’d heard stories about the Harcourt building, where the lights were shattered one night and there were break-ins the next. Depending on who was telling the story, some weird shit happened over there.*\r\n\r\n*But the afternoon soaps were starting, and Catherine decided the Harcourt stuff was just rumors. Besides, she had a baseball bat in case any boogey men came calling. She unplugged the phone and let her ass warm the couch. The lights could wait another day.*\r\n\r\n*By avoiding work despite the repercussions, Catherine indulges her defining Vice and regains a point of spent Willpower.*\r\n\r\n**Other Names:** Apathy, cowardice, ignorance\r\n \r\n**Possessed by:** Couch potatoes, trust-fund heirs, welfare cheats'),
(7, 'Wrath', 'The Vice of Wrath is the sin of uncontrolled anger. The wrathful look for ways to vent their anger and frustration on people or objects at the slightest provocation. In most cases the reaction is far out of proportion to the perceived slight. A wrathful person cut off on the freeway might try to force another driver off the road, or a wrathful cop might delight in beating each and every person he arrests, regardless of the offense.\r\n\r\nYour character regains one spent Willpower point whenever he unleashes his anger in a situation where doing so is dangerous. If the fight has already begun, no Willpower points are regained. It must take place in a situation where anger is unwarranted or inappropriate.\r\n\r\n\r\n**Example:** *As April staggered in, Rebecca surprised her at the door, demanding the month’s rent. April had gotten hooked on the new drug that had hit the streets. She had spent most of her time day-tripping and having paranoia attacks about “things eating through the walls of the world.” Rebecca didn’t care anymore, and when the usual litany of excuses began, Rebecca hit her. Blood ran from April’s nose and down her mouth.*\r\n\r\n*“I want... the damn... rent,” Rebecca yelled, punctuating each statement with another blow until April was on the ground, balled up and crying.* \r\n\r\n*By beating the money out of April, Rebecca indulges her defining Vice and regains a point of spent Willpower.*\r\n\r\n**Other Names:** Antisocial tendencies, hot-headedness, poor anger management, sadism\r\n\r\n**Possessed by:** Bullies, drill sergeants, street thugs');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `vice`
--
ALTER TABLE `vice`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `vice`
--
ALTER TABLE `vice`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
