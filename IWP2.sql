-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2016 at 06:05 PM
-- Server version: 5.7.11
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `iwp2`
--
CREATE DATABASE IF NOT EXISTS `iwp2` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `iwp2`;

-- --------------------------------------------------------

--
-- Table structure for table `automerken`
--

CREATE TABLE `automerken` (
  `automerk_id` int(11) NOT NULL,
  `automerk` varchar(255) NOT NULL,
  `plaatje` text NOT NULL,
  `omschrijving` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `automerken`
--

INSERT INTO `automerken` (`automerk_id`, `automerk`, `plaatje`, `omschrijving`) VALUES
(1, 'Audi', 'http://pngimg.com/upload/car_logo_PNG1640.png', '<p>Een van de vele Duitse automerken.</p>\r\n'),
(5, 'BMW', 'http://seeklogo.com/images/B/bmw-logo-248C3D90E6-seeklogo.com.gif', '<p>Dit is BMW</p>\r\n'),
(6, 'Peugeot', 'http://cdn2.carbuyer.co.uk/sites/carbuyer_d7/files/peugeot-208-gti-cutout.jpg', '<p>Frans Merk. Niet bijzonder duur.</p>\r\n'),
(7, 'Lexus', 'http://o.aolcdn.com/dims-shared/dims3/GLOB/crop/1280x720+0+131/resize/800x450!/format/jpg/quality/85/http://o.aolcdn.com/hss/storage/midas/580be404f2da7d1772cf7456621c1017/203726053/01-2011-lexus-lfa-review.jpg', '<p>Volgens wikipedia is Lexus de luxevariant van Toyota</p>\r\n'),
(8, 'Toyota', 'http://www.toyota.com/content/homepage/img/marquee/car/camry_car.png', '<p>Een japans automerk</p>\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `koppelingen`
--

CREATE TABLE `koppelingen` (
  `vriend_id` int(11) NOT NULL,
  `automerk_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `koppelingen`
--

INSERT INTO `koppelingen` (`vriend_id`, `automerk_id`) VALUES
(3, 6),
(6, 1),
(6, 5),
(1, 1),
(6, 7),
(7, 8),
(7, 6),
(8, 5),
(8, 6),
(9, 6),
(10, 6);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `rechten` int(11) NOT NULL DEFAULT '0',
  `voornaam` varchar(255) DEFAULT NULL,
  `achternaam` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `salt`, `rechten`, `voornaam`, `achternaam`, `email`) VALUES
(1, 'Raymond', 'baf4eed24ed1846976dc9532d2667ca4e69dd28fc8bb108dfe54ae8b67dd546f', 'tyreu', 1, '', '', 'i358895@student.fontys.nl'),
(2, 'testuser', '0b0c2e2343e02792ad745402145b394860f269b3f3dc67448bedc9f1c4f96074', 'Vb2FTaeA7kFZU9q58A5C87KMKI1ijQYoHseMd3RGJzsOzu8pANta8Wyj2T0yF7S8', 0, NULL, NULL, NULL),
(3, 'testuser2', 'cc261f035a8917c1587d438a085341d71695d372f82b452dd89dd1957b4c77cb', 'PBNLUNREIGDwBIeAqSeAwDKIIWmHoi9LmDKCYreu4Ga60PGEDQmSgciZLS5IukOg', 0, NULL, NULL, NULL),
(4, 'testuser3', '61cd39f3241ffd1be56e1ba358554e31586fe76d14a32bb190c89c5af3aa0732', 'fw4mpWYdOx5yNNwAdGS3oEe9cGbBXfPDwiMXek5NVhnSXEKoaoL5TYxbZmFmN1eS', 0, NULL, NULL, NULL),
(5, 'Testuser4', 'bd85b21f73a71117f7a8faf661da632ea213d7840162a9eb0d5a451ed18faa12', 'MYJgvXUaPh2BRbYxao3rR68hw3xwgs88F1eAOZHQj3TogrCbfSfHmzCTcg4fQ8sI', 0, NULL, NULL, NULL),
(6, 'testuser2', 'd4616d47ea117e793e35f06b583942043d766ce5c32e0400b14225ac6e40f3a8', 'yDplgGe8nMv5SyXzc5MqlXJYLhZ5qDj5O8SPTlrF6ncqsOSroLHATOmcq376X5FE', 0, NULL, NULL, NULL),
(7, 'Testuser6', 'cd292afff10942f25c1eeabfabaa575e77af9f49b80f7e43b9641ff13c335f81', 'jPnHLb2A9E6nNIGkzlm7NZe0kESFvLpmakqMFM08Q7Kb3MK67LADoxn0I2HUrMFz', 0, NULL, NULL, NULL),
(8, 'Testuser7', '61eb5738c1319f151f9a1beba84b1b155c2fe4ec8dc8427e5892f1a8f9b11c01', 'fvgsJqTwVtMlOfEeQQI30RH8LmPl4YP0pMTYKnKAyQCHNNdDWjMfYZ0TkV7o0IQ6', 0, NULL, NULL, NULL),
(9, 'Testuser8', 'b601afc50ee13234ff7fa7362d76baa5948d9c00afdb63204567abc0d0162228', 'sroSEHt24AkrFIFqV9IPNva1MRgPOOJAmsL8qo5QrIVyp00AMmwNvodw5aiCuiuq', 0, NULL, NULL, NULL),
(10, 'Testuser9', '6e25d8ba2bbf71c2d19113444b0dbafd5a4e7da28815919a815d4de64c3114e2', 'VoknfDW6bNXBdYCIjJDMVceF3W7G84wHaba2AmfyobvB2M91uIU8d6YOcw6nT4uG', 0, NULL, NULL, NULL),
(11, 'Testuser10', 'b4e06a40900b0093c0081fd4adbd7ab899f5d18ebb41cc3f800c16abecda9a71', 'hlR4OrrotWVZJXyGx42RnnBkdtXrwrk2gTrtVUuBr5dJiwPJtN3Xq16d7NHS2b3M', 0, NULL, NULL, NULL),
(12, 'testuser11', '90c94f30ea8d6cf5be0ec63a4c4782a864b2375cc1a018b117d201a84edce457', 'pGQJXglzVrKQGJaL24c1mWUSujU0LEhF1VDyCB3ToE8oGyYilfaiIeOCDttZsphD', 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vrienden`
--

CREATE TABLE `vrienden` (
  `vriend_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `voornaam` varchar(255) NOT NULL,
  `achternaam` varchar(255) NOT NULL,
  `adres` varchar(255) NOT NULL,
  `woonplaats` varchar(255) NOT NULL,
  `mobiel` varchar(50) NOT NULL,
  `beschrijving` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vrienden`
--

INSERT INTO `vrienden` (`vriend_id`, `user_id`, `voornaam`, `achternaam`, `adres`, `woonplaats`, `mobiel`, `beschrijving`) VALUES
(1, 1, 'Henk', 'Janssen', 'Achterweg 4', 'Nieuw Vennep', '06-12345678', '<p>Dit is Henk. Henk woont in Nieuw Vennep</p>\r\n'),
(3, 1, 'Frits', 'de Vries', 'Langeweg 5', 'Amsterdam', '06-23456789', '<p>Dit is Frits. <strong>Frits</strong> is een vriend.Â </p>\r\n'),
(4, 4, 'Frits', 'Jansma', 'Dorpsstraat 4', 'Breda', '06-58694725', '&lt;p&gt;Dit is de beschrijving van Frits Jansma, vriend van testuser 3&lt;/p&gt;\n'),
(5, 1, 'Jean Claude', 'van Dijk', 'Brusselseweg 2', 'Antwerpen', '06-98453245', '<p>Dit is dus JC v. D</p>\r\n'),
(6, 1, 'Harry', 'Piekema', 'AH-Dreef 77', 'Amsterdam', '06-69587412', '<p>Die van de Albert Heijn</p>\r\n'),
(7, 1, 'Truus', 'Hansels', 'voorweg 4', 'Arnhem', '06-54987863', '<p>Dit is Truusje<img alt="" src="https://www.google.nl/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png" style="height:34px;width:100px;" /></p>\r\n');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `automerken`
--
ALTER TABLE `automerken`
  ADD PRIMARY KEY (`automerk_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `vrienden`
--
ALTER TABLE `vrienden`
  ADD PRIMARY KEY (`vriend_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `automerken`
--
ALTER TABLE `automerken`
  MODIFY `automerk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `vrienden`
--
ALTER TABLE `vrienden`
  MODIFY `vriend_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
