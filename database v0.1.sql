-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2016 at 12:41 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bis`
--

-- --------------------------------------------------------

--
-- Table structure for table `licentie`
--

CREATE TABLE IF NOT EXISTS `licentie` (
  `licentie_id` int(11) NOT NULL AUTO_INCREMENT,
  `software_id` int(11) NOT NULL,
  `key` text NOT NULL,
  `volume` int(11) NOT NULL,
  `verloopdatum` varchar(255) NOT NULL,
  PRIMARY KEY (`licentie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `licentie_gebruikt`
--

CREATE TABLE IF NOT EXISTS `licentie_gebruikt` (
  `systeem_id` int(11) NOT NULL,
  `licentie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `model`
--

CREATE TABLE IF NOT EXISTS `model` (
  `model_id` int(11) NOT NULL AUTO_INCREMENT,
  `naam` varchar(255) NOT NULL,
  `versie` varchar(255) NOT NULL,
  PRIMARY KEY (`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `os`
--

CREATE TABLE IF NOT EXISTS `os` (
  `os_id` int(11) NOT NULL AUTO_INCREMENT,
  `naam` varchar(255) NOT NULL,
  `versie` varchar(255) NOT NULL,
  PRIMARY KEY (`os_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `model_id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `type` varchar(1) NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `software`
--

CREATE TABLE IF NOT EXISTS `software` (
  `software_id` int(11) NOT NULL AUTO_INCREMENT,
  `naam` varchar(255) NOT NULL,
  `versie` varchar(255) NOT NULL,
  PRIMARY KEY (`software_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `systeem`
--

CREATE TABLE IF NOT EXISTS `systeem` (
  `systeem_id` int(11) NOT NULL AUTO_INCREMENT,
  `gebruiker_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `os_id` int(11) NOT NULL,
  `garantie` varchar(255) NOT NULL,
  `servicetag` varchar(255) NOT NULL,
  PRIMARY KEY (`systeem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `rechten` int(11) NOT NULL,
  `voornaam` varchar(255) NOT NULL,
  `achternaam` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `salt`, `rechten`, `voornaam`, `achternaam`, `email`) VALUES
(1, 'Raymond', 'baf4eed24ed1846976dc9532d2667ca4e69dd28fc8bb108dfe54ae8b67dd546f', 'tyreu', 1, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `voorraad_log`
--

CREATE TABLE IF NOT EXISTS `voorraad_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `gebruiker_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `model_id` int(11) NOT NULL,
  `aantal` int(11) NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
