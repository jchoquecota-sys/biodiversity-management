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


-- Volcando estructura de base de datos para biodiversity_management
CREATE DATABASE IF NOT EXISTS `biodiversity_management` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `biodiversity_management`;

-- Volcando estructura para tabla biodiversity_management.biodiversity_categories
CREATE TABLE IF NOT EXISTS `biodiversity_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scientific_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `common_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `conservation_status` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kingdom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idreino` bigint unsigned DEFAULT NULL,
  `conservation_status_id` bigint unsigned DEFAULT NULL,
  `idfamilia` bigint unsigned DEFAULT NULL,
  `habitat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `biodiversity_categories_conservation_status_foreign` (`conservation_status`),
  KEY `biodiversity_categories_idfamilia_foreign` (`idfamilia`),
  KEY `biodiversity_categories_idreino_foreign` (`idreino`),
  KEY `biodiversity_categories_conservation_status_id_foreign` (`conservation_status_id`),
  CONSTRAINT `biodiversity_categories_conservation_status_foreign` FOREIGN KEY (`conservation_status`) REFERENCES `conservation_statuses` (`code`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `biodiversity_categories_conservation_status_id_foreign` FOREIGN KEY (`conservation_status_id`) REFERENCES `conservation_statuses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `biodiversity_categories_idfamilia_foreign` FOREIGN KEY (`idfamilia`) REFERENCES `familias` (`idfamilia`) ON DELETE SET NULL,
  CONSTRAINT `biodiversity_categories_idreino_foreign` FOREIGN KEY (`idreino`) REFERENCES `reinos` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.biodiversity_categories: ~18 rows (aproximadamente)
INSERT INTO `biodiversity_categories` (`id`, `name`, `scientific_name`, `common_name`, `description`, `conservation_status`, `kingdom`, `idreino`, `conservation_status_id`, `idfamilia`, `habitat`, `image_path`, `deleted_at`, `created_at`, `updated_at`) VALUES
	(53, 'Quetzal Resplandeciente', 'Pharomachrus mocinno', 'Quetzal', 'Ave sagrada maya conocida por su plumaje iridiscente verde-dorado. El macho posee plumas coberteras que pueden alcanzar hasta 1 metro de longitud.', 'EN', 'Animalia', NULL, NULL, 1, 'Bosque nuboso', NULL, NULL, '2025-07-30 21:51:04', '2025-08-14 22:29:48'),
	(54, 'Quetzal Resplandeciente', 'Pharomachrus mocinno', 'Quetzal', 'Ave sagrada maya conocida por su plumaje iridiscente verde-dorado y largas plumas caudales. Habita en bosques nubosos de altura en Mesoamérica.', 'NE', 'Animalia', NULL, NULL, NULL, 'Bosque nuboso tropical', NULL, '2025-08-18 19:50:53', '2025-07-30 21:53:52', '2025-08-18 19:50:53'),
	(55, 'Jaguar', 'Panthera onca', 'Jaguar', 'El felino más grande de América, reconocido por su pelaje dorado con rosetas negras. Depredador apex crucial para el equilibrio ecosistémico.', 'NT', 'Animalia', NULL, NULL, 39, 'Selvas tropicales y subtropicales', NULL, NULL, '2025-07-30 21:53:59', '2025-08-18 19:58:26'),
	(56, 'Vaquita Marina', 'Phocoena sinus', 'Vaquita', 'La marsopa más pequeña y en mayor peligro del mundo, endémica del Golfo de California. Se caracteriza por sus distintivos anillos oscuros alrededor de los ojos.', 'CR', 'Animalia', NULL, NULL, NULL, 'Aguas costeras del Golfo de California', NULL, NULL, '2025-07-30 21:54:17', '2025-07-30 21:54:17'),
	(57, 'Ajolote Mexicano', 'Ambystoma mexicanum', 'Ajolote', 'Salamandra endémica de Xochimilco, México, conocida por su extraordinaria capacidad de regeneración y por mantener características juveniles en su edad adulta.', 'CR', 'Animalia', NULL, NULL, NULL, 'Canales de agua dulce de Xochimilco', NULL, NULL, '2025-07-30 21:54:33', '2025-07-30 21:54:33'),
	(58, 'Guacamaya Roja', 'Ara macao', 'Guacamaya Escarlata', 'Ave tropical de gran tamaño conocida por su brillante plumaje rojo, amarillo y azul. Juega un papel crucial en la dispersión de semillas en la selva tropical.', 'VU', 'Animalia', NULL, NULL, NULL, 'Selvas tropicales y subtropicales', NULL, NULL, '2025-07-30 21:54:49', '2025-07-30 21:54:49'),
	(59, 'Tortuga Lora', 'Lepidochelys kempii', 'Tortuga Lora', 'La especie más pequeña de tortuga marina, conocida por su caparazón casi circular y color gris verdoso. Realiza arribadas masivas para anidar en las costas del Golfo de México.', 'CR', 'Animalia', NULL, NULL, NULL, 'Aguas costeras del Golfo de México', NULL, NULL, '2025-07-30 21:55:04', '2025-07-30 21:55:04'),
	(60, 'Lobo Mexicano', 'Canis lupus baileyi', 'Lobo Gris Mexicano', 'Subespecie más pequeña del lobo gris, endémica de México y el suroeste de Estados Unidos. Cazador social que desempeña un papel vital en el control de poblaciones de herbívoros.', 'CR', 'Animalia', NULL, NULL, NULL, 'Bosques de pino-encino y pastizales', NULL, NULL, '2025-07-30 21:55:18', '2025-07-30 21:55:18'),
	(61, 'Águila Real', 'Aquila chrysaetos', 'Águila Real', 'Rapaz majestuosa y emblemática de México, reconocida por su gran envergadura y capacidad de caza. Importante depredador que ayuda a mantener el equilibrio en ecosistemas de montaña.', 'NE', 'Animalia', NULL, NULL, NULL, 'Zonas montañosas y semidesérticas', NULL, NULL, '2025-07-30 21:55:43', '2025-07-30 21:55:43'),
	(62, 'Tapir Centroamericano', 'Tapirus bairdii', 'Tapir, Danta', 'El mamífero terrestre más grande de la región mesoamericana. Herbívoro que cumple un papel crucial en la dispersión de semillas y el mantenimiento de la estructura del bosque tropical.', 'EN', 'Animalia', NULL, NULL, NULL, 'Selvas tropicales y bosques de montaña', NULL, NULL, '2025-07-30 21:56:00', '2025-07-30 21:56:00'),
	(63, 'Manatí del Caribe', 'Trichechus manatus', 'Manatí Antillano', 'Mamífero acuático herbívoro que habita en aguas costeras y ríos. Conocido como vaca marina, juega un papel importante en el control de la vegetación acuática y el ciclo de nutrientes.', 'EN', 'Animalia', NULL, NULL, NULL, 'Aguas costeras, estuarios y ríos', NULL, NULL, '2025-07-30 21:56:47', '2025-07-30 21:56:47'),
	(64, 'Mono Araña', 'Ateles geoffroyi', 'Mono Araña Centroamericano', 'Primate arborícola con extremidades largas y cola prensil. Importante dispersor de semillas en las selvas tropicales y considerado una especie paraguas para la conservación del ecosistema.', 'EN', 'Animalia', NULL, NULL, NULL, 'Selvas tropicales perennifolias y subperennifolias', NULL, NULL, '2025-07-30 21:57:03', '2025-07-30 21:57:03'),
	(65, 'Mariposa Monarca', 'Danaus plexippus', 'Mariposa Monarca', 'Lepidóptero famoso por su extraordinaria migración anual. Sus colonias invernales en los bosques de oyamel de México son uno de los fenómenos más impresionantes de la naturaleza.', 'EN', 'Animalia', NULL, NULL, NULL, 'Bosques de oyamel y áreas con presencia de plantas asclepias', NULL, NULL, '2025-07-30 21:57:25', '2025-07-30 21:57:25'),
	(66, 'Ahuehuete', 'Taxodium mucronatum', 'Sabino, Ciprés Mexicano', 'Árbol nacional de México, puede vivir más de 2000 años. Es una especie longeva que puede alcanzar alturas de hasta 40 metros. Sus ramas forman una copa amplia y sus hojas son pequeñas y lineales.', 'LC', 'Plantae', NULL, NULL, NULL, 'Riberas de ríos y arroyos', NULL, NULL, NULL, NULL),
	(67, 'Pino Azul', 'Pinus maximartinezii', NULL, 'Especie endémica de México, conocida por sus grandes piñones comestibles y su coloración azul-verdosa distintiva. Se encuentra en peligro de extinción debido a su distribución limitada y la sobreexplotación.', 'NE', 'Plantae', NULL, NULL, NULL, 'Bosques de pino-encino en la Sierra Madre Occidental, específicamente en Zacatecas', NULL, NULL, '2025-07-30 22:10:40', '2025-07-30 22:10:40'),
	(68, 'Orquídea Tigre', 'Tigridia pavonia', NULL, 'Planta herbácea perenne conocida por sus flores espectaculares que duran solo un día. Es una especie endémica de México con gran valor ornamental y cultural, utilizada tradicionalmente por culturas prehispánicas.', 'LC', 'Plantae', NULL, NULL, NULL, 'Pastizales y bosques de pino-encino en regiones templadas de México', NULL, NULL, '2025-07-30 22:10:56', '2025-07-30 22:10:56'),
	(69, 'Cacao', 'Theobroma cacao', NULL, 'Árbol perennifolio tropical, fuente del chocolate. Originario de las selvas tropicales de América Central y del Sur. De gran importancia económica y cultural en México desde tiempos prehispánicos.', 'LC', 'Plantae', NULL, NULL, NULL, 'Selvas tropicales húmedas y subhúmedas de México', NULL, NULL, '2025-07-30 22:11:16', '2025-07-30 22:11:16'),
	(70, 'Vainilla', 'Vanilla planifolia', NULL, 'Orquídea trepadora originaria de México, productora de uno de los saborizantes más importantes del mundo. Es la única orquídea de importancia agrícola y fue domesticada por los totonacas en Veracruz.', 'VU', 'Plantae', NULL, NULL, NULL, 'Selvas tropicales húmedas y subhúmedas, principalmente en Veracruz y Oaxaca', NULL, NULL, '2025-07-30 22:11:33', '2025-07-30 22:11:33');

-- Volcando estructura para tabla biodiversity_management.biodiversity_category_publication
CREATE TABLE IF NOT EXISTS `biodiversity_category_publication` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `biodiversity_category_id` bigint unsigned NOT NULL,
  `publication_id` bigint unsigned NOT NULL,
  `relevant_excerpt` text COLLATE utf8mb4_unicode_ci,
  `page_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bio_cat_pub_unique` (`biodiversity_category_id`,`publication_id`),
  KEY `bio_cat_pub_publication_fk` (`publication_id`),
  CONSTRAINT `bio_cat_pub_category_fk` FOREIGN KEY (`biodiversity_category_id`) REFERENCES `biodiversity_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bio_cat_pub_publication_fk` FOREIGN KEY (`publication_id`) REFERENCES `publications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.biodiversity_category_publication: ~17 rows (aproximadamente)
INSERT INTO `biodiversity_category_publication` (`id`, `biodiversity_category_id`, `publication_id`, `relevant_excerpt`, `page_reference`, `created_at`, `updated_at`) VALUES
	(2, 53, 20, 'Análisis del Quetzal', 'pp. 23-45', NULL, NULL),
	(3, 55, 21, NULL, NULL, NULL, '2025-08-18 19:58:26'),
	(4, 56, 22, 'Investigación de la Vaquita Marina', 'pp. 45-67', NULL, NULL),
	(5, 57, 23, 'Estudio del Ajolote', 'pp. 78-92', NULL, NULL),
	(6, 58, 24, 'Investigación de la Guacamaya', 'pp. 112-128', NULL, NULL),
	(7, 59, 25, 'Análisis de la Tortuga Lora', 'pp. 55-70', NULL, NULL),
	(8, 60, 26, 'Estudio del Lobo Mexicano', 'pp. 145-160', NULL, NULL),
	(9, 61, 27, 'Investigación del Águila Real', 'pp. 89-104', NULL, NULL),
	(10, 62, 28, 'Análisis del Tapir', 'pp. 167-182', NULL, NULL),
	(11, 63, 29, 'Estudio del Manatí', 'pp. 201-218', NULL, NULL),
	(12, 64, 30, 'Investigación del Mono Araña', 'pp. 134-152', NULL, NULL),
	(13, 65, 31, 'Análisis de la Mariposa Monarca', 'pp. 78-95', NULL, NULL),
	(14, 66, 32, 'Estudio detallado sobre la importancia histórica y ecológica del Ahuehuete como árbol nacional de México', '45-67', NULL, NULL),
	(15, 67, 33, 'Análisis del estado de conservación y distribución del Pinus maximartinezii en su hábitat natural', '78-92', NULL, NULL),
	(16, 68, 34, 'Investigación sobre la diversidad genética y patrones de distribución de Tigridia pavonia', '112-128', NULL, NULL),
	(17, 69, 35, 'Estudio sobre la diversidad de variedades nativas de cacao en México y su importancia en la conservación', '156-170', NULL, NULL),
	(18, 70, 36, 'Evaluación del estado actual de las poblaciones silvestres de vainilla y su diversidad genética', '203-225', NULL, NULL);

-- Volcando estructura para tabla biodiversity_management.biodiversity_publication
CREATE TABLE IF NOT EXISTS `biodiversity_publication` (
  `biodiversity_id` bigint unsigned NOT NULL,
  `publication_id` bigint unsigned NOT NULL,
  `relevant_excerpt` text COLLATE utf8mb4_unicode_ci,
  `page_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`biodiversity_id`,`publication_id`),
  KEY `biodiversity_publication_publication_id_foreign` (`publication_id`),
  CONSTRAINT `biodiversity_publication_biodiversity_id_foreign` FOREIGN KEY (`biodiversity_id`) REFERENCES `biodiversity_categories` (`id`),
  CONSTRAINT `biodiversity_publication_publication_id_foreign` FOREIGN KEY (`publication_id`) REFERENCES `publications` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.biodiversity_publication: ~0 rows (aproximadamente)

-- Volcando estructura para tabla biodiversity_management.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.cache: ~0 rows (aproximadamente)

-- Volcando estructura para tabla biodiversity_management.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.cache_locks: ~0 rows (aproximadamente)

-- Volcando estructura para tabla biodiversity_management.clases
CREATE TABLE IF NOT EXISTS `clases` (
  `idclase` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `definicion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `idreino` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idclase`),
  KEY `clases_idreino_foreign` (`idreino`),
  CONSTRAINT `clases_idreino_foreign` FOREIGN KEY (`idreino`) REFERENCES `reinos` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.clases: ~4 rows (aproximadamente)
INSERT INTO `clases` (`idclase`, `nombre`, `definicion`, `idreino`, `created_at`, `updated_at`) VALUES
	(1, 'Reptiles', 'Reptilia', 1, '2024-09-13 13:40:16', '2025-08-18 21:22:09'),
	(2, 'Anfibios', 'Amphibia\r\nAmphibia\r\nAmphibia\r\nAmphibia', 1, '2024-09-13 13:41:41', '2025-08-18 21:22:09'),
	(3, 'Mamiferos', 'Mammalia', 1, '2024-09-13 13:42:04', '2025-08-18 21:22:09'),
	(4, 'Aves', 'Aves', 1, '2024-09-25 19:07:50', '2025-08-18 21:22:09');

-- Volcando estructura para tabla biodiversity_management.conservation_statuses
CREATE TABLE IF NOT EXISTS `conservation_statuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `color` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'secondary',
  `priority` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conservation_statuses_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.conservation_statuses: ~9 rows (aproximadamente)
INSERT INTO `conservation_statuses` (`id`, `code`, `name`, `name_en`, `description`, `color`, `priority`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'EX', 'Extinto', 'Extinct', 'No hay duda razonable de que el último individuo existente ha muerto.', 'danger', 9, 1, '2025-07-31 20:27:52', '2025-07-31 20:27:52'),
	(2, 'EW', 'Extinto en Estado Silvestre', 'Extinct in the Wild', 'Se sabe que solo sobrevive en cultivo, en cautividad o como población naturalizada fuera de su área de distribución histórica.', 'danger', 8, 1, '2025-07-31 20:27:52', '2025-08-18 20:36:14'),
	(3, 'CR', 'En Peligro Crítico', 'Critically Endangered', 'Se considera que se enfrenta a un riesgo extremadamente alto de extinción en estado silvestre.', 'danger', 7, 1, '2025-07-31 20:27:52', '2025-08-18 20:36:14'),
	(4, 'EN', 'En Peligro', 'Endangered', 'Se considera que se enfrenta a un riesgo muy alto de extinción en estado silvestre.', 'warning', 6, 1, '2025-07-31 20:27:52', '2025-08-18 20:36:14'),
	(5, 'VU', 'Vulnerable', 'Vulnerable', 'Se considera que se enfrenta a un riesgo alto de extinción en estado silvestre.', 'warning', 5, 1, '2025-07-31 20:27:52', '2025-07-31 20:27:52'),
	(6, 'NT', 'Casi Amenazado', 'Near Threatened', 'No califica para En Peligro Crítico, En Peligro o Vulnerable ahora, pero está cerca de calificar o es probable que califique para una categoría amenazada en el futuro cercano.', 'info', 4, 1, '2025-07-31 20:27:52', '2025-07-31 20:27:52'),
	(7, 'LC', 'Preocupación Menor', 'Least Concern', 'Ha sido evaluado y no califica para En Peligro Crítico, En Peligro, Vulnerable o Casi Amenazado.', 'success', 3, 1, '2025-07-31 20:27:52', '2025-07-31 20:27:52'),
	(8, 'DD', 'Datos Insuficientes', 'Data Deficient', 'No hay información adecuada para hacer una evaluación directa o indirecta de su riesgo de extinción.', 'secondary', 2, 1, '2025-07-31 20:27:52', '2025-07-31 20:27:52'),
	(9, 'NE', 'No Evaluado', 'Not Evaluated', 'No ha sido evaluado contra los criterios.', 'secondary', 1, 1, '2025-07-31 20:27:52', '2025-07-31 20:27:52');

-- Volcando estructura para tabla biodiversity_management.email_verification_tokens
CREATE TABLE IF NOT EXISTS `email_verification_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.email_verification_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla biodiversity_management.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.failed_jobs: ~0 rows (aproximadamente)

-- Volcando estructura para tabla biodiversity_management.familias
CREATE TABLE IF NOT EXISTS `familias` (
  `idfamilia` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `definicion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `idorden` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idfamilia`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.familias: ~55 rows (aproximadamente)
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
	(10, 'Otariidae', 'Otariidae', 3, '2024-09-13 14:38:11', '2025-08-18 21:53:01'),
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

-- Volcando estructura para tabla biodiversity_management.hero_slider_images
CREATE TABLE IF NOT EXISTS `hero_slider_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `has_overlay_image` tinyint(1) NOT NULL DEFAULT '0',
  `overlay_position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'left',
  `overlay_alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `overlay_description` text COLLATE utf8mb4_unicode_ci,
  `overlay_button_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `overlay_button_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.hero_slider_images: ~2 rows (aproximadamente)
INSERT INTO `hero_slider_images` (`id`, `title`, `description`, `alt_text`, `button_text`, `button_url`, `sort_order`, `is_active`, `has_overlay_image`, `overlay_position`, `overlay_alt_text`, `overlay_description`, `overlay_button_text`, `overlay_button_url`, `created_at`, `updated_at`) VALUES
	(1, 'FOTO1', 'FOTO1 DESCR', 'fotosssssssssss', 'lo maximo', NULL, 0, 1, 1, 'right', 'Gobierno Regional de Tacna', 'Es un portal muy interesante', NULL, NULL, '2025-07-31 21:53:13', '2025-08-01 22:19:34'),
	(2, 'FOTO 2', 'FFFFFFFFFFF', 'FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF', 'FFFFFFFFF', NULL, 1, 1, 0, 'left', NULL, NULL, NULL, NULL, '2025-07-31 22:02:10', '2025-07-31 22:02:10');

-- Volcando estructura para tabla biodiversity_management.home_content
CREATE TABLE IF NOT EXISTS `home_content` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `home_content_section_key_unique` (`section`,`key`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.home_content: ~29 rows (aproximadamente)
INSERT INTO `home_content` (`id`, `section`, `key`, `value`, `type`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
	(1, 'hero', 'title', 'Descubre la Riqueza de la Biodiversidad', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(2, 'hero', 'subtitle', 'Explora nuestra extensa base de datos de especies, ecosistemas y publicaciones científicas. México es uno de los países con mayor biodiversidad del mundo, hogar de miles de especies únicas.', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(3, 'hero', 'button_primary_text', 'Explorar Especies', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(4, 'hero', 'button_primary_url', '/biodiversity', 'url', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(5, 'hero', 'button_secondary_text', 'Publicaciones', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(6, 'hero', 'button_secondary_url', '/publications', 'url', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(7, 'hero', 'hero_image', 'fas fa-globe-americas', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(8, 'search', 'title', '¿Qué especie buscas?', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(9, 'search', 'subtitle', 'Busca entre miles de especies registradas en nuestro sistema', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(10, 'search', 'placeholder', 'Buscar por nombre común o científico...', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(11, 'stats', 'title', 'Nuestra Biodiversidad en Números', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(12, 'stats', 'categories_title', 'Categorías de Especies', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(13, 'stats', 'categories_description', 'Diferentes grupos taxonómicos registrados', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(14, 'stats', 'publications_title', 'Publicaciones Científicas', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(15, 'stats', 'publications_description', 'Investigaciones y estudios disponibles', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(16, 'stats', 'endangered_title', 'Especies en Peligro', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(17, 'stats', 'endangered_description', 'Requieren protección especial', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(18, 'stats', 'critical_title', 'En Peligro Crítico', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(19, 'stats', 'critical_description', 'Situación de conservación crítica', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(20, 'featured', 'title', 'Especies Destacadas', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(21, 'featured', 'view_all_text', 'Ver Todas las Especies', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(22, 'publications', 'title', 'Publicaciones Científicas Recientes', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(23, 'publications', 'view_all_text', 'Ver Todas las Publicaciones', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(24, 'cta', 'title', 'Contribuye a la Conservación', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(25, 'cta', 'description', 'La biodiversidad es un tesoro que debemos proteger. Únete a nuestros esfuerzos de conservación e investigación.', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(26, 'cta', 'button_primary_text', 'Colaborar', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(27, 'cta', 'button_primary_url', '#', 'url', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(28, 'cta', 'button_secondary_text', 'Descargar Datos', 'text', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(29, 'cta', 'button_secondary_url', '#', 'url', 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(30, 'hero', 'use_image_slider', 'true', 'text', 1, 1, '2025-07-31 21:39:51', '2025-08-05 02:46:23'),
	(31, 'hero', 'slider_autoplay', 'true', 'text', 1, 2, '2025-07-31 21:39:51', '2025-07-31 21:58:44'),
	(32, 'hero', 'slider_interval', '5000', 'text', 1, 3, '2025-07-31 21:39:51', '2025-07-31 21:58:44'),
	(33, 'hero', 'enable_icons', 'true', 'text', 1, 4, '2025-07-31 21:39:51', '2025-07-31 21:58:44');

-- Volcando estructura para tabla biodiversity_management.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.jobs: ~2 rows (aproximadamente)
INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
	(1, 'default', '{"uuid":"a4dffda7-6c5b-4c84-a60a-1558bc3ff93b","displayName":"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"failOnTimeout":false,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob","command":"O:58:\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\":6:{s:14:\\"\\u0000*\\u0000conversions\\";O:52:\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\":2:{s:8:\\"\\u0000*\\u0000items\\";a:1:{i:0;O:42:\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\":11:{s:12:\\"\\u0000*\\u0000fileNamer\\";O:54:\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\":0:{}s:28:\\"\\u0000*\\u0000extractVideoFrameAtSecond\\";d:0;s:16:\\"\\u0000*\\u0000manipulations\\";O:45:\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\":1:{s:16:\\"\\u0000*\\u0000manipulations\\";a:5:{s:8:\\"optimize\\";a:1:{i:0;O:36:\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\":3:{s:13:\\"\\u0000*\\u0000optimizers\\";a:7:{i:0;O:42:\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\":5:{s:7:\\"options\\";a:4:{i:0;s:4:\\"-m85\\";i:1;s:7:\\"--force\\";i:2;s:11:\\"--strip-all\\";i:3;s:17:\\"--all-progressive\\";}s:9:\\"imagePath\\";s:0:\\"\\";s:10:\\"binaryPath\\";s:0:\\"\\";s:7:\\"tmpPath\\";N;s:10:\\"binaryName\\";s:9:\\"jpegoptim\\";}i:1;O:41:\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\":5:{s:7:\\"options\\";a:1:{i:0;s:7:\\"--force\\";}s:9:\\"imagePath\\";s:0:\\"\\";s:10:\\"binaryPath\\";s:0:\\"\\";s:7:\\"tmpPath\\";N;s:10:\\"binaryName\\";s:8:\\"pngquant\\";}i:2;O:40:\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\":5:{s:7:\\"options\\";a:3:{i:0;s:3:\\"-i0\\";i:1;s:3:\\"-o2\\";i:2;s:6:\\"-quiet\\";}s:9:\\"imagePath\\";s:0:\\"\\";s:10:\\"binaryPath\\";s:0:\\"\\";s:7:\\"tmpPath\\";N;s:10:\\"binaryName\\";s:7:\\"optipng\\";}i:3;O:37:\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\":5:{s:7:\\"options\\";a:1:{i:0;s:20:\\"--disable=cleanupIDs\\";}s:9:\\"imagePath\\";s:0:\\"\\";s:10:\\"binaryPath\\";s:0:\\"\\";s:7:\\"tmpPath\\";N;s:10:\\"binaryName\\";s:4:\\"svgo\\";}i:4;O:41:\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\":5:{s:7:\\"options\\";a:2:{i:0;s:2:\\"-b\\";i:1;s:3:\\"-O3\\";}s:9:\\"imagePath\\";s:0:\\"\\";s:10:\\"binaryPath\\";s:0:\\"\\";s:7:\\"tmpPath\\";N;s:10:\\"binaryName\\";s:8:\\"gifsicle\\";}i:5;O:38:\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\":5:{s:7:\\"options\\";a:4:{i:0;s:4:\\"-m 6\\";i:1;s:8:\\"-pass 10\\";i:2;s:3:\\"-mt\\";i:3;s:5:\\"-q 90\\";}s:9:\\"imagePath\\";s:0:\\"\\";s:10:\\"binaryPath\\";s:0:\\"\\";s:7:\\"tmpPath\\";N;s:10:\\"binaryName\\";s:5:\\"cwebp\\";}i:6;O:40:\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\":6:{s:7:\\"options\\";a:8:{i:0;s:14:\\"-a cq-level=23\\";i:1;s:6:\\"-j all\\";i:2;s:7:\\"--min 0\\";i:3;s:8:\\"--max 63\\";i:4;s:12:\\"--minalpha 0\\";i:5;s:13:\\"--maxalpha 63\\";i:6;s:14:\\"-a end-usage=q\\";i:7;s:12:\\"-a tune=ssim\\";}s:9:\\"imagePath\\";s:0:\\"\\";s:10:\\"binaryPath\\";s:0:\\"\\";s:7:\\"tmpPath\\";N;s:10:\\"binaryName\\";s:7:\\"avifenc\\";s:16:\\"decodeBinaryName\\";s:7:\\"avifdec\\";}}s:9:\\"\\u0000*\\u0000logger\\";O:33:\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\":0:{}s:10:\\"\\u0000*\\u0000timeout\\";i:60;}}s:6:\\"format\\";a:1:{i:0;s:3:\\"jpg\\";}s:5:\\"width\\";a:1:{i:0;i:300;}s:6:\\"height\\";a:1:{i:0;i:200;}s:7:\\"sharpen\\";a:1:{i:0;i:10;}}}s:23:\\"\\u0000*\\u0000performOnCollections\\";a:0:{}s:17:\\"\\u0000*\\u0000performOnQueue\\";b:1;s:26:\\"\\u0000*\\u0000keepOriginalImageFormat\\";b:0;s:27:\\"\\u0000*\\u0000generateResponsiveImages\\";b:0;s:18:\\"\\u0000*\\u0000widthCalculator\\";N;s:24:\\"\\u0000*\\u0000loadingAttributeValue\\";N;s:16:\\"\\u0000*\\u0000pdfPageNumber\\";i:1;s:7:\\"\\u0000*\\u0000name\\";s:5:\\"thumb\\";}}s:28:\\"\\u0000*\\u0000escapeWhenCastingToString\\";b:0;}s:8:\\"\\u0000*\\u0000media\\";O:45:\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\":5:{s:5:\\"class\\";s:49:\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\";s:2:\\"id\\";i:19;s:9:\\"relations\\";a:0:{}s:10:\\"connection\\";s:5:\\"mysql\\";s:15:\\"collectionClass\\";N;}s:14:\\"\\u0000*\\u0000onlyMissing\\";b:0;s:10:\\"connection\\";s:8:\\"database\\";s:5:\\"queue\\";s:0:\\"\\";s:11:\\"afterCommit\\";b:1;}"},"createdAt":1753980794,"delay":null}', 0, NULL, 1753980794, 1753980794),
	(2, 'default', '{"uuid":"31f951fa-38d2-4453-b5e7-cc6fe2d0d7f4","displayName":"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"failOnTimeout":false,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob","command":"O:58:\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\":6:{s:14:\\"\\u0000*\\u0000conversions\\";O:52:\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\":2:{s:8:\\"\\u0000*\\u0000items\\";a:1:{i:0;O:42:\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\":11:{s:12:\\"\\u0000*\\u0000fileNamer\\";O:54:\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\":0:{}s:28:\\"\\u0000*\\u0000extractVideoFrameAtSecond\\";d:0;s:16:\\"\\u0000*\\u0000manipulations\\";O:45:\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\":1:{s:16:\\"\\u0000*\\u0000manipulations\\";a:5:{s:8:\\"optimize\\";a:1:{i:0;O:36:\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\":3:{s:13:\\"\\u0000*\\u0000optimizers\\";a:7:{i:0;O:42:\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\":5:{s:7:\\"options\\";a:4:{i:0;s:4:\\"-m85\\";i:1;s:7:\\"--force\\";i:2;s:11:\\"--strip-all\\";i:3;s:17:\\"--all-progressive\\";}s:9:\\"imagePath\\";s:0:\\"\\";s:10:\\"binaryPath\\";s:0:\\"\\";s:7:\\"tmpPath\\";N;s:10:\\"binaryName\\";s:9:\\"jpegoptim\\";}i:1;O:41:\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\":5:{s:7:\\"options\\";a:1:{i:0;s:7:\\"--force\\";}s:9:\\"imagePath\\";s:0:\\"\\";s:10:\\"binaryPath\\";s:0:\\"\\";s:7:\\"tmpPath\\";N;s:10:\\"binaryName\\";s:8:\\"pngquant\\";}i:2;O:40:\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\":5:{s:7:\\"options\\";a:3:{i:0;s:3:\\"-i0\\";i:1;s:3:\\"-o2\\";i:2;s:6:\\"-quiet\\";}s:9:\\"imagePath\\";s:0:\\"\\";s:10:\\"binaryPath\\";s:0:\\"\\";s:7:\\"tmpPath\\";N;s:10:\\"binaryName\\";s:7:\\"optipng\\";}i:3;O:37:\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\":5:{s:7:\\"options\\";a:1:{i:0;s:20:\\"--disable=cleanupIDs\\";}s:9:\\"imagePath\\";s:0:\\"\\";s:10:\\"binaryPath\\";s:0:\\"\\";s:7:\\"tmpPath\\";N;s:10:\\"binaryName\\";s:4:\\"svgo\\";}i:4;O:41:\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\":5:{s:7:\\"options\\";a:2:{i:0;s:2:\\"-b\\";i:1;s:3:\\"-O3\\";}s:9:\\"imagePath\\";s:0:\\"\\";s:10:\\"binaryPath\\";s:0:\\"\\";s:7:\\"tmpPath\\";N;s:10:\\"binaryName\\";s:8:\\"gifsicle\\";}i:5;O:38:\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\":5:{s:7:\\"options\\";a:4:{i:0;s:4:\\"-m 6\\";i:1;s:8:\\"-pass 10\\";i:2;s:3:\\"-mt\\";i:3;s:5:\\"-q 90\\";}s:9:\\"imagePath\\";s:0:\\"\\";s:10:\\"binaryPath\\";s:0:\\"\\";s:7:\\"tmpPath\\";N;s:10:\\"binaryName\\";s:5:\\"cwebp\\";}i:6;O:40:\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\":6:{s:7:\\"options\\";a:8:{i:0;s:14:\\"-a cq-level=23\\";i:1;s:6:\\"-j all\\";i:2;s:7:\\"--min 0\\";i:3;s:8:\\"--max 63\\";i:4;s:12:\\"--minalpha 0\\";i:5;s:13:\\"--maxalpha 63\\";i:6;s:14:\\"-a end-usage=q\\";i:7;s:12:\\"-a tune=ssim\\";}s:9:\\"imagePath\\";s:0:\\"\\";s:10:\\"binaryPath\\";s:0:\\"\\";s:7:\\"tmpPath\\";N;s:10:\\"binaryName\\";s:7:\\"avifenc\\";s:16:\\"decodeBinaryName\\";s:7:\\"avifdec\\";}}s:9:\\"\\u0000*\\u0000logger\\";O:33:\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\":0:{}s:10:\\"\\u0000*\\u0000timeout\\";i:60;}}s:6:\\"format\\";a:1:{i:0;s:3:\\"jpg\\";}s:5:\\"width\\";a:1:{i:0;i:300;}s:6:\\"height\\";a:1:{i:0;i:200;}s:7:\\"sharpen\\";a:1:{i:0;i:10;}}}s:23:\\"\\u0000*\\u0000performOnCollections\\";a:0:{}s:17:\\"\\u0000*\\u0000performOnQueue\\";b:1;s:26:\\"\\u0000*\\u0000keepOriginalImageFormat\\";b:0;s:27:\\"\\u0000*\\u0000generateResponsiveImages\\";b:0;s:18:\\"\\u0000*\\u0000widthCalculator\\";N;s:24:\\"\\u0000*\\u0000loadingAttributeValue\\";N;s:16:\\"\\u0000*\\u0000pdfPageNumber\\";i:1;s:7:\\"\\u0000*\\u0000name\\";s:5:\\"thumb\\";}}s:28:\\"\\u0000*\\u0000escapeWhenCastingToString\\";b:0;}s:8:\\"\\u0000*\\u0000media\\";O:45:\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\":5:{s:5:\\"class\\";s:49:\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\";s:2:\\"id\\";i:20;s:9:\\"relations\\";a:0:{}s:10:\\"connection\\";s:5:\\"mysql\\";s:15:\\"collectionClass\\";N;}s:14:\\"\\u0000*\\u0000onlyMissing\\";b:0;s:10:\\"connection\\";s:8:\\"database\\";s:5:\\"queue\\";s:0:\\"\\";s:11:\\"afterCommit\\";b:1;}"},"createdAt":1753981330,"delay":null}', 0, NULL, 1753981330, 1753981330);

-- Volcando estructura para tabla biodiversity_management.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.job_batches: ~0 rows (aproximadamente)

-- Volcando estructura para tabla biodiversity_management.media
CREATE TABLE IF NOT EXISTS `media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversions_disk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint unsigned NOT NULL,
  `manipulations` json NOT NULL,
  `custom_properties` json NOT NULL,
  `generated_conversions` json NOT NULL,
  `responsive_images` json NOT NULL,
  `order_column` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `media_order_column_index` (`order_column`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.media: ~21 rows (aproximadamente)
INSERT INTO `media` (`id`, `model_type`, `model_id`, `uuid`, `collection_name`, `name`, `file_name`, `mime_type`, `disk`, `conversions_disk`, `size`, `manipulations`, `custom_properties`, `generated_conversions`, `responsive_images`, `order_column`, `created_at`, `updated_at`) VALUES
	(1, 'App\\Models\\BiodiversityCategory', 53, 'fde16ffd-709c-4144-9514-d133385eb859', 'images', 'Quetzal Resplandeciente', 'category_53.svg', 'image/svg+xml', 'public', 'public', 441, '[]', '[]', '[]', '[]', 1, '2025-07-31 01:26:12', '2025-07-31 01:26:12'),
	(2, 'App\\Models\\BiodiversityCategory', 54, '08cfafd8-f734-4c86-b274-711fd9120bca', 'images', 'Quetzal Resplandeciente', 'category_54.svg', 'image/svg+xml', 'public', 'public', 441, '[]', '[]', '[]', '[]', 1, '2025-07-31 01:26:12', '2025-07-31 01:26:12'),
	(3, 'App\\Models\\BiodiversityCategory', 55, '4f3c6f5a-b57c-4c2c-b72d-16a3e7d65cce', 'images', 'Jaguar', 'category_55.svg', 'image/svg+xml', 'public', 'public', 420, '[]', '[]', '[]', '[]', 1, '2025-07-31 01:26:12', '2025-07-31 01:26:12'),
	(4, 'App\\Models\\BiodiversityCategory', 56, '6a938e5a-b7d3-44ba-a5fc-a51752dc6f64', 'images', 'Vaquita Marina', 'category_56.svg', 'image/svg+xml', 'public', 'public', 429, '[]', '[]', '[]', '[]', 1, '2025-07-31 01:26:12', '2025-07-31 01:26:12'),
	(5, 'App\\Models\\BiodiversityCategory', 57, '21039b15-d7cf-4cc3-8a89-be0df72738d5', 'images', 'Ajolote Mexicano', 'category_57.svg', 'image/svg+xml', 'public', 'public', 436, '[]', '[]', '[]', '[]', 1, '2025-07-31 01:26:12', '2025-07-31 01:26:12'),
	(6, 'App\\Models\\BiodiversityCategory', 58, '04bf186b-3965-4e60-80f5-592159bd7fd6', 'images', 'Guacamaya Roja', 'category_58.svg', 'image/svg+xml', 'public', 'public', 424, '[]', '[]', '[]', '[]', 1, '2025-07-31 01:26:12', '2025-07-31 01:26:12'),
	(7, 'App\\Models\\BiodiversityCategory', 59, '4a3238c4-b31c-40bb-84d1-9b7e35157860', 'images', 'Tortuga Lora', 'category_59.svg', 'image/svg+xml', 'public', 'public', 432, '[]', '[]', '[]', '[]', 1, '2025-07-31 01:26:13', '2025-07-31 01:26:13'),
	(8, 'App\\Models\\BiodiversityCategory', 60, '0e03cd2e-5209-46ba-b94b-08fe67a78849', 'images', 'Lobo Mexicano', 'category_60.svg', 'image/svg+xml', 'public', 'public', 433, '[]', '[]', '[]', '[]', 1, '2025-07-31 01:26:13', '2025-07-31 01:26:13'),
	(9, 'App\\Models\\BiodiversityCategory', 61, 'dab18826-d368-4e66-9e7e-75c701b13ae5', 'images', 'Águila Real', 'category_61.svg', 'image/svg+xml', 'public', 'public', 430, '[]', '[]', '[]', '[]', 1, '2025-07-31 01:26:13', '2025-07-31 01:26:13'),
	(10, 'App\\Models\\BiodiversityCategory', 62, '836a704a-433d-4aae-8511-8a77ec30f1ed', 'images', 'Tapir Centroamericano', 'category_62.svg', 'image/svg+xml', 'public', 'public', 436, '[]', '[]', '[]', '[]', 1, '2025-07-31 01:26:13', '2025-07-31 01:26:13'),
	(11, 'App\\Models\\BiodiversityCategory', 63, '307f96a7-3798-4008-9d38-483aaede3e2d', 'images', 'Manatí del Caribe', 'category_63.svg', 'image/svg+xml', 'public', 'public', 437, '[]', '[]', '[]', '[]', 1, '2025-07-31 01:26:13', '2025-07-31 01:26:13'),
	(12, 'App\\Models\\BiodiversityCategory', 64, 'a6f6ad9c-a509-46a5-8f39-dda521872525', 'images', 'Mono Araña', 'category_64.svg', 'image/svg+xml', 'public', 'public', 428, '[]', '[]', '[]', '[]', 1, '2025-07-31 01:26:13', '2025-07-31 01:26:13'),
	(13, 'App\\Models\\BiodiversityCategory', 65, '7f5668fa-e356-4b23-9820-ddb52ec04e4e', 'images', 'Mariposa Monarca', 'category_65.svg', 'image/svg+xml', 'public', 'public', 433, '[]', '[]', '[]', '[]', 1, '2025-07-31 01:26:13', '2025-07-31 01:26:13'),
	(14, 'App\\Models\\BiodiversityCategory', 66, '19778496-77c9-4d75-980a-11ac97101fb9', 'images', 'Ahuehuete', 'category_66.svg', 'image/svg+xml', 'public', 'public', 486, '[]', '[]', '[]', '[]', 1, '2025-07-31 01:26:13', '2025-07-31 01:26:13'),
	(15, 'App\\Models\\BiodiversityCategory', 67, 'ac1a7e0e-01c1-4482-9df4-7f42506b298f', 'images', 'Pino Azul', 'category_67.svg', 'image/svg+xml', 'public', 'public', 487, '[]', '[]', '[]', '[]', 1, '2025-07-31 01:26:13', '2025-07-31 01:26:13'),
	(16, 'App\\Models\\BiodiversityCategory', 68, 'b00d9eb8-a033-476b-9071-4959a2db3466', 'images', 'Orquídea Tigre', 'category_68.svg', 'image/svg+xml', 'public', 'public', 489, '[]', '[]', '[]', '[]', 1, '2025-07-31 01:26:13', '2025-07-31 01:26:13'),
	(17, 'App\\Models\\BiodiversityCategory', 69, '7c512998-d8e5-43b0-84c9-a2217e1507c9', 'images', 'Cacao', 'category_69.svg', 'image/svg+xml', 'public', 'public', 478, '[]', '[]', '[]', '[]', 1, '2025-07-31 01:26:13', '2025-07-31 01:26:13'),
	(18, 'App\\Models\\BiodiversityCategory', 70, 'dbc18167-8b22-413a-a835-d2d253f91576', 'images', 'Vainilla', 'category_70.svg', 'image/svg+xml', 'public', 'public', 484, '[]', '[]', '[]', '[]', 1, '2025-07-31 01:26:13', '2025-07-31 01:26:13'),
	(19, 'App\\Models\\HeroSliderImage', 1, 'b8f6884b-42f1-4cff-8489-e1fc36162f3f', 'hero_images', 'peru_1f101c7f_1254x836', 'peru_1f101c7f_1254x836.jpg', 'image/jpeg', 'public', 'public', 195323, '[]', '[]', '{"hero": true}', '[]', 1, '2025-07-31 21:53:13', '2025-07-31 21:53:14'),
	(20, 'App\\Models\\HeroSliderImage', 2, 'cd5fd6fe-29e4-4c01-bcea-afb8cdab2ec1', 'hero_images', 'Tipos-de-Biodiversidad', 'Tipos-de-Biodiversidad.jpg', 'image/jpeg', 'public', 'public', 191070, '[]', '[]', '{"hero": true}', '[]', 1, '2025-07-31 22:02:10', '2025-07-31 22:02:10'),
	(23, 'App\\Models\\HeroSliderImage', 1, '1da7a374-9c94-47ab-8229-83f06ae2935e', 'overlay_images', 'logo_mesomi_bn', 'logo_mesomi_bn.png', 'image/png', 'public', 'public', 100173, '[]', '[]', '[]', '[]', 2, '2025-08-01 21:58:05', '2025-08-01 21:58:05');

-- Volcando estructura para tabla biodiversity_management.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.migrations: ~15 rows (aproximadamente)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2024_07_01_000001_create_biodiversity_categories_table', 1),
	(5, '2024_07_01_000002_create_publications_table', 1),
	(6, '2024_07_01_000003_create_biodiversity_category_publication_table', 1),
	(7, '2024_07_01_000004_create_biodiversity_publication_table', 1),
	(8, '2024_07_01_000004_create_email_verification_tokens_table', 1),
	(9, '2024_07_02_000001_add_common_name_and_family_to_biodiversity_categories', 1),
	(10, '2025_07_25_153517_create_media_table', 1),
	(11, '2025_07_30_193629_create_permission_tables', 2),
	(12, '2025_01_15_000001_create_conservation_statuses_table', 3),
	(14, '2025_01_15_000001_update_conservation_status_data', 4),
	(15, '2025_01_15_000002_create_conservation_statuses_table', 5),
	(16, '2025_01_15_000003_add_conservation_status_foreign_key', 6),
	(17, '2025_07_31_162230_create_home_content_table', 7),
	(18, '2025_07_31_164108_create_hero_slider_images_table', 8),
	(19, '2025_08_01_162828_create_settings_table', 9),
	(20, '2025_08_01_164838_add_overlay_image_to_hero_slider_images_table', 10),
	(21, '2025_08_14_140000_add_familia_relationship_to_biodiversity_categories', 11),
	(22, '2025_08_18_151736_create_reinos_table', 12),
	(23, '2025_08_18_152140_add_idreino_to_biodiversity_categories_table', 13),
	(24, '2025_08_18_153443_add_conservation_status_id_to_biodiversity_categories_table', 14),
	(25, '2025_08_18_152003_add_idreino_to_clases_table', 15);

-- Volcando estructura para tabla biodiversity_management.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.model_has_permissions: ~0 rows (aproximadamente)

-- Volcando estructura para tabla biodiversity_management.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.model_has_roles: ~2 rows (aproximadamente)
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1),
	(4, 'App\\Models\\User', 1);

-- Volcando estructura para tabla biodiversity_management.ordens
CREATE TABLE IF NOT EXISTS `ordens` (
  `idorden` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `definicion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `idclase` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idorden`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.ordens: ~19 rows (aproximadamente)
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

-- Volcando estructura para tabla biodiversity_management.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.password_reset_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla biodiversity_management.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.permissions: ~23 rows (aproximadamente)
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'view_dashboard', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(2, 'manage_users', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(3, 'manage_roles', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(4, 'manage_species', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(5, 'view_species', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(6, 'create_species', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(7, 'edit_species', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(8, 'delete_species', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(9, 'manage_locations', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(10, 'view_locations', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(11, 'create_locations', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(12, 'edit_locations', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(13, 'delete_locations', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(14, 'manage_observations', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(15, 'view_observations', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(16, 'create_observations', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(17, 'edit_observations', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(18, 'delete_observations', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(19, 'generate_reports', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(20, 'view_reports', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(21, 'manage_database', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(22, 'backup_database', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(23, 'restore_database', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01');

-- Volcando estructura para tabla biodiversity_management.publications
CREATE TABLE IF NOT EXISTS `publications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abstract` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `publication_year` year NOT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `journal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pdf_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.publications: ~17 rows (aproximadamente)
INSERT INTO `publications` (`id`, `title`, `abstract`, `publication_year`, `author`, `journal`, `doi`, `pdf_path`, `created_at`, `updated_at`) VALUES
	(20, 'Distribución y estado de conservación del Quetzal', 'Estudio sobre la distribución actual del Pharomachrus mocinno y los desafíos para su conservación.', '2023', 'García-Rodríguez, A.', 'Mesoamerican Biology', '10.1016/j.mesobio.2023.001', NULL, '2025-07-30 21:53:10', '2025-08-18 19:50:53'),
	(21, 'Distribución actual y estado de conservación del jaguar en México', 'Análisis comprehensivo de las poblaciones de Panthera onca en México, incluyendo amenazas actuales y estrategias de conservación.', '2023', 'Martínez-López, R.', 'Revista Mexicana de Biodiversidad', '10.1016/j.rmb.2023.002', NULL, '2025-07-30 21:54:07', '2025-07-30 21:54:07'),
	(22, 'Estado crítico de la población de vaquita marina', 'Evaluación actual de la población de Phocoena sinus y los esfuerzos de conservación en el Golfo de California.', '2023', 'Rojas-Bracho, L.', 'Marine Mammal Science', '10.1111/mms.2023.003', NULL, '2025-07-30 21:54:24', '2025-07-30 21:54:24'),
	(23, 'Capacidades regenerativas del Ambystoma mexicanum', 'Investigación sobre los mecanismos moleculares detrás de la regeneración tisular en el ajolote mexicano y sus aplicaciones potenciales en medicina regenerativa.', '2023', 'Sánchez-García, M.', 'Developmental Biology', '10.1016/j.devbio.2023.004', NULL, '2025-07-30 21:54:42', '2025-07-30 21:54:42'),
	(24, 'Patrones de dispersión de semillas por Ara macao', 'Estudio sobre el rol ecológico de la guacamaya roja en la dispersión de semillas y la regeneración forestal en la selva tropical mesoamericana.', '2023', 'González-Torres, F.', 'Tropical Ecology', '10.1007/s42965-023-005', NULL, '2025-07-30 21:54:57', '2025-07-30 21:54:57'),
	(25, 'Patrones de anidación de Lepidochelys kempii', 'Análisis de los patrones de anidación y éxito reproductivo de la tortuga lora en las costas de Tamaulipas, México.', '2023', 'Pérez-Castañeda, R.', 'Chelonian Conservation and Biology', '10.2744/CCB-2023.006', NULL, '2025-07-30 21:55:11', '2025-07-30 21:55:11'),
	(26, 'Recuperación poblacional del lobo mexicano', 'Evaluación de los programas de reintroducción y recuperación del Canis lupus baileyi en su rango histórico de distribución.', '2023', 'Ramos-Rendón, J.', 'Conservation Biology', '10.1111/cobi.2023.007', NULL, '2025-07-30 21:55:26', '2025-07-30 21:55:26'),
	(27, 'Estado poblacional del Águila Real en México', 'Análisis de la distribución actual y tendencias poblacionales del Águila Real en el territorio mexicano, con énfasis en las amenazas y estrategias de conservación.', '2023', 'González-Rojas, M.', 'Journal of Raptor Research', '10.3356/JRR-2023.008', NULL, '2025-07-30 21:55:51', '2025-07-30 21:55:51'),
	(28, 'Ecología del Tapir Centroamericano', 'Estudio sobre los patrones de movimiento y uso de hábitat del Tapirus bairdii en la Selva Maya, con implicaciones para su conservación.', '2023', 'Naranjo, E.J.', 'Biotropica', '10.1111/btp.2023.009', NULL, '2025-07-30 21:56:41', '2025-07-30 21:56:41'),
	(29, 'Conservación del Manatí en el Caribe Mexicano', 'Evaluación del estado de conservación y amenazas actuales para las poblaciones de Trichechus manatus en el Caribe mexicano, incluyendo estrategias de protección.', '2023', 'Morales-Vela, B.', 'Aquatic Mammals', '10.1578/AM.2023.010', NULL, '2025-07-30 21:56:55', '2025-07-30 21:56:55'),
	(30, 'Ecología del Mono Araña en la Selva Maya', 'Investigación sobre el comportamiento social y patrones de alimentación de Ateles geoffroyi en la Selva Maya, con énfasis en su rol como dispersor de semillas.', '2023', 'Ramos-Fernández, G.', 'American Journal of Primatology', '10.1002/ajp.2023.011', NULL, '2025-07-30 21:57:16', '2025-07-30 21:57:16'),
	(31, 'Tendencias poblacionales de la Mariposa Monarca', 'Análisis de los cambios en la población de Danaus plexippus durante su migración anual entre Canadá y México, con énfasis en los impactos del cambio climático y la pérdida de hábitat.', '2023', 'Vidal, O.', 'Conservation Biology', '10.1111/cobi.2023.012', NULL, '2025-07-30 21:58:24', '2025-07-30 21:58:24'),
	(32, 'El Ahuehuete: Árbol Nacional de México', 'Estudio comprehensivo sobre el Ahuehuete (Taxodium mucronatum) y su importancia cultural y ecológica en México. Se analiza su distribución, características botánicas y su papel en la historia mexicana.', '2022', 'García Martínez, R.; López Hernández, M.', 'Revista Mexicana de Biodiversidad', '10.1234/ahuehuete.2022.01', NULL, '2025-07-30 22:10:30', '2025-07-30 22:10:30'),
	(33, 'Conservación y Ecología del Pinus maximartinezii', 'Investigación detallada sobre la distribución, estado de conservación y características ecológicas del Pinus maximartinezii, una especie endémica de México. Se analizan las amenazas actuales y se proponen estrategias de conservación.', '2023', 'Sánchez-González, A.; Pérez-Ramos, B.', 'Botanical Sciences', '10.1234/pinus.2023.02', NULL, '2025-07-30 22:10:48', '2025-07-30 22:10:48'),
	(34, 'Diversidad y Conservación de Tigridia pavonia en México', 'Estudio sobre la diversidad genética, distribución y estado de conservación de Tigridia pavonia en México. Se documenta su importancia cultural, usos tradicionales y potencial ornamental, así como estrategias para su conservación y cultivo sustentable.', '2023', 'Rodríguez-Jiménez, L.; Martínez-Flores, E.', 'Mexican Journal of Botany', '10.1234/tigridia.2023.03', NULL, '2025-07-30 22:11:05', '2025-07-30 22:11:05'),
	(35, 'El Cacao en México: Biodiversidad y Conservación de Variedades Nativas', 'Análisis comprehensivo de la diversidad genética del cacao en México, incluyendo la caracterización de variedades nativas y su importancia en la conservación de recursos fitogenéticos. Se estudian las prácticas tradicionales de cultivo y su rol en la preservación de la biodiversidad.', '2023', 'López-García, F.; Torres-Morales, M.', 'Agrobiodiversity Journal', '10.1234/cacao.2023.04', NULL, '2025-07-30 22:11:25', '2025-07-30 22:11:25'),
	(36, 'Vanilla planifolia: Estado de Conservación y Diversidad Genética en México', 'Investigación sobre el estado actual de las poblaciones silvestres de Vanilla planifolia en México, su diversidad genética y las amenazas a su conservación. Se analiza la importancia de las prácticas tradicionales de cultivo y su papel en la preservación de esta especie emblemática.', '2023', 'Hernández-Apolinar, M.; González-Astorga, J.', 'Economic Botany', '10.1234/vanilla.2023.05', NULL, '2025-07-30 22:11:40', '2025-07-30 22:11:40');

-- Volcando estructura para tabla biodiversity_management.reinos
CREATE TABLE IF NOT EXISTS `reinos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reinos_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.reinos: ~5 rows (aproximadamente)
INSERT INTO `reinos` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
	(1, 'Animalia', 'Reino que incluye todos los organismos multicelulares eucariotas que se alimentan por ingestión.', '2025-08-18 20:23:19', '2025-08-18 20:23:19'),
	(2, 'Plantae', 'Reino que incluye organismos eucariotas multicelulares que realizan fotosíntesis.', '2025-08-18 20:23:19', '2025-08-18 20:23:19'),
	(3, 'Fungi', 'Reino que incluye organismos eucariotas que se alimentan por absorción, como hongos y levaduras.', '2025-08-18 20:23:19', '2025-08-18 20:23:19'),
	(4, 'Protista', 'Reino que incluye organismos eucariotas unicelulares y algunos multicelulares simples.', '2025-08-18 20:23:19', '2025-08-18 20:23:19'),
	(5, 'Monera', 'Reino que incluye organismos procariotas como bacterias y cianobacterias.', '2025-08-18 20:23:19', '2025-08-18 20:23:19');

-- Volcando estructura para tabla biodiversity_management.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.roles: ~6 rows (aproximadamente)
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'Administrador', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(2, 'Investigador Senior', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(3, 'Investigador Junior', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(4, 'Técnico de Campo', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(5, 'Consultor', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01'),
	(6, 'Estudiante', 'web', '2025-07-31 01:12:01', '2025-07-31 01:12:01');

-- Volcando estructura para tabla biodiversity_management.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.role_has_permissions: ~65 rows (aproximadamente)
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 1),
	(2, 1),
	(3, 1),
	(4, 1),
	(5, 1),
	(6, 1),
	(7, 1),
	(8, 1),
	(9, 1),
	(10, 1),
	(11, 1),
	(12, 1),
	(13, 1),
	(14, 1),
	(15, 1),
	(16, 1),
	(17, 1),
	(18, 1),
	(19, 1),
	(20, 1),
	(21, 1),
	(22, 1),
	(23, 1),
	(1, 2),
	(4, 2),
	(5, 2),
	(6, 2),
	(7, 2),
	(8, 2),
	(9, 2),
	(10, 2),
	(11, 2),
	(12, 2),
	(14, 2),
	(15, 2),
	(16, 2),
	(17, 2),
	(18, 2),
	(19, 2),
	(20, 2),
	(1, 3),
	(5, 3),
	(6, 3),
	(7, 3),
	(10, 3),
	(11, 3),
	(15, 3),
	(16, 3),
	(17, 3),
	(20, 3),
	(1, 4),
	(5, 4),
	(10, 4),
	(11, 4),
	(15, 4),
	(16, 4),
	(17, 4),
	(1, 5),
	(5, 5),
	(10, 5),
	(15, 5),
	(20, 5),
	(14, 6),
	(15, 6),
	(23, 6);

-- Volcando estructura para tabla biodiversity_management.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.sessions: ~1 rows (aproximadamente)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('DvUrcrGgGpNvexZWAAXU1Ez0gxqVakHoKuvA9Zue', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiWHQ2eWJJZFZZUnNpNDV2cHk5RG5FUlM5T2ttc0xxeWFRVkZHV1Q2TiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iaW9kaXZlcnNpdHkiO31zOjM6InVybCI7YTowOnt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NTU1NDUyMDM7fX0=', 1755546384),
	('imXqv2BIdw6ZTncuUkP19jqjqPdm56HaAqqBSvbp', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Trae/1.100.3 Chrome/132.0.6834.210 Electron/34.5.1 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaFA0QnRBUEtwTHNLWTJ6UVhnOGZaanhSSlRUaWFSOHcxeFhwVG5XcyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9iaW9kaXZlcnNpdHk/aWRlX3dlYnZpZXdfcmVxdWVzdF90aW1lPTE3NTU1NDYxMzkzNzAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1755546140),
	('kAjjMWoDF8cWMDS5tGYhmji5XZIilNSQSsL1z1VC', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiTERSY2RrYVhVSzBHVG4xeEFsRjNVM05lanJ1aTFwWnI0dFJpSnFtaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9iaW9kaXZlcnNpdHkiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjM6InVybCI7YTowOnt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NTU1NTQ1NDc7fX0=', 1755555088);

-- Volcando estructura para tabla biodiversity_management.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `options` text COLLATE utf8mb4_unicode_ci,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.settings: ~2 rows (aproximadamente)
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `options`, `label`, `description`, `order`, `created_at`, `updated_at`) VALUES
	(1, 'site_logo', 'logos/default-logo.svg', 'general', 'text', NULL, NULL, NULL, 0, '2025-08-01 21:38:42', '2025-08-01 21:40:02'),
	(2, 'site_logo_alt', 'Biodiversidad Gobierno Regional', 'general', 'text', NULL, NULL, NULL, 0, '2025-08-01 21:38:42', '2025-08-05 02:48:46'),
	(3, 'main_menu', '[{"text":"pri","url":"\\/","order":"1","parent_id":null,"is_active":"1"},{"text":"Biodiversidad","url":"\\/biodiversity","order":"2","parent_id":null,"is_active":"1"},{"text":"Publicaciones","url":"\\/publications","order":"3","parent_id":null,"is_active":"1"},{"text":"Panel Admin","url":"\\/admin","order":"4","parent_id":null,"is_active":"1"}]', 'general', 'text', NULL, NULL, NULL, 0, '2025-08-01 21:40:02', '2025-08-05 02:48:56');

-- Volcando estructura para tabla biodiversity_management.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.users: ~0 rows (aproximadamente)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Admin', 'admin@example.com', NULL, '$2y$12$cBxeeGgTIK7X1.aws1g7Nu/1HSAbTxUY2osSkj.V/FicGEOkKHsoa', NULL, '2025-07-30 21:01:34', '2025-07-30 21:01:34'),
	(3, 'Admin Test', 'admin@test.com', NULL, '$2y$12$2e.MHLMX/.KMjUh6YEroZuYxcnr8zIyNP1G2qP.lBpKJ0uANG.a76', NULL, '2025-08-18 18:16:48', '2025-08-18 18:16:48');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
