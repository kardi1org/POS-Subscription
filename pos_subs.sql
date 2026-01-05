/*
SQLyog Professional v12.09 (64 bit)
MySQL - 10.4.21-MariaDB : Database - pos_subs
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`pos_subs` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `pos_subs`;

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `membership_users` */

DROP TABLE IF EXISTS `membership_users`;

CREATE TABLE `membership_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pricing_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `userpassword` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` enum('admin','kasir') COLLATE utf8mb4_unicode_ci NOT NULL,
  `db_database` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `db_host` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `db_port` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `db_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `db_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `membership_users_email_unique` (`email`),
  KEY `membership_users_pricing_id_foreign` (`pricing_id`),
  CONSTRAINT `membership_users_pricing_id_foreign` FOREIGN KEY (`pricing_id`) REFERENCES `pricings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `membership_users` */

insert  into `membership_users`(`id`,`pricing_id`,`name`,`email`,`userpassword`,`level`,`db_database`,`db_host`,`db_port`,`db_username`,`db_password`,`created_at`,`updated_at`) values (43,19,'test lagi','test@gmail.com','$2y$10$3Ml6OIFinGAtstq9YtRwDOgJYP6/QSil5ZkFmQslJd2ogjHKr1eZ.','kasir','db_pos1','127.0.0.1','3306','root','eyJpdiI6IlB3NWFJYVlkWk1sekNtVjNTamFyRXc9PSIsInZhbHVlIjoiRmF3R3dTK2E5ZkN3SnFqQU8wUGVyZz09IiwibWFjIjoiYTQ2ODFmNDM0NzMwMGRjMGIwNGQzY2Y1YWJmYjdiYjJiZWNjYzE3YmNlM2I4YmIyYzZiZTJhOTM0ZGEzNmM4YSIsInRhZyI6IiJ9','2025-12-22 11:16:32','2025-12-22 11:16:32');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2014_10_12_100000_create_password_resets_table',1),(4,'2019_08_19_000000_create_failed_jobs_table',1),(5,'2019_12_14_000001_create_personal_access_tokens_table',1),(6,'2025_09_25_034102_create_pricings_table',1),(7,'2025_09_26_015924_add_bukti_transfer_to_pricings_table',1),(8,'2025_09_26_041158_add_is_admin_to_users_table',1),(9,'2025_09_26_064147_add_role_to_users_table',1),(10,'2025_10_06_073050_add_masa_aktif_to_pricings_table',1),(11,'2025_10_10_065826_create_renewals_table',1),(12,'2025_10_10_100839_add_reminder_sent_at_to_pricings_table',1),(13,'2025_10_14_033046_create_packages_table',1),(14,'2025_10_14_035544_add_package_columns_to_renewals_table',1),(15,'2025_10_15_030351_add_payment_fields_to_renewals_table',1),(16,'2025_10_21_043949_add_harga_paket_and_durasi_to_pricings_table',1),(17,'2025_10_29_024606_add_host_db_to_users_table',1),(18,'2025_11_03_064903_create_membership_users_table',1),(19,'2025_11_03_085013_add_db_credentials_to_membership_users_table',2),(20,'2025_11_03_085318_add_db_credentials_to_membership_users_table',3),(21,'2025_12_22_104236_change_password_columns_to_text',4);

/*Table structure for table `packages` */

DROP TABLE IF EXISTS `packages`;

CREATE TABLE `packages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `packages` */

insert  into `packages`(`id`,`name`,`price`,`created_at`,`updated_at`) values (1,'Basic','300000.00',NULL,NULL),(2,'Pro','600000.00',NULL,NULL),(3,'Premium','900000.00',NULL,NULL);

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `personal_access_tokens` */

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `personal_access_tokens` */

/*Table structure for table `pricings` */

DROP TABLE IF EXISTS `pricings`;

CREATE TABLE `pricings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codepaket` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `namapaket` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga_paket` decimal(15,2) DEFAULT NULL,
  `durasi` int(11) DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Pending','Active','Nonaktif','Waiting Approval','Aktif') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `bukti_transfer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `reminder_sent_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `pricings` */

insert  into `pricings`(`id`,`email`,`codepaket`,`namapaket`,`harga_paket`,`durasi`,`notes`,`status`,`created_at`,`updated_at`,`bukti_transfer`,`start_date`,`end_date`,`reminder_sent_at`) values (15,'mirzamuhamad.dash@gmail.com','2','Pro','600000.00',1,NULL,'Aktif','2025-12-18 15:20:07','2025-12-18 16:17:30','bukti-transfer/Ko10FK1dCyRm2rhyrLMu9CKsVhBPgn8RP6PSVBkN.jpg','2025-12-18','2026-01-17',NULL),(19,'kardi.oke@gmail.com','1','Basic','300000.00',1,NULL,'Aktif','2025-12-22 10:37:56','2025-12-22 10:48:10','bukti-transfer/DIZFrGKLW87YoV3Q98EXs7GlTJpUB00HQjYB5SoA.jpg','2025-12-22','2026-01-21',NULL),(20,'bener.adem@gmail.com','2','Pro','600000.00',1,NULL,'Waiting Approval','2025-12-22 11:01:13','2025-12-22 11:01:22','bukti-transfer/JncyiL5O55IwRUPPU0fb3gJrOsTcAIA1hP799gRN.jpg',NULL,NULL,NULL);

/*Table structure for table `renewals` */

DROP TABLE IF EXISTS `renewals`;

CREATE TABLE `renewals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pricing_id` bigint(20) unsigned NOT NULL,
  `duration` int(11) NOT NULL,
  `total_price` decimal(15,2) DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting approval',
  `bukti_transfer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_end_date` date DEFAULT NULL,
  `new_end_date` date NOT NULL,
  `approved_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `old_package` bigint(20) unsigned DEFAULT NULL,
  `new_package` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `renewals_pricing_id_foreign` (`pricing_id`),
  CONSTRAINT `renewals_pricing_id_foreign` FOREIGN KEY (`pricing_id`) REFERENCES `pricings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `renewals` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `db_host` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `db_port` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `db_database` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `db_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `db_password` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`is_admin`,`email_verified_at`,`password`,`remember_token`,`db_host`,`db_port`,`db_database`,`db_username`,`db_password`,`created_at`,`updated_at`,`role`) values (1,'Kardi','kardi.oke@gmail.com',0,'2025-11-03 07:29:39','$2y$10$hXYMj2rZMnSeK04kZ6hFMe1ry9XK6IfRlF97.OqOl0mmcuui/f.lW',NULL,'127.0.0.1','3306','db_pos1','root','eyJpdiI6IlB3NWFJYVlkWk1sekNtVjNTamFyRXc9PSIsInZhbHVlIjoiRmF3R3dTK2E5ZkN3SnFqQU8wUGVyZz09IiwibWFjIjoiYTQ2ODFmNDM0NzMwMGRjMGIwNGQzY2Y1YWJmYjdiYjJiZWNjYzE3YmNlM2I4YmIyYzZiZTJhOTM0ZGEzNmM4YSIsInRhZyI6IiJ9','2025-11-03 07:27:55','2025-12-22 10:48:10','admin'),(2,'Kardi','bener.adem@gmail.com',0,'2025-11-03 07:31:37','$2y$10$qwJ9sw60XvePHhmm7ho2COjhVum3USSWQcpQm1400ryNn19Wyg9Ci',NULL,'127.0.0.1','3306','triangle_pos1','root',NULL,'2025-11-03 07:31:20','2025-11-03 07:31:37','user'),(3,'Kardi Sanjaya','mirzamuhamad.dash@gmail.com',0,'2025-12-18 15:18:15','$2y$10$/5cJBOQ/roOT4SHOSEPRfujNNUbCPvCc5aonJ2QLKKzcylsJw/kwi',NULL,'127.0.0.1',NULL,'db_pos1','root',NULL,'2025-12-18 15:07:39','2025-12-18 16:17:30','user');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
