-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               11.5.2-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for healz_db
CREATE DATABASE IF NOT EXISTS `healz_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `healz_db`;

-- Dumping structure for table healz_db.messages
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender` varchar(50) DEFAULT NULL,
  `receiver` varchar(50) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table healz_db.messages: ~9 rows (approximately)
INSERT INTO `messages` (`id`, `sender`, `receiver`, `message`, `timestamp`, `created_at`) VALUES
	(1, 'dapin', 'user2', 'tes', '2025-02-06 16:25:05', NULL),
	(2, 'dapin', 'baros', 'tes', '2025-02-06 16:25:11', NULL),
	(3, 'dapin', 'baros', 'tes', '2025-02-06 17:04:33', NULL),
	(4, 'dapin', 'baros', 'tes', '2025-02-06 17:05:28', NULL),
	(5, 'dapin', 'baros', 'tes', '2025-02-06 17:06:43', NULL),
	(6, 'dapin', 'baros', 'test', '2025-02-06 17:07:16', NULL),
	(7, 'dapin', 'dapin', 'tes', '2025-02-06 18:31:34', NULL),
	(8, 'dapin', 'dapin2', '2132123', '2025-02-06 18:32:11', NULL),
	(9, 'dapin2', 'dapin', 'test', '2025-02-06 18:32:32', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
