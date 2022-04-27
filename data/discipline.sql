-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Apr 27, 2022 at 02:03 PM
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
-- Table structure for table `discipline`
--

CREATE TABLE `discipline` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_restricted` tinyint(1) NOT NULL,
  `homebrew_for_id` int DEFAULT NULL,
  `book_id` int DEFAULT NULL,
  `page` smallint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discipline`
--

INSERT INTO `discipline` (`id`, `name`, `rules`, `description`, `is_restricted`, `homebrew_for_id`, `book_id`, `page`) VALUES
(1, 'Animalism', 'They can not only commune with lower creatures, but project their will upon them, forcing them to obey.\r\n\r\nAs the Kindred gain power, some develop the ability to join with animals, or to influence the Beast lurking with their own souls or the souls of other vampires.\r\n\r\nMost Kindred are **repellent to animals**. Lesser creatures grow agitated in the presence of the undead and normally flee the scene (or, in some cases, attack the vampire in question). Kindred who possess Animalism are a very different story. Animals are often attracted to such Kindred, and **their presence is soothing** even to restless beasts.\r\n\r\nOther sentient, supernatural beings such as shapechangers who have animal form or who can assume animal form are **not** affected by **Animalism**. Their intelligence makes them the purview of the Dominate Discipline. Similarly, Animalism is **useless** on another vampire who assumes wolf or another bestial form. Animalism is of no avail to the vampire in regard to such intelligent beings.\r\n\r\nNote that any Animalism power that requires eye contact is made **more difficult if the subject does not stand still** or is not otherwise immobilized. If the animal in question moves about, the roll to initiate the relevant power suffers a **-1 penalty** in addition to all others listed.', 'Although most look human, all the Kindred conceal within them a feral predator, a Beast that divides all others into only two categories: threat or prey.\r\n\r\nSome Kindred feel their affinity with the animals of the world, and their connection with their own animalistic nature to a greater degree than others.\r\n\r\nThese Kindred often develop the Discipline of Animalism, which allows them to bond with the beasts — and the Beasts— around them.', 0, NULL, NULL, NULL),
(2, 'Auspex', 'Auspex can be used to pierce the veil of powers that cloud, dissemble and deceive (see Clash of Wills). Indeed, precious little can be kept secret from a true master of Auspex.\r\n\r\nOnce in a while, this uncanny Discipline provides extrasensory and even precognitive visitations. Such premonitions might come as quick flashes of imagery, overwhelming feelings of empathy or even as an ominous sense of foreboding. The Kindred has absolutely no control over these insights, but he can learn to interpret their significance given time and experience.\r\n\r\nSuch potent sensitivity can have its drawbacks, however. When a vampire actively uses any level of Auspex save the fifth (Twilight Projection), he runs the risk of his delicate senses being overwhelmed by excessive stimuli. Sudden or severe occurrences such as a gun report or flash bulb in the eyes can distract the character unless the player succeeds on a **Resolve roll**.\r\nFailure disorients the character, making him effectively **unaware of his surroundings until the end of the following turn**.', 'This potent Discipline grants a character superlative sensory capabilities. At the lowest levels, it sharpens a Kindred’s mundane senses. As one progresses in mastery, entirely new avenues of insight open up before the user. Ultimately, this is the Discipline of gleaning information, whether that data comes from sights and smells, from auras and patterns of energy or directly from the mind of another creature.', 1, NULL, NULL, NULL),
(3, 'Celerity', ' ', 'Tales and legends of vampires ascribe to them inhuman speed, the ability to move faster than the eye can see, and even to appear in two places at once. While some of those accounts are exaggerated, Kindred with the Discipline of Celerity can indeed move far faster than any mortal. They appear to blur into nothingness, all others moving as if in slow motion in comparison.\r\n', 0, NULL, NULL, NULL),
(4, 'Dominate', 'Use of Dominate requires a character to capture a victim’s gaze. The Discipline can therefore be used on only one subject at a time, and is **useless if eye contact is not possible**.\r\n \r\nDominate does not grant the ability to make oneself understood or to communicate mentally. Commands must be issued verbally, though certain simple commands (such as “Go over there!” indicated with a pointed finger and a forceful expression) may be conveyed by signs at the Storyteller’s discretion.\r\nNo matter how powerful a vampire is, **she cannot force her victim to obey if she cannot make herself understood** — if, for example, the victim doesn’t speak the same language, she cannot hear or the orders simply make no sense.\r\n \r\nNote that victims of Dominate **might realize** what’s been done to them. That is, they do not automatically sense that they are being controlled, but they might subsequently wonder why they suddenly acted as they did. Wise Kindred, especially those familiar with Dominate, are likely to figure it out in the moment, and few vampires take kindly to being manipulated in such a fashion.\r\n Most Kindred who develop Dominate are forceful, controlling personalities, and they can make a reputation for themselves if they use this Discipline wantonly.\r\n \r\nIn all cases, a dramatic failure while attempting to Dominate a victim renders the would-be victim **immune to the character’s Dominate until the next sunset**.\r\n \r\nDominate is far more effective against mortals than it is against other Kindred. Most Dominate abilities described here allow the victim to struggle against the effects; that is, a contested roll is made against the Dominator’s player. As no mortal has Blood Potency, the vast majority of humans are at a disadvantage when dealing with the Discipline.\r\n \r\nDominate is also more effective against those whom the user has subjected to a Vinculum. A regnant may use Dominate powers (with the exception of Conditioning) on a thrall **without the need for eye contact**; the thrall merely has to hear the regnant’s voice.\r\n \r\nOther sentient, supernatural beings such as shapechangers who have animal form or who can assume animal form are affected by Dominate rather than by Animalism. A vampire can therefore try to ply his will on a werewolf — even a werewolf in wolf form — by using Dominate.\r\nSimilarly, Animalism is useless against another vampire who assumes wolf or another bestial form. Animalism is of no avail to the vampire in regard to such intelligent beings.\r\n \r\nNote that any Dominate power requiring eye contact is made more difficult if the subject is not standing still or otherwise immobilized. If the target moves about, the roll to initiate the relevant power suffers a **-1 penalty** in addition to all others listed.', 'Some Kindred are capable of overwhelming the minds of others with their own force of will, influencing actions and even thoughts.', 1, NULL, NULL, NULL),
(5, 'Majesty', 'Unlike some other Disciplines, Majesty can be used on entire crowds of targets simultaneously, making it even more potent— in the right hands.\r\n \r\nThe only requirement for use of most Majesty powers is that **any potential targets see the character**. Eye contact is not required, nor is the ability to hear the character (though it certainly doesn’t hurt).\r\n \r\nThe downside to Majesty, such as is it is, is that its subjects retain their free will. Unlike victims of Dominate, who follow the commands of the Kindred nearly mindlessly, those acting under Majesty are simply emotionally predisposed to do whatever the power (or its user) suggests.\r\nWhile retention of personality makes victims more useful in the long run, it also means they require more care in handling than targets of Dominate. An abused victim of Majesty certainly subverts or represses what his emotions suggest in order to behave in the most appropriate manner. Meanwhile, subjects treated well might be persuaded to act against even their own interests.\r\n \r\nAny mortal can resist Majesty for one turn if a Willpower point is spent and a successful Composure roll is made (though the Willpower point does not add three dice to the roll). This roll is reflexive. If the roll fails, the Willpower point is lost and the target remains under the effects of the power(s). If the roll is successful, the mortal probably spends his turn of “freedom” fleeing the vampire’s proximity, lest he continue to be affected. Refusing to pay attention to the vampire, rather than fleeing, can allow a mortal to resist the spell for a turn, but the power resumes effect if the mortal remains in the Kindred’s vicinity.\r\n \r\nVampires resist Majesty in much the same way (by spending a Willpower point), but Blood Potency is added to Composure rolls made for them.\r\nIn addition, **vampires of higher Blood Potency than the character invoking Majesty are able to resist his power for the entire scene** with the expenditure of one Willpower point and a successful Composure + Blood Potency roll.\r\n \r\nBy and large, the Kindred who choose to develop their Majesty abilities are those who recognize that one achieves more with honey than with vinegar. Those who swear by Majesty often find Dominate, seen as “the flip side of Majesty,” to be both boorish and crass, and they would swear to calling upon it only in times of dire need.', 'One of the most legendary powers of the undead is the ability to attract, sway and control the emotions of others, especially those of mortals. Majesty is perhaps the most versatile of Disciplines, for its potential uses and applications are both varied and multitudinous. The more savvy the practitioner, the more use he can get out of each of the Discipline’s levels.', 1, NULL, NULL, NULL),
(6, 'Nightmare', 'Vampires who delve into the dark side of their being —often exploring the Beast or what it means to be monstrous— invest in the Discipline of Nightmare. They learn to bear that which is terrifying or unholy about their spirits, manifesting their inhumanity in their appearance or letting unfortunate onlookers peer deep into the creatures’ depraved souls.\r\n\r\nThe results can take a jaded individual aback or subject an unsuspecting victim to a fatal physiological reaction (to literally be frightened to death).\r\n\r\nPractitioners of Nightmare explore this route to power for different reasons. One vampire might exult in his inhuman nature and enjoy lording over lessers. The Discipline offers immediate gratification, and these Kindred display what is hideous about themselves to everyone, hiding it only insofar as they must in order to observe the secrecy of the Traditions. Other undead recognize the wisdom or even benevolence that fear affords. What better way to deal with a problem or avoid a confrontation than by frightening away an opponent? How better to protect someone from harm than by scaring her off? And if one seeks solitude, striking fear is certainly more effective than issuing threats, trying to reason with would-be intruders or orchestrating ever more elaborate means by which to hide.\r\n\r\nAll uses of Nightmare gain a +2 bonus if the individual power is turned on a vampire with whom the user has a Blood Tie. Naturally, this bonus does not apply to the subject’s resistance.', 'There’s no question that one of the foremost powers of legendary vampires is the ability to strike fear in the hearts of mortal men. Also born of mortal existence, other now-supernatural beings are susceptible. Fear is a fact of existence that transcends any origins.\r\n', 1, NULL, NULL, NULL),
(7, 'Obfuscate', 'Obfuscate clouds the mind in practice. For example, a character hiding an object by using this Discipline doesn’t actually make the object disappear, nor does someone using the Discipline to hide himself truly vanish. Rather, the mind sees “around” the Obfuscated object, refusing to acknowledge it, even if that requires a bit of filling in mental blanks. To continue the example, if a character Obfuscated a large sheet of plywood and tried to hide behind it herself, those looking at the plywood would, indeed, see the character lurking behind it but not see the plywood itself.\r\n\r\nThe shroud of Obfuscate is very difficult to penetrate. Few Kindred or other supernatural creatures can see through it, and only under the rarest of circumstances do mortals have any hope. Because they operate on a less conscious and mostly instinctual level, however, animals often perceive a vampire’s presence — and react with appropriate fear or hostility — even if they cannot detect him with their normal senses. Similarly, children, the mentally ill and others who see the world in ways not quite normal might pierce the deception at the Storyteller’s discretion.\r\n\r\nSome Kindred with Auspex are able to see through Obfuscate, or at least sense the presence of a supernatural deception. Refer to the “Clash of Wills” for details.\r\n\r\nIt’s important to note that Obfuscate affects the viewer’s mind, rather than making any true physical change to the vampire. Thus, the Discipline is not effective at cloaking a character from mechanical devices. Photographs, video cameras and the like record the normal blurred image that all vampires leave in such media, not the assumed appearance.\r\n\r\nObfuscate does affect any individual currently using the recording device, however, so someone videotaping an Obfuscated vampire sees the illusion when looking through the lens, discovering the truth only later when he reviews the tape itself.\r\n\r\nUnless stated otherwise, Obfuscate powers require very little concentration to maintain once invoked, and they last for the duration of a scene.', 'Night-dwellers, predators by nature and keepers of the Masquerade, vampires are inherently (and necessarily) creatures of secrecy and stealth. From hiding minute objects to the ability to appear as someone else to the power to fade from sight entirely, the Discipline of Obfuscate grants the Kindred uncanny powers of concealment, stealth and deception.', 0, NULL, NULL, NULL),
(8, 'Protean', 'Since the core of a vampire’s self doesn’t alter with his shape, a transformed Kindred can generally take any action or use any Discipline that his new form can reasonably allow. Gangrel in the form of a cloud of mist, for example, could read auras (as the sense of sight doesn’t vanish), but couldn’t Dominate someone effectively (as the prerequisite eye contact can no longer be established). A vampire’s clothes and personal effects change shape with him, but he cannot normally transmute especially large objects or other creatures.\r\n\r\nUnless stated otherwise, Protean powers — being permanent physical changes — last as long as the vampire wishes them to, or until he is forced into torpor. Any state that prevents the character from taking action (such as being staked) likewise prevents transformation; the vampire needs the freedom to invoke his will.', 'Easily one of the most overtly spectacular of the gifts of the Damned, the Discipline of Protean is the study of physical metamorphosis and transformation. The nature of this power is hotly debated among the Kindred, for its abilities are so varied while simultaneously stemming from no obvious aspect of the Curse. Whatever its cause or origin, Protean allows its masters to assume virtually any form or shape.', 1, NULL, NULL, NULL),
(9, 'Resilience', NULL, 'Legends abound of vampires who are able to withstand even the most brutal punishment to their unliving forms. While all Kindred possess a certain degree of the toughness of which these tales speak, those with the Discipline of Resilience are commensurately more stalwart. Vampires with several dots of Resilience are capable of walking through a hail of bullets, shrugging off even the most punishing blows, and even resisting the deadly claws and fangs of supernatural foes.', 0, NULL, NULL, NULL),
(10, 'Vigor', NULL, 'Nearly every vampire legend across the globe expresses the preternatural strength possessed by the undead. In truth, not all Kindred possess such inhuman might, but the Discipline of Vigor makes those who do far more powerful than any mortal could ever hope to be. Vigor allows Kindred to strike opponents with the force of a falling boulder or speeding car; to lift enormous weights as though they were paper; to shatter concrete like glass; to leap distances so great that those elders with obscenely high levels of Vigor may, in fact, be responsible for legends of vampiric flight.', 0, NULL, NULL, NULL),
(11, 'Path of Blood', 'The Path of Blood, as its name indicate, is centered around blood.\r\n\r\nBob permet à Anastasia de tirer plus de puissance du sang, et de jouer avec ce sang, le sien ou celui des autres.\r\n\r\nAll uses of this Discipline gain a +2 bonus if the individual power is turned on a vampire with whom the user has a Blood Tie, in addition to any other modifier. \r\n\r\nIn addition, each power of this discipline suffer a malus equal to its level.', 'Bob allow Anastasia to benefit more power from blood, to play with it, be it hers or other\'s.\r\nThis Discipline open almost unlimited possibilities, as long as Bob sage advices are followed and that it is not abused.', 1, 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `discipline`
--
ALTER TABLE `discipline`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75BEEE3F7B02D7EA` (`homebrew_for_id`),
  ADD KEY `IDX_75BEEE3F16A2B381` (`book_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `discipline`
--
ALTER TABLE `discipline`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `discipline`
--
ALTER TABLE `discipline`
  ADD CONSTRAINT `FK_75BEEE3F16A2B381` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`),
  ADD CONSTRAINT `FK_75BEEE3F7B02D7EA` FOREIGN KEY (`homebrew_for_id`) REFERENCES `chronicle` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
