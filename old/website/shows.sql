-- phpMyAdmin SQL Dump
-- version 3.5.0-beta1
-- http://www.phpmyadmin.net
--
-- Host: mysql-shared-02.phpfog.com
-- Generation Time: Dec 29, 2012 at 09:10 AM
-- Server version: 5.5.27-log
-- PHP Version: 5.3.2-1ubuntu4.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `timewasted_phpfogapp_com`
--

-- --------------------------------------------------------

--
-- Table structure for table `shows`
--

CREATE TABLE IF NOT EXISTS `shows` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL DEFAULT '',
  `secondary_name` varchar(250) DEFAULT '',
  `third_name` varchar(250) DEFAULT NULL,
  `seasons` int(2) DEFAULT NULL,
  `episodes` int(2) DEFAULT NULL,
  `runtime` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=66 ;

--
-- Dumping data for table `shows`
--

INSERT INTO `shows` (`id`, `name`, `secondary_name`, `third_name`, `seasons`, `episodes`, `runtime`) VALUES
(1, 'How I Met Your Mother', 'HIMYM', 'Mother', 6, 23, 22),
(2, 'The Big Bang Theory', 'BBT', 'Big Bang Theory', 4, 22, 21),
(3, 'House MD', 'Gregory House', 'Dr House', 7, 22, 42),
(4, 'Supernatural', 'Winchester', NULL, 6, 21, 44),
(5, 'Breaking Bad', 'Bad', 'Heisenberg', 4, 12, 47),
(6, 'Dexter', 'Morgan', 'Michael Hall', 6, 12, 50),
(7, 'Game Of Thrones', 'Thrones', NULL, 1, 10, 60),
(8, 'Family Guy', 'Stewie Griffin', 'Peter Griffin', 10, 17, 22),
(9, 'Weeds', 'Botwin', NULL, 7, 13, 26),
(10, '30 Rock', 'Liz Lemon', 'Tracy Jordan', 5, 21, 22),
(11, 'The Sopranos', 'Sopranos', NULL, 6, 14, 53),
(12, 'Lost', NULL, NULL, 6, 20, 44),
(13, 'Heroes', 'Bennet', NULL, 4, 19, 42),
(14, 'Six Feet Under', 'Michael Hall', 'Feet', 5, 13, 51),
(15, 'Mad Men', 'Men', NULL, 4, 13, 47),
(16, '24', NULL, NULL, 8, 24, 43),
(17, 'American Dad!', 'Stan Smith', 'Dad', 6, 19, 23),
(18, 'Prison Break', 'Michael Scofield', 'Break', 4, 20, 43),
(19, 'Desperate Housewives', 'Housewives', NULL, 8, 22, 45),
(20, 'Californication', 'Hank Moody', NULL, 4, 12, 28),
(21, 'South Park', 'Stan', 'Park', 15, 15, 22),
(22, 'Scrubs', NULL, NULL, 9, 20, 23),
(23, 'Fringe', NULL, NULL, 4, 22, 48),
(24, 'True Blood', 'Blood', NULL, 4, 12, 55),
(25, 'Mr Bean', 'Bean', 'Mr. Bean', 1, 14, 25),
(26, 'Skins', 'Sid Jenkins', 'Chris Miles', 5, 9, 45),
(27, 'The Simpsons', 'Simpsons', 'Homer Simpson', 23, 22, 22),
(28, 'Band Of Brothers', 'Brothers', NULL, 1, 10, 70),
(29, 'Misfits', 'Simon Bellamy', NULL, 3, 7, 45),
(30, 'Top Gear', 'Stig', 'Jeremy Clarkson', 17, 8, 60),
(31, 'Man vs Wild', 'Man vs. Wild', 'Bear Grylls', 7, 10, 45),
(32, 'MythBusters', 'Jamie Hyneman', 'Adam Savage', 9, 22, 43),
(33, 'The Apprentice', 'Apprentice', 'Donald Trump', 11, 14, 65),
(34, 'Little Britain', 'Britain', 'Matt Lucas', 4, 9, 30),
(35, 'The Real Hustle', 'Hustle', 'Paul Wilson', 10, 10, 29),
(36, 'Jamie&#8217;s 30 Minute Meals', 'Jamie''s 30 Minute Meals', '30 Minute Meals', 2, 20, 23),
(37, 'LA Ink', 'L.A. Ink', 'Kat Von D', 4, 21, 46),
(38, 'SpongeBob', 'Squarepants', 'Bob', 8, 42, 22),
(39, 'Boardwalk Empire', 'Empire', NULL, 2, 12, 55),
(41, 'The X-Files', 'X-Files', NULL, 9, 22, 44),
(42, 'Seinfeld', 'Jerry', NULL, 9, 20, 22),
(43, 'Friends', 'Rachel Green', 'Joey Tribbiani', 10, 24, 20),
(44, 'The Vampire Diaries', 'Vampire Diaries', 'Elena Gilbert', 3, 22, 41),
(45, 'Two And a Half Men', 'Men', 'Half', 8, 22, 21),
(46, 'Gossip Girl', 'Girl', 'Serena', 5, 22, 42),
(47, 'Grey&#8217;s Anatomy', 'Anatomy', 'Dr. Meredith Grey', 8, 22, 41),
(48, '2 Broke Girls', 'Broke', 'Girls', 1, 22, 22),
(49, 'Nikita', NULL, NULL, 2, 22, 43),
(50, 'The Mentalist', 'Mentalist', 'Patrick Jane', 4, 23, 43),
(51, 'The Office', 'Office', 'Jim Halpert', 8, 22, 23),
(52, 'It&#8217;s Always Sunny in Philadelphia', 'Sunny', 'Philadelphia', 7, 12, 22),
(53, 'Man Up', 'Up', 'Will Keen', 1, 13, 22),
(54, 'Web Therapy', 'Therapy', 'Fiona Wallice', 1, 10, 22),
(55, 'Chuck', 'Chuck Bartowski', NULL, 5, 18, 43),
(56, 'Criminal Minds', 'Derek Morgan', 'Minds', 7, 23, 45),
(57, 'CSI Miami', 'CSI', 'Miami', 10, 23, 41),
(58, 'CSI New York', 'CSI', 'New York', 8, 22, 42),
(59, 'CSI', 'Crime', 'Scene', 12, 23, 42),
(60, 'Flashpoint', NULL, NULL, 4, 16, 44),
(61, 'Hell on Wheels', 'Wheels', NULL, 1, 10, 43),
(62, 'Last Man Standing', 'Man', 'Standing', 1, 22, 21),
(63, 'Burn Notice', 'Notice', 'Michael Westen', 5, 16, 42),
(64, 'White Collar', 'Collar', 'Neal Caffrey', 3, 15, 42),
(65, 'NCIS Los Angeles', 'Los Angeles', NULL, 3, 24, 43);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
