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
-- Table structure for table `virtue`
--

CREATE TABLE `virtue` (
  `id` int NOT NULL,
  `name` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `virtue`
--

INSERT INTO `virtue` (`id`, `name`, `details`) VALUES
(1, 'Charity', 'True Charity comes from sharing gifts with others, be it money or possessions, or simply giving time to help another in need. A charitable character is guided by her compassion to share what she has in order to improve the plight of those around her. Charitable individuals are guided by the principle of treating others as they would be treated themselves. By sharing gifts and taking on the role of the Samaritan, they hope to cultivate goodwill in others, and the gifts they give will eventually return to them in their hour of need.\r\n\r\nYour character regains all spent Willpower points whenever she helps another at the risk of loss or harm to herself. It isn’t enough to share what your character has in abundance. She must make a real sacrifice in terms of time, possessions or energy, or she must risk life and limb to help another.\r\n\r\n**Example:** *Deloris could see her dad losing his mind if he knew his little girl was driving around a south-side neighborhood so late at night. But if she wanted to be a top investigative reporter, she had to take some risks, even if it meant tracking down clues on a serial killer in a slum.*\r\n\r\n*She’d been the one to dub him “Tommy 10 Tongues” in her first cover story. Now he was up to 12 tongues, and she was determined to catch him before he harvested another. She knew the police had no idea how to decipher the bloody hieroglyphs at the crime scenes, or why the victims were all missing their tongues. But the police didn’t have an ex-lover who was a linguistics professor.*\r\n\r\n*Then Deloris passed the other motorist. A white man with a cast on one arm struggling to change a flat. If Tommy 10 Tongues didn’t get him, the locals surely would. And yet, Deloris wasn’t born yesterday. The cast could have been a fake. The killer could have used such tricks to lure his victims in. She didn’t want to be the next one, but she couldn’t bear the thought of writing the morning headline, “Stranded Motorist 13th Victim.” So, she pulled over to do the charitable thing.*\r\n\r\n*Deloris gains all spent Willpower for her act of charity. Her willingness to risk herself for someone else validates her defining Virtue.*\r\n\r\n**Other Names:** Compassion, mercy \r\n\r\n**Possessed by:** Philanthropists, saints, soup-kitchen workers'),
(2, 'Faith', 'Those with Faith know that the universe is not random, meaningless chaos, but ordered by a higher power. No matter how horrifying the world might be, everything has its place in the Plan and ultimately serves that Purpose. This Virtue does not necessarily involve belief in a personified deity. It might involve belief in a Grand Unified Theory whereby the seeming randomness of the universe is ultimately an expression of mathematical precision. Or it might be a view that everything is One and that even evil is indistinguishable from good when all discriminating illusions are overcome.\r\n\r\nYour character regains all spent Willpower points whenever he is able to forge meaning from chaos and tragedy.\r\n\r\n**Example:** *Kevin stood at a precipice. Images flashed through his mind: his wife’s bulging eyes, his son’s mutilated body, his daughter’s pink pajamas shredded and matted with hair and blood. Despair and rage whirled up from the psychological chasm before him. He had only to take a step into the cold comfort that the world was meaningless, random and violent, that there could be no God if such evil could come to pass.*\r\n\r\n*No! He didn’t believe it. He couldn’t believe it. Something had done this. Something sick and twisted. Something inhuman. Kevin would find it. God give him strength, he would find it and stop it.*\r\n\r\n*By dedicating himself to finding the meaning of the crime, knowing that there must be a reason for the madness, Kevin regains any spent Willpower points.*\r\n\r\n**Other Names:** Belief, conviction, humility, loyalty\r\n\r\n**Possessed by:** Detectives, philosophers, priests, scientists, true believers'),
(3, 'Fortitude', 'A person’s ideals are meaningless unless they’re tested. When it seems as though the entire world is arrayed against him because of his beliefs, a person possessing Fortitude weathers the storm and emerges with his convictions intact. Fortitude is about standing up for one’s beliefs and holding the course no matter how tempting it may be to relent or give up. By staying the course — regardless of the cost — he proves the worth of his ideals.\r\n\r\nYour character regains all spent Willpower points whenever he withstands overwhelming or tempting pressure to alter his goals. This does not include temporary distractions from his course of action, only pressure that might cause him to abandon or change his goals altogether.\r\n\r\n**Example:** *Noel was absorbed in reading the newspaper when the stranger walked in. The town was opening its eyes to the corruption; his campaign to oust the criminal who called himself ‘mayor’ was working.*\r\n\r\n*The stranger dropped some photos on the desk, breaking Noel’s concentration. They were of Noel 15 years before. The alcoholic years. “I suggest you drop your high and mighty crusade unless you want these on the front page.” Noel instantly recognized that his political career hung in the balance. Was this how it started? Was this how they got their hooks into you?*\r\n\r\n*“Run them,” he said. “I don’t care. You can tell your boss he’s through. He has more to lose than I do.”*\r\n\r\n*By refusing to budge and pressing on in the wake of scandal, Noel behaves in a way that validates his defining Virtue and he regains any spent Willpower points.*\r\n\r\n**Other Names:** Courage, integrity, mettle, stoicism\r\n\r\n**Possessed by:** Dictators, fanatic cultists, gumshoes'),
(4, 'Hope', 'Being hopeful means believing that evil and misfortune cannot prevail, no matter how grim things become. Not only do the hopeful believe in the ultimate triumph of morality and decency over malevolence, they maintain steadfast belief in a greater sense of cosmic justice — whether it’s Karma or the idea of an all-knowing, all-seeing God who waits to punish the wicked. All will turn out right in the end, and the hopeful mean to be around when it happens.\r\n\r\nYour character regains all spent Willpower points whenever she refuses to let others give in to despair, even though doing so risks harming her own goals or wellbeing. This is similar to Fortitude, above, except that your character tries to prevent others from losing hope in their goals. She need not share those goals herself or even be successful in upholding them, but there must be a risk involved.\r\n\r\n**Example:** *The activists’ anger was palpable as Eva entered the room.*\r\n\r\n*“I know you see me as the enemy — Trent Thorson’s daughter. The truth is, I may own Thorson Lumber, but I don’t control it or I’d shut it down. If my uncle has his way, I’ll never have that chance.*\r\n\r\n*“I know his lawyers and thugs are pressuring you to stop the protest, but you can’t give up. You feel the power of that forest. There’s something there, something bigger than any of us that needs to be protected.*\r\n\r\n*“All I came here to say is don’t lose hope. I’ll feed you what information I can from the inside to keep you one step ahead of them. If you give up now, there’ll be nothing left to save.”*\r\n\r\n*By supporting the activists at her own personal expense and risk, Eva regains any spent Willpower.*\r\n\r\n**Other Names:** Dreamer, optimist, utopian\r\n\r\n**Possessed by:** Anti-globalization activists, entrepreneurs, martyrs, visionaries'),
(5, 'Justice', 'Wrongs cannot go unpunished. This is the central tenet of the just, who believe that protecting the innocent and confronting inequity is the responsibility of every decent person, even in the face of great personal danger. The just believe that evil cannot prosper so long as one good person strives to do what is right, regardless of the consequences.\r\n\r\nYour character regains all spent Willpower points whenever he does the right thing at risk of personal loss or setback. The “right thing” can be defined by the letter or spirit of a particular code of conduct, whether it be the United States penal code or a biblical Commandment.\r\n\r\n**Example:** *For five years Malcolm watched the bastard parade into court, smiling through appeal after appeal. His gold-plated lawyers ran circles around the district attorney. Then they settled the class-action suit at such a ridiculously low payment that Malcolm had to wonder how far the bribes went. And the bastard was going free.*\r\n\r\n*Hundreds had been driven insane by the drug he distributed. It made him rich even while it made Malcolm’s sister a corpse... and then a ghost. It was only then that Malcolm realized why she always appeared outside his hall closet. That’s where he kept his gun.*\r\n\r\n*If Malcolm takes the law into his own hands and makes himself a criminal as a result, he acts in a way that validates his defining Virtue and he regains any spent Willpower.*\r\n\r\n**Other Names:** Condemnatory, righteous\r\n\r\n**Possessed by:** Critics, judges, parents, role models'),
(6, 'Prudence', 'The Virtue of Prudence places wisdom and restraint above rash action and thoughtless behavior. One maintains integrity and principles by moderating actions and avoiding unnecessary risks. While that means a prudent person might never take big gambles that bring huge rewards, neither is his life ruined by a bad roll of the dice. By choosing wisely and avoiding the easy road he prospers slowly but surely.\r\n\r\nYour character regains all spent Willpower points whenever he refuses a tempting course of action by which he could gain significantly. The “temptation” must involve some reward that, by refusing it, might cost him later on.\r\n\r\n**Example:** *“Miss Hernandez, you’re an intelligent woman — and a beautiful one, I might add. There are so many benefits available to the people who contribute to our family business. The least of them is the considerable fee we’re offering for your services in this matter.”*\r\n\r\n*“Your offer is generous,” Louise replied, “and I thank you for it. But the types of offshore transactions you propose are tantamount to money laundering and tax evasion. It wouldn’t be prudent for me to jeopardize my legal career by being party to this.”*\r\n\r\n*“You think you know, Miss Hernandez, but I assure you, you have no idea what you’re passing up.”*\r\n\r\n*If Louise passes on the possibility of riches to preserve her job and name, she acts in a way that validates her defining Virtue and regains any spent Willpower.*\r\n\r\n**Other Names:** Patience, vigilance\r\n**Possessed by:** Businessmen, doctors, priests, scientists'),
(7, 'Temperance', 'Moderation in all things is the secret to happiness, so says the doctrine of Temperance. It’s all about balance. Everything has its place in a person’s life, from anger to forgiveness, lust to chastity. The temperate do not believe in denying their urges, as none of it is unnatural or unholy. The trouble comes when things are taken to excess, whether it’s a noble or base impulse. Too much righteousness can be just as bad as too much wickedness.\r\n\r\nYour character regains all spent Willpower when he resists a temptation to indulge in an excess of any behavior, whether good or bad, despite the obvious rewards it might offer.\r\n\r\n**Example:** *Michael pressed Ravera to the pavement and cuffed him. For half his years on the force, Michael had been trying to bring Douglas Ravera to justice.*\r\n\r\n*How many kids had died from Ravera’s peddled junk? How many times had Michael’s family received death threats? How many times had Ravera been collared only to walk on a technicality?*\r\n\r\n*Michael’s mind kept turning back to the unregistered .38 stashed in his patrol car. He could fire some rounds into the car door and put Ravera’s prints on the weapon. Who would doubt that Michael had to kill him in self-defense?*\r\n\r\n*“No,” Michael muttered to himself. He couldn’t lower himself to the same level as this criminal, no matter how tempting. He’d be no better. Instead, he hauled Ravera into the back of the car and slammed the door.*\r\n\r\n*By refusing to give in to extreme and compelling impulses, remaining centered instead, Michael acts in a way that validates his defining Virtue and he regains any spent Willpower.*\r\n\r\n**Other Names:** Chastity, even-temperament, frugality\r\n**Possessed by:** Clergy, police officers, social workers');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `virtue`
--
ALTER TABLE `virtue`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `virtue`
--
ALTER TABLE `virtue`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
