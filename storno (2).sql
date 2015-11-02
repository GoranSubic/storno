-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Домаћин: localhost
-- Време креирања: 08. јул 2014. у 07:41
-- Верзија сервера: 5.5.8
-- верзија PHP-a: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База података: `storno`
--
CREATE DATABASE `storno` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `storno`;

-- --------------------------------------------------------

--
-- Структура табеле `artikal`
--

CREATE TABLE IF NOT EXISTS `artikal` (
  `ean` varchar(14) NOT NULL,
  `idartikla` varchar(6) NOT NULL,
  `nebitna` int(1) NOT NULL,
  `nazivartikla` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура табеле `tglava`
--

CREATE TABLE IF NOT EXISTS `tglava` (
  `vreme` datetime NOT NULL,
  `sccode` int(3) NOT NULL,
  `kasabr` int(2) NOT NULL,
  `racunbr` int(5) NOT NULL,
  `naplata` int(1) NOT NULL,
  `racuniznos` varchar(50) NOT NULL,
  `naplata1` varchar(50) NOT NULL,
  `naplata2` varchar(50) NOT NULL,
  `naplata3` varchar(50) NOT NULL,
  `naplata4` varchar(50) NOT NULL,
  `tf` tinyint(1) NOT NULL,
  `vreme2` varchar(13) NOT NULL,
  `operater` varchar(50) NOT NULL,
  `subjekat` int(6) NOT NULL,
  `banka` varchar(50) NOT NULL,
  `prazno1` varchar(50) NOT NULL,
  `prazno2` varchar(50) NOT NULL,
  `datum` varchar(17) NOT NULL,
  `kljuc` int(20) NOT NULL,
  `vremestring` char(20) NOT NULL,
  KEY `kljuc` (`kljuc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура табеле `tstavke`
--

CREATE TABLE IF NOT EXISTS `tstavke` (
  `vreme` datetime NOT NULL,
  `ean` varchar(14) NOT NULL,
  `kolicina` varchar(50) NOT NULL,
  `jm` varchar(10) NOT NULL,
  `cenastavke` varchar(50) NOT NULL,
  `vrednoststavke` varchar(50) NOT NULL,
  `racunbr` int(5) NOT NULL,
  `sccode` int(3) NOT NULL,
  `kasabr` int(2) NOT NULL,
  `kljuc` int(100) NOT NULL,
  `vremestring` char(20) NOT NULL,
  KEY `kljuc` (`kljuc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
