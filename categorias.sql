-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para appgestorland
CREATE DATABASE IF NOT EXISTS `appgestorland` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `appgestorland`;

-- Volcando estructura para tabla appgestorland.clases
CREATE TABLE IF NOT EXISTS `clases` (
  `idclase` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `definicion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idclase`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla appgestorland.clases: ~4 rows (aproximadamente)
INSERT INTO `clases` (`idclase`, `nombre`, `definicion`, `created_at`, `updated_at`) VALUES
	(1, 'Reptiles', 'Reptilia', '2024-09-13 13:40:16', NULL),
	(2, 'Anfibios', 'Amphibia\r\nAmphibia\r\nAmphibia\r\nAmphibia', '2024-09-13 13:41:41', NULL),
	(3, 'Mamiferos', 'Mammalia', '2024-09-13 13:42:04', NULL),
	(4, 'Aves', 'Aves', '2024-09-25 19:07:50', NULL);

-- Volcando estructura para tabla appgestorland.familias
CREATE TABLE IF NOT EXISTS `familias` (
  `idfamilia` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `definicion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `idorden` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idfamilia`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla appgestorland.familias: ~55 rows (aproximadamente)
INSERT INTO `familias` (`idfamilia`, `nombre`, `definicion`, `idorden`, `created_at`, `updated_at`) VALUES
	(1, 'Dipsadidae', 'Dipsadidae', 1, '2024-09-13 14:10:25', NULL),
	(2, 'Gekkonidae', 'Gekkonidae', 1, '2024-09-13 14:13:48', NULL),
	(3, 'Liolaemidae', 'Liolaemidae', 1, '2024-09-13 14:16:08', NULL),
	(4, 'Tropiduridae', 'Tropiduridae', 1, '2024-09-13 14:19:11', NULL),
	(5, 'Bufonidae', 'Bufonidae', 2, '2024-09-13 14:21:20', NULL),
	(6, 'Canidae\r\n', 'Canidae\r\n', 3, '2024-09-13 14:34:34', NULL),
	(7, 'Felidae\r\n', 'Felidae\r\n', 3, '2024-09-13 14:37:12', NULL),
	(8, 'Mustelidae\r\n', 'Mustelidae\r\n', 3, '2024-09-13 14:37:27', NULL),
	(9, 'Mephitidae\r\n', 'Mephitidae\r\n', 3, '2024-09-13 14:37:55', NULL),
	(10, 'Otariidae\r\n', 'Otariidae\r\n', 3, '2024-09-13 14:38:11', NULL),
	(11, 'Balaenopteridae\r\n', 'Balaenopteridae\r\n', 4, '2024-09-13 14:39:06', NULL),
	(12, 'Camelidae\r\n', 'Camelidae\r\n', 4, '2024-09-13 14:39:21', NULL),
	(13, 'Cervidae\r\n', 'Cervidae\r\n', 4, '2024-09-13 14:39:41', NULL),
	(14, 'Delphinidae\r\n', 'Delphinidae\r\n', 4, '2024-09-13 14:40:04', NULL),
	(15, 'Phocoenidae\r\n', 'Phocoenidae\r\n', 4, '2024-09-13 14:44:02', NULL),
	(16, 'Furipteridae\r\n', 'Furipteridae\r\n', 5, '2024-09-13 14:44:32', NULL),
	(17, 'Molossidae\r\n', 'Molossidae\r\n', 5, '2024-09-13 14:44:58', NULL),
	(18, 'Phyllostomidae\r\n', 'Phyllostomidae\r\n', 5, '2024-09-13 14:45:45', NULL),
	(19, 'Vespertilionidae\r\n', 'Vespertilionidae\r\n', 5, '2024-09-13 14:46:09', NULL),
	(20, 'Didelphidae\r\n', 'Didelphidae\r\n', 6, '2024-09-13 14:50:45', NULL),
	(21, 'Leporidae\r\n', 'Leporidae\r\n', 7, '2024-09-13 14:51:10', NULL),
	(22, 'Abrocomidae\r\n', 'Abrocomidae\r\n', 8, '2024-09-13 14:51:26', NULL),
	(23, 'Caviidae\r\n', 'Caviidae\r\n', 8, '2024-09-13 14:53:05', NULL),
	(24, 'Chinchillidae\r\n', 'Chinchillidae\r\n', 8, '2024-09-13 14:53:19', NULL),
	(25, 'Abrothrix andinus\r\n', 'Abrothrix andinus\r\n', 9, '2024-09-13 14:53:42', NULL),
	(26, 'Abrothrix jelskii\r\n', 'Abrothrix jelskii\r\n', 9, '2024-09-13 14:54:04', NULL),
	(27, 'Akodon albiventer\r\n', 'Akodon albiventer\r\n', 9, '2024-09-13 14:54:20', NULL),
	(28, 'Chinchillula sahamae\r\n', 'Chinchillula sahamae\r\n', 9, '2024-09-13 14:54:41', NULL),
	(29, 'Phyllotis limatus\r\n', 'Phyllotis limatus\r\n', 9, '2024-09-13 14:54:56', NULL),
	(30, 'Phyllotis magister\r\n', 'Phyllotis magister\r\n', 9, '2024-09-13 14:55:41', NULL),
	(31, 'Punomys lemminus\r\n', 'Punomys lemminus\r\n', 9, '2024-09-13 14:56:05', NULL),
	(32, 'Ctenomys opimus\r\n', 'Ctenomys opimus\r\n', 10, '2024-09-13 14:56:30', NULL),
	(33, 'Mus musculus\r\n', 'Mus musculus\r\n', 11, '2024-09-13 14:56:48', NULL),
	(34, 'Anatidae\r\n', 'Anatidae\r\n', 12, '2024-09-25 19:40:47', NULL),
	(35, 'Apodidae\r\n', 'Apodidae\r\n', 13, '2024-09-25 19:45:05', NULL),
	(36, 'Trochilidae\r\n', 'Trochilidae\r\n', 13, '2024-09-25 19:46:43', NULL),
	(37, 'Caprimulgidae\r\n', 'Caprimulgidae\r\n', 14, '2024-09-25 19:47:22', NULL),
	(38, 'Burhinidae\r\n', 'Burhinidae\r\n', 15, '2024-09-25 19:47:43', NULL),
	(39, 'Charadriidae\r\n', 'Charadriidae\r\n', 15, '2024-09-25 19:48:11', NULL),
	(40, 'Haematopodidae\r\n', 'Haematopodidae\r\n', 15, '2024-09-25 19:49:06', NULL),
	(41, 'Laridae\r\n', 'Laridae\r\n', 15, '2024-09-25 19:49:40', NULL),
	(42, 'Recurvirostridae\r\n', 'Recurvirostridae\r\n', 15, '2024-09-25 19:50:04', NULL),
	(43, 'Rhynchopidae\r\n', 'Rhynchopidae\r\n', 15, '2024-09-25 19:50:28', NULL),
	(44, 'Scolopacidae\r\n', 'Scolopacidae\r\n', 15, '2024-09-25 19:51:03', NULL),
	(45, 'Stercorariidae\r\n', 'Stercorariidae\r\n', 15, '2024-09-25 19:51:31', NULL),
	(46, 'Thinocoridae\r\n', 'Thinocoridae\r\n', 15, '2024-09-25 19:51:48', NULL),
	(47, 'Ardeidae\r\n', 'Ardeidae\r\n', 16, '2024-09-25 19:52:32', NULL),
	(48, 'Cathartidae\r\n', 'Cathartidae\r\n', 16, '2024-09-25 19:54:06', NULL),
	(49, 'Threskiornithidae\r\n', 'Threskiornithidae\r\n', 16, '2024-09-25 19:54:35', NULL),
	(50, 'Columbidae\r\n', 'Columbidae\r\n', 17, '2024-09-25 19:54:54', NULL),
	(51, 'Cuculidae\r\n', 'Cuculidae\r\n', 18, '2024-09-25 19:55:59', NULL),
	(52, 'Accipitridae\r\n', 'Accipitridae\r\n', 19, '2024-09-25 19:56:38', NULL),
	(53, 'Falconidae\r\n', 'Falconidae\r\n', 19, '2024-09-25 19:57:06', NULL),
	(54, 'Pandionidae\r\n', 'Pandionidae\r\n', 19, '2024-09-25 19:57:45', NULL),
	(55, 'Leptodactylidae', 'Leptodactylidae', 2, '2024-10-21 14:26:36', NULL);

-- Volcando estructura para tabla appgestorland.ordens
CREATE TABLE IF NOT EXISTS `ordens` (
  `idorden` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `definicion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `idclase` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idorden`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla appgestorland.ordens: ~19 rows (aproximadamente)
INSERT INTO `ordens` (`idorden`, `nombre`, `definicion`, `idclase`, `created_at`, `updated_at`) VALUES
	(1, 'squamata', 'squamata', 1, '2024-09-13 14:09:30', NULL),
	(2, 'Anura', 'Anura', 2, '2024-09-13 14:20:42', NULL),
	(3, 'Carnivora', 'Carnivora', 3, '2024-09-13 14:24:49', NULL),
	(4, 'Cetartiodactyla\r\n', 'Cetartiodactyla\r\n', 3, '2024-09-13 14:25:40', NULL),
	(5, 'Chiroptera\r\n', 'Chiroptera\r\n', 3, '2024-09-13 14:27:02', NULL),
	(6, 'Didelphimorphia\r\n', 'Didelphimorphia\r\n', 3, '2024-09-13 14:27:44', NULL),
	(7, 'Lagomorpha\r\n', 'Lagomorpha\r\n', 3, '2024-09-13 14:28:31', NULL),
	(8, 'Rodentia\r\n', 'Rodentia\r\n', 3, '2024-09-13 14:29:02', NULL),
	(9, 'Cricetidae\r\n', 'Cricetidae\r\n', 3, '2024-09-13 14:29:27', NULL),
	(10, 'Ctenomyidae\r\n', 'Ctenomyidae\r\n', 3, '2024-09-13 14:29:56', NULL),
	(11, 'Muridae\r\n', 'Muridae\r\n', 3, '2024-09-13 14:30:14', NULL),
	(12, 'ANSERIFORMES', 'ANSERIFORMES\r\n', 4, '2024-09-25 19:31:49', NULL),
	(13, 'APODIFORMES', 'APODIFORMES\r\n', 4, '2024-09-25 19:32:35', NULL),
	(14, 'CAPRIMULGIFORMES', 'CAPRIMULGIFORMES\r\n', 4, '2024-09-25 19:33:25', NULL),
	(15, 'CHARADRIIFORMES\r\n', 'CHARADRIIFORMES\r\n', 4, '2024-09-25 19:34:04', NULL),
	(16, 'CICONIIFORMES\r\n', 'CICONIIFORMES\r\n', 4, '2024-09-25 19:34:37', NULL),
	(17, 'COLUMBIFORMES\r\n', 'COLUMBIFORMES\r\n', 4, '2024-09-25 19:35:00', NULL),
	(18, 'CUCULIFORMES\r\n', 'CUCULIFORMES\r\n', 4, '2024-09-25 19:35:19', NULL),
	(19, 'FALCONIFORMES\r\n', 'FALCONIFORMES\r\n', 4, '2024-09-25 19:35:37', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
