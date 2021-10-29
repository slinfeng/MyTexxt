-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1:3306
-- 生成日時: 2021-09-30 06:11:08
-- サーバのバージョン： 8.0.21
-- PHP のバージョン: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `lactes_smarhr`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `permission_role`
--

DROP TABLE IF EXISTS `permission_role`;
CREATE TABLE IF NOT EXISTS `permission_role` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_id` int UNSIGNED NOT NULL COMMENT '役割ID',
  `permission_id` int UNSIGNED NOT NULL COMMENT '権限ID',
  PRIMARY KEY (`id`),
  KEY `role_id_fk_476162` (`role_id`) USING BTREE,
  KEY `permission_id_fk_476162` (`permission_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=163 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='権限と役割マッピング';

--
-- テーブルのデータのダンプ `permission_role`
--

INSERT INTO `permission_role` (`role_id`, `permission_id`) VALUES
(3, 3),
(3, 8),
(3, 13),
(3, 18),
(3, 23),
(3, 28),
(3, 34),
(3, 38),
(3, 43),
(3, 48),
(3, 53),
(3, 57),
(3, 61),
(3, 67),
(3, 71),
(3, 75),
(3, 80),
(3, 85);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
