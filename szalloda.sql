-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2024. Okt 08. 12:06
-- Kiszolgáló verziója: 10.4.28-MariaDB
-- PHP verzió: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `szalloda`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `alkalmazottak`
--

CREATE TABLE `alkalmazottak` (
  `alkalmazott_id` int(11) NOT NULL,
  `vezeteknev` varchar(50) NOT NULL,
  `keresztnev` varchar(50) NOT NULL,
  `pozicio` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefonszam` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `alkalmazottak`
--

INSERT INTO `alkalmazottak` (`alkalmazott_id`, `vezeteknev`, `keresztnev`, `pozicio`, `email`, `telefonszam`) VALUES
(1, 'Kiss', 'Tamás', 'Recepciós', 'kiss.tamas@example.com', '06201234567'),
(2, 'Horváth', 'Éva', 'Szobalány', 'horvath.eva@example.com', '06209876543'),
(3, 'Tóth', 'Gábor', 'Manager', 'toth.gabor@example.com', '06207654321');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `foglalasok`
--

CREATE TABLE `foglalasok` (
  `foglalas_id` int(11) NOT NULL,
  `vendeg_id` int(11) DEFAULT NULL,
  `szoba_id` int(11) DEFAULT NULL,
  `erkezes_datuma` date NOT NULL,
  `tavozas_datuma` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `foglalasok`
--

INSERT INTO `foglalasok` (`foglalas_id`, `vendeg_id`, `szoba_id`, `erkezes_datuma`, `tavozas_datuma`) VALUES
(1, 1, 101, '2023-06-01', '2023-06-05'),
(2, 2, 102, '2023-06-10', '2023-06-14'),
(3, 3, 201, '2023-06-20', '2023-06-25');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `szobak`
--

CREATE TABLE `szobak` (
  `szoba_id` int(11) NOT NULL,
  `szobatipus` varchar(50) NOT NULL,
  `ar_per_ejszaka` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `szobak`
--

INSERT INTO `szobak` (`szoba_id`, `szobatipus`, `ar_per_ejszaka`) VALUES
(101, 'Egyágyas', 15000.00),
(102, 'Kétágyas', 25000.00),
(201, 'Lakosztály', 50000.00);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `vendegek`
--

CREATE TABLE `vendegek` (
  `vendeg_id` int(11) NOT NULL,
  `vezeteknev` varchar(50) NOT NULL,
  `keresztnev` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefonszam` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `vendegek`
--

INSERT INTO `vendegek` (`vendeg_id`, `vezeteknev`, `keresztnev`, `email`, `telefonszam`) VALUES
(1, 'Kovács', 'János', 'kovacs.janos@example.com', '06301234567'),
(2, 'Nagy', 'Péter', 'nagy.peter@example.com', '06309876543'),
(3, 'Szabó', 'Anna', 'szabo.anna@example.com', '06307654321');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `alkalmazottak`
--
ALTER TABLE `alkalmazottak`
  ADD PRIMARY KEY (`alkalmazott_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- A tábla indexei `foglalasok`
--
ALTER TABLE `foglalasok`
  ADD PRIMARY KEY (`foglalas_id`),
  ADD KEY `vendeg_id` (`vendeg_id`),
  ADD KEY `szoba_id` (`szoba_id`);

--
-- A tábla indexei `szobak`
--
ALTER TABLE `szobak`
  ADD PRIMARY KEY (`szoba_id`);

--
-- A tábla indexei `vendegek`
--
ALTER TABLE `vendegek`
  ADD PRIMARY KEY (`vendeg_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `alkalmazottak`
--
ALTER TABLE `alkalmazottak`
  MODIFY `alkalmazott_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT a táblához `foglalasok`
--
ALTER TABLE `foglalasok`
  MODIFY `foglalas_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT a táblához `vendegek`
--
ALTER TABLE `vendegek`
  MODIFY `vendeg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `foglalasok`
--
ALTER TABLE `foglalasok`
  ADD CONSTRAINT `foglalasok_ibfk_1` FOREIGN KEY (`vendeg_id`) REFERENCES `vendegek` (`vendeg_id`),
  ADD CONSTRAINT `foglalasok_ibfk_2` FOREIGN KEY (`szoba_id`) REFERENCES `szobak` (`szoba_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
