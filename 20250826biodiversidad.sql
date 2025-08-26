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
  `image_path_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path_3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path_4` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=1829 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.biodiversity_categories: ~42 rows (aproximadamente)
INSERT INTO `biodiversity_categories` (`id`, `name`, `scientific_name`, `common_name`, `description`, `conservation_status`, `kingdom`, `idreino`, `conservation_status_id`, `idfamilia`, `habitat`, `image_path`, `image_path_2`, `image_path_3`, `image_path_4`, `deleted_at`, `created_at`, `updated_at`) VALUES
	(1, 'Oso de Anteojos', 'Tremarctos ornatus', NULL, 'Único oso nativo de Sudamérica, caracterizado por las manchas claras alrededor de los ojos que le dan su nombre. Es una especie emblemática del Perú y símbolo de conservación.', 'VU', 'Animalia', 1, 5, 1, 'Bosques nublados y páramos andinos entre 1,000 y 4,200 msnm', 'images/peru/real/oso_de_anteojos_1.jpg', 'images/peru/real/oso_de_anteojos_2.jpg', 'images/peru/real/oso_de_anteojos_3.jpg', 'images/peru/real/oso_de_anteojos_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:33:27', '2025-08-26 19:24:09'),
	(2, 'Vicuña', 'Vicugna vicugna', NULL, 'Camélido sudamericano silvestre, ancestro de la alpaca. Produce la fibra más fina del mundo y es símbolo nacional del Perú.', 'LC', 'Animalia', 1, 7, 2, 'Puna y altiplano andino entre 3,200 y 4,800 msnm', 'images/peru/real/vicu__a_1.jpg', 'images/peru/real/vicu__a_2.jpg', 'images/peru/real/vicu__a_3.jpg', 'images/peru/real/vicu__a_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:33:27', '2025-08-26 19:24:09'),
	(3, 'Jaguar', 'Panthera onca', NULL, 'El felino más grande de América, depredador tope de la Amazonía peruana. Excelente nadador y cazador nocturno.', 'NT', 'Animalia', 1, 6, 3, 'Selva amazónica, bosques tropicales húmedos', 'images/peru/real/jaguar_1.jpg', 'images/peru/real/jaguar_2.jpg', 'images/peru/real/jaguar_3.jpg', 'images/peru/real/jaguar_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:33:27', '2025-08-26 19:24:09'),
	(4, 'Mono Choro de Cola Amarilla', 'Oreonax flavicauda', NULL, 'Primate endémico del Perú, redescubierto en 1974. Vive en los bosques nublados de Amazonas y San Martín.', 'CR', 'Animalia', 1, 3, NULL, 'Bosques nublados de los Andes del norte del Perú, entre 1,500 y 2,700 msnm', 'images/peru/real/mono_choro_de_cola_amarilla_1.jpg', 'images/peru/real/mono_choro_de_cola_amarilla_2.jpg', 'images/peru/real/mono_choro_de_cola_amarilla_3.jpg', 'images/peru/real/mono_choro_de_cola_amarilla_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:33:27', '2025-08-26 19:24:09'),
	(5, 'Nutria Gigante', 'Pteronura brasiliensis', NULL, 'La nutria más grande del mundo, puede medir hasta 2 metros. Vive en grupos familiares y es excelente pescadora.', 'EN', 'Animalia', 1, 4, 5, 'Ríos y lagos de la Amazonía peruana', 'images/peru/real/nutria_gigante_1.jpg', 'images/peru/real/nutria_gigante_2.jpg', 'images/peru/real/nutria_gigante_3.jpg', 'images/peru/real/nutria_gigante_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:33:27', '2025-08-26 19:24:09'),
	(6, 'Oso de Anteojos', 'Tremarctos ornatus', NULL, 'Único oso nativo de Sudamérica, caracterizado por las manchas claras alrededor de los ojos que le dan su nombre. Es una especie emblemática del Perú y símbolo de conservación.', 'VU', 'Animalia', 1, 5, 1, 'Bosques nublados y páramos andinos entre 1,000 y 4,200 msnm', 'images/peru/real/oso_de_anteojos_1.jpg', 'images/peru/real/oso_de_anteojos_2.jpg', 'images/peru/real/oso_de_anteojos_3.jpg', 'images/peru/real/oso_de_anteojos_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:33:46', '2025-08-26 19:24:09'),
	(7, 'Vicuña', 'Vicugna vicugna', NULL, 'Camélido sudamericano silvestre, ancestro de la alpaca. Produce la fibra más fina del mundo y es símbolo nacional del Perú.', 'LC', 'Animalia', 1, 7, 2, 'Puna y altiplano andino entre 3,200 y 4,800 msnm', 'images/peru/real/vicu__a_1.jpg', 'images/peru/real/vicu__a_2.jpg', 'images/peru/real/vicu__a_3.jpg', 'images/peru/real/vicu__a_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:33:46', '2025-08-26 19:24:09'),
	(8, 'Jaguar', 'Panthera onca', NULL, 'El felino más grande de América, depredador tope de la Amazonía peruana. Excelente nadador y cazador nocturno.', 'NT', 'Animalia', 1, 6, 3, 'Selva amazónica, bosques tropicales húmedos', 'images/peru/real/jaguar_1.jpg', 'images/peru/real/jaguar_2.jpg', 'images/peru/real/jaguar_3.jpg', 'images/peru/real/jaguar_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:33:46', '2025-08-26 19:24:09'),
	(9, 'Mono Choro de Cola Amarilla', 'Oreonax flavicauda', NULL, 'Primate endémico del Perú, redescubierto en 1974. Vive en los bosques nublados de Amazonas y San Martín.', 'CR', 'Animalia', 1, 3, NULL, 'Bosques nublados de los Andes del norte del Perú, entre 1,500 y 2,700 msnm', 'images/peru/real/mono_choro_de_cola_amarilla_1.jpg', 'images/peru/real/mono_choro_de_cola_amarilla_2.jpg', 'images/peru/real/mono_choro_de_cola_amarilla_3.jpg', 'images/peru/real/mono_choro_de_cola_amarilla_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:33:46', '2025-08-26 19:24:09'),
	(10, 'Nutria Gigante', 'Pteronura brasiliensis', NULL, 'La nutria más grande del mundo, puede medir hasta 2 metros. Vive en grupos familiares y es excelente pescadora.', 'EN', 'Animalia', 1, 4, 5, 'Ríos y lagos de la Amazonía peruana', 'images/peru/real/nutria_gigante_1.jpg', 'images/peru/real/nutria_gigante_2.jpg', 'images/peru/real/nutria_gigante_3.jpg', 'images/peru/real/nutria_gigante_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:33:46', '2025-08-26 19:24:09'),
	(11, 'Gallito de las Rocas', 'Rupicola peruvianus', NULL, 'Ave nacional del Perú, famosa por su plumaje anaranjado brillante y su elaborado ritual de cortejo.', 'LC', 'Animalia', 1, 7, 6, 'Bosques nublados de la vertiente oriental de los Andes, entre 500 y 2,400 msnm', 'images/peru/real/gallito_de_las_rocas_1.jpg', 'images/peru/real/gallito_de_las_rocas_2.jpg', 'images/peru/real/gallito_de_las_rocas_3.jpg', 'images/peru/real/gallito_de_las_rocas_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:33:46', '2025-08-26 19:24:09'),
	(12, 'Cóndor Andino', 'Vultur gryphus', NULL, 'Ave voladora más grande del mundo, símbolo de los Andes. Puede volar hasta 300 km sin batir las alas.', 'NT', 'Animalia', 1, 6, 7, 'Cordillera de los Andes, desde el nivel del mar hasta 5,000 msnm', 'images/peru/real/c__ndor_andino_1.jpg', 'images/peru/real/c__ndor_andino_2.jpg', 'images/peru/real/c__ndor_andino_3.jpg', 'images/peru/real/c__ndor_andino_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:33:46', '2025-08-26 19:24:09'),
	(13, 'Colibrí Cola de Espátula', 'Loddigesia mirabilis', NULL, 'Colibrí endémico del Perú con una cola única en forma de espátula. Una de las aves más raras del mundo.', 'EN', 'Animalia', 1, 4, NULL, 'Bosques montanos del norte del Perú, entre 2,100 y 2,900 msnm', 'images/peru/real/colibr___cola_de_esp__tula_1.jpg', 'images/peru/real/colibr___cola_de_esp__tula_2.jpg', 'images/peru/real/colibr___cola_de_esp__tula_3.jpg', 'images/peru/real/colibr___cola_de_esp__tula_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:33:46', '2025-08-26 19:24:09'),
	(14, 'Oso de Anteojos', 'Tremarctos ornatus', NULL, 'Único oso nativo de Sudamérica, caracterizado por las manchas claras alrededor de los ojos que le dan su nombre. Es una especie emblemática del Perú y símbolo de conservación.', 'VU', 'Animalia', 1, 5, 1, 'Bosques nublados y páramos andinos entre 1,000 y 4,200 msnm', 'images/peru/real/oso_de_anteojos_1.jpg', 'images/peru/real/oso_de_anteojos_2.jpg', 'images/peru/real/oso_de_anteojos_3.jpg', 'images/peru/real/oso_de_anteojos_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(15, 'Vicuña', 'Vicugna vicugna', NULL, 'Camélido sudamericano silvestre, ancestro de la alpaca. Produce la fibra más fina del mundo y es símbolo nacional del Perú.', 'LC', 'Animalia', 1, 7, 2, 'Puna y altiplano andino entre 3,200 y 4,800 msnm', 'images/peru/real/vicu__a_1.jpg', 'images/peru/real/vicu__a_2.jpg', 'images/peru/real/vicu__a_3.jpg', 'images/peru/real/vicu__a_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(16, 'Jaguar', 'Panthera onca', NULL, 'El felino más grande de América, depredador tope de la Amazonía peruana. Excelente nadador y cazador nocturno.', 'NT', 'Animalia', 1, 6, 3, 'Selva amazónica, bosques tropicales húmedos', 'images/peru/real/jaguar_1.jpg', 'images/peru/real/jaguar_2.jpg', 'images/peru/real/jaguar_3.jpg', 'images/peru/real/jaguar_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(17, 'Mono Choro de Cola Amarilla', 'Oreonax flavicauda', NULL, 'Primate endémico del Perú, redescubierto en 1974. Vive en los bosques nublados de Amazonas y San Martín.', 'CR', 'Animalia', 1, 3, NULL, 'Bosques nublados de los Andes del norte del Perú, entre 1,500 y 2,700 msnm', 'images/peru/real/mono_choro_de_cola_amarilla_1.jpg', 'images/peru/real/mono_choro_de_cola_amarilla_2.jpg', 'images/peru/real/mono_choro_de_cola_amarilla_3.jpg', 'images/peru/real/mono_choro_de_cola_amarilla_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(18, 'Nutria Gigante', 'Pteronura brasiliensis', NULL, 'La nutria más grande del mundo, puede medir hasta 2 metros. Vive en grupos familiares y es excelente pescadora.', 'EN', 'Animalia', 1, 4, 5, 'Ríos y lagos de la Amazonía peruana', 'images/peru/real/nutria_gigante_1.jpg', 'images/peru/real/nutria_gigante_2.jpg', 'images/peru/real/nutria_gigante_3.jpg', 'images/peru/real/nutria_gigante_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(19, 'Gallito de las Rocas', 'Rupicola peruvianus', NULL, 'Ave nacional del Perú, famosa por su plumaje anaranjado brillante y su elaborado ritual de cortejo.', 'LC', 'Animalia', 1, 7, 6, 'Bosques nublados de la vertiente oriental de los Andes, entre 500 y 2,400 msnm', 'images/peru/real/gallito_de_las_rocas_1.jpg', 'images/peru/real/gallito_de_las_rocas_2.jpg', 'images/peru/real/gallito_de_las_rocas_3.jpg', 'images/peru/real/gallito_de_las_rocas_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(20, 'Cóndor Andino', 'Vultur gryphus', NULL, 'Ave voladora más grande del mundo, símbolo de los Andes. Puede volar hasta 300 km sin batir las alas.', 'NT', 'Animalia', 1, 6, 7, 'Cordillera de los Andes, desde el nivel del mar hasta 5,000 msnm', 'images/peru/real/c__ndor_andino_1.jpg', 'images/peru/real/c__ndor_andino_2.jpg', 'images/peru/real/c__ndor_andino_3.jpg', 'images/peru/real/c__ndor_andino_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(21, 'Colibrí Cola de Espátula', 'Loddigesia mirabilis', NULL, 'Colibrí endémico del Perú con una cola única en forma de espátula. Una de las aves más raras del mundo.', 'EN', 'Animalia', 1, 4, NULL, 'Bosques montanos del norte del Perú, entre 2,100 y 2,900 msnm', 'images/peru/real/colibr___cola_de_esp__tula_1.jpg', 'images/peru/real/colibr___cola_de_esp__tula_2.jpg', 'images/peru/real/colibr___cola_de_esp__tula_3.jpg', 'images/peru/real/colibr___cola_de_esp__tula_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(22, 'Guacamayo Rojo', 'Ara macao', NULL, 'Loro grande y colorido, símbolo de la biodiversidad amazónica. Forma parejas de por vida.', 'LC', 'Animalia', 1, 7, 8, 'Selva amazónica, bosques tropicales húmedos', 'images/peru/real/guacamayo_rojo_1.jpg', 'images/peru/real/guacamayo_rojo_2.jpg', 'images/peru/real/guacamayo_rojo_3.jpg', 'images/peru/real/guacamayo_rojo_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(23, 'Pinguino de Humboldt', 'Spheniscus humboldti', NULL, 'Pingüino que habita las costas del Perú y Chile, adaptado a aguas templadas de la Corriente de Humboldt.', 'VU', 'Animalia', 1, 5, NULL, 'Costa peruana, islas guaneras y acantilados rocosos', 'images/peru/real/pinguino_de_humboldt_1.jpg', 'images/peru/real/pinguino_de_humboldt_2.jpg', 'images/peru/real/pinguino_de_humboldt_3.jpg', 'images/peru/real/pinguino_de_humboldt_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(24, 'Anaconda Verde', 'Eunectes murinus', NULL, 'La serpiente más pesada del mundo, excelente nadadora. Puede medir hasta 9 metros de longitud.', 'LC', 'Animalia', 1, 7, NULL, 'Ríos, pantanos y humedales de la Amazonía peruana', 'images/peru/real/anaconda_verde_1.jpg', 'images/peru/real/anaconda_verde_2.jpg', 'images/peru/real/anaconda_verde_3.jpg', 'images/peru/real/anaconda_verde_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(25, 'Iguana Verde', 'Iguana iguana', NULL, 'Lagarto herbívoro de gran tamaño, excelente nadador y trepador. Importante dispersor de semillas.', 'LC', 'Animalia', 1, 7, NULL, 'Bosques tropicales de la Amazonía peruana', 'images/peru/real/iguana_verde_1.jpg', 'images/peru/real/iguana_verde_2.jpg', 'images/peru/real/iguana_verde_3.jpg', 'images/peru/real/iguana_verde_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(26, 'Rana Venenosa Dorada', 'Phyllobates aurotaenia', NULL, 'Pequeña rana de colores brillantes, su piel contiene alcaloides tóxicos utilizados tradicionalmente en dardos.', 'NT', 'Animalia', 1, 6, NULL, 'Bosques húmedos de la Amazonía peruana', 'images/peru/real/rana_venenosa_dorada_1.jpg', 'images/peru/real/rana_venenosa_dorada_2.jpg', 'images/peru/real/rana_venenosa_dorada_3.jpg', 'images/peru/real/rana_venenosa_dorada_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(27, 'Paiche', 'Arapaima gigas', NULL, 'Uno de los peces de agua dulce más grandes del mundo, puede medir hasta 3 metros. Respira aire atmosférico.', 'VU', 'Animalia', 1, 5, NULL, 'Ríos y lagos de la Amazonía peruana', 'images/peru/real/paiche_1.jpg', 'images/peru/real/paiche_2.jpg', 'images/peru/real/paiche_3.jpg', 'images/peru/real/paiche_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(28, 'Delfín Rosado', 'Inia geoffrensis', NULL, 'Delfín de río endémico de la Amazonía, su color rosado se intensifica con la edad. Muy inteligente y social.', 'EN', 'Animalia', 1, 4, NULL, 'Ríos de la Amazonía peruana', 'images/peru/real/delf__n_rosado_1.jpg', 'images/peru/real/delf__n_rosado_2.jpg', 'images/peru/real/delf__n_rosado_3.jpg', 'images/peru/real/delf__n_rosado_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(29, 'Puya Raimondi', 'Puya raimondii', NULL, 'La bromeliacea más grande del mundo, puede vivir hasta 100 años y alcanzar 12 metros de altura al florecer.', 'VU', 'Plantae', 2, 5, 9, 'Puna de los Andes centrales del Perú, entre 3,200 y 4,800 msnm', 'images/peru/real/puya_raimondi_1.jpg', 'images/peru/real/puya_raimondi_2.jpg', 'images/peru/real/puya_raimondi_3.jpg', 'images/peru/real/puya_raimondi_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(30, 'Orquídea Waqanki', 'Masdevallia veitchiana', NULL, 'Orquídea endémica del Perú con flores de color naranja intenso. Flor nacional del Perú.', 'VU', 'Plantae', 2, 5, NULL, 'Bosques nublados de los Andes peruanos, entre 2,500 y 3,500 msnm', 'images/peru/real/orqu__dea_waqanki_1.jpg', 'images/peru/real/orqu__dea_waqanki_2.jpg', 'images/peru/real/orqu__dea_waqanki_3.jpg', 'images/peru/real/orqu__dea_waqanki_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(31, 'Cantuta', 'Cantua buxifolia', NULL, 'Flor sagrada de los incas, símbolo nacional del Perú junto con Bolivia. Flores tubulares de colores vivos.', 'LC', 'Plantae', 2, 7, NULL, 'Valles interandinos del Perú, entre 1,200 y 3,800 msnm', 'images/peru/real/cantuta_1.jpg', 'images/peru/real/cantuta_2.jpg', 'images/peru/real/cantuta_3.jpg', 'images/peru/real/cantuta_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(32, 'Quinua', 'Chenopodium quinoa', NULL, 'Pseudocereal andino, superalimento con proteínas completas. Cultivado por más de 5,000 años en los Andes.', 'LC', 'Plantae', 2, 7, NULL, 'Altiplano andino del Perú, entre 3,500 y 4,000 msnm', 'images/peru/real/quinua_1.jpg', 'images/peru/real/quinua_2.jpg', 'images/peru/real/quinua_3.jpg', 'images/peru/real/quinua_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(33, 'Cacao Peruano', 'Theobroma cacao', NULL, 'Árbol del cacao, origen del chocolate. El Perú produce algunos de los cacaos más finos del mundo.', 'LC', 'Plantae', 2, 7, NULL, 'Selva amazónica del Perú, entre 200 y 1,000 msnm', 'images/peru/real/cacao_peruano_1.jpg', 'images/peru/real/cacao_peruano_2.jpg', 'images/peru/real/cacao_peruano_3.jpg', 'images/peru/real/cacao_peruano_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(34, 'Mariposa Morpho Azul', 'Morpho menelaus', NULL, 'Mariposa de gran tamaño con alas azul metálico iridiscente. Sus alas pueden medir hasta 20 cm de envergadura.', 'LC', 'Animalia', 1, 7, NULL, 'Selva amazónica del Perú, bosques tropicales húmedos', 'images/peru/real/mariposa_morpho_azul_1.jpg', 'images/peru/real/mariposa_morpho_azul_2.jpg', 'images/peru/real/mariposa_morpho_azul_3.jpg', 'images/peru/real/mariposa_morpho_azul_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(35, 'Tarántula Goliath', 'Theraphosa blondi', NULL, 'La araña más grande del mundo por masa corporal. Puede tener una envergadura de hasta 30 cm.', 'LC', 'Animalia', 1, 7, NULL, 'Selva amazónica del Perú, suelos húmedos del bosque', 'images/peru/real/tar__ntula_goliath_1.jpg', 'images/peru/real/tar__ntula_goliath_2.jpg', 'images/peru/real/tar__ntula_goliath_3.jpg', 'images/peru/real/tar__ntula_goliath_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(36, 'Lobo Marino Sudamericano', 'Otaria flavescens', NULL, 'Pinnípedo que habita las costas del Pacífico sudamericano. Los machos pueden pesar hasta 350 kg.', 'LC', 'Animalia', 1, 7, 10, 'Costa peruana, playas rocosas e islas', 'images/peru/real/lobo_marino_sudamericano_1.jpg', 'images/peru/real/lobo_marino_sudamericano_2.jpg', 'images/peru/real/lobo_marino_sudamericano_3.jpg', 'images/peru/real/lobo_marino_sudamericano_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(37, 'Anchoveta Peruana', 'Engraulis ringens', NULL, 'Pez pelágico base de la cadena alimentaria marina peruana. Sustenta la industria pesquera más importante del país.', 'LC', 'Animalia', 1, 7, NULL, 'Aguas costeras del Perú, mar peruano', 'images/peru/real/anchoveta_peruana_1.jpg', 'images/peru/real/anchoveta_peruana_2.jpg', 'images/peru/real/anchoveta_peruana_3.jpg', 'images/peru/real/anchoveta_peruana_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(38, 'Chinchilla de Cola Larga', 'Chinchilla chinchilla', NULL, 'Roedor con el pelaje más denso del mundo, hasta 60 pelos por folículo. Casi extinto en estado silvestre.', 'CR', 'Animalia', 1, 3, NULL, 'Andes del norte del Perú, zonas rocosas entre 3,000 y 5,000 msnm', 'images/peru/real/chinchilla_de_cola_larga_1.jpg', 'images/peru/real/chinchilla_de_cola_larga_2.jpg', 'images/peru/real/chinchilla_de_cola_larga_3.jpg', 'images/peru/real/chinchilla_de_cola_larga_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(39, 'Sachavaca', 'Tapirus terrestris', NULL, 'El mamífero terrestre más grande de la Amazonía. Excelente nadador y dispersor de semillas.', 'VU', 'Animalia', 1, 5, NULL, 'Selva amazónica del Perú, cerca de ríos y pantanos', 'images/peru/real/sachavaca_1.jpg', 'images/peru/real/sachavaca_2.jpg', 'images/peru/real/sachavaca_3.jpg', 'images/peru/real/sachavaca_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(40, 'Rana Gigante del Titicaca', 'Telmatobius culeus', NULL, 'Rana acuática endémica del Lago Titicaca, la más grande de Sudamérica. Respira a través de su piel.', 'CR', 'Animalia', 1, 3, NULL, 'Lago Titicaca, endémica de este ecosistema', 'images/peru/real/rana_gigante_del_titicaca_1.jpg', 'images/peru/real/rana_gigante_del_titicaca_2.jpg', 'images/peru/real/rana_gigante_del_titicaca_3.jpg', 'images/peru/real/rana_gigante_del_titicaca_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(41, 'Maca', 'Lepidium meyenii', NULL, 'Planta adaptógena cultivada en los Andes por más de 2,000 años. Conocida por sus propiedades nutritivas y medicinales.', 'LC', 'Plantae', 2, 7, NULL, 'Puna de los Andes centrales del Perú, entre 4,000 y 4,500 msnm', 'images/peru/real/maca_1.jpg', 'images/peru/real/maca_2.jpg', 'images/peru/real/maca_3.jpg', 'images/peru/real/maca_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(42, 'Uña de Gato', 'Uncaria tomentosa', NULL, 'Liana medicinal amazónica con propiedades inmunoestimulantes. Utilizada tradicionalmente por pueblos indígenas.', 'LC', 'Plantae', 2, 7, NULL, 'Selva amazónica del Perú, bosques tropicales húmedos', 'images/peru/real/u__a_de_gato_1.jpg', 'images/peru/real/u__a_de_gato_2.jpg', 'images/peru/real/u__a_de_gato_3.jpg', 'images/peru/real/u__a_de_gato_4.jpg', '2025-08-26 19:24:09', '2025-08-25 21:35:14', '2025-08-26 19:24:09'),
	(1387, 'Lagartija', 'Liolaemus basadrei', 'Lagartija', 'Liolaemus basadrei es una especie de lagarto de la familia Liolaemidae. Es originario de Perú.', 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1388, 'Lagarto Negro', 'Microlophus thoracicus', 'Lagarto Negro', 'La Iguana Tschudi Del Pacífico o lagartija de los gramadales es una especie de reptil escamoso que pertenece a la familia Tropiduridae.​ Es endémica de Perú.​', 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1389, 'Salamanqueja', 'Phyllodactylus gerrhopygus ', 'Salamanqueja', 'Phyllodactylus gerrhopygus es una especie de lagarto que se encuentra en la Reserva Nacional de Paracas (RNP)14. Fue descrita por Wiegmann en 1835 y se le conoce como "Salamanqueja del norte grande"5. La nomenclatura actual considera válida el nombre Phyllodactylus gerrhopygus', 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1390, 'Culebrita', 'Tachymenis peruviana', 'Culebrita', 'Tachymenis peruviana, también conocida en donde habita como falsa yarará o culebra peruana, es una serpiente de tamaño medio que puede alcanzar los 60 cm de longitud. Habita en zonas de altura, desde los 2000 m aproximadamente. Se oculta entre la vegetación y las piedras, y su dieta se basa en anfibios y lagartijas.', 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1391, 'Serpiente corredor dorso rojizo', 'Pseudalsophis elegans', 'Serpiente corredor dorso rojizo', 'Pseudalsophis elegans es una especie de serpiente de la familia Colubridae. Es la única especie de serpiente del género Pseudalsophis que no se encuentra en las Islas Galápagos', 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1392, 'Sapo', 'Rhinella spinulosa (Bufo spinulosa, Rhinella arequipensis)', 'Sapo', 'El sapo espinoso (Rhinella spinulosa, anteriormente Bufo spinulosus) es una especie de anfibio anuro propio de Sudamérica. Vive en el altiplano andino chileno, argentino, peruano y boliviano. Es mediano, de 5 a 12 centímetros de largo. Su nombre proviene de las espinas queratinosas que tiene en la piel del dorso que es extremadamente granulosa y verrucosa.', 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1393, 'Rana acuática Perú', 'Telmatobius peruvianus', 'Rana acuática Perú', 'Telmatobius peruvianus es una especie de anfibios de la familia Leptodactylidae. Se encuentra en Chile y el Perú. Se encuentra amenazada de extinción por la pérdida de su hábitat natural.', 'VU', 'Animalia', NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1394, 'Rana', 'Pleurodema marmorata', 'Rana', 'Pleurodema marmoratum es una especie de anfibio anuro de la familia Leiuperidae. Se encuentra en Argentina, Bolivia, Chile y Perú.', 'VU', 'Animalia', NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1395, 'Rata chinchilla cenicienta', 'Abrocoma cinerea', 'Rata chinchilla cenicienta', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1396, 'Ratón campestre andino', 'Abrothrix andinus', 'Ratón campestre andino', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1397, 'Ratón campestre de jelski', 'Abrothrix jelskii', 'Ratón campestre de jelski', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1398, 'Ratón campestre de vientre blanco', 'Akodon albiventer', 'Ratón campestre de vientre blanco', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1399, 'Murciélago ahumado', 'Amorphochilus schnablii', 'Murciélago ahumado', NULL, 'EN', 'Animalia', NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1400, 'Lobo fino, cochapuma', 'Arctocephalus australis ', 'Lobo fino, cochapuma', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1401, 'Chinchilla andina', 'Chinchilla chinchilla', 'Chinchilla andina', NULL, 'CR', 'Animalia', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1402, 'Rata chinchilla de Sajama', 'Chinchillula sahamae ', 'Rata chinchilla de Sajama', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1403, 'Zorrino, añás', 'Conepatus chinga ', 'Zorrino, añás', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1404, 'Tucu-tucu del Titicaca', 'Ctenomys opimus', 'Tucu-tucu del Titicaca', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1405, 'Delfín común de hocico corto', 'Delphinus delphis', 'Delfín común de hocico corto', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1406, 'Vampiro común', 'Desmodus rotundus', 'Vampiro común', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1407, 'Sacha cuy', 'Galea musteloides ', 'Sacha cuy', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1408, 'Hurón menor, cuya', 'Galictis cuja ', 'Hurón menor, cuya', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1409, 'Ciervo altoandino, taruca', 'Hippocamelus antisensis', 'Ciervo altoandino, taruca', NULL, 'VU', 'Animalia', NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1410, 'Murciélago orejudo mayor', 'Histiotus macrotus ', 'Murciélago orejudo mayor', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1411, 'Murciélago orejón andino', 'Histiotus montanus ', 'Murciélago orejón andino', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1412, 'Delfín obscuro', 'Lagenorhynchus obscurus ', 'Delfín obscuro', NULL, 'DD', 'Animalia', NULL, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1413, 'Viscacha peruana', 'Lagidium peruanum ', 'Viscacha peruana', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1414, 'Viscacha Chilena', 'Lagidium viscacia ', 'Viscacha Chilena', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1415, 'Guanaco', 'Lama guanicoe', 'Guanaco', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1416, 'Gato del pajonal, oscollo', 'Leopardus colocolo (Oncifelis colocolo*, Lynchailurus pajeros*)', 'Gato del pajonal, oscollo', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1417, 'Gato montés, gato andino', 'Leopardus jacobita (Oreailurus jacobita*)', 'Gato montés, gato andino', NULL, 'EN', 'Animalia', NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1418, 'Liebre europea', 'Lepus europaeus A', 'Liebre europea', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1419, 'Gato marino, chingungo, huallaque', 'Lontra felina ', 'Gato marino, chingungo, huallaque', NULL, 'EN', 'Animalia', NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1420, 'Zorro colorado, atoj', 'Lycalopex culpaeus (Pseudalopex culpaeus*)', 'Zorro colorado, atoj', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1421, 'Zorro gris, chilla', 'Lycalopex griseus (Pseudalopex griseus*)', 'Zorro gris, chilla', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1422, 'Ballena jorobada, yubarta', 'Megaptera novaeangliae', 'Ballena jorobada, yubarta', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1423, 'Murciélago de cola libre de Kalinowski', 'Mormopterus kalinowskii ', 'Murciélago de cola libre de Kalinowski', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1424, 'Ratón común', 'Mus musculusA', 'Ratón común', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1425, 'Murcielaguito de Atacama', 'Myotis atacamensis ', 'Murcielaguito de Atacama', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1426, 'Lobo chusco, cochapuma', 'Otaria flavescens (Otaria byronia)', 'Lobo chusco, cochapuma', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1427, 'Marsopa espinosa, chancho marino', 'Phocoena spinipinnis', 'Marsopa espinosa, chancho marino', NULL, 'DD', 'Animalia', NULL, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1428, 'Ratón orejón de Lima', 'Phyllotis limatus', 'Ratón orejón de Lima', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1429, 'Ratón orejón maestro', 'Phyllotis magister', 'Ratón orejón maestro', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1430, 'Ratón orejón chileno', 'Phyllotis chilensis', 'Ratón orejón chileno', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1431, 'Murciélago longirostro peruano', 'Platalina genovensium ', 'Murciélago longirostro peruano', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1432, 'Murciélago mastín con cresta de Davison', 'Promops davisoni', 'Murciélago mastín con cresta de Davison', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1433, 'Puma, león, lluichu-puma, kirajari matsonsori', 'Puma concolor ', 'Puma, león, lluichu-puma, kirajari matsonsori', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1434, 'Ratón de puna', 'Punomys lemminus', 'Ratón de puna', NULL, 'VU', 'Animalia', NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1435, 'Murciélago mastín', 'Tadarida brasiliensis ', 'Murciélago mastín', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1436, 'Marmosa coligruesa de vientre blanco', 'Thylamys pallidior              ', 'Marmosa coligruesa de vientre blanco', 'La marmosa pálida o comadrejita de vientre blanco, llaca de la Puna o comadreja enana, es una especie de marsupial didelfimorfo de la familia Didelphidae propio de Sudamérica. Se encuentra en el este de Argentina, sur y este de Bolivia, norte de Chile y la parte occidental de los Andes peruanos', 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1437, 'Delfín pico de botella', 'Tursiops truncatus', 'Delfín pico de botella', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1438, 'Vicuña', 'Vicugna vicugna', 'Vicuña', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1439, 'Playero Coleador', 'Actitis macularius (A. macularia*)', 'Playero Coleador', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1440, 'Vencejo Andino', 'Aeronautes andecolus', 'Vencejo Andino', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1441, 'Arriero de Pico Negro', 'Agriornis montanus (A. montana*)', 'Arriero de Pico Negro', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1442, 'Arriero de Vientre Gris', 'Agriornis micropterus', 'Arriero de Vientre Gris', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1443, 'Torito de Pico Amarillo', 'Anairetes flavirostris', 'Torito de Pico Amarillo', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1444, 'Torito de Cresta Pintada', 'Anairetes reguloides', 'Torito de Cresta Pintada', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1445, 'Pato Gargantillo', 'Anas bahamensis ', 'Pato Gargantillo', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1446, 'Pato Colorado', 'Anas cyanoptera ', 'Pato Colorado', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1447, 'Pato Barcino', 'Anas flavirostris ', 'Pato Barcino', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1448, 'Pato Jergón', 'Anas georgica ', 'Pato Jergón', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1449, 'Pato de la Puna', 'Anas puna ', 'Pato de la Puna', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1450, 'Cachirla Amarillenta', 'Anthus lutescens', 'Cachirla Amarillenta', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1451, 'Chorlo de las Rompientes', 'Aphriza virgata', 'Chorlo de las Rompientes', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1452, 'Garza Grande', 'Ardea alba ', 'Garza Grande', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:30', '2025-08-26 19:49:30'),
	(1453, 'Garza Cuca', 'Ardea cocoi ', 'Garza Cuca', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1454, 'Pardela de Buller', 'Ardenna bulleri (Puffinus bulleri*)', 'Pardela de Buller', NULL, 'VU', 'Animalia', NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1455, 'Pardela de Pata Rosada', 'Ardenna creatopus (Puffinus creatopus*)', 'Pardela de Pata Rosada', NULL, 'VU', 'Animalia', NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1456, 'Pardela Oscura', 'Ardenna grisea (Puffinus griseus*)', 'Pardela Oscura', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1457, 'Vuelvepiedras Rojizo', 'Arenaria interpres', 'Vuelvepiedras Rojizo', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1458, 'Lechuza de Oreja Corta', 'Asio flammeus ', 'Lechuza de Oreja Corta', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1459, 'Canastero de Pecho Cremoso', 'Asthenes dorbignyi', 'Canastero de Pecho Cremoso', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1460, 'Canastero Cordillerano', 'Asthenes modesta', 'Canastero Cordillerano', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1461, 'Canastero de Quebradas', 'Asthenes pudibunda', 'Canastero de Quebradas', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1462, 'Canastero de la Puna', 'Asthenes sclateri', 'Canastero de la Puna', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1463, 'Lechuza Terrestre', 'Athene cunicularia ', 'Lechuza Terrestre', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1464, 'Agachona de Vientre Rufo', 'Attagis gayi', 'Agachona de Vientre Rufo', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1465, 'Playero Batitú', 'Bartramia longicauda', 'Playero Batitú', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1466, 'Búho Americano', 'Bubo virginianus ', 'Búho Americano', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1467, 'Garcita Bueyera', 'Bubulcus ibis ', 'Garcita Bueyera', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1468, 'Alcaraván Huerequeque', 'Burhinus superciliaris', 'Alcaraván Huerequeque', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1469, 'Aguilucho de Garganta Blanca', 'Buteo albigula', 'Aguilucho de Garganta Blanca', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1470, 'Garcita Estriada', 'Butorides striata', 'Garcita Estriada', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1471, 'Playero Arenero', 'Calidris alba', 'Playero Arenero', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1472, 'Playerito de Baird', 'Calidris bairdii ', 'Playerito de Baird', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1473, 'Playerito de Cuello Rojo', 'Calidris fuscicollis', 'Playerito de Cuello Rojo', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1474, 'Playerito Occidental', 'Calidris mauri', 'Playerito Occidental', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1475, 'Playero Pectoral', 'Calidris melanotos ', 'Playero Pectoral', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1476, 'Playerito Menudo', 'Calidris minutilla', 'Playerito Menudo', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1477, 'Playerito Semipalmado', 'Calidris pusilla', 'Playerito Semipalmado', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1478, 'Semillero de Cola Bandeada', 'Catamenia analis', 'Semillero de Cola Bandeada', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1479, 'Semillero Simple', 'Catamenia inornata', 'Semillero Simple', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1480, 'Gallinazo de Cabeza Roja', 'Cathartes aura', 'Gallinazo de Cabeza Roja', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1481, 'Vencejo de Chimenea', 'Chaetura pelagica', 'Vencejo de Chimenea', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1482, 'Chorlo de la Puna', 'Charadrius alticola ', 'Chorlo de la Puna', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1483, 'Chorlo Acollarado', 'Charadrius collaris', 'Chorlo Acollarado', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1484, 'Chorlo Chileno', 'Charadrius modestus', 'Chorlo Chileno', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1485, 'Chorlo Nevado', 'Charadrius nivosus (C. alexandrinus*)', 'Chorlo Nevado', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1486, 'Chorlo Semipalmado', 'Charadrius semipalmatus', 'Chorlo Semipalmado', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1487, 'Chorlo Gritón', 'Charadrius vociferus ', 'Chorlo Gritón', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1488, 'Gaviotín Negro', 'Chlidonias niger', 'Gaviotín Negro', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1489, 'Chotacabras Menor', 'Chordeiles acutipennis', 'Chotacabras Menor', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1490, 'Gaviota de Capucha Gris', 'Chroicocephalus cirrocephalus (Larus cirrocephalus*)', 'Gaviota de Capucha Gris', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1491, 'Gaviota Andina', 'Chroicocephalus serranus (Larus serranus*)', 'Gaviota Andina', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1492, 'Churrete de Ala Crema', 'Cinclodes albiventris (C. fuscus*)', 'Churrete de Ala Crema', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1493, 'Churrete de Ala Blanca', 'Cinclodes atacamensis', 'Churrete de Ala Blanca', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1494, 'Churrete acanelado', 'Cinclodes fuscus (Cinclodes fuscus fuscus)', 'Churrete acanelado', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1495, 'Churrete Marisquero', 'Cinclodes taczanowskii', 'Churrete Marisquero', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1496, 'Aguilucho Cenizo', 'Circus cinereus', 'Aguilucho Cenizo', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1497, 'Carpintero de Cuello Negro', 'Colaptes atricollis', 'Carpintero de Cuello Negro', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1498, 'Carpintero Andino', 'Colaptes rupicola ', 'Carpintero Andino', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1499, 'Oreja-Violeta de Vientre Azul', 'Colibri coruscans ', 'Oreja-Violeta de Vientre Azul', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1500, 'Paloma Doméstica', 'Columba livia', 'Paloma Doméstica', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1501, 'Tortolita Peruana', 'Columbina cruziana', 'Tortolita Peruana', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1502, 'Pico-de-Cono Cinéreo', 'Conirostrum cinereum', 'Pico-de-Cono Cinéreo', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1503, 'Pico-de-Cono de los Tamarugales', 'Conirostrum tamarugense ', 'Pico-de-Cono de los Tamarugales', NULL, 'VU', 'Animalia', NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1504, 'Garrapatero de Pico Estriado', 'Crotophaga sulcirostris', 'Garrapatero de Pico Estriado', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1505, 'Petrel Damero', 'Daption capense', 'Petrel Damero', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1506, 'Pincha-Flor de Garganta Negra', 'Diglossa brunneiventris', 'Pincha-Flor de Garganta Negra', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1507, 'Diuca de Ala Blanca', 'Diuca speculifera ', 'Diuca de Ala Blanca', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1508, 'Garcita Azul', 'Egretta caerulea ', 'Garcita Azul', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1509, 'Garcita Blanca', 'Egretta thula ', 'Garcita Blanca', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1510, 'Garcita Tricolor', 'Egretta tricolor', 'Garcita Tricolor', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1511, 'Fío-Fío de Cresta Blanca', 'Elaenia albiceps', 'Fío-Fío de Cresta Blanca', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1512, 'Halcón Aplomado', 'Falco femoralis ', 'Halcón Aplomado', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1513, 'Halcón Peregrino', 'Falco peregrinus', 'Halcón Peregrino', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1514, 'Cernícalo Americano', 'Falco sparverius ', 'Cernícalo Americano', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1515, 'Gallareta Andina', 'Fulica ardesiaca ', 'Gallareta Andina', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1516, 'Gallareta Gigante', 'Fulica gigantea ', 'Gallareta Gigante', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1517, 'Petrel Plateado', 'Fulmarus glacialoides', 'Petrel Plateado', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1518, 'Becasina de la Puna', 'Gallinago andina', 'Becasina de la Puna', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1519, 'Polla de Agua Común', 'Gallinula galeata (G.chloropus*)', 'Polla de Agua Común', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1520, 'Minero Común', 'Geositta cunicularia', 'Minero Común', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1521, 'Minero Gris', 'Geositta maritima', 'Minero Gris', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1522, 'Minero de la Puna', 'Geositta punensis ', 'Minero de la Puna', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1523, 'Minero de Pico Largo', 'Geositta tenuirostris', 'Minero de Pico Largo', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1524, 'Aguilucho de Pecho Negro', 'Geranoaetus melanoleucus ', 'Aguilucho de Pecho Negro', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1525, 'Aguilucho Variable', 'Geranoaetus polyosoma (Buteo polyosoma*)', 'Aguilucho Variable', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1526, 'Lechucita Peruana', 'Glaucidium peruanum', 'Lechucita Peruana', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1527, 'Ostrero Negruzco', 'Haematopus ater', 'Ostrero Negruzco', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1528, 'Ostrero Americano', 'Haematopus palliatus', 'Ostrero Americano', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1529, 'Cigüeñuela común', 'Himantopus melanurus(a)  ', 'Cigüeñuela común', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1530, 'Cigüeñuela de Cuello Negro', 'Himantopus mexicanus', 'Cigüeñuela de Cuello Negro', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1531, 'Golondrina Tijereta', 'Hirundo rustica', 'Golondrina Tijereta', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1532, 'Mirasol Leonado', 'Ixobrychus exilis', 'Mirasol Leonado', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1533, 'Gaviotín Zarcillo', 'Larosterna inca', 'Gaviotín Zarcillo', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1534, 'Gaviota Peruana', 'Larus belcheri', 'Gaviota Peruana', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1535, 'Gaviota Dominicana', 'Larus dominicanus', 'Gaviota Dominicana', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1536, 'Gallineta Negra', 'Laterallus jamaicensis', 'Gallineta Negra', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1537, 'Tijeral de Manto Llano', 'Leptasthenura aegithaloides', 'Tijeral de Manto Llano', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1538, 'Tijeral Listado', 'Leptasthenura striata ', 'Tijeral Listado', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1539, 'Negrito Andino', 'Lessonia oreas ', 'Negrito Andino', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1540, 'Gaviota Reidora', 'Leucophaeus atricilla (Larus atricilla*)', 'Gaviota Reidora', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1541, 'Gaviota Gris', 'Leucophaeus modestus (Larus modestus*)', 'Gaviota Gris', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1542, 'Gaviota de Franklin', 'Leucophaeus pipixcan (Larus pipixcan*)', 'Gaviota de Franklin', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1543, 'Aguja de Mar', 'Limosa haemastica', 'Aguja de Mar', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1544, 'Agujeta de Pico Corto', 'Limnodromus griseus', 'Agujeta de Pico Corto', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1545, 'Pato Crestón', 'Lophonetta specularioides ', 'Pato Crestón', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1546, 'Petrel Gigante Sureño', 'Macronectes giganteus', 'Petrel Gigante Sureño', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1547, 'Petrel Gigante Norteño', 'Macronectes halli', 'Petrel Gigante Norteño', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1548, 'Colibrí Negro', 'Metallura phoebe ', 'Colibrí Negro', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1549, 'Tortolita de Puntos Dorados', 'Metriopelia aymara', 'Tortolita de Puntos Dorados', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1550, 'Tortolita Moteada', 'Metriopelia ceciliae ', 'Tortolita Moteada', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1551, 'Tortolita de Ala Negra', 'Metriopelia melanoptera', 'Tortolita de Ala Negra', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1552, 'Tordo Brilloso', 'Molothrus bonariensis', 'Tordo Brilloso', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1553, 'Dormilona de Cola Corta', 'Muscigralla brevicauda', 'Dormilona de Cola Corta', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1554, 'Dormilona de Frente Blanca', 'Muscisaxicola albifrons ', 'Dormilona de Frente Blanca', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1555, 'Dormilona Cinérea', 'Muscisaxicola cinereus (M. cinerea*)', 'Dormilona Cinérea', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1556, 'Dormilona de Nuca Ocrácea', 'Muscisaxicola flavinucha', 'Dormilona de Nuca Ocrácea', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1557, 'Dormilona de Taczanowski', 'Muscisaxicola griseus(b)', 'Dormilona de Taczanowski', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1558, 'Dormilona de la Puna', 'Muscisaxicola juninensis', 'Dormilona de la Puna', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1559, 'Dormilona de Cara Oscura', 'Muscisaxicola maclovianus', 'Dormilona de Cara Oscura', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1560, 'Dormilona Chica', 'Muscisaxicola maculirostris', 'Dormilona Chica', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1561, 'Dormilona de Nuca Rojiza', 'Muscisaxicola rufivertex', 'Dormilona de Nuca Rojiza', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1562, 'Mosquerito de Pecho Rayado', 'Myiophobus fasciatus', 'Mosquerito de Pecho Rayado', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1563, 'Estrellita de Collar Púrpura', 'Myrtis fanny', 'Estrellita de Collar Púrpura', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1564, 'Perdiz Andina', 'Nothoprocta pentlandii', 'Perdiz Andina', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1565, 'Zarapito Trinador', 'Numenius phaeopus', 'Zarapito Trinador', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1566, 'Huaco Común', 'Nycticorax nycticorax ', 'Huaco Común', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1567, 'Golondrina de Mar Chica', 'Oceanites gracilis', 'Golondrina de Mar Chica', NULL, 'DD', 'Animalia', NULL, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1568, 'Golondrina de Mar de Wilson', 'Oceanites oceanicus', 'Golondrina de Mar de Wilson', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1569, 'Golondrina de Mar Acollarada', 'Oceanodroma hornbyi', 'Golondrina de Mar Acollarada', NULL, 'DD', 'Animalia', NULL, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1570, 'Golondrina de Mar de Markham', 'Oceanodroma markhami', 'Golondrina de Mar de Markham', NULL, 'DD', 'Animalia', NULL, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1571, 'Pitajo de Ceja Blanca', 'Ochthoeca leucophrys', 'Pitajo de Ceja Blanca', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1572, 'Pitajo de d’Orbigny', 'Ochthoeca oenanthoides', 'Pitajo de d’Orbigny', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1573, 'Pico-de-Cono Gigante', 'Oreomanes fraseri', 'Pico-de-Cono Gigante', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1574, 'Chorlo de Campo', 'Oreopholus ruficollis', 'Chorlo de Campo', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1575, 'Estrella Andina', 'Oreotrochilus estella  (Oreotrichilus estella**, Oreotrochillus stella**)', 'Estrella Andina', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1576, 'Oressochen melanopterus', 'Oressochen melanopterus (Chloephaga melanoptera*)', 'Oressochen melanopterus', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1577, 'Orochelidon murina', 'Orochelidon murina (Notiochelidon murina*)', 'Orochelidon murina', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1578, 'Orochelidum andecola', 'Orochelidon andecola (Stelgidopteryx andecola, Haplochelidon andecola*)', 'Orochelidum andecola', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1579, 'Pato Rana', 'Oxyura jamaicensis (O. ferruginea*)', 'Pato Rana', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1580, 'Petrel-Azul Antártico', 'Pachyptila desolata', 'Petrel-Azul Antártico', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1581, 'Aguila Pescadora', 'Pandion haliaetus', 'Aguila Pescadora', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1582, 'Gavilán Mixto', 'Parabuteo unicinctus', 'Gavilán Mixto', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1583, 'Pardirallus sanguinolentus', 'Pardirallus sanguinolentus', 'Pardirallus sanguinolentus', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1584, 'Rascón Plomizo', 'Passer domesticus', 'Rascón Plomizo', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1585, 'Paloma de Ala Moteada', 'Patagioenas maculosa', 'Paloma de Ala Moteada', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1586, 'Colibrí Gigante', 'Patagona gigas ', 'Colibrí Gigante', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1587, 'Potoyunco Peruano', 'Pelecanoides garnotii', 'Potoyunco Peruano', NULL, 'EN', 'Animalia', NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1588, 'Pelícano Peruano', 'Pelecanus thagus', 'Pelícano Peruano', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1589, 'Golondrina de Collar Castaño', 'Petrochelidon rufocollaris', 'Golondrina de Collar Castaño', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1590, 'Cormorán Guanay', 'Phalacrocorax bougainvillii', 'Cormorán Guanay', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1591, 'Cormorán Neotropical', 'Phalacrocorax brasilianus', 'Cormorán Neotropical', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1592, 'Cormorán de Pata Roja', 'Phalacrocorax gaimardi', 'Cormorán de Pata Roja', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1593, 'Faláropo de Pico Fino', 'Phalaropus lobatus', 'Faláropo de Pico Fino', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1594, 'Faláropo Tricolor', 'Phalaropus tricolor ', 'Faláropo Tricolor', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1595, 'Caracara Cordillerano', 'Phalcoboenus megalopterus ', 'Caracara Cordillerano', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1596, 'Chorlo Cordillerano', 'Phegornis mitchellii', 'Chorlo Cordillerano', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1597, 'Junquero', 'Phleocryptes melanops', 'Junquero', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1598, 'Parina Grande', 'Phoenicoparrus andinus ', 'Parina Grande', NULL, 'VU', 'Animalia', NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1599, 'Parina Chica', 'Phoenicoparrus jamesi ', 'Parina Chica', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1600, 'Flamenco Chileno', 'Phoenicopterus chilensis ', 'Flamenco Chileno', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1601, 'Fringilo de Cola Bandeada', 'Phrygilus alaudinus', 'Fringilo de Cola Bandeada', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1602, 'Fringilo de Capucha Negra', 'Phrygilus atriceps', 'Fringilo de Capucha Negra', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1603, 'Fringilo de Garganta Blanca', 'Phrygilus erythronotus', 'Fringilo de Garganta Blanca', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1604, 'Fringilo de Pecho Negro', 'Phrygilus fruticeti', 'Fringilo de Pecho Negro', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1605, 'Fringilo de Pecho Cenizo', 'Phrygilus plebejus', 'Fringilo de Pecho Cenizo', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1606, 'Fringilo Peruano', 'Phrygilus punensis ', 'Fringilo Peruano', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1607, 'Fringilo Plomizo', 'Phrygilus unicolor', 'Fringilo Plomizo', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1608, 'Tangara Azul y Amarilla', 'Pipraeidea bonariensis (Thraupis bonariensis*)', 'Tangara Azul y Amarilla', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1609, 'Espátula Rosada', 'Platalea ajaja', 'Espátula Rosada', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1610, 'Ibis de la Puna', 'Plegadis ridgwayi ', 'Ibis de la Puna', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1611, 'Chorlo Dorado Americano', 'Pluvialis dominica ', 'Chorlo Dorado Americano', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1612, 'Chorlo Gris', 'Pluvialis squatarola', 'Chorlo Gris', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1613, 'Zambullidor Grande', 'Podiceps major', 'Zambullidor Grande', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1614, 'Zambullidor Plateado', 'Podiceps occipitalis ', 'Zambullidor Plateado', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1615, 'Zambullidor de Pico Grueso', 'Podilymbus podiceps ', 'Zambullidor de Pico Grueso', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1616, 'Ala-Rufa Canelo', 'Polioxolmis rufipennis', 'Ala-Rufa Canelo', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1617, 'Monterita Acollarada', 'Poospiza hispaniolensis', 'Monterita Acollarada', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1618, 'Petrel de Mentón Blanco', 'Procellaria aequinoctialis', 'Petrel de Mentón Blanco', NULL, 'VU', 'Animalia', NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1619, 'Martín Peruano', 'Progne murphyi', 'Martín Peruano', NULL, 'VU', 'Animalia', NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1620, 'Perico Cordillerano', 'Psilopsiagon aurifrons ', 'Perico Cordillerano', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1621, 'Cotorra de Frente Escarlata', 'Psittacara wagleri (Aratinga wagleri*)', 'Cotorra de Frente Escarlata', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1622, 'Golondrina Azul y Blanca', 'Pygochelidon cyanoleuca', 'Golondrina Azul y Blanca', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1623, 'Mosquero Bermellón', 'Pyrocephalus rubinus', 'Mosquero Bermellón', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1624, 'Avoceta Andina', 'Recurvirostra andina ', 'Avoceta Andina', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1625, 'Ñandú Petizo (Suri)', 'Rhea pennata', 'Ñandú Petizo (Suri)', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1626, 'Colibrí de Oasis', 'Rhodopis vesper', 'Colibrí de Oasis', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1627, 'Golondrina Ribereña', 'Riparia riparia', 'Golondrina Ribereña', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1628, 'Zambullidor Pimpollo', 'Rollandia rolland', 'Zambullidor Pimpollo', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1629, 'Rayador Negro', 'Rynchops niger (Rhynchops niger**)', 'Rayador Negro', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1630, 'Saltador de Pico Dorado', 'Saltator aurantiirostris', 'Saltador de Pico Dorado', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1631, 'Chirigüe de la Puna', 'Sicalis lutea ', 'Chirigüe de la Puna', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1632, 'Chirigüe Común', 'Sicalis luteola', 'Chirigüe Común', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1633, 'Chirigüe Verdoso', 'Sicalis olivascens', 'Chirigüe Verdoso', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1634, 'Chirigüe de Raimondi', 'Sicalis raimondii', 'Chirigüe de Raimondi', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1635, 'Chirigüe de Lomo Brillante', 'Sicalis uropygialis', 'Chirigüe de Lomo Brillante', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1636, 'Pingüino de Humboldt', 'Spheniscus humboldti', 'Pingüino de Humboldt', NULL, 'VU', 'Animalia', NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1637, 'Jilguero Negro', 'Spinus atratus (Carduelis atrata*)', 'Jilguero Negro', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1638, 'Jilguero de Pico Grueso', 'Spinus crassirostris (Carduelis crassirostris*)', 'Jilguero de Pico Grueso', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1639, 'Jilguero Encapuchado', 'Spinus magellanicus (Carduelis magellanica*)', 'Jilguero Encapuchado', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1640, 'Jilguero Cordillerano', 'Spinus uropygialis (Carduelis uropygialis*)', 'Jilguero Cordillerano', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1641, 'Espiguero Simple', 'Sporophila simplex', 'Espiguero Simple', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1642, 'Espiguero de Garganta Castaña', 'Sporophila telasco', 'Espiguero de Garganta Castaña', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1643, 'Salteador Chileno', 'Stercorarius chilensis', 'Salteador Chileno', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1644, 'Salteador Parásito', 'Stercorarius parasiticus', 'Salteador Parásito', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1645, 'Salteador Pomarino', 'Stercorarius pomarinus', 'Salteador Pomarino', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1646, 'Gaviotín Sudamericano', 'Sterna hirundinacea', 'Gaviotín Sudamericano', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1647, 'Gaviotín Común', 'Sterna hirundo', 'Gaviotín Común', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1648, 'Gaviotín Artico', 'Sterna paradisaea', 'Gaviotín Artico', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1649, 'Gaviotín Peruano', 'Sternula lorata', 'Gaviotín Peruano', NULL, 'EN', 'Animalia', NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1650, 'Pastorero Peruano', 'Sturnella bellicosa', 'Pastorero Peruano', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1651, 'Piquero Enmascarado', 'Sula dactylatra', 'Piquero Enmascarado', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1652, 'Piquero de Pata Azul', 'Sula nebouxii', 'Piquero de Pata Azul', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1653, 'Piquero Peruano', 'Sula variegata', 'Piquero Peruano', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1654, 'Chotacabras de Ala Bandeada', 'Systellura longirostris (Caprimulgus longirostris*)', 'Chotacabras de Ala Bandeada', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1655, 'Siete Colores de la Totora', 'Tachuris rubrigastra', 'Siete Colores de la Totora', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1656, 'Albatros de Ceja Negra', 'Thalassarche melanophrys', 'Albatros de Ceja Negra', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1657, 'Albatros de Salvin', 'Thalassarche salvini', 'Albatros de Salvin', NULL, 'VU', 'Animalia', NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1658, 'Colibrí de Cora', 'Thalasseus elegans (Sterna elegans*)', 'Colibrí de Cora', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1659, 'Bandurria Andina', 'Theristicus branickii', 'Bandurria Andina', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1660, 'Bandurria de Cara Negra', 'Theristicus melanopis ', 'Bandurria de Cara Negra', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1661, 'Agachona de Pecho Gris', 'Thinocorus orbignyianus', 'Agachona de Pecho Gris', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1662, 'Agachona Chica', 'Thinocorus rumicivorus', 'Agachona Chica', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1663, 'Perdiz de la Puna', 'Tinamotis pentlandii', 'Perdiz de la Puna', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1664, 'Playero Pata Amarilla Menor', 'Tringa flavipes ', 'Playero Pata Amarilla Menor', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1665, 'Playero Pata Amarilla Mayor', 'Tringa melanoleuca ', 'Playero Pata Amarilla Mayor', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1666, 'Playero de Ala Blanca', 'Tringa semipalmatus (Catoptrophorus semipalmatus*)', 'Playero de Ala Blanca', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1667, 'Playero Solitario', 'Tringa solitaria', 'Playero Solitario', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1668, 'Cucarachero Común', 'Troglodytes aedon', 'Cucarachero Común', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1669, 'Playero Acanelado', 'Tryngites subruficollis', 'Playero Acanelado', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1670, 'Zorzal Chiguanco', 'Turdus chiguanco', 'Zorzal Chiguanco', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1671, 'Lechuza de Campanario', 'Tyto alba', 'Lechuza de Campanario', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1672, 'Bandurrita de Pecho Anteado', 'Upucerthia validirostris (U. jelskii*)', 'Bandurrita de Pecho Anteado', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1673, 'Avefría Andina', 'Vanellus resplendens ', 'Avefría Andina', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1674, 'Semillerito Negro Azulado', 'Volatinia jacarina', 'Semillerito Negro Azulado', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1675, 'Cóndor Andino', 'Vultur gryphus ', 'Cóndor Andino', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1676, 'Azulito Altoandino', 'Xenodacnis parina', 'Azulito Altoandino', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1677, 'Fringilo Apizarrado', 'Xenospingus concolor', 'Fringilo Apizarrado', NULL, 'NT', 'Animalia', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1678, 'Tórtola Orejuda', 'Zenaida auriculata', 'Tórtola Orejuda', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1679, 'Tórtola Melódica', 'Zenaida meloda', 'Tórtola Melódica', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1680, 'Gorrión de Collar Rufo', 'Zonotrichia capensis', 'Gorrión de Collar Rufo', NULL, 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1681, 'Pinco-pinco', 'Ephedra americana Humb. & Bonpl. ex Willd.', 'Pinco-pinco', 'Ephedraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1682, 'Orko-orko', 'Bomarea dulcis (Hook.) Beauverd', 'Orko-orko', 'Alstroemeriaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1683, 'Lenteja de agua', 'Lemna minuta Kunth', 'Lenteja de agua', 'Araceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1684, 'Siempre viva', 'Tillandsia capillaris var. capillaris Ruiz & Pav.', 'Siempre viva', 'Bromeliaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1685, 'Kunkuna, Orccotina, Waricha, Tiña', 'Distichia muscoides Nees & Meyen', 'Kunkuna, Orccotina, Waricha, Tiña', 'Juncaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1686, 'Packo', 'Oxychloe andina Phil.', 'Packo', 'Juncaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1687, 'Bertero', 'Elodea potamogeton (Bertero) Espinosa', 'Bertero', 'Hydrocharitaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1688, 'Pasto', 'Anthochloa lepidula Nees & Meyen', 'Pasto', 'Poaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1689, 'Caña hueca', 'Arundo donax L.', 'Caña hueca', 'Poaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1690, 'Cebadilla', 'Bromus catharticus Vahl', 'Cebadilla', 'Poaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1691, 'Cadillo', 'Cenchrus echinatus L.', 'Cadillo', 'Poaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1692, 'Cortadera', 'Cortaderia jubata (Lemoine) Stapf', 'Cortadera', 'Poaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1693, 'Hierba luisa', 'Cymbopogon citratus (DC.) Stapf', 'Hierba luisa', 'Poaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1694, 'Grama dulce', 'Cynodon dactylon (L.) Pers.', 'Grama dulce', 'Poaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1695, 'Grama salada', 'Distichlis spicata (L.) Greene', 'Grama salada', 'Poaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1696, 'Pajonal', 'Festuca orthophylla Pilg.', 'Pajonal', 'Poaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1697, 'Carrizo', 'Phragmites australis (Cav.) Trin. ex Steud.', 'Carrizo', 'Poaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1698, 'Ichu', 'Jarava ichu (Stipa ichu) (Ruiz & Pav.) Kunth', 'Ichu', 'Poaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1699, 'Sábila', 'Aloe vera  (L.) Burm. f.', 'Sábila', 'Xanthorrhoeaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1700, 'Verdolaga', 'Sesuvium portulacastrum (L.) L.', 'Verdolaga', 'Aizoaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1701, 'Hierba blanca', 'Alternanthera halimifolia (Lam.) Standl. ex', 'Hierba blanca', 'Amaranthaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1702, 'Paico', 'Dysphania ambrioides (Chenopodium ambrosioides*)  L. ', 'Paico', 'Amaranthaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1703, 'Jassi, molle, carzo', 'Haplorhus peruviana Engl.', 'Jassi, molle, carzo', 'Anacardiaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1704, 'Molle', 'Schinus molle L.', 'Molle', 'Anacardiaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1705, 'Yareta, Capo', 'Azorella compacta Phil.', 'Yareta, Capo', 'Apiaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1706, 'Hoja redonda', 'Hydrocotyle bonariensis Lam.', 'Hoja redonda', 'Araliaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1707, 'Espina de perro, Anuchapi', 'Acanthoxanthium spinosum (L.) Fourr.', 'Espina de perro, Anuchapi', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1708, 'Wira wira hembra', 'Achyrocline alata (Kunth) DC.', 'Wira wira hembra', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1709, 'Altamiza', 'Ambrosia arborescens Mill.', 'Altamiza', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1710, 'Ajenjo', 'Artemisia absinthium L.', 'Ajenjo', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1711, 'Tolilla', 'Baccharis boliviensis (Wedd.) Cabrera', 'Tolilla', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1712, 'Kimsaucho, Karkeja', 'Baccharis genistelloides (Lam.) Pers.', 'Kimsaucho, Karkeja', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1713, 'Tola', 'Baccharis incarum (Wedd.) Cuatrec', 'Tola', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1714, 'Altamisa', 'Baccharis peruviana Cuatrec.', 'Altamisa', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1715, 'Chilca, Chara', 'Baccharis petiolata DC', 'Chilca, Chara', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1716, 'Misico', 'Bidens andicola var. andicola Kunth', 'Misico', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1717, 'Cadillo, chiriro', 'Bidens pilosa var pilosa (DC.) Sherff', 'Cadillo, chiriro', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1718, 'Tola blanca', 'Chersodoma jodopappa Cabrera', 'Tola blanca', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1719, 'Guishuara karkataya', 'Chersodoma juanisernii (Cuatrec.) Cuatrec.', 'Guishuara karkataya', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1720, 'Guishuara', 'Chuquiraga rotundifolia Wedd.', 'Guishuara', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1721, 'Kalihua, Kasihua', 'Diplostephium meyenii (Sch. Bip. ex Wedd.) S.F.', 'Kalihua, Kasihua', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1722, 'Matagusano', 'Flaveria bidentis (L.) Kuntze', 'Matagusano', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1723, 'Wira wira macho', 'Gnaphalium dombeyanum DC.', 'Wira wira macho', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1724, 'Chiñe', 'Grindelia glutinosa (Cav.) Mart.', 'Chiñe', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1725, 'Sasawi', 'Leucheria daucifolia (D.Don) Crisci', 'Sasawi', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1726, 'Manzanilla', 'Matricaria recutita L.', 'Manzanilla', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1727, 'Chinchirkuma', 'Mutisia acuminata var. bicolor Cabrera', 'Chinchirkuma', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1728, 'Chilliniza', 'Ophryosporus heptanthus (Sch.Bip. ex Wedd.) R.M.King & H.Rob.', 'Chilliniza', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1729, 'Chancoromo', 'Perezia multiflora (Bonpl.) Less.', 'Chancoromo', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1730, 'Chingoyo, Toñuz', 'Pluchea chingoyo (Kunth) DC.', 'Chingoyo, Toñuz', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1731, 'Taza', 'Proustia berberidifolia (Cuatrec.) Ferreyra', 'Taza', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1732, 'Chachacomo', 'Senecio nutans Sch.Bip.', 'Chachacomo', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1733, 'Canlla', 'Senecio spinosus DC.', 'Canlla', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1734, 'Cerraja, Canacho', 'Sonchus oleraceus L.', 'Cerraja, Canacho', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1735, 'Chicchipa', 'Tagetes multiflora Kunth', 'Chicchipa', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1736, 'Diente de León', 'Taraxacum officinale F.H. Wigg.', 'Diente de León', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1737, 'Pajaro bobo', 'Tessaria integrifolia Ruiz & Pav.', 'Pajaro bobo', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1738, 'Pura pura', 'Xenophyllum poposum (Phil.) V.A.Funk', 'Pura pura', 'Asteraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1739, 'Chuve', 'Tecoma fulva (Cav.) G. Don', 'Chuve', 'Bignoniaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1740, 'Huaranhua', 'Tecoma sambucifolia Kunth', 'Huaranhua', 'Bignoniaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1741, 'Hierba de alacrán', 'Heliotropium curassavicum L.', 'Hierba de alacrán', 'Boraginaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1742, 'Flor de arena', 'Tiquilia paronychioides (Phil.) A.T. Richardson', 'Flor de arena', 'Boraginaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1743, 'Nabo silvestre, Mostaza', 'Brassica rapa subsp. campestris (L.)', 'Nabo silvestre, Mostaza', 'Brassicaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1744, 'Bolsa de pastor', 'Capsella bursa-pastoris (L.) Medik.', 'Bolsa de pastor', 'Brassicaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1745, 'Cani cani', 'Descurainia myriophylla (Willd.) R.E.Fr.', 'Cani cani', 'Brassicaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1746, 'Mata conejo, Anis silvestre', 'Lepidium chichicara Desv.', 'Mata conejo, Anis silvestre', 'Brassicaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1747, 'Cure, coniña', 'Austrocylindropuntia subulata (Muehlenpf.) Backeb.', 'Cure, coniña', 'Cactaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1748, 'Candelabro', 'Browningia candelaris (Meyen) Britton & Rose', 'Candelabro', 'Cactaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1749, 'Cactus', 'Cleistocactus sextonianus (Backeb.) D.R. Hunt', 'Cactus', 'Cactaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1750, 'Curi, Sankallo', 'Corryocactus brevistylus (K. Schum. ex Vaupel) Britton & Rose', 'Curi, Sankallo', 'Cactaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1751, 'Gigantón', 'Neoraimondia arequipensis Backeb.', 'Gigantón', 'Cactaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1752, 'Tuna', 'Opuntia ficus-indica (L.) Mill.', 'Tuna', 'Cactaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1753, 'Pulla-pulla, Puscalla', 'Opuntia ignescens Vaupel', 'Pulla-pulla, Puscalla', 'Cactaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1754, 'Viejito, Abuelito', 'Oreocereus leucotrichus (Phil.) Wagenkn.', 'Viejito, Abuelito', 'Cactaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1755, 'Ayrampo', 'Tunilla soehrensii (Britton & Rose) D.R. Hunt & Iliff', 'Ayrampo', 'Cactaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1756, 'Zapato, Amayzapato', 'Calceolaria inamoena subsp inamoena', 'Zapato, Amayzapato', 'Calceolariaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1757, 'Amayzapato, Zapato zapato', 'Calceolaria inamoena Kraenzl', 'Amayzapato, Zapato zapato', 'Calceolariaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1758, 'Chiñi kururu', 'Hypsela reniformis (Kunth) C.Presl', 'Chiñi kururu', 'Campanulaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1759, 'Mito, Papaya silvestre', 'Vasconcellea candicans (A. Gray) (Carica candicans)', 'Mito, Papaya silvestre', 'Caricaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1760, 'Arenilla', 'Arenaria serpens Kunth', 'Arenilla', 'Caryophyllaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1761, 'Almohadilla, Huaricuca', 'Pycnophyllum molle Remy', 'Almohadilla, Huaricuca', 'Caryophyllaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1762, 'Casuarina', 'Casuarina equisetifolia J.R. Forst. & G. Forst.', 'Casuarina', 'Casuarinaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1763, 'Chalsa', 'Escallonia angustifolia C. Presl', 'Chalsa', 'Escalloniaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1764, 'Higuerilla', 'Ricinus communis L.', 'Higuerilla', 'Euphorbiaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1765, 'Aromo', 'Vachellia aroma (Acacia aroma)Gillies ex Hook. & Arn.', 'Aromo', 'Fabaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1766, 'Yaro, Huarango', 'Vachellia huarango (Acacia huarango) Ruiz ex J.F. Macbr.', 'Yaro, Huarango', 'Fabaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1767, 'Faique', 'Vachellia macracantha Humb. & Bonpl. ex Willd. (Acacia macracantha)', 'Faique', 'Fabaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1768, 'Vilca', 'Vachellia visco (Acacia visco) Lorentz ex Griseb.', 'Vilca', 'Fabaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1769, 'Kanllia hembra', 'Adesmia spinosissima Meyen ex Vogel', 'Kanllia hembra', 'Fabaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1770, 'Tara', 'Tara spinosa Feuillée ex Molina (Caesalpinia spinosa Kuntze)', 'Tara', 'Fabaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1771, 'Chañar', 'Geoffroea decorticans (Gillies ex Hook. & Arn.) Burkart', 'Chañar', 'Fabaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1772, 'Arabisca, Valquilla, Aroma blanco', 'Leucaena leucocephala (Lam.) de Wit', 'Arabisca, Valquilla, Aroma blanco', 'Fabaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1773, 'Quela', 'Lupinus pinguis Ulbr.', 'Quela', 'Fabaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1774, 'Trebol', 'Medicago polymorpha L.', 'Trebol', 'Fabaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1775, 'Alfalfilla', 'Melilotus albus Medik.', 'Alfalfilla', 'Fabaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1776, 'Pulén de tomar', 'Otholobium pubescens (Poir.)J.W. Grimes', 'Pulén de tomar', 'Fabaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1777, 'Azote de cristo', 'Parkinsonia aculeata L', 'Azote de cristo', 'Fabaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1778, 'Algarrobo chileno', 'Prosopis chilensis (Molina) Stuntz', 'Algarrobo chileno', 'Fabaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1779, 'Algarrobo cultivado', 'Prosopis pallida (Humb. & Bonpl. ex Willd.) Kunth', 'Algarrobo cultivado', 'Fabaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1780, 'Retama', 'Spartium junceum L.', 'Retama', 'Fabaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1781, 'Llacho', 'Myriophyllum elatinoides Gaudich.', 'Llacho', 'Haloragaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1782, 'Llacho, Chchinqui', 'Myriophyllum quitense Kunth', 'Llacho, Chchinqui', 'Haloragaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1783, 'Lipi lipi', 'Krameria lappacea (Dombey) Burdet & B.B. Simpson', 'Lipi lipi', 'Krameriaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1784, 'Matico', 'Marrubium vulgare L.', 'Matico', 'Lamiaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1785, 'Menta', 'Mentha aquatica L. (piperita)', 'Menta', 'Lamiaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1786, 'Orégano', 'Origanum vulgare L.', 'Orégano', 'Lamiaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1787, 'Muña', 'Satureja boliviana (Benth.) Briq.', 'Muña', 'Lamiaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1788, 'Ortiga colorada', 'Caiophora andina Urb. & Gilg', 'Ortiga colorada', 'Loasaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1789, 'Ortiga macho', 'Caiophora pentlandii (Paxton ex Graham) G. Don ex Loudon', 'Ortiga macho', 'Loasaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1790, 'Sulta sulta, Sueda sueda', 'Ligaria cuneifolia (Ruiz & Pav.) Tiegh.', 'Sulta sulta, Sueda sueda', 'Loranthaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1791, 'Algodón', 'Gossypium barbadense L.', 'Algodón', 'Malvaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1792, 'Malva', 'Malva parviflora L.', 'Malva', 'Malvaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1793, 'Aldia', 'Nototriche argentea A.W. Hill', 'Aldia', 'Malvaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1794, 'Palo blanco', 'Tarasa operculata (Cav.) Krapov', 'Palo blanco', 'Malvaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1795, 'Membrillejo, Palo negro', 'Waltheria ovata Cav.', 'Membrillejo, Palo negro', 'Malvaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1796, 'Eucalipto', 'Eucalyptus camaldulensis Dehnh.', 'Eucalipto', 'Myrtaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1797, 'Layo', 'Epilobium denticulatum Ruiz & Pav.', 'Layo', 'Onagraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1798, 'Chupa sangre', 'Oenothera rosea L\'Hér. ex Aiton', 'Chupa sangre', 'Onagraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1799, 'Cardosanto', 'Argemone subfusiformis G.B. Ownbey', 'Cardosanto', 'Papaveraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1800, 'Lampayo', 'Malesherbia turbinea J.F. Macbr', 'Lampayo', 'Passifloraceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1801, 'Berro, Occocolo', 'Mimulus glabratus Kunth', 'Berro, Occocolo', 'Phrymaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1802, 'Llantén', 'Plantago lanceolata L.', 'Llantén', 'Plantaginaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1803, 'Catuta, Flor del Inca, Cando', 'Cantua buxifolia Juss. ex Lam.', 'Catuta, Flor del Inca, Cando', 'Polemoniaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1804, 'Mora', 'Muehlenbeckia hastulata (Sm.) I.M. Johnst.', 'Mora', 'Polygonaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1805, 'Lengua de vaca, romasa', 'Rumex cuneifolius Campd.', 'Lengua de vaca, romasa', 'Polygonaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1806, 'Libro-libro', 'Alchemilla diplophylla Diels', 'Libro-libro', 'Rosaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1807, 'Lloque', 'Kageneckia lanceolata Ruiz & Pav.', 'Lloque', 'Rosaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1808, 'Queñoa', 'Polylepis rugulosa Bitter', 'Queñoa', 'Rosaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1809, 'Kanllia macho', 'Tetraglochin cristatum (Britton) Rothm.', 'Kanllia macho', 'Rosaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1810, 'Alamo italiano', 'Populus nigra  L.', 'Alamo italiano', 'Salicaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1811, 'Sauce', 'Salix humboldtiana Willd.', 'Sauce', 'Salicaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1812, 'Safrán', 'Buddleja coriacea Remy', 'Safrán', 'Scrophulariaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1813, 'Hierba santa', 'Cestrum auriculatum L\'Hér.', 'Hierba santa', 'Solanaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1814, 'Chamico', 'Datura stramonium L.', 'Chamico', 'Solanaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1815, 'Yara', 'Dunalia spinosa (Meyen) Dammer', 'Yara', 'Solanaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1816, 'Quilla', 'Fabiana stephanii Hunz. & Barboza', 'Quilla', 'Solanaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1817, 'Tomatillo', 'Lycopersicon chilense Dunal', 'Tomatillo', 'Solanaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1818, 'Tabaquillo', 'Nicotiana glauca Graham', 'Tabaquillo', 'Solanaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1819, 'Tabaco cimarrón', 'Nicotiana paniculata L.', 'Tabaco cimarrón', 'Solanaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1820, 'Nolana', 'Nolana adansonii (Roem. &Schult.) I.M. Johnst.', 'Nolana', 'Solanaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1821, 'Ortiga hembra', 'Urtica magellanica Juss. ex Poir.', 'Ortiga hembra', 'Urticaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1822, 'Cedrón', 'Aloysia triphylla Royle', 'Cedrón', 'Verbenaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1823, 'Tiquil tiquil', 'Lippia nodiflora (L.) Michx.', 'Tiquil tiquil', 'Verbenaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1824, 'Cipres', 'Cupressus sempervirens L.', 'Cipres', 'Cupressaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1825, 'Pino', 'Pinus radiata D. Don', 'Pino', 'Pinaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1826, 'Cola de caballo', 'Equisetum giganteum L.', 'Cola de caballo', 'Equisetaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1827, 'Helecho', 'Thelypteris cheilanthoides (Kunze) Proctor', 'Helecho', 'Thelypteridaceae', 'NE', 'Animalia', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31'),
	(1828, 'PLanta nueva', 'Epecia Nueva', 'PLanta nueva', 'INformacion eetc', 'LC', 'Animalia', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 19:49:31', '2025-08-26 19:49:31');

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.biodiversity_category_publication: ~10 rows (aproximadamente)
INSERT INTO `biodiversity_category_publication` (`id`, `biodiversity_category_id`, `publication_id`, `relevant_excerpt`, `page_reference`, `created_at`, `updated_at`) VALUES
	(1, 1, 39, 'Las poblaciones de oso de anteojos en el Perú se estiman entre 18,000-20,000 individuos, distribuidos principalmente en los bosques nublados de la cordillera oriental.', 'pp. 15-18', '2025-08-25 21:59:08', '2025-08-25 21:59:08'),
	(2, 2, 40, 'El Perú alberga aproximadamente 208,899 vicuñas según el último censo nacional, representando el 80% de la población mundial de esta especie.', 'p. 23', '2025-08-25 21:59:08', '2025-08-25 21:59:08'),
	(3, 3, 41, 'La densidad de jaguares en la Amazonía peruana varía entre 2-8 individuos por 100 km², siendo mayor en áreas protegidas como el Parque Nacional Manu.', 'pp. 8-12', '2025-08-25 21:59:08', '2025-08-25 21:59:08'),
	(4, 11, 42, 'El Gallito de las Rocas es endémico de los Andes tropicales y su población en el Perú se considera estable, aunque amenazada por la deforestación.', 'pp. 45-50', '2025-08-25 21:59:08', '2025-08-25 21:59:08'),
	(5, 12, 43, 'La población de cóndores en el Perú se estima en 2,500-3,000 individuos, siendo una de las poblaciones más importantes de la especie.', 'pp. 12-15', '2025-08-25 21:59:08', '2025-08-25 21:59:08'),
	(6, 13, 44, 'Loddigesia mirabilis es endémica de los valles del río Utcubamba en Amazonas, con una población estimada de menos de 1,000 individuos.', 'pp. 78-82', '2025-08-25 21:59:08', '2025-08-25 21:59:08'),
	(7, 5, 45, 'Las poblaciones de nutria gigante en el Perú se concentran principalmente en Madre de Dios y Loreto, con grupos familiares de 3-8 individuos.', 'pp. 34-38', '2025-08-25 21:59:08', '2025-08-25 21:59:08'),
	(8, 27, 46, 'El paiche puede alcanzar hasta 3 metros de longitud y 200 kg de peso, siendo uno de los peces de agua dulce más grandes del mundo.', 'pp. 67-72', '2025-08-25 21:59:08', '2025-08-25 21:59:08'),
	(9, 28, 47, 'Los delfines rosados en el Perú habitan principalmente en los ríos Amazonas, Ucayali y Marañón, con poblaciones estimadas en 15,000-20,000 individuos.', 'pp. 23-28', '2025-08-25 21:59:08', '2025-08-25 21:59:08'),
	(10, 29, 48, 'Puya raimondii puede vivir hasta 100 años y alcanzar 12 metros de altura durante su única floración, siendo endémica de la cordillera Blanca.', 'pp. 156-162', '2025-08-25 21:59:08', '2025-08-25 21:59:08');

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

-- Volcando datos para la tabla biodiversity_management.cache: ~2 rows (aproximadamente)
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
	('laravel-cache-site_visit_stats', 'a:5:{s:12:"total_visits";i:0;s:15:"unique_visitors";i:0;s:12:"today_visits";i:0;s:16:"this_week_visits";i:0;s:17:"this_month_visits";i:0;}', 1756158529),
	('laravel-cache-top_pages_10', 'a:0:{}', 1756158829);

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
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `overlay_image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
INSERT INTO `hero_slider_images` (`id`, `title`, `description`, `alt_text`, `image_path`, `overlay_image_path`, `button_text`, `button_url`, `sort_order`, `is_active`, `has_overlay_image`, `overlay_position`, `overlay_alt_text`, `overlay_description`, `overlay_button_text`, `overlay_button_url`, `created_at`, `updated_at`) VALUES
	(1, 'FOTO1', 'FOTO1 DESCR', 'fotosssssssssss', NULL, NULL, 'lo maximo', NULL, 0, 1, 1, 'right', 'Gobierno Regional de Tacna', 'Es un portal muy interesante', NULL, NULL, '2025-07-31 21:53:13', '2025-08-01 22:19:34'),
	(2, 'FOTO 2', 'FFFFFFFFFFF', 'FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF', NULL, NULL, 'FFFFFFFFF', NULL, 1, 1, 0, 'left', NULL, NULL, NULL, NULL, '2025-07-31 22:02:10', '2025-07-31 22:02:10');

-- Volcando estructura para tabla biodiversity_management.home_content
CREATE TABLE IF NOT EXISTS `home_content` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `home_content_section_key_unique` (`section`,`key`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.home_content: ~33 rows (aproximadamente)
INSERT INTO `home_content` (`id`, `section`, `key`, `value`, `type`, `image_path`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
	(1, 'hero', 'title', 'Descubre la Riqueza de la Biodiversidad', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(2, 'hero', 'subtitle', 'Explora nuestra extensa base de datos de especies, ecosistemas y publicaciones científicas. México es uno de los países con mayor biodiversidad del mundo, hogar de miles de especies únicas.', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(3, 'hero', 'button_primary_text', 'Explorar Especies', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(4, 'hero', 'button_primary_url', '/biodiversity', 'url', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(5, 'hero', 'button_secondary_text', 'Publicaciones', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(6, 'hero', 'button_secondary_url', '/publications', 'url', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(7, 'hero', 'hero_image', 'fas fa-globe-americas', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(8, 'search', 'title', '¿Qué especie buscas?', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(9, 'search', 'subtitle', 'Busca entre miles de especies registradas en nuestro sistema', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(10, 'search', 'placeholder', 'Buscar por nombre común o científico...', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(11, 'stats', 'title', 'Nuestra Biodiversidad en Números', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(12, 'stats', 'categories_title', 'Categorías de Especies', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(13, 'stats', 'categories_description', 'Diferentes grupos taxonómicos registrados', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(14, 'stats', 'publications_title', 'Publicaciones Científicas', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(15, 'stats', 'publications_description', 'Investigaciones y estudios disponibles', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(16, 'stats', 'endangered_title', 'Especies en Peligro', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(17, 'stats', 'endangered_description', 'Requieren protección especial', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(18, 'stats', 'critical_title', 'En Peligro Crítico', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(19, 'stats', 'critical_description', 'Situación de conservación crítica', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(20, 'featured', 'title', 'Especies Destacadas', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(21, 'featured', 'view_all_text', 'Ver Todas las Especies', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(22, 'publications', 'title', 'Publicaciones Científicas Recientes', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(23, 'publications', 'view_all_text', 'Ver Todas las Publicaciones', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(24, 'cta', 'title', 'Contribuye a la Conservación', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(25, 'cta', 'description', 'La biodiversidad es un tesoro que debemos proteger. Únete a nuestros esfuerzos de conservación e investigación.', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(26, 'cta', 'button_primary_text', 'Colaborar', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(27, 'cta', 'button_primary_url', '#', 'url', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(28, 'cta', 'button_secondary_text', 'Descargar Datos', 'text', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(29, 'cta', 'button_secondary_url', '#', 'url', NULL, 1, 0, '2025-07-31 21:26:16', '2025-07-31 21:26:16'),
	(30, 'hero', 'use_image_slider', 'true', 'text', NULL, 1, 1, '2025-07-31 21:39:51', '2025-08-05 02:46:23'),
	(31, 'hero', 'slider_autoplay', 'true', 'text', NULL, 1, 2, '2025-07-31 21:39:51', '2025-07-31 21:58:44'),
	(32, 'hero', 'slider_interval', '5000', 'text', NULL, 1, 3, '2025-07-31 21:39:51', '2025-07-31 21:58:44'),
	(33, 'hero', 'enable_icons', 'true', 'text', NULL, 1, 4, '2025-07-31 21:39:51', '2025-07-31 21:58:44');

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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
	(23, 'App\\Models\\HeroSliderImage', 1, '1da7a374-9c94-47ab-8229-83f06ae2935e', 'overlay_images', 'logo_mesomi_bn', 'logo_mesomi_bn.png', 'image/png', 'public', 'public', 100173, '[]', '[]', '[]', '[]', 2, '2025-08-01 21:58:05', '2025-08-01 21:58:05'),
	(24, 'App\\Models\\Publication', 36, 'a8e8c1b3-bd5d-42e1-bc39-45551cf60624', 'pdfs', '(1)', '(1).pdf', 'application/pdf', 'public', 'public', 384729, '[]', '[]', '[]', '[]', 1, '2025-08-20 20:52:05', '2025-08-20 20:52:05'),
	(25, 'App\\Models\\Publication', 35, '5dc98eb1-47a2-469e-a4f9-bcbfd4685ee2', 'pdfs', '(2)', '(2).pdf', 'application/pdf', 'public', 'public', 1115460, '[]', '[]', '[]', '[]', 1, '2025-08-20 20:55:08', '2025-08-20 20:55:08');

-- Volcando estructura para tabla biodiversity_management.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.migrations: ~30 rows (aproximadamente)
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
	(25, '2025_08_18_152003_add_idreino_to_clases_table', 15),
	(26, '2025_01_21_000001_add_image_path_to_home_content_table', 16),
	(27, '2025_08_25_145201_add_additional_image_paths_to_biodiversity_categories_table', 17),
	(28, '2025_08_25_163950_add_familia_and_publications_to_biodiversity_categories', 18),
	(29, '2025_08_25_190455_remove_publication_fields_from_biodiversity_categories_table', 19),
	(30, '2025_08_25_213306_create_page_visits_table', 20),
	(31, '2025_08_26_134747_migrate_publication_data_before_removal', 21);

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

-- Volcando estructura para tabla biodiversity_management.page_visits
CREATE TABLE IF NOT EXISTS `page_visits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_visits_url_created_at_index` (`url`,`created_at`),
  KEY `page_visits_ip_address_index` (`ip_address`),
  KEY `page_visits_user_id_foreign` (`user_id`),
  CONSTRAINT `page_visits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.page_visits: ~0 rows (aproximadamente)

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
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.publications: ~10 rows (aproximadamente)
INSERT INTO `publications` (`id`, `title`, `abstract`, `publication_year`, `author`, `journal`, `doi`, `pdf_path`, `created_at`, `updated_at`) VALUES
	(39, 'Conservación del Oso de Anteojos en los Andes Peruanos: Estrategias y Desafíos', 'Estudio integral sobre las poblaciones de Tremarctos ornatus en el Perú, analizando su distribución, amenazas principales y estrategias de conservación implementadas en diferentes áreas protegidas.', '2023', 'Peyton, B. & García, M.', 'Ursus - International Association for Bear Research', '10.2192/URSUS-D-22-00015', NULL, '2025-08-25 21:59:08', '2025-08-25 21:59:08'),
	(40, 'Manejo Sostenible de la Vicuña en las Comunidades Altoandinas del Perú', 'Análisis del programa de manejo sostenible de vicuñas en el Perú, evaluando el impacto socioeconómico y de conservación de la esquila en vivo en comunidades campesinas.', '2022', 'Lichtenstein, G. & Vilá, B.', 'Journal of Arid Environments', '10.1016/j.jaridenv.2022.104567', NULL, '2025-08-25 21:59:08', '2025-08-25 21:59:08'),
	(41, 'Jaguar en la Amazonía Peruana: Ecología y Conservación', 'Investigación sobre la ecología del jaguar en la Amazonía peruana, incluyendo patrones de movimiento, uso de hábitat y conflictos con actividades humanas.', '2023', 'Tobler, M. & De Angelo, C.', 'Oryx - The International Journal of Conservation', '10.1017/S0030605322001119', NULL, '2025-08-25 21:59:08', '2025-08-25 21:59:08'),
	(42, 'El Gallito de las Rocas: Ave Nacional del Perú y su Conservación', 'Estudio sobre la biología reproductiva y estado de conservación del Gallito de las Rocas en los bosques nublados del Perú.', '2022', 'Schulenberg, T. & Pequeño, T.', 'Neotropical Ornithology', '10.58843/ornneo.v33i1.892', NULL, '2025-08-25 21:59:08', '2025-08-25 21:59:08'),
	(43, 'Cóndor Andino: Símbolo de los Andes y Estrategias de Recuperación', 'Análisis de las poblaciones de Cóndor Andino en Sudamérica, con énfasis en los programas de reproducción en cautiverio y reintroducción en el Perú.', '2023', 'Lambertucci, S. & Wallace, R.', 'Bird Conservation International', '10.1017/S0959270923000072', NULL, '2025-08-25 21:59:08', '2025-08-25 21:59:08'),
	(44, 'Colibrí Cola de Espátula: Endemismo y Conservación en Amazonas, Perú', 'Investigación sobre la ecología y conservación del Colibrí Cola de Espátula, especie endémica del norte del Perú.', '2022', 'Fjeldså, J. & Krabbe, N.', 'Journal of Ornithology', '10.1007/s10336-022-01987-3', NULL, '2025-08-25 21:59:08', '2025-08-25 21:59:08'),
	(45, 'Nutria Gigante en la Amazonía: Ecología Acuática y Amenazas', 'Estudio sobre la ecología de la nutria gigante en los sistemas acuáticos de la Amazonía peruana y las principales amenazas para su conservación.', '2023', 'Groenendijk, J. & Duplaix, N.', 'Aquatic Mammals', '10.1578/AM.49.2.2023.156', NULL, '2025-08-25 21:59:08', '2025-08-25 21:59:08'),
	(46, 'Paiche: Gigante de la Amazonía y su Manejo Sostenible', 'Análisis del manejo sostenible del paiche en la Amazonía peruana, incluyendo programas de acuicultura y pesca regulada.', '2022', 'Castello, L. & Stewart, D.', 'Reviews in Fish Biology and Fisheries', '10.1007/s11160-022-09715-4', NULL, '2025-08-25 21:59:08', '2025-08-25 21:59:08'),
	(47, 'Delfín Rosado del Amazonas: Ecología y Conservación en Aguas Peruanas', 'Investigación sobre la distribución y comportamiento del delfín rosado en los ríos de la Amazonía peruana.', '2023', 'Gómez-Salazar, C. & Trujillo, F.', 'Marine Mammal Science', '10.1111/mms.12989', NULL, '2025-08-25 21:59:08', '2025-08-25 21:59:08'),
	(48, 'Puya Raimondi: La Bromeliacea Gigante de los Andes Peruanos', 'Estudio sobre la ecología y conservación de Puya raimondii, la bromeliacea más grande del mundo, endémica de los Andes peruanos.', '2022', 'Young, K. & León, B.', 'Plant Ecology & Diversity', '10.1080/17550874.2022.2089456', NULL, '2025-08-25 21:59:08', '2025-08-25 21:59:08');

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

-- Volcando datos para la tabla biodiversity_management.sessions: ~6 rows (aproximadamente)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('AISxDoccGZy2OyELYnQa1zvjS83QjYmdWLQn9JWq', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiZlFLTnZ6ZENGYkRlWDI4Q0tLWlhEWEUwbGxWeksxbkpyVkx0TVg2SyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9iaW9kaXZlcnNpdHkiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjM6InVybCI7YTowOnt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NTYxNDA4MTA7fX0=', 1756151335),
	('ejzsTgwYqWPuoMalb6Q6HAhHSZ0eUVkjwIQaJ1n2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Trae/1.100.3 Chrome/132.0.6834.210 Electron/34.5.1 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoid21ucTNKUjI2SzFMZnJMZFg4bVllVHFkbkc4NmxiVVQ1aThnVVkwUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Nzk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9iaW9kaXZlcnNpdHk/aWRlX3dlYnZpZXdfcmVxdWVzdF90aW1lPTE3NTYxNTEzMzY1ODkiO319', 1756157847),
	('J0d5PDeTMQVnMqvusi1EltO9smsJHTFADZi17frp', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR0ZUaFgwSm9pMjk2RFVqRVYwTHF4TFhISUVWT2RqSFRSbmZsd3ZnViI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iaW9kaXZlcnNpdHkvNTUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1756135211),
	('nktwNWPGqXKxRaahyBeo2Xs1SsZv32Ow2bTVBd8j', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQWs1cFBNajFBYk5BQ1Q0VHZuUzdsZEVRZlBWdTFHcjBlZm9XUWFyciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iaW9kaXZlcnNpdHkvNTUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1756134951),
	('ptJOU4vF3h0ifV5YMmZ9qpG20l0LjVYR7OOPytHQ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Trae/1.100.3 Chrome/132.0.6834.210 Electron/34.5.1 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTmhXTDFEMUxTM2U1clBGVVAyWjhWenEyN3liNXdtc1NkeXFwbHNZWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iaW9kaXZlcnNpdHkiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1756157847),
	('q16DU59v5StJm6PRq1Vb1V35mHMF5fTN2wr0nBuD', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiQU0wWjd0S3d5d29JVnFVeVhIbzc4MDljdmk5Vmt2bExrUFdxR0ptWCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9iaW9kaXZlcnNpdHkiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjM6InVybCI7YTowOnt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NTYxMjcwMTY7fX0=', 1756159100);

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
	(3, 'main_menu', '[{"text":"Inicio","url":"\\/","order":"1","parent_id":null,"is_active":"1"},{"text":"Biodiversidad","url":"\\/biodiversity","order":"2","parent_id":null,"is_active":"1"},{"text":"Publicaciones","url":"\\/publications","order":"3","parent_id":null,"is_active":"1"},{"text":"Panel Admin","url":"\\/admin","order":"4","parent_id":null,"is_active":"1"}]', 'general', 'text', NULL, NULL, NULL, 0, '2025-08-01 21:40:02', '2025-08-20 02:07:29');

-- Volcando estructura para tabla biodiversity_management.temp_migration_log
CREATE TABLE IF NOT EXISTS `temp_migration_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `operation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `success` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.temp_migration_log: ~1 rows (aproximadamente)
INSERT INTO `temp_migration_log` (`id`, `operation`, `details`, `success`, `created_at`, `updated_at`) VALUES
	(1, 'no_migration_needed', 'Los campos de publicación ya no existen en biodiversity_categories', 1, '2025-08-26 18:48:33', '2025-08-26 18:48:33');

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla biodiversity_management.users: ~4 rows (aproximadamente)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Admin', 'admin@example.com', NULL, '$2y$12$cBxeeGgTIK7X1.aws1g7Nu/1HSAbTxUY2osSkj.V/FicGEOkKHsoa', 'cBoFrlUGlYljoScxM5FfukWPVtgRWXJzv1HJ36KcZ0kwgVKBAzJSWRCmRgoY', '2025-07-30 21:01:34', '2025-07-30 21:01:34'),
	(3, 'Admin Test', 'admin@test.com', NULL, '$2y$12$6wUeofjkU5wdUASyEtZyeeUL4xj4vOFK53IRP4MG.fvACbUSt7iEq', NULL, '2025-08-18 18:16:48', '2025-08-20 01:15:04'),
	(5, 'Administrador Principal', 'admin@biodiversidad.com', '2025-08-20 00:43:48', '$2y$12$c9B/gRr8aS3pPcH/oWxO1eeIn89QUku3S3.inTiigzXocVR6Q5ju2', NULL, '2025-08-20 00:43:48', '2025-08-20 00:43:48'),
	(6, 'Usuario de Prueba', 'usuario@biodiversidad.com', '2025-08-20 00:43:48', '$2y$12$HUXKaP8Ot9Q8nt/bGiA6B.AyW1Y7WYTBbo6MVzIhVrKkOVSaCjHn.', NULL, '2025-08-20 00:43:48', '2025-08-20 00:43:48'),
	(7, 'Test User', 'test@example.com', '2025-08-20 00:43:48', '$2y$12$o81ysmsVLpOX/YB7LzTbr.sv4ftCAGWoh3bm/vVoLOMSRlhHvxB8C', NULL, '2025-08-20 00:43:48', '2025-08-20 00:43:48');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
