-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: mysql36.mydevil.net
-- Czas generowania: 10 Kwi 2019, 20:39
-- Wersja serwera: 5.7.21-20-log
-- Wersja PHP: 7.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `Projekt`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL COMMENT 'Unique language ID',
  `lang_code` tinyint(4) NOT NULL COMMENT 'Code used in relations',
  `name` varchar(15) NOT NULL COMMENT 'Language name'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `languages`
--

INSERT INTO `languages` (`id`, `lang_code`, `name`) VALUES
(1, 1, 'Polski'),
(2, 2, 'Angielski'),
(3, 3, 'Niemiecki');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL COMMENT 'Unique product ID',
  `price` double NOT NULL DEFAULT '0' COMMENT 'Product price',
  `quantity` int(11) NOT NULL DEFAULT '0' COMMENT 'Quantity in stock'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contains every product';

--
-- Zrzut danych tabeli `products`
--

INSERT INTO `products` (`id`, `price`, `quantity`) VALUES
(1, 4797.12, 94),
(2, 3200, 240),
(3, 75000, 2),
(4, 325.17, 1772);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `products_description`
--

CREATE TABLE `products_description` (
  `desc_id` int(11) NOT NULL COMMENT 'Description record id',
  `product_id` int(11) NOT NULL COMMENT 'Reference to ID in products table',
  `lang_code` tinyint(4) NOT NULL COMMENT 'Defines record language',
  `name` varchar(300) NOT NULL COMMENT 'Product name',
  `description` text NOT NULL COMMENT 'Product description'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `products_description`
--

INSERT INTO `products_description` (`desc_id`, `product_id`, `lang_code`, `name`, `description`) VALUES
(1, 1, 1, 'Komputer PC', 'Najszybszy komputer'),
(2, 2, 1, 'Telewizor LCD', 'Telewizor marki Samsung'),
(3, 3, 1, 'Samochód osobowy', 'Samochód Nissan GTR'),
(4, 4, 1, 'Czerwony stolik', 'Stolik ogrodowy w kolorze czerwonym'),
(5, 1, 2, 'Desktop PC', 'The fastest computer'),
(6, 1, 3, 'Computer PC', 'Der schnellste Computer'),
(7, 2, 3, 'LCD Fernseher', 'Samsung Fernsehgerät'),
(8, 2, 2, 'LCD TV', 'Samsung TV set'),
(9, 3, 2, 'Car', 'Nissan GTR car'),
(10, 3, 3, 'Ein Auto', 'Nissan GTR Auto'),
(11, 4, 3, 'Roter Tisch', 'Gartentisch in rot'),
(12, 4, 2, 'Red table', 'Garden table in red');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', 'd74ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f1');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `id_2` (`id`);

--
-- Indeksy dla tabeli `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indeksy dla tabeli `products_description`
--
ALTER TABLE `products_description`
  ADD PRIMARY KEY (`desc_id`),
  ADD UNIQUE KEY `id` (`desc_id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique language ID', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique product ID', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT dla tabeli `products_description`
--
ALTER TABLE `products_description`
  MODIFY `desc_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Description record id', AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
