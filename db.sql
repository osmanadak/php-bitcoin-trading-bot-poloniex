-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 31 Ağu 2017, 18:54:31
-- Sunucu sürümü: 5.6.37
-- PHP Sürümü: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `coinnect_test`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `actions`
--

CREATE TABLE `actions` (
  `id` int(11) NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `user` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `ip` varchar(55) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `username` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `api_settings`
--

CREATE TABLE `api_settings` (
  `id` int(11) NOT NULL,
  `api_key` text COLLATE utf8_bin NOT NULL,
  `api_secret` text COLLATE utf8_bin NOT NULL,
  `btc_amount_per_buy` decimal(20,8) NOT NULL,
  `buy_limit_per_coin` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Tablo döküm verisi `api_settings`
--

INSERT INTO `api_settings` (`id`, `api_key`, `api_secret`, `btc_amount_per_buy`, `buy_limit_per_coin`) VALUES
(1, '', '', '0.00200000', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `current_balances`
--

CREATE TABLE `current_balances` (
  `id` int(11) NOT NULL,
  `coin` varchar(255) COLLATE utf8_bin NOT NULL,
  `balance` decimal(20,8) NOT NULL,
  `btc_value` decimal(20,8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `data`
--

CREATE TABLE `data` (
  `id` int(11) NOT NULL,
  `coinType` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `last` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `lowestAsk` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `highestBid` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `percentChange` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `baseVolume` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `quoteVolume` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `isFrozen` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `high24hr` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `low24hr` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `etc` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `text` text COLLATE utf8_bin NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `processes`
--

CREATE TABLE `processes` (
  `id` int(11) NOT NULL,
  `coinType` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `buyPrice` decimal(20,8) NOT NULL,
  `sellPrice` decimal(20,8) NOT NULL,
  `stopLossPrice` decimal(20,8) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `orderNumber` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `sellOrderNumber` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `amount` decimal(20,8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `rules`
--

CREATE TABLE `rules` (
  `id` int(11) NOT NULL,
  `coin` varchar(15) COLLATE utf8_bin NOT NULL,
  `buy_type` tinyint(1) NOT NULL,
  `time` int(11) NOT NULL,
  `buy_percent` int(11) NOT NULL,
  `sell_on_profit` int(11) NOT NULL,
  `stop_loss` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `total_btc`
--

CREATE TABLE `total_btc` (
  `id` int(11) NOT NULL,
  `amount` decimal(20,8) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(225) NOT NULL,
  `password` varchar(225) NOT NULL,
  `email` varchar(225) NOT NULL,
  `rights` int(11) NOT NULL DEFAULT '1',
  `payment_method` int(11) NOT NULL,
  `payment_address` varchar(225) NOT NULL,
  `groups` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `rights`, `payment_method`, `payment_address`, `groups`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'info@coinnect.xyz', 0, 0, '', '');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `actions`
--
ALTER TABLE `actions`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `api_settings`
--
ALTER TABLE `api_settings`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `current_balances`
--
ALTER TABLE `current_balances`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coinType` (`coinType`),
  ADD KEY `coinType_2` (`coinType`);

--
-- Tablo için indeksler `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `processes`
--
ALTER TABLE `processes`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `rules`
--
ALTER TABLE `rules`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `total_btc`
--
ALTER TABLE `total_btc`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `actions`
--
ALTER TABLE `actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Tablo için AUTO_INCREMENT değeri `api_settings`
--
ALTER TABLE `api_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Tablo için AUTO_INCREMENT değeri `current_balances`
--
ALTER TABLE `current_balances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Tablo için AUTO_INCREMENT değeri `data`
--
ALTER TABLE `data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Tablo için AUTO_INCREMENT değeri `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Tablo için AUTO_INCREMENT değeri `processes`
--
ALTER TABLE `processes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Tablo için AUTO_INCREMENT değeri `rules`
--
ALTER TABLE `rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Tablo için AUTO_INCREMENT değeri `total_btc`
--
ALTER TABLE `total_btc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
