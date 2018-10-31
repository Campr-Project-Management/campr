-- MySQL dump 10.13  Distrib 5.7.21, for Linux (x86_64)
--
-- Host: localhost    Database: campr_welcometocampr
-- ------------------------------------------------------
-- Server version	5.7.21-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `assignment`
--

DROP TABLE IF EXISTS `assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assignment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `work_package_id` int(11) DEFAULT NULL,
  `work_package_project_work_cost_type_id` int(11) DEFAULT NULL,
  `percent_work_complete` int(11) NOT NULL DEFAULT '0',
  `milestone` int(11) NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `started_at` datetime DEFAULT NULL,
  `finished_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `external_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_30C544BA9F75D7B0` (`external_id`),
  KEY `IDX_30C544BAEF2F062C` (`work_package_id`),
  KEY `IDX_30C544BA8E25B2F5` (`work_package_project_work_cost_type_id`),
  CONSTRAINT `FK_30C544BA8E25B2F5` FOREIGN KEY (`work_package_project_work_cost_type_id`) REFERENCES `work_package_project_work_cost_type` (`id`),
  CONSTRAINT `FK_30C544BAEF2F062C` FOREIGN KEY (`work_package_id`) REFERENCES `work_package` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assignment`
--

LOCK TABLES `assignment` WRITE;
/*!40000 ALTER TABLE `assignment` DISABLE KEYS */;
/*!40000 ALTER TABLE `assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `automailer`
--

DROP TABLE IF EXISTS `automailer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `automailer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `swift_message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `sent_at` datetime DEFAULT NULL,
  `started_sending_at` datetime DEFAULT NULL,
  `is_html` tinyint(1) NOT NULL,
  `is_sending` tinyint(1) DEFAULT NULL,
  `is_sent` tinyint(1) DEFAULT NULL,
  `is_failed` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `find_next` (`is_sent`,`is_failed`,`is_sending`,`created_at`),
  KEY `recover_sending` (`is_sending`,`started_sending_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `automailer`
--

LOCK TABLES `automailer` WRITE;
/*!40000 ALTER TABLE `automailer` DISABLE KEYS */;
/*!40000 ALTER TABLE `automailer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calendar`
--

DROP TABLE IF EXISTS `calendar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_based` tinyint(1) NOT NULL DEFAULT '1',
  `is_baseline` tinyint(1) NOT NULL,
  `external_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_6EA9A1469F75D7B0` (`external_id`),
  KEY `IDX_6EA9A146727ACA70` (`parent_id`),
  KEY `IDX_6EA9A146166D1F9C` (`project_id`),
  CONSTRAINT `FK_6EA9A146166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_6EA9A146727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `calendar` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calendar`
--

LOCK TABLES `calendar` WRITE;
/*!40000 ALTER TABLE `calendar` DISABLE KEYS */;
/*!40000 ALTER TABLE `calendar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_room`
--

DROP TABLE IF EXISTS `chat_room`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat_room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D403CCDA166D1F9C` (`project_id`),
  CONSTRAINT `FK_D403CCDA166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_room`
--

LOCK TABLES `chat_room` WRITE;
/*!40000 ALTER TABLE `chat_room` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat_room` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `close_down_action`
--

DROP TABLE IF EXISTS `close_down_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `close_down_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_close_down_id` int(11) DEFAULT NULL,
  `responsibility_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `due_date` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_18A90D158E744ACD` (`project_close_down_id`),
  KEY `IDX_18A90D15385A88B7` (`responsibility_id`),
  CONSTRAINT `FK_18A90D15385A88B7` FOREIGN KEY (`responsibility_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_18A90D158E744ACD` FOREIGN KEY (`project_close_down_id`) REFERENCES `project_close_down` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `close_down_action`
--

LOCK TABLES `close_down_action` WRITE;
/*!40000 ALTER TABLE `close_down_action` DISABLE KEYS */;
/*!40000 ALTER TABLE `close_down_action` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `color_status`
--

DROP TABLE IF EXISTS `color_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `color_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '0',
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_5E1E7BEE77153098` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `color_status`
--

LOCK TABLES `color_status` WRITE;
/*!40000 ALTER TABLE `color_status` DISABLE KEYS */;
INSERT INTO `color_status` VALUES (1,'color_status.not_started','#c87369',0,NULL,'red'),(2,'color_status.in_progress','#ccba54',1,NULL,'yellow'),(3,'color_status.finished','#5fc3a5',2,NULL,'green');
/*!40000 ALTER TABLE `color_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `command_queue_log`
--

DROP TABLE IF EXISTS `command_queue_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `command_queue_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `command` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_count` int(11) NOT NULL DEFAULT '0',
  `output` longtext COLLATE utf8mb4_unicode_ci,
  `exit_code` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `command_queue_log`
--

LOCK TABLES `command_queue_log` WRITE;
/*!40000 ALTER TABLE `command_queue_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `command_queue_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9474526CA76ED395` (`user_id`),
  CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment`
--

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `communication`
--

DROP TABLE IF EXISTS `communication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `communication` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `schedule_id` int(11) DEFAULT NULL,
  `meeting_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F9AFB5EB166D1F9C` (`project_id`),
  KEY `IDX_F9AFB5EBA40BC2D5` (`schedule_id`),
  CONSTRAINT `FK_F9AFB5EB166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_F9AFB5EBA40BC2D5` FOREIGN KEY (`schedule_id`) REFERENCES `schedule` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `communication`
--

LOCK TABLES `communication` WRITE;
/*!40000 ALTER TABLE `communication` DISABLE KEYS */;
/*!40000 ALTER TABLE `communication` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `communication_participant`
--

DROP TABLE IF EXISTS `communication_participant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `communication_participant` (
  `communication_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`communication_id`,`user_id`),
  KEY `IDX_FCFF88551C2D1E0C` (`communication_id`),
  KEY `IDX_FCFF8855A76ED395` (`user_id`),
  CONSTRAINT `FK_FCFF88551C2D1E0C` FOREIGN KEY (`communication_id`) REFERENCES `communication` (`id`),
  CONSTRAINT `FK_FCFF8855A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `communication_participant`
--

LOCK TABLES `communication_participant` WRITE;
/*!40000 ALTER TABLE `communication_participant` DISABLE KEYS */;
/*!40000 ALTER TABLE `communication_participant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_4FBF094F5E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company`
--

LOCK TABLES `company` WRITE;
/*!40000 ALTER TABLE `company` DISABLE KEYS */;
INSERT INTO `company` VALUES (1,'Default','2018-08-30 14:33:29','2018-08-30 14:33:29'),(2,'CAMPR GmbH','2018-09-06 18:34:19','2018-09-06 18:34:19');
/*!40000 ALTER TABLE `company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contract`
--

DROP TABLE IF EXISTS `contract`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `proposed_start_date` date DEFAULT NULL,
  `proposed_end_date` date DEFAULT NULL,
  `forecast_start_date` date DEFAULT NULL,
  `forecast_end_date` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `project_start_event` longtext COLLATE utf8mb4_unicode_ci,
  `frozen` tinyint(1) NOT NULL DEFAULT '0',
  `approved_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E98F28595E237E06` (`name`),
  KEY `IDX_E98F2859A76ED395` (`user_id`),
  KEY `IDX_E98F2859166D1F9C` (`project_id`),
  CONSTRAINT `FK_E98F2859166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_E98F2859A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contract`
--

LOCK TABLES `contract` WRITE;
/*!40000 ALTER TABLE `contract` DISABLE KEYS */;
/*!40000 ALTER TABLE `contract` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cost`
--

DROP TABLE IF EXISTS `cost`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cost` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `work_package_id` int(11) DEFAULT NULL,
  `resource_id` int(11) DEFAULT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `rate` decimal(9,2) NOT NULL,
  `quantity` decimal(9,2) NOT NULL,
  `duration` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `expense_type` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_182694FC166D1F9C` (`project_id`),
  KEY `IDX_182694FCEF2F062C` (`work_package_id`),
  KEY `IDX_182694FC89329D25` (`resource_id`),
  KEY `IDX_182694FCF8BD700D` (`unit_id`),
  CONSTRAINT `FK_182694FC166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_182694FC89329D25` FOREIGN KEY (`resource_id`) REFERENCES `resource` (`id`),
  CONSTRAINT `FK_182694FCEF2F062C` FOREIGN KEY (`work_package_id`) REFERENCES `work_package` (`id`),
  CONSTRAINT `FK_182694FCF8BD700D` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cost`
--

LOCK TABLES `cost` WRITE;
/*!40000 ALTER TABLE `cost` DISABLE KEYS */;
INSERT INTO `cost` VALUES (1,2,69,4,NULL,0,40.00,40.00,'1','2018-10-23 06:05:19','2018-10-23 06:05:19',NULL,NULL),(2,2,70,5,NULL,0,40.00,40.00,'1','2018-10-23 06:07:00','2018-10-23 06:07:00',NULL,NULL),(3,2,71,5,NULL,0,100.00,40.00,'1','2018-10-23 06:11:50','2018-10-23 06:11:50',NULL,NULL),(4,2,72,5,NULL,0,100.00,10.00,'1','2018-10-23 06:14:58','2018-10-23 06:14:58',NULL,NULL),(5,2,73,5,NULL,0,150.00,40.00,'1','2018-10-23 06:17:37','2018-10-23 06:43:10',NULL,NULL),(6,2,74,5,NULL,0,150.00,30.00,'1','2018-10-23 06:22:27','2018-10-23 06:43:47',NULL,NULL),(7,2,75,8,NULL,0,40.00,10.00,'1','2018-10-23 06:31:05','2018-10-23 06:31:05',NULL,NULL),(8,2,76,7,NULL,0,0.00,2.00,'1','2018-10-23 06:35:20','2018-10-23 06:35:20',NULL,NULL);
/*!40000 ALTER TABLE `cost` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency`
--

DROP TABLE IF EXISTS `currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_6956883F77153098` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency`
--

LOCK TABLES `currency` WRITE;
/*!40000 ALTER TABLE `currency` DISABLE KEYS */;
INSERT INTO `currency` VALUES (1,'EUR','2018-08-30 16:33:17','2018-08-30 16:33:17'),(2,'USD','2018-08-30 16:33:17','2018-08-30 16:33:17'),(3,'GBP','2018-08-30 16:33:17','2018-08-30 16:33:17');
/*!40000 ALTER TABLE `currency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_field`
--

DROP TABLE IF EXISTS `custom_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_field`
--

LOCK TABLES `custom_field` WRITE;
/*!40000 ALTER TABLE `custom_field` DISABLE KEYS */;
/*!40000 ALTER TABLE `custom_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_field_value`
--

DROP TABLE IF EXISTS `custom_field_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_field_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_field_id` int(11) DEFAULT NULL,
  `obj_id` int(11) NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_EC7B05A1E5E0D4` (`custom_field_id`),
  CONSTRAINT `FK_EC7B05A1E5E0D4` FOREIGN KEY (`custom_field_id`) REFERENCES `custom_field` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_field_value`
--

LOCK TABLES `custom_field_value` WRITE;
/*!40000 ALTER TABLE `custom_field_value` DISABLE KEYS */;
/*!40000 ALTER TABLE `custom_field_value` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `day`
--

DROP TABLE IF EXISTS `day`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `day` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `calendar_id` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `working` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E5A02990A40A2C8` (`calendar_id`),
  CONSTRAINT `FK_E5A02990A40A2C8` FOREIGN KEY (`calendar_id`) REFERENCES `calendar` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `day`
--

LOCK TABLES `day` WRITE;
/*!40000 ALTER TABLE `day` DISABLE KEYS */;
/*!40000 ALTER TABLE `day` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `decision`
--

DROP TABLE IF EXISTS `decision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `decision` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `meeting_id` int(11) DEFAULT NULL,
  `responsibility_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `show_in_status_report` tinyint(1) NOT NULL DEFAULT '0',
  `due_date` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `decision_category_id` int(11) DEFAULT NULL,
  `done` tinyint(1) NOT NULL DEFAULT '0',
  `done_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_84ACBE48166D1F9C` (`project_id`),
  KEY `IDX_84ACBE4867433D9C` (`meeting_id`),
  KEY `IDX_84ACBE48385A88B7` (`responsibility_id`),
  KEY `IDX_84ACBE48FFB4008B` (`decision_category_id`),
  CONSTRAINT `FK_84ACBE48166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_84ACBE48385A88B7` FOREIGN KEY (`responsibility_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_84ACBE4867433D9C` FOREIGN KEY (`meeting_id`) REFERENCES `meeting` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_84ACBE48FFB4008B` FOREIGN KEY (`decision_category_id`) REFERENCES `decision_category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `decision`
--

LOCK TABLES `decision` WRITE;
/*!40000 ALTER TABLE `decision` DISABLE KEYS */;
/*!40000 ALTER TABLE `decision` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `decision_category`
--

DROP TABLE IF EXISTS `decision_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `decision_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8B90D6FF5E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `decision_category`
--

LOCK TABLES `decision_category` WRITE;
/*!40000 ALTER TABLE `decision_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `decision_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `decision_media`
--

DROP TABLE IF EXISTS `decision_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `decision_media` (
  `decision_id` int(11) NOT NULL,
  `media_id` int(11) NOT NULL,
  PRIMARY KEY (`decision_id`,`media_id`),
  KEY `IDX_4D696605BDEE7539` (`decision_id`),
  KEY `IDX_4D696605EA9FDD75` (`media_id`),
  CONSTRAINT `FK_4D696605BDEE7539` FOREIGN KEY (`decision_id`) REFERENCES `decision` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_4D696605EA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `decision_media`
--

LOCK TABLES `decision_media` WRITE;
/*!40000 ALTER TABLE `decision_media` DISABLE KEYS */;
/*!40000 ALTER TABLE `decision_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `distribution_list`
--

DROP TABLE IF EXISTS `distribution_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `distribution_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_project_unique` (`name`,`project_id`),
  KEY `IDX_4C7574AC166D1F9C` (`project_id`),
  KEY `IDX_4C7574ACA76ED395` (`user_id`),
  CONSTRAINT `FK_4C7574AC166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_4C7574ACA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `distribution_list`
--

LOCK TABLES `distribution_list` WRITE;
/*!40000 ALTER TABLE `distribution_list` DISABLE KEYS */;
INSERT INTO `distribution_list` VALUES (1,1,1,'label.status_report_distribution',-1,2,'2018-08-30 14:35:07','2018-08-30 16:03:36'),(2,2,1,'label.status_report_distribution',-1,1,'2018-10-23 05:37:43','2018-10-23 05:37:43'),(3,3,1,'label.status_report_distribution',-1,0,'2018-10-23 06:45:22','2018-10-23 07:11:38');
/*!40000 ALTER TABLE `distribution_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `distribution_list_meeting`
--

DROP TABLE IF EXISTS `distribution_list_meeting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `distribution_list_meeting` (
  `distribution_list_id` int(11) NOT NULL,
  `meeting_id` int(11) NOT NULL,
  PRIMARY KEY (`distribution_list_id`,`meeting_id`),
  KEY `IDX_A2742F85DD1F5839` (`distribution_list_id`),
  KEY `IDX_A2742F8567433D9C` (`meeting_id`),
  CONSTRAINT `FK_A2742F8567433D9C` FOREIGN KEY (`meeting_id`) REFERENCES `meeting` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_A2742F85DD1F5839` FOREIGN KEY (`distribution_list_id`) REFERENCES `distribution_list` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `distribution_list_meeting`
--

LOCK TABLES `distribution_list_meeting` WRITE;
/*!40000 ALTER TABLE `distribution_list_meeting` DISABLE KEYS */;
/*!40000 ALTER TABLE `distribution_list_meeting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `distribution_list_user`
--

DROP TABLE IF EXISTS `distribution_list_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `distribution_list_user` (
  `distribution_list_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`distribution_list_id`,`user_id`),
  KEY `IDX_8FCA49BCDD1F5839` (`distribution_list_id`),
  KEY `IDX_8FCA49BCA76ED395` (`user_id`),
  CONSTRAINT `FK_8FCA49BCA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_8FCA49BCDD1F5839` FOREIGN KEY (`distribution_list_id`) REFERENCES `distribution_list` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `distribution_list_user`
--

LOCK TABLES `distribution_list_user` WRITE;
/*!40000 ALTER TABLE `distribution_list_user` DISABLE KEYS */;
INSERT INTO `distribution_list_user` VALUES (1,1),(1,2),(1,3),(2,1),(3,1),(3,2),(3,3);
/*!40000 ALTER TABLE `distribution_list_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluation_objective`
--

DROP TABLE IF EXISTS `evaluation_objective`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evaluation_objective` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_close_down_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_6B963A858E744ACD` (`project_close_down_id`),
  CONSTRAINT `FK_6B963A858E744ACD` FOREIGN KEY (`project_close_down_id`) REFERENCES `project_close_down` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evaluation_objective`
--

LOCK TABLES `evaluation_objective` WRITE;
/*!40000 ALTER TABLE `evaluation_objective` DISABLE KEYS */;
/*!40000 ALTER TABLE `evaluation_objective` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `file_system`
--

DROP TABLE IF EXISTS `file_system`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `file_system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `driver` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `config` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json_array)',
  `created_at` datetime NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_715A3734166D1F9C` (`project_id`),
  CONSTRAINT `FK_715A3734166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file_system`
--

LOCK TABLES `file_system` WRITE;
/*!40000 ALTER TABLE `file_system` DISABLE KEYS */;
INSERT INTO `file_system` VALUES (1,NULL,'local_adapter','welcometocampr','{\"path\":\"\\/var\\/www\\/workspace.prod\\/shared\\/web\\/uploads\\/welcometocampr\"}','2018-08-30 14:33:34',1);
/*!40000 ALTER TABLE `file_system` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `impact`
--

DROP TABLE IF EXISTS `impact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `impact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C409C0075E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `impact`
--

LOCK TABLES `impact` WRITE;
/*!40000 ALTER TABLE `impact` DISABLE KEYS */;
/*!40000 ALTER TABLE `impact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `info`
--

DROP TABLE IF EXISTS `info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `info_category_id` int(11) NOT NULL,
  `topic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `expires_at` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `meeting_id` int(11) DEFAULT NULL,
  `responsibility_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CB893157166D1F9C` (`project_id`),
  KEY `IDX_CB893157CD7EB71D` (`info_category_id`),
  KEY `IDX_CB89315767433D9C` (`meeting_id`),
  KEY `IDX_CB893157385A88B7` (`responsibility_id`),
  CONSTRAINT `FK_CB893157166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_CB893157385A88B7` FOREIGN KEY (`responsibility_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_CB89315767433D9C` FOREIGN KEY (`meeting_id`) REFERENCES `meeting` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_CB893157CD7EB71D` FOREIGN KEY (`info_category_id`) REFERENCES `info_category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `info`
--

LOCK TABLES `info` WRITE;
/*!40000 ALTER TABLE `info` DISABLE KEYS */;
/*!40000 ALTER TABLE `info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `info_category`
--

DROP TABLE IF EXISTS `info_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `info_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `info_category`
--

LOCK TABLES `info_category` WRITE;
/*!40000 ALTER TABLE `info_category` DISABLE KEYS */;
INSERT INTO `info_category` VALUES (1,'label.production'),(2,'label.logistics'),(3,'label.quality_management'),(4,'label.human_resources'),(5,'label.purchasing'),(6,'label.maintenance'),(7,'label.assembly'),(8,'label.tooling'),(9,'label.process_engineering'),(10,'label.industrialization');
/*!40000 ALTER TABLE `info_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `label`
--

DROP TABLE IF EXISTS `label`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `label` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_EA750E8166D1F9C` (`project_id`),
  CONSTRAINT `FK_EA750E8166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `label`
--

LOCK TABLES `label` WRITE;
/*!40000 ALTER TABLE `label` DISABLE KEYS */;
INSERT INTO `label` VALUES (1,1,'High Priority',NULL,'#DE2B39'),(2,2,'High Priority',NULL,'#DE2B39'),(3,3,'High Priority',NULL,'#DE2B39'),(4,1,'Required',NULL,'#FFFF00'),(5,2,'Required',NULL,'#FFFF00'),(6,3,'Required',NULL,'#FFFF00');
/*!40000 ALTER TABLE `label` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lesson`
--

DROP TABLE IF EXISTS `lesson`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lesson` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_close_down_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_F87474F38E744ACD` (`project_close_down_id`),
  CONSTRAINT `FK_F87474F38E744ACD` FOREIGN KEY (`project_close_down_id`) REFERENCES `project_close_down` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lesson`
--

LOCK TABLES `lesson` WRITE;
/*!40000 ALTER TABLE `lesson` DISABLE KEYS */;
/*!40000 ALTER TABLE `lesson` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `obj_id` int(11) NOT NULL,
  `old_value` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `new_value` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8F3F68C5A76ED395` (`user_id`),
  CONSTRAINT `FK_8F3F68C5A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log`
--

LOCK TABLES `log` WRITE;
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
INSERT INTO `log` VALUES (1,1,'AppBundle\\Entity\\User',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 14:34:01.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-07-31 19:27:58.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-08-30 14:34:15'),(2,1,'AppBundle\\Entity\\User',1,'a:2:{s:6:\"locale\";s:2:\"en\";s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-07-31 19:27:58.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:6:\"locale\";s:2:\"de\";s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 14:40:42.540152\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-08-30 14:40:42'),(3,1,'AppBundle\\Entity\\WorkPackage',7,'a:5:{s:8:\"progress\";i:0;s:13:\"actualStartAt\";N;s:14:\"actualFinishAt\";N;s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:2;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 14:36:40.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:5:{s:8:\"progress\";i:100;s:13:\"actualStartAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 15:54:01.025489\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:14:\"actualFinishAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 15:54:01.025495\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:5;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 15:54:01.026430\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-08-30 15:54:01'),(4,1,'AppBundle\\Entity\\User',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 14:40:42.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 14:40:42.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-08-30 15:58:46'),(5,1,'AppBundle\\Entity\\User',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 14:40:42.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 14:40:42.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-08-30 16:00:58'),(6,1,'AppBundle\\Entity\\User',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 14:40:42.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 14:40:42.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-08-30 16:01:42'),(7,1,'AppBundle\\Entity\\DistributionList',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 14:35:07.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:03:32.214987\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-08-30 16:03:32'),(8,1,'AppBundle\\Entity\\DistributionList',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:03:32.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:03:36.025873\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-08-30 16:03:36'),(9,NULL,'AppBundle\\Entity\\User',3,'a:7:{s:5:\"phone\";N;s:9:\"firstName\";s:13:\"manuel.weiler\";s:8:\"lastName\";s:10:\"@campr.biz\";s:8:\"apiToken\";s:128:\"dd94fcb5c695a9b72846377cda3f3a567fd32e11014d2d98bd49a9fe3d2e94ed3a4ca7243a032c51d11c47d684db57bdb23e1d165dcf56523d75dc12c150caa6\";s:6:\"avatar\";N;s:6:\"medium\";N;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 15:22:28.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:7:{s:5:\"phone\";s:14:\"+4917610339930\";s:9:\"firstName\";s:6:\"Manuel\";s:8:\"lastName\";s:6:\"Weiler\";s:8:\"apiToken\";s:128:\"d18e56462a4151567cf91b5730ff94bb737a2d7dd3f9771baea9a2db0be1a24d496a7c563d1234f15a7a17a654ff0f860df2cdf563349b45794e7505e3fe90c8\";s:6:\"avatar\";s:17:\"5b5754250782f.png\";s:6:\"medium\";s:23:\"claragoodman@campr.biz\";s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:11:51.989654\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-08-30 16:11:51'),(10,NULL,'AppBundle\\Entity\\TeamInvite',2,'a:1:{s:10:\"acceptedAt\";N;}','a:1:{s:10:\"acceptedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:11:52.539298\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-08-30 16:11:52'),(11,3,'AppBundle\\Entity\\User',3,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:11:51.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-01 15:32:28.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-08-30 16:12:04'),(12,1,'AppBundle\\Entity\\WorkPackage',30,'a:2:{s:5:\"phase\";N;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:09:18.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:5:\"phase\";a:2:{i:0;s:28:\"AppBundle\\Entity\\WorkPackage\";i:1;i:9;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:17:21.592999\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-08-30 16:17:21'),(13,1,'AppBundle\\Entity\\WorkPackage',31,'a:2:{s:5:\"phase\";N;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:09:57.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:5:\"phase\";a:2:{i:0;s:28:\"AppBundle\\Entity\\WorkPackage\";i:1;i:9;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:17:54.068821\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-08-30 16:17:54'),(14,1,'AppBundle\\Entity\\WorkPackage',32,'a:2:{s:5:\"phase\";N;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:10:35.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:5:\"phase\";a:2:{i:0;s:28:\"AppBundle\\Entity\\WorkPackage\";i:1;i:9;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:18:07.944022\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-08-30 16:18:07'),(15,1,'AppBundle\\Entity\\WorkPackage',33,'a:2:{s:5:\"phase\";N;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:10:56.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:5:\"phase\";a:2:{i:0;s:28:\"AppBundle\\Entity\\WorkPackage\";i:1;i:9;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:18:22.863085\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-08-30 16:18:22'),(16,1,'AppBundle\\Entity\\WorkPackage',34,'a:2:{s:5:\"phase\";N;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:11:17.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:5:\"phase\";a:2:{i:0;s:28:\"AppBundle\\Entity\\WorkPackage\";i:1;i:9;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:18:49.205739\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-08-30 16:18:49'),(17,1,'AppBundle\\Entity\\WorkPackage',35,'a:2:{s:5:\"phase\";N;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:15:57.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:5:\"phase\";a:2:{i:0;s:28:\"AppBundle\\Entity\\WorkPackage\";i:1;i:9;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:19:04.042463\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-08-30 16:19:04'),(18,1,'AppBundle\\Entity\\WorkPackage',27,'a:9:{s:16:\"scheduledStartAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2019-02-01 00:00:00.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:17:\"scheduledFinishAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2019-02-28 00:00:00.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:15:\"forecastStartAt\";N;s:16:\"forecastFinishAt\";N;s:18:\"externalActualCost\";s:4:\"0.00\";s:20:\"externalForecastCost\";s:4:\"0.00\";s:18:\"internalActualCost\";s:4:\"0.00\";s:20:\"internalForecastCost\";s:4:\"0.00\";s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:04:39.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:9:{s:16:\"scheduledStartAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2019-03-01 00:00:00.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:17:\"scheduledFinishAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2019-03-31 00:00:00.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:15:\"forecastStartAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2019-03-01 00:00:00.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:16:\"forecastFinishAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2019-03-31 00:00:00.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:18:\"externalActualCost\";N;s:20:\"externalForecastCost\";N;s:18:\"internalActualCost\";N;s:20:\"internalForecastCost\";N;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:27:44.548230\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-08-30 16:27:44'),(19,1,'AppBundle\\Entity\\User',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 14:40:42.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 14:40:42.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-08-30 16:27:50'),(20,1,'AppBundle\\Entity\\User',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 14:40:42.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 14:40:42.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-08-31 10:43:28'),(21,1,'AppBundle\\Entity\\User',2,'a:2:{s:5:\"roles\";a:1:{i:0;s:9:\"ROLE_USER\";}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 15:22:03.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:5:\"roles\";a:3:{i:0;s:9:\"ROLE_USER\";i:1;s:10:\"ROLE_ADMIN\";i:2;s:16:\"ROLE_SUPER_ADMIN\";}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-31 10:51:20.688285\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-08-31 10:51:20'),(22,1,'AppBundle\\Entity\\User',3,'a:2:{s:5:\"roles\";a:1:{i:0;s:9:\"ROLE_USER\";}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-01 15:32:28.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:5:\"roles\";a:3:{i:0;s:9:\"ROLE_USER\";i:1;s:10:\"ROLE_ADMIN\";i:2;s:16:\"ROLE_SUPER_ADMIN\";}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-31 10:51:33.283637\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-08-31 10:51:33'),(23,1,'AppBundle\\Entity\\User',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 14:40:42.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 14:40:42.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 09:12:17'),(24,1,'AppBundle\\Entity\\User',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 14:40:42.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 14:40:42.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 14:54:33'),(25,1,'AppBundle\\Entity\\User',1,'a:2:{s:6:\"locale\";s:2:\"de\";s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 14:40:42.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:6:\"locale\";s:2:\"en\";s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 14:54:57.022457\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 14:54:57'),(26,1,'AppBundle\\Entity\\WorkPackage',50,'a:3:{s:5:\"phase\";N;s:9:\"milestone\";N;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:30:13.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:3:{s:5:\"phase\";a:2:{i:0;s:28:\"AppBundle\\Entity\\WorkPackage\";i:1;i:27;}s:9:\"milestone\";a:2:{i:0;s:28:\"AppBundle\\Entity\\WorkPackage\";i:1;i:58;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 14:57:15.886581\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 14:57:15'),(27,1,'AppBundle\\Entity\\WorkPackage',50,'a:2:{s:9:\"milestone\";a:2:{i:0;s:28:\"AppBundle\\Entity\\WorkPackage\";i:1;i:58;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 14:57:15.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:9:\"milestone\";N;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 14:57:52.344305\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 14:57:52'),(28,1,'AppBundle\\Entity\\WorkPackage',45,'a:2:{s:5:\"phase\";N;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:26:22.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:5:\"phase\";a:2:{i:0;s:28:\"AppBundle\\Entity\\WorkPackage\";i:1;i:26;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 14:59:11.050937\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 14:59:11'),(29,1,'AppBundle\\Entity\\WorkPackage',8,'a:2:{s:5:\"phase\";N;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 14:37:11.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:5:\"phase\";a:2:{i:0;s:28:\"AppBundle\\Entity\\WorkPackage\";i:1;i:9;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 15:07:45.463070\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 15:07:45'),(30,1,'AppBundle\\Entity\\WorkPackage',8,'a:2:{s:5:\"phase\";a:2:{i:0;s:28:\"AppBundle\\Entity\\WorkPackage\";i:1;i:9;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 15:07:45.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:5:\"phase\";a:2:{i:0;s:28:\"AppBundle\\Entity\\WorkPackage\";i:1;i:6;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 15:07:58.056018\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 15:07:58'),(31,NULL,'AppBundle\\Entity\\User',3,'a:2:{s:5:\"roles\";a:3:{i:0;s:9:\"ROLE_USER\";i:1;s:10:\"ROLE_ADMIN\";i:2;s:16:\"ROLE_SUPER_ADMIN\";}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-31 10:51:33.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:5:\"roles\";a:1:{i:0;s:9:\"ROLE_USER\";}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:14:03.934412\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:14:03'),(32,3,'AppBundle\\Entity\\User',3,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:14:03.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-01 15:32:28.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:14:16'),(33,3,'AppBundle\\Entity\\WorkPackage',50,'a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:2;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 14:57:52.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:1;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:15:18.990697\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:15:18'),(34,3,'AppBundle\\Entity\\WorkPackage',49,'a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:2;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:29:51.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:1;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:15:29.606600\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:15:29'),(35,3,'AppBundle\\Entity\\User',3,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-01 15:32:28.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-01 15:32:28.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:15:47'),(36,3,'AppBundle\\Entity\\WorkPackage',48,'a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:2;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:29:25.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:1;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:16:03.452532\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:16:03'),(37,3,'AppBundle\\Entity\\WorkPackage',47,'a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:2;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:29:11.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:1;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:16:29.283125\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:16:29'),(38,3,'AppBundle\\Entity\\WorkPackage',46,'a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:2;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:26:48.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:1;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:16:44.921139\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:16:44'),(39,3,'AppBundle\\Entity\\WorkPackage',45,'a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:2;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 14:59:11.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:1;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:16:52.097781\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:16:52'),(40,3,'AppBundle\\Entity\\WorkPackage',44,'a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:2;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:25:53.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:1;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:17:00.924170\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:17:00'),(41,3,'AppBundle\\Entity\\WorkPackage',43,'a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:2;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:22:34.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:1;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:17:09.381047\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:17:09'),(42,3,'AppBundle\\Entity\\WorkPackage',42,'a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:2;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:22:10.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:1;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:17:24.091373\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:17:24'),(43,3,'AppBundle\\Entity\\WorkPackage',41,'a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:2;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:21:39.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:1;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:17:32.364548\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:17:32'),(44,3,'AppBundle\\Entity\\WorkPackage',40,'a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:2;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:21:20.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:1;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:17:40.999232\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:17:40'),(45,3,'AppBundle\\Entity\\WorkPackage',39,'a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:2;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:20:52.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:1;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:17:50.564570\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:17:50'),(46,3,'AppBundle\\Entity\\WorkPackage',38,'a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:2;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:20:29.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:1;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:17:58.582868\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:17:58'),(47,3,'AppBundle\\Entity\\WorkPackage',37,'a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:2;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:19:54.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:1;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:18:04.558488\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:18:04'),(48,3,'AppBundle\\Entity\\WorkPackage',36,'a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:2;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:16:46.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:1;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:18:19.586888\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:18:19'),(49,3,'AppBundle\\Entity\\WorkPackage',36,'a:4:{s:8:\"progress\";i:0;s:13:\"actualStartAt\";N;s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:1;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:18:19.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:4:{s:8:\"progress\";i:25;s:13:\"actualStartAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:18:22.383604\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:3;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:18:22.384760\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:18:22'),(50,3,'AppBundle\\Entity\\WorkPackage',8,'a:4:{s:8:\"progress\";i:0;s:13:\"actualStartAt\";N;s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:2;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 15:07:58.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:4:{s:8:\"progress\";i:75;s:13:\"actualStartAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:18:30.005896\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:3;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:18:30.007265\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:18:30'),(51,3,'AppBundle\\Entity\\WorkPackage',28,'a:4:{s:8:\"progress\";i:0;s:13:\"actualStartAt\";N;s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:2;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 16:08:01.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:4:{s:8:\"progress\";i:50;s:13:\"actualStartAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:18:38.253509\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:3;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:18:38.254452\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:18:38'),(52,1,'AppBundle\\Entity\\User',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 14:54:57.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 14:54:57.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:26:19'),(53,1,'AppBundle\\Entity\\User',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 14:54:57.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 14:54:57.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:26:46'),(54,NULL,'AppBundle\\Entity\\User',3,'a:2:{s:5:\"roles\";a:1:{i:0;s:9:\"ROLE_USER\";}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-01 15:32:28.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:5:\"roles\";a:2:{i:0;s:9:\"ROLE_USER\";i:1;s:10:\"ROLE_ADMIN\";}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:29:42.726408\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:29:42'),(55,3,'AppBundle\\Entity\\User',3,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:29:42.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-01 15:32:28.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:29:44'),(56,1,'AppBundle\\Entity\\User',3,'a:2:{s:5:\"roles\";a:2:{i:0;s:9:\"ROLE_USER\";i:1;s:10:\"ROLE_ADMIN\";}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-01 15:32:28.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:5:\"roles\";a:3:{i:0;s:9:\"ROLE_USER\";i:1;s:10:\"ROLE_ADMIN\";i:2;s:16:\"ROLE_SUPER_ADMIN\";}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:31:05.869297\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:31:05'),(57,3,'AppBundle\\Entity\\ProjectUser',3,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 15:22:28.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:31:12.437810\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:31:12'),(58,3,'AppBundle\\Entity\\ProjectUser',2,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-30 15:22:03.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:31:24.336445\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:31:24'),(59,3,'AppBundle\\Entity\\Resource',1,'a:2:{s:4:\"rate\";s:4:\"0.00\";s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:31:12.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:4:\"rate\";d:100;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:32:30.334946\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:32:30'),(60,3,'AppBundle\\Entity\\Resource',2,'a:2:{s:4:\"rate\";s:4:\"0.00\";s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:31:24.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:4:\"rate\";d:100;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:32:36.990045\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:32:36'),(61,3,'AppBundle\\Entity\\ProjectUser',2,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:31:24.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:36:15.825522\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:36:15'),(62,3,'AppBundle\\Entity\\ProjectUser',3,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:31:12.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:36:26.275794\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:36:26'),(63,3,'AppBundle\\Entity\\ProjectUser',3,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:36:26.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:36:50.112386\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:36:50'),(64,3,'AppBundle\\Entity\\ProjectUser',3,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:36:50.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 18:37:00.331736\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-06 18:37:00'),(65,1,'AppBundle\\Entity\\User',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 14:54:57.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 14:54:57.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-09-08 10:58:14'),(66,1,'AppBundle\\Entity\\User',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-06 14:54:57.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-20 10:46:03.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-04 10:06:01'),(67,1,'AppBundle\\Entity\\User',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-20 10:46:03.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-20 10:46:03.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-04 10:08:28'),(68,NULL,'AppBundle\\Entity\\User',1,'a:4:{s:4:\"uuid\";N;s:6:\"locale\";s:2:\"en\";s:9:\"avatarUrl\";N;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-09-20 10:46:03.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:4:{s:4:\"uuid\";s:36:\"0e4ec47d-c8b1-11e8-b629-96000008a04b\";s:6:\"locale\";s:2:\"de\";s:9:\"avatarUrl\";s:77:\"https://www.gravatar.com/avatar//3ec3ff3ca5b93d9765e0702257131a54?d=identicon\";s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-22 17:13:48.695670\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-22 17:13:48'),(69,1,'AppBundle\\Entity\\User',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-22 17:13:48.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-18 15:35:11.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-22 17:13:53'),(70,NULL,'AppBundle\\Entity\\User',2,'a:8:{s:4:\"uuid\";N;s:5:\"phone\";N;s:9:\"firstName\";s:14:\"christoph.pohl\";s:8:\"lastName\";s:10:\"@campr.biz\";s:5:\"roles\";a:3:{i:0;s:9:\"ROLE_USER\";i:1;s:10:\"ROLE_ADMIN\";i:2;s:16:\"ROLE_SUPER_ADMIN\";}s:8:\"apiToken\";s:128:\"023ae08d48736d76967913973336b17f6827c1aff6cc7d6eced7938f95fc71f306298715ddd56d7f044a9f1434883f0b47795a5cececf8349ecca55f4d54501f\";s:9:\"avatarUrl\";N;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-31 10:51:20.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:8:{s:4:\"uuid\";s:36:\"0e4ec380-c8b1-11e8-b629-96000008a04b\";s:5:\"phone\";s:13:\"+491772008396\";s:9:\"firstName\";s:9:\"Christoph\";s:8:\"lastName\";s:4:\"Pohl\";s:5:\"roles\";a:1:{i:0;s:9:\"ROLE_USER\";}s:8:\"apiToken\";s:128:\"4234f3a94bda03ab54946c5f8bf778c2b9d80487f8046f0184c0b76957b716fae1f778575844e2bc6f54b7607343fbed083e2f00a40ffa48045f955143d7c859\";s:9:\"avatarUrl\";s:51:\"https://campr.biz/uploads/avatars/5afbca9f2c835.JPG\";s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-22 17:18:11.219254\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-22 17:18:11'),(71,NULL,'AppBundle\\Entity\\TeamInvite',1,'a:1:{s:10:\"acceptedAt\";N;}','a:1:{s:10:\"acceptedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-22 17:18:11.771888\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-22 17:18:11'),(72,2,'AppBundle\\Entity\\User',2,'a:2:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-22 17:18:11.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:6:\"avatar\";N;}','a:2:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-15 14:37:18.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:6:\"avatar\";s:17:\"5afbca9f2c835.JPG\";}','2018-10-22 17:18:13'),(73,2,'AppBundle\\Entity\\User',2,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-15 14:37:18.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-15 14:37:18.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-22 17:23:17'),(74,NULL,'AppBundle\\Entity\\User',1,'a:2:{s:4:\"uuid\";C:16:\"Ramsey\\Uuid\\Uuid\":36:{0e4ec47d-c8b1-11e8-b629-96000008a04b}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-18 15:35:11.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:4:\"uuid\";s:36:\"0e4ec47d-c8b1-11e8-b629-96000008a04b\";s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:36:06.963029\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 05:36:06'),(75,1,'AppBundle\\Entity\\User',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:36:06.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-18 15:35:11.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 05:36:09'),(76,1,'AppBundle\\Entity\\WorkPackage',68,'a:2:{s:5:\"phase\";N;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:46:12.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:5:\"phase\";a:2:{i:0;s:28:\"AppBundle\\Entity\\WorkPackage\";i:1;i:65;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:46:28.971755\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 05:46:28'),(77,1,'AppBundle\\Entity\\User',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-18 15:35:11.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-18 15:35:11.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 05:50:37'),(78,1,'AppBundle\\Entity\\ProjectUser',6,'a:2:{s:11:\"showInRasci\";b:0;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:51:26.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:11:\"showInRasci\";b:1;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:53:43.666352\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 05:53:43'),(79,1,'AppBundle\\Entity\\ProjectUser',6,'a:2:{s:9:\"showInOrg\";b:0;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:53:43.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:9:\"showInOrg\";b:1;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:53:44.920066\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 05:53:44'),(80,1,'AppBundle\\Entity\\User',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-18 15:35:11.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-18 15:35:11.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 05:57:00'),(81,1,'AppBundle\\Entity\\ProjectUser',6,'a:2:{s:15:\"showInResources\";b:0;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:53:44.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:15:\"showInResources\";b:1;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:57:12.039847\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 05:57:12'),(82,1,'AppBundle\\Entity\\ProjectUser',6,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:57:12.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:02.790625\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 05:58:02'),(83,1,'AppBundle\\Entity\\ProjectUser',7,'a:2:{s:11:\"showInRasci\";b:0;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:57:38.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:11:\"showInRasci\";b:1;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:15.788506\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 05:58:15'),(84,1,'AppBundle\\Entity\\ProjectUser',8,'a:2:{s:11:\"showInRasci\";b:0;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:57:38.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:11:\"showInRasci\";b:1;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:16.595177\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 05:58:16'),(85,1,'AppBundle\\Entity\\ProjectUser',9,'a:2:{s:11:\"showInRasci\";b:0;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:57:39.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:11:\"showInRasci\";b:1;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:18.275175\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 05:58:18'),(86,1,'AppBundle\\Entity\\ProjectUser',7,'a:2:{s:9:\"showInOrg\";b:0;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:15.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:9:\"showInOrg\";b:1;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:18.600043\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 05:58:18'),(87,1,'AppBundle\\Entity\\ProjectUser',8,'a:2:{s:9:\"showInOrg\";b:0;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:16.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:9:\"showInOrg\";b:1;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:18.960482\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 05:58:18'),(88,1,'AppBundle\\Entity\\ProjectUser',9,'a:2:{s:9:\"showInOrg\";b:0;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:18.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:9:\"showInOrg\";b:1;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:19.574729\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 05:58:19'),(89,1,'AppBundle\\Entity\\ProjectUser',7,'a:2:{s:15:\"showInResources\";b:0;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:18.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:15:\"showInResources\";b:1;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:22.017522\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 05:58:22'),(90,1,'AppBundle\\Entity\\ProjectUser',8,'a:2:{s:15:\"showInResources\";b:0;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:18.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:15:\"showInResources\";b:1;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:22.550787\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 05:58:22'),(91,1,'AppBundle\\Entity\\ProjectUser',9,'a:2:{s:15:\"showInResources\";b:0;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:19.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:15:\"showInResources\";b:1;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:22.882161\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 05:58:22'),(92,1,'AppBundle\\Entity\\ProjectUser',7,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:22.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:33.914258\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 05:58:33'),(93,1,'AppBundle\\Entity\\ProjectUser',8,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:22.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:39.580691\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 05:58:39'),(94,1,'AppBundle\\Entity\\ProjectUser',9,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:22.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:45.342519\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 05:58:45'),(95,1,'AppBundle\\Entity\\ProjectUser',5,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:37:43.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 05:58:53.620969\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 05:58:53'),(96,1,'AppBundle\\Entity\\User',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-18 15:35:11.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-18 15:35:11.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 06:04:26'),(97,NULL,'AppBundle\\Entity\\User',2,'a:2:{s:4:\"uuid\";C:16:\"Ramsey\\Uuid\\Uuid\":36:{0e4ec380-c8b1-11e8-b629-96000008a04b}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-15 14:37:18.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:4:\"uuid\";s:36:\"0e4ec380-c8b1-11e8-b629-96000008a04b\";s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 06:40:06.646128\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 06:40:06'),(98,2,'AppBundle\\Entity\\User',2,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 06:40:06.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-15 14:37:18.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 06:40:10'),(99,1,'AppBundle\\Entity\\WorkPackage',74,'a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:2;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 06:22:27.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:17:\"workPackageStatus\";a:2:{i:0;s:34:\"AppBundle\\Entity\\WorkPackageStatus\";i:1;i:1;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 06:42:06.615349\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 06:42:06'),(100,1,'AppBundle\\Entity\\WorkPackage',73,'a:3:{s:16:\"forecastFinishAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-11-17 00:00:00.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:5:\"phase\";N;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 06:17:37.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:3:{s:16:\"forecastFinishAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-11-15 00:00:00.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:5:\"phase\";a:2:{i:0;s:28:\"AppBundle\\Entity\\WorkPackage\";i:1;i:65;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 06:43:10.357053\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 06:43:10'),(101,1,'AppBundle\\Entity\\Cost',5,'a:3:{s:4:\"rate\";s:6:\"150.00\";s:8:\"quantity\";s:5:\"40.00\";s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 06:17:37.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:3:{s:4:\"rate\";d:150;s:8:\"quantity\";d:40;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 06:43:10.357165\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 06:43:10'),(102,1,'AppBundle\\Entity\\WorkPackage',74,'a:2:{s:5:\"phase\";N;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 06:42:06.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:5:\"phase\";a:2:{i:0;s:28:\"AppBundle\\Entity\\WorkPackage\";i:1;i:65;}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 06:43:47.967118\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 06:43:47'),(103,1,'AppBundle\\Entity\\Cost',6,'a:3:{s:4:\"rate\";s:6:\"150.00\";s:8:\"quantity\";s:5:\"30.00\";s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 06:22:27.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:3:{s:4:\"rate\";d:150;s:8:\"quantity\";d:30;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 06:43:47.967236\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 06:43:47'),(104,1,'AppBundle\\Entity\\ProjectModule',35,'a:2:{s:9:\"isEnabled\";b:0;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 06:45:22.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:9:\"isEnabled\";b:1;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 06:48:40.660291\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 06:48:40'),(105,1,'AppBundle\\Entity\\User',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-18 15:35:11.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-18 15:35:11.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 06:48:47'),(106,1,'AppBundle\\Entity\\User',1,'a:2:{s:4:\"uuid\";C:16:\"Ramsey\\Uuid\\Uuid\":36:{0e4ec47d-c8b1-11e8-b629-96000008a04b}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-18 15:35:11.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:4:\"uuid\";s:36:\"0e4ec47d-c8b1-11e8-b629-96000008a04b\";s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 07:09:00.266949\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 07:09:00'),(107,1,'AppBundle\\Entity\\User',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 07:09:00.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-18 15:35:11.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 07:09:02'),(108,1,'AppBundle\\Entity\\ProjectUser',11,'a:2:{s:15:\"showInResources\";b:0;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 06:45:47.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:15:\"showInResources\";b:1;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 07:11:28.850962\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 07:11:28'),(109,1,'AppBundle\\Entity\\ProjectUser',11,'a:2:{s:15:\"showInResources\";b:1;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 07:11:28.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:15:\"showInResources\";b:0;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 07:11:29.289269\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 07:11:29'),(110,1,'AppBundle\\Entity\\ProjectUser',12,'a:2:{s:15:\"showInResources\";b:0;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 06:45:48.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:15:\"showInResources\";b:1;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 07:11:29.540440\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 07:11:29'),(111,1,'AppBundle\\Entity\\ProjectUser',11,'a:2:{s:15:\"showInResources\";b:0;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 07:11:29.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:15:\"showInResources\";b:1;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 07:11:30.057504\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 07:11:30'),(112,1,'AppBundle\\Entity\\ProjectUser',11,'a:2:{s:11:\"showInRasci\";b:0;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 07:11:30.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:11:\"showInRasci\";b:1;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 07:11:31.534193\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 07:11:31'),(113,1,'AppBundle\\Entity\\ProjectUser',11,'a:2:{s:11:\"showInRasci\";b:1;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 07:11:31.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:11:\"showInRasci\";b:0;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 07:11:32.351034\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 07:11:32'),(114,1,'AppBundle\\Entity\\ProjectUser',11,'a:2:{s:9:\"showInOrg\";b:0;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 07:11:32.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:9:\"showInOrg\";b:1;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 07:11:32.664877\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 07:11:32'),(115,1,'AppBundle\\Entity\\ProjectUser',12,'a:2:{s:9:\"showInOrg\";b:0;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 07:11:29.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:9:\"showInOrg\";b:1;s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 07:11:33.097893\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 07:11:33'),(116,1,'AppBundle\\Entity\\DistributionList',3,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 06:45:22.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 07:11:38.623067\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 07:11:38'),(117,1,'AppBundle\\Entity\\DistributionList',3,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 07:11:38.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 07:11:38.879076\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 07:11:38'),(118,1,'AppBundle\\Entity\\User',1,'a:2:{s:4:\"uuid\";C:16:\"Ramsey\\Uuid\\Uuid\":36:{0e4ec47d-c8b1-11e8-b629-96000008a04b}s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-18 15:35:11.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:2:{s:4:\"uuid\";s:36:\"0e4ec47d-c8b1-11e8-b629-96000008a04b\";s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 07:26:25.635688\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 07:26:25'),(119,1,'AppBundle\\Entity\\User',1,'a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-23 07:26:25.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','a:1:{s:9:\"updatedAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-10-18 15:35:11.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}','2018-10-23 07:26:27');
/*!40000 ALTER TABLE `log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `measure`
--

DROP TABLE IF EXISTS `measure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `measure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `risk_id` int(11) DEFAULT NULL,
  `opportunity_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_80071925A76ED395` (`user_id`),
  KEY `IDX_80071925235B6D1` (`risk_id`),
  KEY `IDX_800719259A34590F` (`opportunity_id`),
  CONSTRAINT `FK_80071925235B6D1` FOREIGN KEY (`risk_id`) REFERENCES `risk` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_800719259A34590F` FOREIGN KEY (`opportunity_id`) REFERENCES `opportunity` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_80071925A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `measure`
--

LOCK TABLES `measure` WRITE;
/*!40000 ALTER TABLE `measure` DISABLE KEYS */;
/*!40000 ALTER TABLE `measure` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `measure_comment`
--

DROP TABLE IF EXISTS `measure_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `measure_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `measure_id` int(11) DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F0F8614A76ED395` (`user_id`),
  KEY `IDX_F0F86145DA37D00` (`measure_id`),
  CONSTRAINT `FK_F0F86145DA37D00` FOREIGN KEY (`measure_id`) REFERENCES `measure` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_F0F8614A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `measure_comment`
--

LOCK TABLES `measure_comment` WRITE;
/*!40000 ALTER TABLE `measure_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `measure_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `measure_comment_media`
--

DROP TABLE IF EXISTS `measure_comment_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `measure_comment_media` (
  `measure_comment_id` int(11) NOT NULL,
  `media_id` int(11) NOT NULL,
  PRIMARY KEY (`measure_comment_id`,`media_id`),
  KEY `IDX_15CA1F448008ECCF` (`measure_comment_id`),
  KEY `IDX_15CA1F44EA9FDD75` (`media_id`),
  CONSTRAINT `FK_15CA1F448008ECCF` FOREIGN KEY (`measure_comment_id`) REFERENCES `measure_comment` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_15CA1F44EA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `measure_comment_media`
--

LOCK TABLES `measure_comment_media` WRITE;
/*!40000 ALTER TABLE `measure_comment_media` DISABLE KEYS */;
/*!40000 ALTER TABLE `measure_comment_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `measure_media`
--

DROP TABLE IF EXISTS `measure_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `measure_media` (
  `measure_id` int(11) NOT NULL,
  `media_id` int(11) NOT NULL,
  PRIMARY KEY (`measure_id`,`media_id`),
  KEY `IDX_A6670FDF5DA37D00` (`measure_id`),
  KEY `IDX_A6670FDFEA9FDD75` (`media_id`),
  CONSTRAINT `FK_A6670FDF5DA37D00` FOREIGN KEY (`measure_id`) REFERENCES `measure` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_A6670FDFEA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `measure_media`
--

LOCK TABLES `measure_media` WRITE;
/*!40000 ALTER TABLE `measure_media` DISABLE KEYS */;
/*!40000 ALTER TABLE `measure_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_system_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `path` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `original_name` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6A2CA10C5E9A90D3` (`file_system_id`),
  KEY `IDX_6A2CA10CA76ED395` (`user_id`),
  CONSTRAINT `FK_6A2CA10C5E9A90D3` FOREIGN KEY (`file_system_id`) REFERENCES `file_system` (`id`),
  CONSTRAINT `FK_6A2CA10CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_meeting`
--

DROP TABLE IF EXISTS `media_meeting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_meeting` (
  `media_id` int(11) NOT NULL,
  `meeting_id` int(11) NOT NULL,
  PRIMARY KEY (`media_id`,`meeting_id`),
  KEY `IDX_94C26770EA9FDD75` (`media_id`),
  KEY `IDX_94C2677067433D9C` (`meeting_id`),
  CONSTRAINT `FK_94C2677067433D9C` FOREIGN KEY (`meeting_id`) REFERENCES `meeting` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_94C26770EA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_meeting`
--

LOCK TABLES `media_meeting` WRITE;
/*!40000 ALTER TABLE `media_meeting` DISABLE KEYS */;
/*!40000 ALTER TABLE `media_meeting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meeting`
--

DROP TABLE IF EXISTS `meeting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meeting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `start` time NOT NULL,
  `end` time NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `meeting_category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F515E139166D1F9C` (`project_id`),
  KEY `IDX_F515E139A76ED395` (`user_id`),
  KEY `IDX_F515E1393E269397` (`meeting_category_id`),
  CONSTRAINT `FK_F515E139166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_F515E1393E269397` FOREIGN KEY (`meeting_category_id`) REFERENCES `meeting_category` (`id`),
  CONSTRAINT `FK_F515E139A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meeting`
--

LOCK TABLES `meeting` WRITE;
/*!40000 ALTER TABLE `meeting` DISABLE KEYS */;
/*!40000 ALTER TABLE `meeting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meeting_agenda`
--

DROP TABLE IF EXISTS `meeting_agenda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meeting_agenda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meeting_id` int(11) DEFAULT NULL,
  `responsibility_id` int(11) DEFAULT NULL,
  `topic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start` time NOT NULL,
  `duration` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_2C85F93567433D9C` (`meeting_id`),
  KEY `IDX_2C85F935385A88B7` (`responsibility_id`),
  CONSTRAINT `FK_2C85F935385A88B7` FOREIGN KEY (`responsibility_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_2C85F93567433D9C` FOREIGN KEY (`meeting_id`) REFERENCES `meeting` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meeting_agenda`
--

LOCK TABLES `meeting_agenda` WRITE;
/*!40000 ALTER TABLE `meeting_agenda` DISABLE KEYS */;
/*!40000 ALTER TABLE `meeting_agenda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meeting_category`
--

DROP TABLE IF EXISTS `meeting_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meeting_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_95DD34145E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meeting_category`
--

LOCK TABLES `meeting_category` WRITE;
/*!40000 ALTER TABLE `meeting_category` DISABLE KEYS */;
INSERT INTO `meeting_category` VALUES (1,'Internal'),(4,'Planning'),(3,'Review'),(2,'Supplier Meeting');
/*!40000 ALTER TABLE `meeting_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meeting_objective`
--

DROP TABLE IF EXISTS `meeting_objective`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meeting_objective` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meeting_id` int(11) DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4FBC467767433D9C` (`meeting_id`),
  CONSTRAINT `FK_4FBC467767433D9C` FOREIGN KEY (`meeting_id`) REFERENCES `meeting` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meeting_objective`
--

LOCK TABLES `meeting_objective` WRITE;
/*!40000 ALTER TABLE `meeting_objective` DISABLE KEYS */;
/*!40000 ALTER TABLE `meeting_objective` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meeting_participant`
--

DROP TABLE IF EXISTS `meeting_participant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meeting_participant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meeting_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_present` tinyint(1) NOT NULL DEFAULT '0',
  `is_excused` tinyint(1) NOT NULL DEFAULT '0',
  `in_distribution_list` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_FBFF656467433D9C` (`meeting_id`),
  KEY `IDX_FBFF6564A76ED395` (`user_id`),
  CONSTRAINT `FK_FBFF656467433D9C` FOREIGN KEY (`meeting_id`) REFERENCES `meeting` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_FBFF6564A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meeting_participant`
--

LOCK TABLES `meeting_participant` WRITE;
/*!40000 ALTER TABLE `meeting_participant` DISABLE KEYS */;
/*!40000 ALTER TABLE `meeting_participant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `chat_room_id` int(11) DEFAULT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) DEFAULT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `read_at` datetime DEFAULT NULL,
  `chat_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_from_at` datetime DEFAULT NULL,
  `deleted_to_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B6BD307F166D1F9C` (`project_id`),
  KEY `IDX_B6BD307F1819BCFA` (`chat_room_id`),
  KEY `IDX_B6BD307F78CED90B` (`from_id`),
  KEY `IDX_B6BD307F30354A65` (`to_id`),
  KEY `chat_key_idx` (`chat_key`),
  CONSTRAINT `FK_B6BD307F166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_B6BD307F1819BCFA` FOREIGN KEY (`chat_room_id`) REFERENCES `chat_room` (`id`),
  CONSTRAINT `FK_B6BD307F30354A65` FOREIGN KEY (`to_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_B6BD307F78CED90B` FOREIGN KEY (`from_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message`
--

LOCK TABLES `message` WRITE;
/*!40000 ALTER TABLE `message` DISABLE KEYS */;
/*!40000 ALTER TABLE `message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration_versions`
--

DROP TABLE IF EXISTS `migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration_versions` (
  `version` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration_versions`
--

LOCK TABLES `migration_versions` WRITE;
/*!40000 ALTER TABLE `migration_versions` DISABLE KEYS */;
INSERT INTO `migration_versions` VALUES ('20161025165242'),('20161026131848'),('20161028083609'),('20161031081027'),('20161107080557'),('20161109073824'),('20161205120530'),('20161214085818'),('20161221011920'),('20161221145544'),('20161222103324'),('20161231111845'),('20170113114959'),('20170118064927'),('20170123075759'),('20170126091753'),('20170126203740'),('20170131111109'),('20170201104233'),('20170202085628'),('20170207082245'),('20170215145444'),('20170216072727'),('20170216093204'),('20170221160336'),('20170224113503'),('20170228095917'),('20170302171458'),('20170308141409'),('20170315074509'),('20170320095845'),('20170325180249'),('20170327094123'),('20170327115935'),('20170327210712'),('20170328075424'),('20170328140535'),('20170329081803'),('20170330100733'),('20170331073349'),('20170405142144'),('20170405143150'),('20170411024650'),('20170412051847'),('20170412062355'),('20170412120527'),('20170414033055'),('20170418014743'),('20170418215154'),('20170419094240'),('20170424144629'),('20170428023058'),('20170428125809'),('20170508131840'),('20170510073333'),('20170511080448'),('20170522133832'),('20170523081440'),('20170529061804'),('20170601094950'),('20170601115413'),('20170606151025'),('20170608103709'),('20170612114137'),('20170619090746'),('20170713133725'),('20170718223258'),('20170728093157'),('20170731125659'),('20170810105040'),('20170810124920'),('20170814112214'),('20170816090451'),('20170823082152'),('20170823095134'),('20170825105148'),('20170828053806'),('20170830122846'),('20170831130244'),('20170831130838'),('20170904134930'),('20170906134220'),('20170910230519'),('20170912130611'),('20170927003220'),('20171004080619'),('20171012075321'),('20171022182134'),('20171023101635'),('20171112230202'),('20171205122443'),('20171205133749'),('20171221025654'),('20180110063557'),('20180125202800'),('20180213032027'),('20180214040514'),('20180220155408'),('20180318222511'),('20180319025708'),('20180404132844'),('20180417085943'),('20180424150738'),('20180427091320'),('20180427092502'),('20180429124216'),('20180430122836'),('20180503060233'),('20180508002032'),('20180510072526'),('20180510095012'),('20180510222423'),('20180511125638'),('20180517144141'),('20180517145244'),('20180523072834'),('20180523073237'),('20180523073638'),('20180529044737'),('20180603182121'),('20180604080432'),('20180604135921'),('20180604170340'),('20180605104127'),('20180612080156'),('20180612135552'),('20180618124903'),('20180624054350'),('20180625090735'),('20180628083436'),('20180628131628'),('20180704120139'),('20180710134842'),('20180813210725'),('20180825000125'),('20180827131329'),('20180829191922'),('20180924102636'),('20180924115856'),('20180925080932'),('20180925113527'),('20180927100836');
/*!40000 ALTER TABLE `migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `note`
--

DROP TABLE IF EXISTS `note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `meeting_id` int(11) DEFAULT NULL,
  `responsibility_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `show_in_status_report` tinyint(1) NOT NULL DEFAULT '0',
  `date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CFBDFA14166D1F9C` (`project_id`),
  KEY `IDX_CFBDFA1467433D9C` (`meeting_id`),
  KEY `IDX_CFBDFA14385A88B7` (`responsibility_id`),
  KEY `IDX_CFBDFA146BF700BD` (`status_id`),
  CONSTRAINT `FK_CFBDFA14166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_CFBDFA14385A88B7` FOREIGN KEY (`responsibility_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_CFBDFA1467433D9C` FOREIGN KEY (`meeting_id`) REFERENCES `meeting` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_CFBDFA146BF700BD` FOREIGN KEY (`status_id`) REFERENCES `note_status` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `note`
--

LOCK TABLES `note` WRITE;
/*!40000 ALTER TABLE `note` DISABLE KEYS */;
/*!40000 ALTER TABLE `note` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `note_status`
--

DROP TABLE IF EXISTS `note_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `note_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_63D232C85E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `note_status`
--

LOCK TABLES `note_status` WRITE;
/*!40000 ALTER TABLE `note_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `note_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opportunity`
--

DROP TABLE IF EXISTS `opportunity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opportunity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `opportunity_strategy_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `opportunity_status_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `impact` int(11) NOT NULL,
  `probability` int(11) NOT NULL,
  `cost_savings` decimal(9,2) DEFAULT NULL,
  `time_savings` decimal(9,2) DEFAULT NULL,
  `priority` int(11) NOT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `time_unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8389C3D7DED0E39F` (`opportunity_strategy_id`),
  KEY `IDX_8389C3D7A76ED395` (`user_id`),
  KEY `IDX_8389C3D7EF666483` (`opportunity_status_id`),
  KEY `IDX_8389C3D7166D1F9C` (`project_id`),
  KEY `IDX_8389C3D7DE12AB56` (`created_by`),
  CONSTRAINT `FK_8389C3D7166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_8389C3D7A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_8389C3D7DE12AB56` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_8389C3D7DED0E39F` FOREIGN KEY (`opportunity_strategy_id`) REFERENCES `opportunity_strategy` (`id`),
  CONSTRAINT `FK_8389C3D7EF666483` FOREIGN KEY (`opportunity_status_id`) REFERENCES `opportunity_status` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opportunity`
--

LOCK TABLES `opportunity` WRITE;
/*!40000 ALTER TABLE `opportunity` DISABLE KEYS */;
/*!40000 ALTER TABLE `opportunity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opportunity_status`
--

DROP TABLE IF EXISTS `opportunity_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opportunity_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_383118785E237E06` (`name`),
  UNIQUE KEY `UNIQ_3831187877153098` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opportunity_status`
--

LOCK TABLES `opportunity_status` WRITE;
/*!40000 ALTER TABLE `opportunity_status` DISABLE KEYS */;
INSERT INTO `opportunity_status` VALUES (1,'label.open','open'),(2,'label.follow_up','followup'),(3,'label.taken','taken');
/*!40000 ALTER TABLE `opportunity_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opportunity_strategy`
--

DROP TABLE IF EXISTS `opportunity_strategy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opportunity_strategy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_3B63A5FE5E237E06` (`name`),
  KEY `IDX_3B63A5FE166D1F9C` (`project_id`),
  CONSTRAINT `FK_3B63A5FE166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opportunity_strategy`
--

LOCK TABLES `opportunity_strategy` WRITE;
/*!40000 ALTER TABLE `opportunity_strategy` DISABLE KEYS */;
INSERT INTO `opportunity_strategy` VALUES (1,'label.share',NULL),(2,'label.enhance',NULL),(3,'label.ignore',NULL);
/*!40000 ALTER TABLE `opportunity_strategy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_id` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6D28840D4C3A3BB` (`payment_id`),
  KEY `IDX_6D28840D296CD8AE` (`team_id`),
  CONSTRAINT `FK_6D28840D296CD8AE` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`),
  CONSTRAINT `FK_6D28840D4C3A3BB` FOREIGN KEY (`payment_id`) REFERENCES `payment_method` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment`
--

LOCK TABLES `payment` WRITE;
/*!40000 ALTER TABLE `payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_method`
--

DROP TABLE IF EXISTS `payment_method`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_method` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_method`
--

LOCK TABLES `payment_method` WRITE;
/*!40000 ALTER TABLE `payment_method` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_method` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portfolio`
--

DROP TABLE IF EXISTS `portfolio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `portfolio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_A9ED10625E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portfolio`
--

LOCK TABLES `portfolio` WRITE;
/*!40000 ALTER TABLE `portfolio` DISABLE KEYS */;
INSERT INTO `portfolio` VALUES (1,'Business',NULL,'2018-09-06 18:33:37','2018-09-06 18:33:37'),(2,'New Markets',NULL,'2018-09-06 18:34:06','2018-09-06 18:34:06');
/*!40000 ALTER TABLE `portfolio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programme`
--

DROP TABLE IF EXISTS `programme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `programme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_3DDCB9FF5E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programme`
--

LOCK TABLES `programme` WRITE;
/*!40000 ALTER TABLE `programme` DISABLE KEYS */;
INSERT INTO `programme` VALUES (1,'Development'),(2,'Quality Improvement');
/*!40000 ALTER TABLE `programme` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project`
--

DROP TABLE IF EXISTS `project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `project_complexity_id` int(11) DEFAULT NULL,
  `project_category_id` int(11) DEFAULT NULL,
  `project_scope_id` int(11) DEFAULT NULL,
  `project_status_id` int(11) DEFAULT NULL,
  `portfolio_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_updated_at` datetime DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `programme_id` int(11) DEFAULT NULL,
  `configuration` longtext COLLATE utf8mb4_unicode_ci COMMENT '(DC2Type:json_array)',
  `label_id` int(11) DEFAULT NULL,
  `short_note` longtext COLLATE utf8mb4_unicode_ci,
  `progress` int(11) NOT NULL DEFAULT '0',
  `currency_id` int(11) DEFAULT NULL,
  `traffic_light` int(11) NOT NULL DEFAULT '2',
  `max_upload_file_size` int(11) NOT NULL DEFAULT '10485760',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2FB3D0EE96901F54` (`number`),
  KEY `IDX_2FB3D0EE979B1AD6` (`company_id`),
  KEY `IDX_2FB3D0EEE566340` (`project_complexity_id`),
  KEY `IDX_2FB3D0EEDA896A19` (`project_category_id`),
  KEY `IDX_2FB3D0EE5565D2EA` (`project_scope_id`),
  KEY `IDX_2FB3D0EE7ACB456A` (`project_status_id`),
  KEY `IDX_2FB3D0EEB96B5643` (`portfolio_id`),
  KEY `IDX_2FB3D0EE62BB7AEE` (`programme_id`),
  KEY `IDX_2FB3D0EE33B92F39` (`label_id`),
  KEY `IDX_2FB3D0EE38248176` (`currency_id`),
  CONSTRAINT `FK_2FB3D0EE33B92F39` FOREIGN KEY (`label_id`) REFERENCES `label` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_2FB3D0EE38248176` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`),
  CONSTRAINT `FK_2FB3D0EE5565D2EA` FOREIGN KEY (`project_scope_id`) REFERENCES `project_scope` (`id`),
  CONSTRAINT `FK_2FB3D0EE62BB7AEE` FOREIGN KEY (`programme_id`) REFERENCES `programme` (`id`),
  CONSTRAINT `FK_2FB3D0EE7ACB456A` FOREIGN KEY (`project_status_id`) REFERENCES `project_status` (`id`),
  CONSTRAINT `FK_2FB3D0EE979B1AD6` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
  CONSTRAINT `FK_2FB3D0EEB96B5643` FOREIGN KEY (`portfolio_id`) REFERENCES `portfolio` (`id`),
  CONSTRAINT `FK_2FB3D0EEDA896A19` FOREIGN KEY (`project_category_id`) REFERENCES `project_category` (`id`),
  CONSTRAINT `FK_2FB3D0EEE566340` FOREIGN KEY (`project_complexity_id`) REFERENCES `project_complexity` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project`
--

LOCK TABLES `project` WRITE;
/*!40000 ALTER TABLE `project` DISABLE KEYS */;
INSERT INTO `project` VALUES (1,1,NULL,1,1,1,NULL,'Welcome to CAMPR','1',NULL,NULL,'2018-08-30 14:35:06','2018-08-30 14:35:07',NULL,NULL,'{\"projectDuration\":\"12\",\"projectBudget\":\"500000\",\"projectInvolved\":\"1,20\",\"departmentsInvolved\":\"1,20\",\"strategicalMeaning\":\"high\",\"risks\":\"high\"}',NULL,NULL,0,1,2,10485760),(2,2,NULL,1,1,1,NULL,'Machine Relocation','2',NULL,NULL,'2018-10-23 05:37:43','2018-10-23 05:37:43',NULL,NULL,NULL,NULL,NULL,0,1,2,10485760),(3,2,NULL,1,1,1,NULL,'Company Anniversary','3',NULL,NULL,'2018-10-23 06:45:22','2018-10-23 06:45:22',NULL,NULL,NULL,NULL,NULL,0,1,2,10485760);
/*!40000 ALTER TABLE `project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_category`
--

DROP TABLE IF EXISTS `project_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_3B02921A5E237E06` (`name`),
  KEY `IDX_3B02921A166D1F9C` (`project_id`),
  CONSTRAINT `FK_3B02921A166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_category`
--

LOCK TABLES `project_category` WRITE;
/*!40000 ALTER TABLE `project_category` DISABLE KEYS */;
INSERT INTO `project_category` VALUES (1,NULL,'Default',0,'2018-08-30 14:33:29','2018-08-30 14:33:29');
/*!40000 ALTER TABLE `project_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_close_down`
--

DROP TABLE IF EXISTS `project_close_down`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_close_down` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `overall_impression` longtext COLLATE utf8mb4_unicode_ci,
  `performance_schedule` longtext COLLATE utf8mb4_unicode_ci,
  `organization_context` longtext COLLATE utf8mb4_unicode_ci,
  `project_management` longtext COLLATE utf8mb4_unicode_ci,
  `frozen` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_70212FF2166D1F9C` (`project_id`),
  CONSTRAINT `FK_70212FF2166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_close_down`
--

LOCK TABLES `project_close_down` WRITE;
/*!40000 ALTER TABLE `project_close_down` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_close_down` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_complexity`
--

DROP TABLE IF EXISTS `project_complexity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_complexity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_4043BDA95E237E06` (`name`),
  KEY `IDX_4043BDA9166D1F9C` (`project_id`),
  CONSTRAINT `FK_4043BDA9166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_complexity`
--

LOCK TABLES `project_complexity` WRITE;
/*!40000 ALTER TABLE `project_complexity` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_complexity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_cost_type`
--

DROP TABLE IF EXISTS `project_cost_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_cost_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C1FA0B415E237E06` (`name`),
  KEY `IDX_C1FA0B41166D1F9C` (`project_id`),
  CONSTRAINT `FK_C1FA0B41166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_cost_type`
--

LOCK TABLES `project_cost_type` WRITE;
/*!40000 ALTER TABLE `project_cost_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_cost_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_deliverable`
--

DROP TABLE IF EXISTS `project_deliverable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_deliverable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_69B253BA2576E0FD` (`contract_id`),
  KEY `IDX_69B253BA166D1F9C` (`project_id`),
  CONSTRAINT `FK_69B253BA166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_69B253BA2576E0FD` FOREIGN KEY (`contract_id`) REFERENCES `contract` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_deliverable`
--

LOCK TABLES `project_deliverable` WRITE;
/*!40000 ALTER TABLE `project_deliverable` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_deliverable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_department`
--

DROP TABLE IF EXISTS `project_department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_work_cost_type_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abbreviation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '0',
  `rate` decimal(10,2) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D5BB9AB8B5830A79` (`project_work_cost_type_id`),
  KEY `IDX_D5BB9AB8166D1F9C` (`project_id`),
  CONSTRAINT `FK_D5BB9AB8166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_D5BB9AB8B5830A79` FOREIGN KEY (`project_work_cost_type_id`) REFERENCES `project_work_cost_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_department`
--

LOCK TABLES `project_department` WRITE;
/*!40000 ALTER TABLE `project_department` DISABLE KEYS */;
INSERT INTO `project_department` VALUES (1,NULL,'R & D','r & d',0,0.00,'2018-09-06 18:35:07','2018-09-06 18:35:07',1),(2,NULL,'Quality','quality',1,0.00,'2018-09-06 18:35:25','2018-09-06 18:35:25',1),(3,NULL,'Industrial Engineering','industrial engineering',2,0.00,'2018-09-06 18:35:34','2018-09-06 18:35:34',1),(4,NULL,'Procurement','procurement',3,0.00,'2018-09-06 18:35:41','2018-09-06 18:35:41',1),(5,NULL,'Human Ressources','human ressources',4,0.00,'2018-09-06 18:35:48','2018-09-06 18:35:48',1),(6,NULL,'Sales','sales',5,0.00,'2018-09-06 18:35:54','2018-09-06 18:35:54',1),(7,NULL,'Logistics','logistics',0,0.00,'2018-10-23 05:52:02','2018-10-23 05:52:02',2),(8,NULL,'Industrial Engineering','industrial engineering',1,0.00,'2018-10-23 05:52:19','2018-10-23 05:52:19',2),(9,NULL,'Maintenance','maintenance',2,0.00,'2018-10-23 05:53:02','2018-10-23 05:53:02',2),(10,NULL,'Purchasing','purchasing',3,0.00,'2018-10-23 05:53:08','2018-10-23 05:53:08',2),(11,NULL,'Controlling','controlling',4,0.00,'2018-10-23 05:53:13','2018-10-23 05:53:13',2);
/*!40000 ALTER TABLE `project_department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_limitation`
--

DROP TABLE IF EXISTS `project_limitation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_limitation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_4A2291602576E0FD` (`contract_id`),
  KEY `IDX_4A229160166D1F9C` (`project_id`),
  CONSTRAINT `FK_4A229160166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_4A2291602576E0FD` FOREIGN KEY (`contract_id`) REFERENCES `contract` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_limitation`
--

LOCK TABLES `project_limitation` WRITE;
/*!40000 ALTER TABLE `project_limitation` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_limitation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_module`
--

DROP TABLE IF EXISTS `project_module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `module` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_enabled` tinyint(1) NOT NULL,
  `is_required` tinyint(1) DEFAULT NULL,
  `is_print` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1B80CD62166D1F9C` (`project_id`),
  CONSTRAINT `FK_1B80CD62166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_module`
--

LOCK TABLES `project_module` WRITE;
/*!40000 ALTER TABLE `project_module` DISABLE KEYS */;
INSERT INTO `project_module` VALUES (1,1,'contract',1,0,0,'2018-08-30 14:35:07','2018-08-30 14:35:07'),(2,1,'organization',1,0,0,'2018-08-30 14:35:07','2018-08-30 14:35:07'),(3,1,'phases_and_milestones',1,0,0,'2018-08-30 14:35:07','2018-08-30 14:35:07'),(4,1,'task_management',1,0,0,'2018-08-30 14:35:07','2018-08-30 14:35:07'),(5,1,'gantt_chart',1,0,0,'2018-08-30 14:35:07','2018-08-30 14:35:07'),(6,1,'rasci_matrix',1,0,0,'2018-08-30 14:35:07','2018-08-30 14:35:07'),(7,1,'wbs',1,0,0,'2018-08-30 14:35:07','2018-08-30 14:35:07'),(8,1,'internal_costs',1,0,0,'2018-08-30 14:35:07','2018-08-30 14:35:07'),(9,1,'external_costs',1,0,0,'2018-08-30 14:35:07','2018-08-30 14:35:07'),(10,1,'risks_and_opportunities',1,0,0,'2018-08-30 14:35:07','2018-08-30 14:35:07'),(11,1,'meetings',1,0,0,'2018-08-30 14:35:07','2018-08-30 14:35:07'),(12,1,'todos',1,0,0,'2018-08-30 14:35:07','2018-08-30 14:35:07'),(13,1,'infos',1,0,0,'2018-08-30 14:35:07','2018-08-30 14:35:07'),(14,1,'decisions',1,0,0,'2018-08-30 14:35:07','2018-08-30 14:35:07'),(15,1,'status_report',1,0,0,'2018-08-30 14:35:07','2018-08-30 14:35:07'),(16,1,'close_down_project',1,0,0,'2018-08-30 14:35:07','2018-08-30 14:35:07'),(17,2,'contract',1,0,0,'2018-10-23 05:37:43','2018-10-23 05:37:43'),(18,2,'organization',1,0,0,'2018-10-23 05:37:43','2018-10-23 05:37:43'),(19,2,'phases_and_milestones',1,0,0,'2018-10-23 05:37:43','2018-10-23 05:37:43'),(20,2,'task_management',1,0,0,'2018-10-23 05:37:43','2018-10-23 05:37:43'),(21,2,'internal_costs',0,0,0,'2018-10-23 05:37:43','2018-10-23 05:37:43'),(22,2,'external_costs',0,0,0,'2018-10-23 05:37:43','2018-10-23 05:37:43'),(23,2,'risks_and_opportunities',0,0,0,'2018-10-23 05:37:43','2018-10-23 05:37:43'),(24,2,'gantt_chart',1,0,0,'2018-10-23 05:37:43','2018-10-23 05:37:43'),(25,2,'rasci_matrix',1,0,0,'2018-10-23 05:37:43','2018-10-23 05:37:43'),(26,2,'wbs',0,0,0,'2018-10-23 05:37:43','2018-10-23 05:37:43'),(27,2,'meetings',1,0,0,'2018-10-23 05:37:43','2018-10-23 05:37:43'),(28,2,'todos',1,0,0,'2018-10-23 05:37:43','2018-10-23 05:37:43'),(29,2,'infos',1,0,0,'2018-10-23 05:37:43','2018-10-23 05:37:43'),(30,2,'decisions',1,0,0,'2018-10-23 05:37:43','2018-10-23 05:37:43'),(31,2,'status_report',1,0,0,'2018-10-23 05:37:43','2018-10-23 05:37:43'),(32,2,'close_down_project',1,0,0,'2018-10-23 05:37:43','2018-10-23 05:37:43'),(33,3,'contract',1,0,0,'2018-10-23 06:45:22','2018-10-23 06:45:22'),(34,3,'organization',1,0,0,'2018-10-23 06:45:22','2018-10-23 06:45:22'),(35,3,'phases_and_milestones',1,0,0,'2018-10-23 06:45:22','2018-10-23 06:48:40'),(36,3,'task_management',1,0,0,'2018-10-23 06:45:22','2018-10-23 06:45:22'),(37,3,'internal_costs',0,0,0,'2018-10-23 06:45:22','2018-10-23 06:45:22'),(38,3,'external_costs',0,0,0,'2018-10-23 06:45:22','2018-10-23 06:45:22'),(39,3,'risks_and_opportunities',0,0,0,'2018-10-23 06:45:22','2018-10-23 06:45:22'),(40,3,'gantt_chart',0,0,0,'2018-10-23 06:45:22','2018-10-23 06:45:22'),(41,3,'rasci_matrix',0,0,0,'2018-10-23 06:45:22','2018-10-23 06:45:22'),(42,3,'wbs',0,0,0,'2018-10-23 06:45:22','2018-10-23 06:45:22'),(43,3,'meetings',0,0,0,'2018-10-23 06:45:22','2018-10-23 06:45:22'),(44,3,'todos',0,0,0,'2018-10-23 06:45:22','2018-10-23 06:45:22'),(45,3,'infos',0,0,0,'2018-10-23 06:45:22','2018-10-23 06:45:22'),(46,3,'decisions',0,0,0,'2018-10-23 06:45:22','2018-10-23 06:45:22'),(47,3,'status_report',1,0,0,'2018-10-23 06:45:22','2018-10-23 06:45:22'),(48,3,'close_down_project',1,0,0,'2018-10-23 06:45:22','2018-10-23 06:45:22');
/*!40000 ALTER TABLE `project_module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_objective`
--

DROP TABLE IF EXISTS `project_objective`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_objective` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_A8AAB4D62576E0FD` (`contract_id`),
  KEY `IDX_A8AAB4D6166D1F9C` (`project_id`),
  CONSTRAINT `FK_A8AAB4D6166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_A8AAB4D62576E0FD` FOREIGN KEY (`contract_id`) REFERENCES `contract` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_objective`
--

LOCK TABLES `project_objective` WRITE;
/*!40000 ALTER TABLE `project_objective` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_objective` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_role`
--

DROP TABLE IF EXISTS `project_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '0',
  `is_lead` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_project_unique` (`name`,`project_id`),
  KEY `IDX_6EF84272727ACA70` (`parent_id`),
  KEY `IDX_6EF84272166D1F9C` (`project_id`),
  CONSTRAINT `FK_6EF84272166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_6EF84272727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `project_role` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_role`
--

LOCK TABLES `project_role` WRITE;
/*!40000 ALTER TABLE `project_role` DISABLE KEYS */;
INSERT INTO `project_role` VALUES (1,'roles.project_sponsor',1,0,'2017-01-01 00:00:00',NULL,NULL,NULL),(2,'roles.project_manager',2,0,'2017-01-01 00:00:00',NULL,NULL,NULL),(3,'roles.team_member',3,0,'2017-01-01 00:00:00',NULL,NULL,NULL),(4,'roles.team_leader',4,0,'2017-01-01 00:00:00',NULL,NULL,NULL);
/*!40000 ALTER TABLE `project_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_scope`
--

DROP TABLE IF EXISTS `project_scope`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_scope` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_13FA5C4D5E237E06` (`name`),
  KEY `IDX_13FA5C4D166D1F9C` (`project_id`),
  CONSTRAINT `FK_13FA5C4D166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_scope`
--

LOCK TABLES `project_scope` WRITE;
/*!40000 ALTER TABLE `project_scope` DISABLE KEYS */;
INSERT INTO `project_scope` VALUES (1,NULL,'Default',0,'2018-08-30 14:33:29','2018-08-30 14:33:29');
/*!40000 ALTER TABLE `project_scope` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_status`
--

DROP TABLE IF EXISTS `project_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_6CA48E565E237E06` (`name`),
  KEY `IDX_6CA48E56166D1F9C` (`project_id`),
  CONSTRAINT `FK_6CA48E56166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_status`
--

LOCK TABLES `project_status` WRITE;
/*!40000 ALTER TABLE `project_status` DISABLE KEYS */;
INSERT INTO `project_status` VALUES (1,NULL,'label.not_started',-1,'2018-08-30 14:33:29','2018-08-30 14:33:29'),(2,NULL,'label.in_progress',0,'2018-08-30 14:33:29','2018-08-30 14:33:29'),(3,NULL,'label.pending',1,'2018-08-30 14:33:29','2018-08-30 14:33:29'),(4,NULL,'label.open',2,'2018-08-30 14:33:29','2018-08-30 14:33:29'),(5,NULL,'label.closed',-1,'2018-08-30 14:33:29','2018-08-30 14:33:29');
/*!40000 ALTER TABLE `project_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_team`
--

DROP TABLE IF EXISTS `project_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `project_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_FD716E075E237E06` (`name`),
  KEY `IDX_FD716E07166D1F9C` (`project_id`),
  KEY `IDX_FD716E07727ACA70` (`parent_id`),
  CONSTRAINT `FK_FD716E07166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_FD716E07727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `project_team` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_team`
--

LOCK TABLES `project_team` WRITE;
/*!40000 ALTER TABLE `project_team` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_team` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_user`
--

DROP TABLE IF EXISTS `project_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `project_category_id` int(11) DEFAULT NULL,
  `project_team_id` int(11) DEFAULT NULL,
  `show_in_resources` tinyint(1) NOT NULL DEFAULT '1',
  `show_in_rasci` tinyint(1) DEFAULT '1',
  `show_in_org` tinyint(1) DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `company` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B4021E51A76ED395` (`user_id`),
  KEY `IDX_B4021E51166D1F9C` (`project_id`),
  KEY `IDX_B4021E51DA896A19` (`project_category_id`),
  KEY `IDX_B4021E51BF72D4CB` (`project_team_id`),
  CONSTRAINT `FK_B4021E51166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_B4021E51A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_B4021E51BF72D4CB` FOREIGN KEY (`project_team_id`) REFERENCES `project_team` (`id`),
  CONSTRAINT `FK_B4021E51DA896A19` FOREIGN KEY (`project_category_id`) REFERENCES `project_category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_user`
--

LOCK TABLES `project_user` WRITE;
/*!40000 ALTER TABLE `project_user` DISABLE KEYS */;
INSERT INTO `project_user` VALUES (1,1,1,NULL,NULL,1,1,1,'2018-08-30 14:35:07','2018-08-30 14:35:07',NULL,NULL),(2,2,1,NULL,NULL,1,1,1,'2018-08-30 15:22:03','2018-09-06 18:36:15',NULL,NULL),(3,3,1,NULL,NULL,1,1,1,'2018-08-30 15:22:28','2018-09-06 18:37:00',NULL,NULL),(4,4,1,NULL,NULL,1,1,1,'2018-09-06 18:37:58','2018-09-06 18:37:58',NULL,NULL),(5,1,2,NULL,NULL,1,1,1,'2018-10-23 05:37:43','2018-10-23 05:58:53',NULL,NULL),(6,5,2,NULL,NULL,1,1,1,'2018-10-23 05:51:26','2018-10-23 05:58:02',NULL,NULL),(7,6,2,NULL,NULL,1,1,1,'2018-10-23 05:57:38','2018-10-23 05:58:33',NULL,NULL),(8,7,2,NULL,NULL,1,1,1,'2018-10-23 05:57:38','2018-10-23 05:58:39',NULL,NULL),(9,8,2,NULL,NULL,1,1,1,'2018-10-23 05:57:39','2018-10-23 05:58:45',NULL,NULL),(10,1,3,NULL,NULL,1,1,1,'2018-10-23 06:45:22','2018-10-23 06:45:22',NULL,NULL),(11,2,3,NULL,NULL,1,0,1,'2018-10-23 06:45:47','2018-10-23 07:11:32',NULL,NULL),(12,3,3,NULL,NULL,1,0,1,'2018-10-23 06:45:47','2018-10-23 07:11:33',NULL,NULL);
/*!40000 ALTER TABLE `project_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_user_favorites`
--

DROP TABLE IF EXISTS `project_user_favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_user_favorites` (
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`project_id`,`user_id`),
  KEY `IDX_D3ED9B3166D1F9C` (`project_id`),
  KEY `IDX_D3ED9B3A76ED395` (`user_id`),
  CONSTRAINT `FK_D3ED9B3166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_D3ED9B3A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_user_favorites`
--

LOCK TABLES `project_user_favorites` WRITE;
/*!40000 ALTER TABLE `project_user_favorites` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_user_favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_user_project_department`
--

DROP TABLE IF EXISTS `project_user_project_department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_user_project_department` (
  `project_user_id` int(11) NOT NULL,
  `project_department_id` int(11) NOT NULL,
  PRIMARY KEY (`project_user_id`,`project_department_id`),
  KEY `IDX_66B3B0CA3170DFF0` (`project_user_id`),
  KEY `IDX_66B3B0CA7A1162D9` (`project_department_id`),
  CONSTRAINT `FK_66B3B0CA3170DFF0` FOREIGN KEY (`project_user_id`) REFERENCES `project_user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_66B3B0CA7A1162D9` FOREIGN KEY (`project_department_id`) REFERENCES `project_department` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_user_project_department`
--

LOCK TABLES `project_user_project_department` WRITE;
/*!40000 ALTER TABLE `project_user_project_department` DISABLE KEYS */;
INSERT INTO `project_user_project_department` VALUES (2,1),(3,6),(5,11),(6,7),(7,8),(8,9),(9,10);
/*!40000 ALTER TABLE `project_user_project_department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_user_project_role`
--

DROP TABLE IF EXISTS `project_user_project_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_user_project_role` (
  `project_user_id` int(11) NOT NULL,
  `project_role_id` int(11) NOT NULL,
  PRIMARY KEY (`project_user_id`,`project_role_id`),
  KEY `IDX_DAE7624F3170DFF0` (`project_user_id`),
  KEY `IDX_DAE7624F401D2EC9` (`project_role_id`),
  CONSTRAINT `FK_DAE7624F3170DFF0` FOREIGN KEY (`project_user_id`) REFERENCES `project_user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_DAE7624F401D2EC9` FOREIGN KEY (`project_role_id`) REFERENCES `project_role` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_user_project_role`
--

LOCK TABLES `project_user_project_role` WRITE;
/*!40000 ALTER TABLE `project_user_project_role` DISABLE KEYS */;
INSERT INTO `project_user_project_role` VALUES (1,2),(2,4),(3,1),(5,2),(10,2);
/*!40000 ALTER TABLE `project_user_project_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_work_cost_type`
--

DROP TABLE IF EXISTS `project_work_cost_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_work_cost_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_5B79D2615E237E06` (`name`),
  KEY `IDX_5B79D261166D1F9C` (`project_id`),
  CONSTRAINT `FK_5B79D261166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_work_cost_type`
--

LOCK TABLES `project_work_cost_type` WRITE;
/*!40000 ALTER TABLE `project_work_cost_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_work_cost_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rasci`
--

DROP TABLE IF EXISTS `rasci`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rasci` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `work_package_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `data` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_EAA3E345EF2F062C` (`work_package_id`),
  KEY `IDX_EAA3E345A76ED395` (`user_id`),
  CONSTRAINT `FK_EAA3E345A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_EAA3E345EF2F062C` FOREIGN KEY (`work_package_id`) REFERENCES `work_package` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rasci`
--

LOCK TABLES `rasci` WRITE;
/*!40000 ALTER TABLE `rasci` DISABLE KEYS */;
INSERT INTO `rasci` VALUES (3,NULL,1,'responsible'),(4,NULL,1,'responsible'),(5,NULL,1,'responsible'),(6,NULL,1,'responsible'),(7,NULL,1,'responsible'),(8,NULL,1,'responsible'),(9,7,1,'responsible'),(11,29,1,'responsible'),(20,30,1,'responsible'),(21,31,1,'responsible'),(22,32,1,'responsible'),(23,33,1,'responsible'),(24,34,1,'responsible'),(25,35,1,'responsible'),(26,35,3,'accountable'),(46,50,1,'responsible'),(47,49,1,'responsible'),(48,48,1,'responsible'),(49,47,1,'responsible'),(50,46,1,'responsible'),(51,45,1,'responsible'),(52,44,1,'responsible'),(53,43,1,'responsible'),(54,42,1,'responsible'),(55,41,1,'responsible'),(56,40,1,'responsible'),(57,39,1,'responsible'),(58,38,1,'responsible'),(59,37,1,'responsible'),(62,36,1,'responsible'),(63,8,1,'responsible'),(65,28,1,'responsible'),(66,69,5,'responsible'),(67,69,6,'support'),(68,69,1,'informed'),(69,70,6,'responsible'),(70,70,1,'informed'),(71,71,6,'responsible'),(72,71,1,'informed'),(73,72,6,'responsible'),(74,72,1,'informed'),(79,75,1,'responsible'),(80,75,5,'support'),(81,75,7,'consulted'),(82,76,8,'responsible'),(83,76,1,'informed'),(84,79,5,'responsible'),(85,79,1,'informed'),(88,73,6,'responsible'),(89,73,1,'informed'),(90,74,6,'responsible'),(91,74,1,'informed');
/*!40000 ALTER TABLE `rasci` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resource`
--

DROP TABLE IF EXISTS `resource`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `rate` decimal(9,2) NOT NULL,
  `project_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BC91F416166D1F9C` (`project_id`),
  KEY `IDX_BC91F4163170DFF0` (`project_user_id`),
  CONSTRAINT `FK_BC91F416166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_BC91F4163170DFF0` FOREIGN KEY (`project_user_id`) REFERENCES `project_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resource`
--

LOCK TABLES `resource` WRITE;
/*!40000 ALTER TABLE `resource` DISABLE KEYS */;
INSERT INTO `resource` VALUES (1,'Manuel Weiler','2018-09-06 18:31:12','2018-09-06 18:32:30',1,0.00,3),(2,'christoph.pohl @campr.biz','2018-09-06 18:31:24','2018-09-06 18:32:36',1,0.00,2),(3,'Beer','2018-09-06 18:32:51','2018-09-06 18:32:51',1,1.00,NULL),(4,'Mick Jagger','2018-10-23 05:57:12','2018-10-23 05:57:12',2,0.00,6),(5,'John Lennon','2018-10-23 05:58:22','2018-10-23 05:58:22',2,0.00,7),(6,'Janis Joplin','2018-10-23 05:58:22','2018-10-23 05:58:22',2,0.00,8),(7,'Joe Strummer','2018-10-23 05:58:22','2018-10-23 05:58:22',2,0.00,9),(8,'Hannes Breese','2018-10-23 05:58:53','2018-10-23 05:58:53',2,0.00,5),(10,'Manuel Weiler','2018-10-23 07:11:29','2018-10-23 07:11:29',3,0.00,12),(11,'Christoph Pohl','2018-10-23 07:11:30','2018-10-23 07:11:30',3,0.00,11);
/*!40000 ALTER TABLE `resource` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `risk`
--

DROP TABLE IF EXISTS `risk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `risk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `risk_strategy_id` int(11) DEFAULT NULL,
  `risk_category_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `risk_status_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost` decimal(25,2) DEFAULT NULL,
  `delay` decimal(9,2) DEFAULT NULL,
  `priority` int(11) NOT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `impact` int(11) NOT NULL,
  `probability` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `delay_unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7906D541FA7D163` (`risk_strategy_id`),
  KEY `IDX_7906D541C82B95B3` (`risk_category_id`),
  KEY `IDX_7906D541A76ED395` (`user_id`),
  KEY `IDX_7906D541166D1F9C` (`project_id`),
  KEY `IDX_7906D541DE12AB56` (`created_by`),
  KEY `IDX_7906D541B61C5537` (`risk_status_id`),
  CONSTRAINT `FK_7906D541166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_7906D541A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_7906D541B61C5537` FOREIGN KEY (`risk_status_id`) REFERENCES `risk_status` (`id`),
  CONSTRAINT `FK_7906D541C82B95B3` FOREIGN KEY (`risk_category_id`) REFERENCES `risk_category` (`id`),
  CONSTRAINT `FK_7906D541DE12AB56` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_7906D541FA7D163` FOREIGN KEY (`risk_strategy_id`) REFERENCES `risk_strategy` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `risk`
--

LOCK TABLES `risk` WRITE;
/*!40000 ALTER TABLE `risk` DISABLE KEYS */;
/*!40000 ALTER TABLE `risk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `risk_category`
--

DROP TABLE IF EXISTS `risk_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `risk_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E0655AAE5E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `risk_category`
--

LOCK TABLES `risk_category` WRITE;
/*!40000 ALTER TABLE `risk_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `risk_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `risk_status`
--

DROP TABLE IF EXISTS `risk_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `risk_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `risk_status`
--

LOCK TABLES `risk_status` WRITE;
/*!40000 ALTER TABLE `risk_status` DISABLE KEYS */;
INSERT INTO `risk_status` VALUES (1,'label.not_entered'),(2,'label.entered');
/*!40000 ALTER TABLE `risk_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `risk_strategy`
--

DROP TABLE IF EXISTS `risk_strategy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `risk_strategy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '0',
  `project_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F26F06825E237E06` (`name`),
  KEY `IDX_F26F0682166D1F9C` (`project_id`),
  CONSTRAINT `FK_F26F0682166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `risk_strategy`
--

LOCK TABLES `risk_strategy` WRITE;
/*!40000 ALTER TABLE `risk_strategy` DISABLE KEYS */;
INSERT INTO `risk_strategy` VALUES (1,'label.avoid',0,NULL),(2,'label.transfer',1,NULL),(3,'label.mitigate',2,NULL),(4,'label.accept',3,NULL);
/*!40000 ALTER TABLE `risk_strategy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedule`
--

DROP TABLE IF EXISTS `schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_5A3811FB5E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedule`
--

LOCK TABLES `schedule` WRITE;
/*!40000 ALTER TABLE `schedule` DISABLE KEYS */;
/*!40000 ALTER TABLE `schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_7B00651C5E237E06` (`name`),
  KEY `IDX_7B00651C166D1F9C` (`project_id`),
  CONSTRAINT `FK_7B00651C166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status`
--

LOCK TABLES `status` WRITE;
/*!40000 ALTER TABLE `status` DISABLE KEYS */;
/*!40000 ALTER TABLE `status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status_report`
--

DROP TABLE IF EXISTS `status_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `information` longtext COLLATE utf8mb4_unicode_ci COMMENT '(DC2Type:json_array)',
  `created_at` datetime NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci,
  `project_action_needed` tinyint(1) NOT NULL DEFAULT '0',
  `project_traffic_light` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7965DFC9166D1F9C` (`project_id`),
  KEY `IDX_7965DFC9A76ED395` (`user_id`),
  CONSTRAINT `FK_7965DFC9166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_7965DFC9A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status_report`
--

LOCK TABLES `status_report` WRITE;
/*!40000 ALTER TABLE `status_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `status_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status_report_config`
--

DROP TABLE IF EXISTS `status_report_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status_report_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `per_day` int(11) NOT NULL,
  `minutes_interval` int(11) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_D0B1AB76166D1F9C` (`project_id`),
  CONSTRAINT `FK_D0B1AB76166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status_report_config`
--

LOCK TABLES `status_report_config` WRITE;
/*!40000 ALTER TABLE `status_report_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `status_report_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subteam`
--

DROP TABLE IF EXISTS `subteam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subteam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `parent_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1D55D494166D1F9C` (`project_id`),
  KEY `IDX_1D55D494727ACA70` (`parent_id`),
  KEY `IDX_1D55D494AE80F5DF` (`department_id`),
  CONSTRAINT `FK_1D55D494166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_1D55D494727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `subteam` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_1D55D494AE80F5DF` FOREIGN KEY (`department_id`) REFERENCES `project_department` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subteam`
--

LOCK TABLES `subteam` WRITE;
/*!40000 ALTER TABLE `subteam` DISABLE KEYS */;
/*!40000 ALTER TABLE `subteam` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subteam_member`
--

DROP TABLE IF EXISTS `subteam_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subteam_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `subteam_id` int(11) NOT NULL,
  `is_lead` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_497FDE14A76ED395` (`user_id`),
  KEY `IDX_497FDE14620E7061` (`subteam_id`),
  CONSTRAINT `FK_497FDE14620E7061` FOREIGN KEY (`subteam_id`) REFERENCES `subteam` (`id`),
  CONSTRAINT `FK_497FDE14A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subteam_member`
--

LOCK TABLES `subteam_member` WRITE;
/*!40000 ALTER TABLE `subteam_member` DISABLE KEYS */;
/*!40000 ALTER TABLE `subteam_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subteam_role`
--

DROP TABLE IF EXISTS `subteam_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subteam_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subteam_role`
--

LOCK TABLES `subteam_role` WRITE;
/*!40000 ALTER TABLE `subteam_role` DISABLE KEYS */;
/*!40000 ALTER TABLE `subteam_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subteam_role_subteam_member`
--

DROP TABLE IF EXISTS `subteam_role_subteam_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subteam_role_subteam_member` (
  `subteam_role_id` int(11) NOT NULL,
  `subteam_member_id` int(11) NOT NULL,
  PRIMARY KEY (`subteam_role_id`,`subteam_member_id`),
  KEY `IDX_C158B1E2923E94FB` (`subteam_role_id`),
  KEY `IDX_C158B1E2A26F4AE9` (`subteam_member_id`),
  CONSTRAINT `FK_C158B1E2923E94FB` FOREIGN KEY (`subteam_role_id`) REFERENCES `subteam_role` (`id`),
  CONSTRAINT `FK_C158B1E2A26F4AE9` FOREIGN KEY (`subteam_member_id`) REFERENCES `subteam_member` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subteam_role_subteam_member`
--

LOCK TABLES `subteam_role_subteam_member` WRITE;
/*!40000 ALTER TABLE `subteam_role_subteam_member` DISABLE KEYS */;
/*!40000 ALTER TABLE `subteam_role_subteam_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team`
--

DROP TABLE IF EXISTS `team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `encryption_key` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `available` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '(DC2Type:uuid)',
  `logo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_unique` (`slug`),
  UNIQUE KEY `UNIQ_C4E0A61FD17F50A6` (`uuid`),
  KEY `IDX_C4E0A61FA76ED395` (`user_id`),
  CONSTRAINT `FK_C4E0A61FA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team`
--

LOCK TABLES `team` WRITE;
/*!40000 ALTER TABLE `team` DISABLE KEYS */;
/*!40000 ALTER TABLE `team` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_invite`
--

DROP TABLE IF EXISTS `team_invite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team_invite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `accepted_at` datetime DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `inviter_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B1F9570E296CD8AE` (`team_id`),
  KEY `IDX_B1F9570EA76ED395` (`user_id`),
  KEY `IDX_B1F9570E166D1F9C` (`project_id`),
  KEY `IDX_B1F9570EB79F4F04` (`inviter_id`),
  CONSTRAINT `FK_B1F9570E166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_B1F9570E296CD8AE` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_B1F9570EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_B1F9570EB79F4F04` FOREIGN KEY (`inviter_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_invite`
--

LOCK TABLES `team_invite` WRITE;
/*!40000 ALTER TABLE `team_invite` DISABLE KEYS */;
INSERT INTO `team_invite` VALUES (1,NULL,2,'beta2@campr.biz','26405b880b9bda55e3.01644720','2018-08-30 15:22:03','2018-10-22 17:18:11',1,NULL),(2,NULL,3,'claragoodman@campr.biz','36905b880bb5222605.79389411','2018-08-30 15:22:29','2018-08-30 16:11:52',1,NULL),(3,NULL,4,'beta3@campr.biz','46545b917406972e40.85865878','2018-09-06 18:37:58',NULL,1,NULL);
/*!40000 ALTER TABLE `team_invite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_member`
--

DROP TABLE IF EXISTS `team_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json_array)',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6FFBDA1A76ED395` (`user_id`),
  KEY `IDX_6FFBDA1296CD8AE` (`team_id`),
  CONSTRAINT `FK_6FFBDA1296CD8AE` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_6FFBDA1A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_member`
--

LOCK TABLES `team_member` WRITE;
/*!40000 ALTER TABLE `team_member` DISABLE KEYS */;
/*!40000 ALTER TABLE `team_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_slug`
--

DROP TABLE IF EXISTS `team_slug`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team_slug` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_id` int(11) DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_497C6F19989D9B62` (`slug`),
  KEY `IDX_497C6F19296CD8AE` (`team_id`),
  CONSTRAINT `FK_497C6F19296CD8AE` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_slug`
--

LOCK TABLES `team_slug` WRITE;
/*!40000 ALTER TABLE `team_slug` DISABLE KEYS */;
/*!40000 ALTER TABLE `team_slug` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timephase`
--

DROP TABLE IF EXISTS `timephase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timephase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assignment_id` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `unit` int(11) NOT NULL,
  `value` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `started_at` datetime DEFAULT NULL,
  `finished_at` datetime DEFAULT NULL,
  `external_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_4125AFFD9F75D7B0` (`external_id`),
  KEY `IDX_4125AFFDD19302F8` (`assignment_id`),
  CONSTRAINT `FK_4125AFFDD19302F8` FOREIGN KEY (`assignment_id`) REFERENCES `assignment` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timephase`
--

LOCK TABLES `timephase` WRITE;
/*!40000 ALTER TABLE `timephase` DISABLE KEYS */;
/*!40000 ALTER TABLE `timephase` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `todo`
--

DROP TABLE IF EXISTS `todo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `todo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `meeting_id` int(11) DEFAULT NULL,
  `responsibility_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `show_in_status_report` tinyint(1) NOT NULL DEFAULT '0',
  `due_date` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `todo_category_id` int(11) DEFAULT NULL,
  `status_updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5A0EB6A0166D1F9C` (`project_id`),
  KEY `IDX_5A0EB6A067433D9C` (`meeting_id`),
  KEY `IDX_5A0EB6A0385A88B7` (`responsibility_id`),
  KEY `IDX_5A0EB6A06BF700BD` (`status_id`),
  KEY `IDX_5A0EB6A07A86D49F` (`todo_category_id`),
  CONSTRAINT `FK_5A0EB6A0166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_5A0EB6A0385A88B7` FOREIGN KEY (`responsibility_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_5A0EB6A067433D9C` FOREIGN KEY (`meeting_id`) REFERENCES `meeting` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_5A0EB6A06BF700BD` FOREIGN KEY (`status_id`) REFERENCES `todo_status` (`id`),
  CONSTRAINT `FK_5A0EB6A07A86D49F` FOREIGN KEY (`todo_category_id`) REFERENCES `todo_category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `todo`
--

LOCK TABLES `todo` WRITE;
/*!40000 ALTER TABLE `todo` DISABLE KEYS */;
/*!40000 ALTER TABLE `todo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `todo_category`
--

DROP TABLE IF EXISTS `todo_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `todo_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_219B51A15E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `todo_category`
--

LOCK TABLES `todo_category` WRITE;
/*!40000 ALTER TABLE `todo_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `todo_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `todo_status`
--

DROP TABLE IF EXISTS `todo_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `todo_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_128BF03E5E237E06` (`name`),
  UNIQUE KEY `UNIQ_128BF03E77153098` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `todo_status`
--

LOCK TABLES `todo_status` WRITE;
/*!40000 ALTER TABLE `todo_status` DISABLE KEYS */;
INSERT INTO `todo_status` VALUES (1,'todo_status.initiated','initiated'),(2,'todo_status.ongoing','ongoing'),(3,'todo_status.finished','finished'),(4,'todo_status.on_hold','on_hold'),(5,'todo_status.discontinued','discountinued');
/*!40000 ALTER TABLE `todo_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unit`
--

DROP TABLE IF EXISTS `unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_DCBB0C535E237E06` (`name`),
  KEY `IDX_DCBB0C53166D1F9C` (`project_id`),
  CONSTRAINT `FK_DCBB0C53166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unit`
--

LOCK TABLES `unit` WRITE;
/*!40000 ALTER TABLE `unit` DISABLE KEYS */;
INSERT INTO `unit` VALUES (1,NULL,'label.days',0,'2018-08-30 16:33:16','2018-08-30 16:33:16'),(2,NULL,'label.weeks',1,'2018-08-30 16:33:16','2018-08-30 16:33:16'),(3,NULL,'label.months',2,'2018-08-30 16:33:16','2018-08-30 16:33:16');
/*!40000 ALTER TABLE `unit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salt` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json_array)',
  `activation_token` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activation_token_created_at` datetime DEFAULT NULL,
  `reset_password_token` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_password_token_created_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `activated_at` datetime DEFAULT NULL,
  `api_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `widget_settings` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json_array)',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gplus` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linked_in` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `medium` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_auth_secret` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trusted_computers` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json_array)',
  `sign_up_details` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json_array)',
  `locale` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `company_id` int(11) DEFAULT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '(DC2Type:uuid)',
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `suspended` tinyint(1) NOT NULL DEFAULT '0',
  `avatar_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_unique` (`username`),
  UNIQUE KEY `email_unique` (`email`),
  UNIQUE KEY `UNIQ_8D93D6497BA2F5EB` (`api_token`),
  UNIQUE KEY `UNIQ_8D93D649D17F50A6` (`uuid`),
  KEY `IDX_8D93D649979B1AD6` (`company_id`),
  CONSTRAINT `FK_8D93D649979B1AD6` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'beta1@campr.biz','beta1@campr.biz','01702911641','Lisa','Johnson','$2y$13$jThf4l57ah8iOSm1rEnv1.xxxeNdqx6qL53aHE2u6DPCNHHQ6sC1O','0be3aa0188ca1b4b4c8df2d064750dd0','[\"ROLE_SUPER_ADMIN\"]','fe9498','2018-08-30 14:34:02',NULL,NULL,'2018-08-30 14:34:01','2018-10-18 15:35:11','2018-08-30 14:34:01','2ddce9ab35a93b81395f4ab4d7968caf63d3d2715a38ecf6155200e0668c8bed157d479d816fed521cbe3aa8f1622b2bcfade3493af4ce256afc95ad1cf2b13f','[]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'[]','[]','de',NULL,'0e4ec47d-c8b1-11e8-b629-96000008a04b',1,0,'https://www.gravatar.com/avatar//3ec3ff3ca5b93d9765e0702257131a54?d=identicon'),(2,'beta2@campr.biz','beta2@campr.biz','+491772008396','Brian','York','$2y$13$RKtd4OGTR.2iL4jze1HsJ.lvbfWpGKVaAlsQ0xPsxc51ngTOBH3Ie','81c1d6f0c58dea6066134556a53d5088','[\"ROLE_USER\"]','441357','2018-08-30 15:22:03',NULL,NULL,'2018-08-30 15:22:03','2018-10-15 14:37:18','2018-08-30 15:22:03','4234f3a94bda03ab54946c5f8bf778c2b9d80487f8046f0184c0b76957b716fae1f778575844e2bc6f54b7607343fbed083e2f00a40ffa48045f955143d7c859','[]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'[]','[]','en',NULL,'0e4ec380-c8b1-11e8-b629-96000008a04b',1,0,NULL), (3,'claragoodman@campr.biz','claragoodman@campr.biz','+4917610339930','Clara','Goodman','$2y$13$9mA/zFXNskbr4wQBsznsAeNHHeSddRwCKj1hochSzfBDJR4faHUrK','42ecc73073a638df97eddfd2de9989dc','[\"ROLE_USER\",\"ROLE_ADMIN\",\"ROLE_SUPER_ADMIN\"]','d0a820','2018-08-30 15:22:29',NULL,NULL,'2018-08-30 15:22:28','2018-09-06 18:31:05','2018-08-30 15:22:28','d18e56462a4151567cf91b5730ff94bb737a2d7dd3f9771baea9a2db0be1a24d496a7c563d1234f15a7a17a654ff0f860df2cdf563349b45794e7505e3fe90c8','[]',NULL,NULL,NULL,NULL,NULL,NULL,'claragoodman@campr.biz',NULL,'[]','[]','en',NULL,NULL,1,0,NULL),(4,'beta3@campr.biz','beta3@campr.biz',NULL,'Larry','Russell','$2y$13$uXg.n9vn8J6HIHZUc5.ac.1E3kKsNXSTVE/BPbeisY8LhaNAmTr9O','40bc6b2b27cf541a5bc14d05e2accad6','[\"ROLE_USER\"]','099627','2018-09-06 18:37:58',NULL,NULL,'2018-09-06 18:37:58','2018-09-06 18:37:58','2018-09-06 18:37:58','65ba8a02bb397ad14816e8dcaed3e60c956d42e2633617b39352949ea46c721335817edac7f892807287edfe94b5e431b9e213ebbae3109afec4cb4f32127e2b','[]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'[]','[]','en',NULL,NULL,1,0,NULL),(5,'Lauretta Contreras','12121221@campr.biz',NULL,'Lauretta','Contreras','$2y$13$WYv01/YQNtW5g1mxE991tepntq6EmWoels3uN0EBz4lOjBJAKOBC2','579f27c01d3142d86f898e333ac9625f','[]','94d812','2018-10-23 05:50:16',NULL,NULL,'2018-10-23 05:50:15','2018-10-23 05:50:15',NULL,'60cde1ee3b6967fb2e62807854c6c37e7242984a3a748fe8bc4325ea0183c49db04b52a2cfe85d5da7a9bfcc44b8c9717d7d338373b321e9ed0c509ba9bd41eb','[]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'[]','[]','en',2,NULL,0,0,NULL),(6,'Chelsea Hoffman','211212@campr.biz',NULL,'Chelsea','Hoffman','$2y$13$sqYKUKOyyt9ZxK/ztzdxkOEjUCT6FMAPqiYf.8nWFkFvXv.ximQ.O','8181956239c03ee31dbd66afed342770','[]','56f853','2018-10-23 05:55:08',NULL,NULL,'2018-10-23 05:55:08','2018-10-23 05:55:08',NULL,'1b8543f8844f8a323deee1a86bce9e812cd2182d6676a5b71fdf3ad11b2726b6b4e79d6030c189abd2de6d63debc218dee39da37258e8461d6660a99c62d496f','[]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'[]','[]','en',2,NULL,0,0,NULL),(7,'Melissa Higgins','3111@campr.biz',NULL,'Melissa','Higgins','$2y$13$5KFNWqpHTufBu6CZ5SRBd.O9VC58byeoAdsa/rttqJXsN8y0fJPZW','80d34c388e998b776b4c141ba62026e4','[]','bd268f','2018-10-23 05:56:05',NULL,NULL,'2018-10-23 05:56:05','2018-10-23 05:56:05',NULL,'8d36ba37053cddca81ce0869d256b69182fb5d9b68c26ceac55b9aace252053028ae8a0ae52432617bc2901c43fed859b09f099fb820f0605116b5879efe6e6a','[]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'[]','[]','en',2,NULL,0,0,NULL),(8,'Lillian Toribio','1@campr.biz',NULL,'Lillian','Toribio','$2y$13$WYOlepeYqPSObmOHWHQv8OjwI9lWLME400XgPUlw0.HHjr178bdom','acf02bea7cc42dc24e66cebc41c84b01','[]','e7d63f','2018-10-23 05:56:49',NULL,NULL,'2018-10-23 05:56:49','2018-10-23 05:56:49',NULL,'d5ab03734d6ab72256e1639d0f77bba9799c3d44dd588ef6e79b6fd63a6f0ff399758029b08ba4b0d94d3ddd01e2ea0e31e0a87befa9354e2e417770bd9075ee','[]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'[]','[]','en',2,NULL,0,0,NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_package`
--

DROP TABLE IF EXISTS `work_package`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `work_package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `responsibility_id` int(11) DEFAULT NULL,
  `puid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Task',
  `progress` int(11) NOT NULL DEFAULT '0',
  `scheduled_start_at` date DEFAULT NULL,
  `scheduled_finish_at` date DEFAULT NULL,
  `forecast_start_at` date DEFAULT NULL,
  `forecast_finish_at` date DEFAULT NULL,
  `actual_start_at` date DEFAULT NULL,
  `actual_finish_at` date DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `results` longtext COLLATE utf8mb4_unicode_ci,
  `is_key_milestone` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `calendar_id` int(11) DEFAULT NULL,
  `external_id` int(11) DEFAULT NULL,
  `work_package_status_id` int(11) DEFAULT NULL,
  `work_package_category_id` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `automatic_schedule` tinyint(1) NOT NULL DEFAULT '0',
  `duration` int(11) NOT NULL DEFAULT '0',
  `phase_id` int(11) DEFAULT NULL,
  `milestone_id` int(11) DEFAULT NULL,
  `external_actual_cost` decimal(9,2) DEFAULT NULL,
  `external_forecast_cost` decimal(9,2) DEFAULT NULL,
  `internal_actual_cost` decimal(9,2) DEFAULT NULL,
  `internal_forecast_cost` decimal(9,2) DEFAULT NULL,
  `accountability_id` int(11) DEFAULT NULL,
  `traffic_light` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_BA3DFB79F75D7B0` (`external_id`),
  KEY `IDX_BA3DFB7727ACA70` (`parent_id`),
  KEY `IDX_BA3DFB7166D1F9C` (`project_id`),
  KEY `IDX_BA3DFB7385A88B7` (`responsibility_id`),
  KEY `IDX_BA3DFB7A40A2C8` (`calendar_id`),
  KEY `IDX_BA3DFB7A73A0B9D` (`work_package_status_id`),
  KEY `IDX_BA3DFB71010AC05` (`work_package_category_id`),
  KEY `IDX_BA3DFB799091188` (`phase_id`),
  KEY `IDX_BA3DFB74B3E2EDA` (`milestone_id`),
  KEY `IDX_BA3DFB7EFF0A1F4` (`accountability_id`),
  CONSTRAINT `FK_BA3DFB71010AC05` FOREIGN KEY (`work_package_category_id`) REFERENCES `work_package_category` (`id`),
  CONSTRAINT `FK_BA3DFB7166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_BA3DFB7385A88B7` FOREIGN KEY (`responsibility_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_BA3DFB74B3E2EDA` FOREIGN KEY (`milestone_id`) REFERENCES `work_package` (`id`),
  CONSTRAINT `FK_BA3DFB7727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `work_package` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_BA3DFB799091188` FOREIGN KEY (`phase_id`) REFERENCES `work_package` (`id`),
  CONSTRAINT `FK_BA3DFB7A40A2C8` FOREIGN KEY (`calendar_id`) REFERENCES `calendar` (`id`),
  CONSTRAINT `FK_BA3DFB7A73A0B9D` FOREIGN KEY (`work_package_status_id`) REFERENCES `work_package_status` (`id`),
  CONSTRAINT `FK_BA3DFB7EFF0A1F4` FOREIGN KEY (`accountability_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_package`
--

LOCK TABLES `work_package` WRITE;
/*!40000 ALTER TABLE `work_package` DISABLE KEYS */;
INSERT INTO `work_package` VALUES (6,NULL,1,1,1,'Initiating',0,'2018-08-30','2018-09-13',NULL,NULL,NULL,NULL,NULL,NULL,0,'2018-08-30 14:35:45','2018-08-30 14:35:45',NULL,NULL,NULL,NULL,0,0,0,NULL,NULL,0.00,0.00,0.00,0.00,NULL,2),(7,NULL,1,1,2,'Develop Project Charter',100,'2018-08-30','2018-09-13','2018-08-30','2018-09-13','2018-08-30','2018-08-30','<p>Produce the Project Charter, which formally authorizes the project, names the project manager, and provides them with the authority to use the organization\'s resources to accomplish project activities</p>',NULL,0,'2018-08-30 14:36:40','2018-08-30 15:54:01',NULL,NULL,5,NULL,2,0,0,NULL,NULL,NULL,NULL,2000.00,4000.00,NULL,2),(8,NULL,1,1,1,'Identify Stakeholder',75,'2018-08-30','2018-09-13','2018-08-30','2018-09-13','2018-09-06',NULL,'<p>Identify the individuals or groups that could impact the project or be impacted by the project, analyze key characteristics of these individuals or groups, and then document the result in a stakeholder register. </p>',NULL,0,'2018-08-30 14:37:11','2018-09-06 18:18:30',NULL,NULL,3,NULL,2,0,0,6,NULL,NULL,NULL,NULL,NULL,NULL,2),(9,NULL,1,1,3,'Planning',0,'2018-09-13','2018-11-15',NULL,NULL,NULL,NULL,NULL,NULL,0,'2018-08-30 14:38:29','2018-08-30 14:38:29',NULL,NULL,NULL,NULL,0,0,0,NULL,NULL,0.00,0.00,0.00,0.00,NULL,2),(25,NULL,1,1,4,'Execute',0,'2018-11-16','2019-01-31',NULL,NULL,NULL,NULL,NULL,NULL,0,'2018-08-30 16:02:17','2018-08-30 16:02:17',NULL,NULL,NULL,NULL,0,0,0,NULL,NULL,0.00,0.00,0.00,0.00,NULL,2),(26,NULL,1,1,5,'Monitoring',0,'2018-12-17','2019-02-28',NULL,NULL,NULL,NULL,NULL,NULL,0,'2018-08-30 16:03:18','2018-08-30 16:03:18',NULL,NULL,NULL,NULL,0,0,0,NULL,NULL,0.00,0.00,0.00,0.00,NULL,2),(27,NULL,1,1,6,'Closing',0,'2019-03-01','2019-03-31','2019-03-01','2019-03-31',NULL,NULL,NULL,NULL,0,'2018-08-30 16:04:39','2018-08-30 16:27:44',NULL,NULL,NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2),(28,NULL,1,1,2,'Develop Project Charter',50,'2018-08-30','2018-09-13','2018-08-30','2018-09-13','2018-09-06',NULL,'<p>Produce the Project Charter, which formally authorizes the project, names the project manager, and provides them with the authority to use the organization\'s resources to accomplish project activities</p>',NULL,0,'2018-08-30 16:08:00','2018-09-06 18:18:38',NULL,NULL,3,NULL,2,0,0,6,NULL,NULL,NULL,2000.00,4000.00,NULL,2),(29,NULL,1,1,1,'Develop Project Management Plan',0,'2018-09-13','2018-11-15','2018-09-13','2018-11-15',NULL,NULL,'<p>Produce the project management plan - a single, cohesive guide to project execution comprised of management plans, baselines, and other documents. It is regarded by many as the most important project document. </p>',NULL,0,'2018-08-30 16:08:47','2018-08-30 16:08:47',NULL,NULL,2,NULL,2,0,0,9,NULL,NULL,NULL,NULL,8000.00,NULL,2),(30,NULL,1,1,2,'Plan Scope Management',0,'2018-09-13','2018-10-14','2018-09-13','2018-10-14',NULL,NULL,'<ul><li>Produce the scope management plan, a document that explains how the project scope will be defined, validated, and controlled</li></ul><p><br></p><ul><li>In the project context, \"scope\" refers to both product scope and project scope</li></ul>',NULL,0,'2018-08-30 16:09:18','2018-08-30 16:17:21',NULL,NULL,2,NULL,2,0,0,9,NULL,NULL,NULL,NULL,NULL,NULL,2),(31,NULL,1,1,3,'Collect Requirements',0,'2018-09-24','2018-10-31','2018-09-24','2018-10-31',NULL,NULL,'<p>Determine and document - in the requirements documentation - the stakeholders\' needs and requirements in order to meet the objectives of the project. While the PMBOK Guide does not address product requirements specifically, it acknowledges that active stakeholder involvement in the discovery and decomposition of needs is directly related to project success. </p>',NULL,0,'2018-08-30 16:09:57','2018-08-30 16:17:54',NULL,NULL,2,NULL,2,0,0,9,NULL,NULL,NULL,NULL,NULL,NULL,2),(32,NULL,1,1,4,'Define Scope',0,'2018-10-15','2018-11-15','2018-10-15','2018-11-15',NULL,NULL,'<p>Produce the project scope statement, a detailed description of both the product and the project scope</p>',NULL,0,'2018-08-30 16:10:35','2018-08-30 16:18:07',NULL,NULL,2,NULL,2,0,0,9,NULL,NULL,NULL,NULL,NULL,NULL,2),(33,NULL,1,1,5,'Create WBS',0,'2018-11-01','2018-11-15','2018-11-01','2018-11-15',NULL,NULL,'<p>Produce the work breakdown structure (WBS), a graphical subdivision of project deliverables - beginning with the scope statement - broken into smaller, more manageable components. </p>',NULL,0,'2018-08-30 16:10:56','2018-08-30 16:18:22',NULL,NULL,2,NULL,2,0,0,9,NULL,NULL,NULL,NULL,6000.00,NULL,2),(34,NULL,1,1,6,'Plan Schedule Management',0,'2018-11-01','2018-11-15','2018-11-01','2018-11-15',NULL,NULL,'<p>Produce the schedule management plan, a document that explains the policies, procedures, and documentation required to properly manage -that is, plan, develop, execute, and control - the project schedule</p>',NULL,0,'2018-08-30 16:11:17','2018-08-30 16:18:49',NULL,NULL,2,NULL,2,0,0,9,NULL,NULL,NULL,NULL,NULL,NULL,2),(35,NULL,1,1,7,'Define Activities',0,'2018-11-01','2018-11-15','2018-11-01','2018-11-15',NULL,NULL,'<p>Develop and document - in the activity list - the activities that must be performed to produce the project\'s deliverables. </p>',NULL,0,'2018-08-30 16:15:57','2018-08-30 16:19:04',NULL,NULL,2,NULL,2,0,0,9,NULL,NULL,NULL,NULL,NULL,3,2),(36,NULL,1,1,1,'Direct and Manage Project Work',25,'2018-11-16','2019-01-31','2018-11-16','2019-01-31','2018-09-06',NULL,'<p>It is the process of leading and performing the work defined in the PMP framework and implementing approved changes to achieve the project\'s objectives. </p>',NULL,0,'2018-08-30 16:16:45','2018-09-06 18:18:22',NULL,NULL,3,NULL,2,0,0,25,NULL,NULL,NULL,NULL,NULL,NULL,2),(37,NULL,1,1,2,'Manage Project Knowledge',0,'2018-11-16','2019-01-31','2018-11-16','2019-01-31',NULL,NULL,'<p>To use existing knowledge but also to create new knowledge in order to delver the project\'s objectives. </p>',NULL,0,'2018-08-30 16:19:54','2018-09-06 18:18:04',NULL,NULL,1,NULL,2,0,0,25,NULL,NULL,NULL,NULL,NULL,NULL,2),(38,NULL,1,1,3,'Manage Quality',0,'2018-11-16','2019-01-31','2018-11-16','2019-01-31',NULL,NULL,'<ul><li>The process of translating the quality management plan into executable quality activities that incorporate the organization\'s quality policies into the project</li></ul><p><br></p><ul><li>Manage Quality is sometimes called quality assurance. The focus is on the effective use of processes in the project but it also concerns product design aspects and process improvement. </li></ul>',NULL,0,'2018-08-30 16:20:29','2018-09-06 18:17:58',NULL,NULL,1,NULL,2,0,0,25,NULL,NULL,NULL,NULL,NULL,NULL,2),(39,NULL,1,1,4,'Acquire Resources',0,'2018-11-16','2019-01-31','2018-11-16','2019-01-31',NULL,NULL,'<ul><li>The process of obtaining team members, facilities, equipment, materials, supplies, and other resources necessary to complete project work. As the team may not have direct control over resource selection, the following factors should be considered: </li></ul><p><br></p><ul><li class=\"ql-indent-1\">How the PM/Team can negotiate/influence others in a position to provide the required resources</li><li class=\"ql-indent-1\">How failure to acquire the necessary resources may affect schedules, budgets, quality, and risks</li><li class=\"ql-indent-1\">If the team resources are unavailable, consideration should be made for comparable resources with equivalent skills levels or competencies</li></ul>',NULL,0,'2018-08-30 16:20:52','2018-09-06 18:17:50',NULL,NULL,1,NULL,2,0,0,25,NULL,NULL,NULL,NULL,NULL,NULL,2),(40,NULL,1,1,5,'Develop Team',0,'2018-11-16','2019-01-31','2018-11-16','2019-01-31',NULL,NULL,'<p>Improve team members competencies, team interaction, and the overall team environment; all with the goal of enhancing overall team performance </p>',NULL,0,'2018-08-30 16:21:20','2018-09-06 18:17:40',NULL,NULL,1,NULL,2,0,0,25,NULL,NULL,NULL,NULL,NULL,NULL,2),(41,NULL,1,1,6,'Manage Team',0,'2018-11-16','2019-01-31','2018-11-16','2019-01-31',NULL,NULL,'<p>Maximizing team performance by monitoring performance of team members, providing feedback to individual team members, resolving issues among them, and managing changes to the team. </p>',NULL,0,'2018-08-30 16:21:39','2018-09-06 18:17:32',NULL,NULL,1,NULL,2,0,0,25,NULL,NULL,NULL,NULL,NULL,NULL,2),(42,NULL,1,1,7,'Manage Communication',0,'2018-11-16','2019-01-31','2018-11-16','2019-01-31',NULL,NULL,'<p>The process of ensuring timely and appropriate collecting, creation, distribution, storage, retrieval, management, monitoring, and the ultimate disposition of project information. It should also allow for flexibility in the communications activities to accommodate the changing needs of stakeholders and the project. </p>',NULL,0,'2018-08-30 16:22:10','2018-09-06 18:17:24',NULL,NULL,1,NULL,2,0,0,25,NULL,NULL,NULL,NULL,NULL,NULL,2),(43,NULL,1,1,8,'Implement Risk Responses',0,'2018-11-16','2019-01-31','2018-11-16','2019-01-31',NULL,NULL,'<p>To implement agreed - upon risk response plans</p>',NULL,0,'2018-08-30 16:22:34','2018-09-06 18:17:09',NULL,NULL,1,NULL,2,0,0,25,NULL,NULL,NULL,NULL,NULL,NULL,2),(44,NULL,1,1,1,'Perform integrated Change Control',0,'2018-12-17','2019-02-28','2018-12-17','2019-02-28',NULL,NULL,'<p>PICC reviews change requests; approves / rejects requests; and manages the approved changes</p>',NULL,0,'2018-08-30 16:25:53','2018-09-06 18:17:00',NULL,NULL,1,NULL,2,0,0,26,NULL,NULL,NULL,NULL,NULL,NULL,2),(45,NULL,1,1,2,'Control Scope',0,'2018-12-17','2019-02-28','2018-12-17','2019-02-28',NULL,NULL,'<p>Monitor scope status - both product and project scope - and manage changes to the scope baseline</p>',NULL,0,'2018-08-30 16:26:22','2018-09-06 18:16:52',NULL,NULL,1,NULL,2,0,0,26,NULL,NULL,NULL,NULL,NULL,NULL,2),(46,NULL,1,1,3,'Control Resources',0,'2018-12-17','2019-02-28','2018-12-17','2019-02-28',NULL,NULL,'<ul><li>Ensuring that physical resources allocated and assigned to the project are available</li></ul><p><br></p><ul><li>Monitoring plannes vs. actual utilization of resources </li></ul>',NULL,0,'2018-08-30 16:26:48','2018-09-06 18:16:44',NULL,NULL,1,NULL,2,0,0,26,NULL,NULL,NULL,NULL,NULL,NULL,2),(47,NULL,1,1,4,'Monitor Risks',0,'2018-12-17','2019-02-28','2018-12-17','2019-02-28',NULL,NULL,'<p>Keep track of identified risks, watch out for new ones, monitor risk response plans, and evaluate the overall effectiveness of the team\'s risk approach. </p>',NULL,0,'2018-08-30 16:29:10','2018-09-06 18:16:29',NULL,NULL,1,NULL,2,0,0,26,NULL,NULL,NULL,NULL,NULL,NULL,2),(48,NULL,1,1,5,'Control Procurements',0,'2018-10-01','2018-11-30','2018-10-01','2018-11-30',NULL,NULL,'<p>Manage relations with contracted seller team members, monitoring contract performance, make resource changes and amend contracts, as required, and closing contracts</p>',NULL,0,'2018-08-30 16:29:25','2018-09-06 18:16:03',NULL,NULL,1,NULL,2,0,0,26,NULL,NULL,NULL,NULL,NULL,NULL,2),(49,NULL,1,1,6,'Monitor Stakeholder Engagement',0,'2018-12-17','2019-02-28','2018-12-17','2019-02-28',NULL,NULL,'<p>Monitor stakeholder relationships in general and tailoring strategies and stakeholder engagement plans, as needed.</p>',NULL,0,'2018-08-30 16:29:51','2018-09-06 18:15:29',NULL,NULL,1,NULL,2,0,0,26,NULL,NULL,NULL,NULL,NULL,NULL,2),(50,NULL,1,1,1,'Close Project or Phase',0,'2019-03-01','2019-03-31','2019-03-01','2019-03-31',NULL,NULL,'<p>Finalizing the project activities of all the process groups to complete the project or phase in an organized, formal manner. The PM will review the PMP project to ensure that all project work is completed and the project has met its objectives.. </p>',NULL,0,'2018-08-30 16:30:13','2018-09-06 18:15:18',NULL,NULL,1,NULL,2,0,0,27,NULL,NULL,NULL,NULL,NULL,NULL,2),(51,NULL,1,1,8,'Kick-off Planning Phase',0,NULL,'2018-09-13',NULL,NULL,NULL,NULL,NULL,NULL,0,'2018-08-31 10:45:19','2018-08-31 10:45:19',NULL,NULL,NULL,NULL,1,0,0,9,NULL,0.00,0.00,0.00,0.00,NULL,2),(52,NULL,1,1,9,'1. Review Project Plan',0,NULL,'2018-10-01',NULL,NULL,NULL,NULL,NULL,NULL,0,'2018-08-31 10:46:05','2018-08-31 10:46:05',NULL,NULL,NULL,NULL,1,0,0,9,NULL,0.00,0.00,0.00,0.00,NULL,2),(53,NULL,1,1,10,'Project Management Plan Approval',0,NULL,'2018-11-15',NULL,NULL,NULL,NULL,NULL,NULL,0,'2018-08-31 10:46:53','2018-08-31 10:46:53',NULL,NULL,NULL,NULL,1,0,0,9,NULL,0.00,0.00,0.00,0.00,NULL,2),(54,NULL,1,1,9,'Kick-off Project Execution',0,NULL,'2018-11-19',NULL,NULL,NULL,NULL,NULL,NULL,0,'2018-08-31 10:47:41','2018-08-31 10:47:41',NULL,NULL,NULL,NULL,1,0,0,25,NULL,0.00,0.00,0.00,0.00,NULL,2),(55,NULL,1,1,7,'Start Monitoring',0,NULL,'2018-12-17',NULL,NULL,NULL,NULL,NULL,NULL,0,'2018-08-31 10:48:16','2018-08-31 10:48:16',NULL,NULL,NULL,NULL,1,0,0,26,NULL,0.00,0.00,0.00,0.00,NULL,2),(56,NULL,1,1,10,'Substantial Completion',0,NULL,'2019-01-30',NULL,NULL,NULL,NULL,NULL,NULL,0,'2018-08-31 10:49:12','2018-08-31 10:49:12',NULL,NULL,NULL,NULL,1,0,0,25,NULL,0.00,0.00,0.00,0.00,NULL,2),(57,NULL,1,1,2,'Handover Project Deliverables',0,NULL,'2019-03-01',NULL,NULL,NULL,NULL,NULL,NULL,0,'2018-08-31 10:50:07','2018-08-31 10:50:07',NULL,NULL,NULL,NULL,1,0,0,27,NULL,0.00,0.00,0.00,0.00,NULL,2),(58,NULL,1,1,3,'Project Close Out',0,NULL,'2019-03-31',NULL,NULL,NULL,NULL,NULL,NULL,0,'2018-08-31 10:50:38','2018-08-31 10:50:38',NULL,NULL,NULL,NULL,1,0,0,27,NULL,0.00,0.00,0.00,0.00,NULL,2),(59,NULL,2,1,1558,'Kostengruppen und Ressourcen',0,NULL,NULL,NULL,NULL,NULL,NULL,'<p>Diese Aufgabe beschäftigt sich mit dem Kosten- und Ressourcenmanagement in Ihrem Projekt. Die Kosten und Ressourcen sind präzise definiert.</p>\n<p><strong>Interne Kosten:</strong> Hier definieren Sie die Kostengruppen Ihrer Organisation. Diese beinhalten tatsächliche Kosten Ihrer internen Projektmitglieder, um ihre Mitwirkung in Ihrem Projekt zu begleichen.</p>\n<p><strong>Externe Kosten:</strong> Definieren die Kostengruppen für externe Ausgaben. Diese können externe Projektmitarbeiter, die temporär im Projekt tätig sind, sein oder andere Dienstleistungen und Güter zur Erfüllung Ihrer Projektziele.</p>\n',NULL,0,'2018-10-23 05:37:43','2018-10-23 05:37:43',NULL,NULL,1,NULL,1558,0,0,NULL,NULL,0.00,0.00,0.00,0.00,NULL,2),(60,NULL,2,1,1558,'Optional',0,NULL,NULL,NULL,NULL,NULL,NULL,'<p>Um Ihre Arbeit in einer komplexen Umgebung mit mehreren Projekten und einer Vielzahl an Aufgaben übersichtlicher zu gestalten, können Sie Farblabels zuordnen. Im Adminbereich können Sie eine beliebige Anzahl Labels benennen und ihnen Farben zuordnen.</p>\n<p>\n    <a href=\"/assets/tasks/en/big/Task-Project-Labels.jpg\" target=\"_blank\">\n        <img src=\"/assets/tasks/en/Task-Project-Labels.jpg\" alt=\"Task-Read-Me-Dashboard-Super-Admin\" />\n    </a>\n</p>\n<p><strong>Label:</strong> Geben Sie einem Label einen Namen, definieren Sie ein Farbe und ordnen Sie es einem Projekt oder einer Aufgabe zu, um einen besseren Überblick zu erhalten.</p>\n',NULL,0,'2018-10-23 05:37:43','2018-10-23 05:37:43',NULL,NULL,1,NULL,1558,0,0,NULL,NULL,0.00,0.00,0.00,0.00,NULL,2),(61,NULL,2,1,1558,'Organisation',0,NULL,NULL,NULL,NULL,NULL,NULL,'<p>Lassen Sie uns nun zum wichtigsten Erfolgsfaktor Ihres Projektes kommen - Ihrem Team.</p>\n<p><strong>Abteilung:</strong> Fügen Sie die Namen der einzelnen Abteiungen oder externen Teams hinzu, welche Teil Ihrer Projektorganisation werden.</p>\n<p><strong>Teams:</strong> Definieren Sie hier die einzelnen Teams innerhalb Ihrer Abteilung.</p>\n<p><strong>Unternehmen:</strong> Wie lautet der Name Ihres Unternehmens und der Ihrer (externen) Partner?</p>\n<p>Abteilungen und Teams können später im Modul Organisation eingerichtet werden.</p>\n',NULL,0,'2018-10-23 05:37:43','2018-10-23 05:37:43',NULL,NULL,1,NULL,1558,0,0,NULL,NULL,0.00,0.00,0.00,0.00,NULL,2),(62,NULL,2,1,1558,'Projekteinstellungen',0,NULL,NULL,NULL,NULL,NULL,NULL,'<p>Hier finden Sie wichtige Einstellungen für Ihr Projekt, welche Sie anpassen können, sodass die den Standards Ihrer Organisation oder PMO entsprechen.</p>\n<p>\n    <a href=\"/assets/tasks/en/big/Task-Project-Settings-Step-1.jpg\" target=\"_blank\">\n        <img src=\"/assets/tasks/en/Task-Project-Settings-Step-1.jpg\" alt=\"Task-Project-Settings-Step-1\" />\n    </a>\n</p>\n<p>\n    <a href=\"/assets/tasks/en/big/Task-Project-Settings-Step-2.jpg\" target=\"_blank\">\n        <img src=\"/assets/tasks/en/Task-Project-Settings-Step-2.jpg\" alt=\"Task-Project-Settings-Step-2\" />\n    </a>\n</p>\n<p><strong>Customer:</strong> Wer ist der tatsächliche Kunde Ihres Projekts? Beispielsweise eine interne Abteilung oder ein externer Kunde?</p>\n<p><strong>Programm:</strong> Gehört Ihr Projekt zu einer größer angelegten Struktur mehrerer Projekte mit ähnlichen Zielen? Hier können Sie Ihre Projekte in einem Programm gruppieren indem Sie den Programmnamen eintragen.</p>\n<p><strong>Portfolio:</strong> Managen Sie mehrere Projekte, die zu einer gemeinsamen Strategie mit großer Wochtigkeit für Ihr Unternehmen oder Ihren Kunden? Dann tragen Sie hier den Namen des Portfolios ein.</p>\n<p>Wie ist Ihr Projekt in Ihrer Organisation integriert? Wie ist der Umfang Ihres Projektes definiert? Nutzen Sie unsere vorkonfigurierten Werte oder erstellen Sie Ihre eigenen.</p>\n<p><strong>Kategorie:</strong> Ist Ihr Projekt ein Kundenprojekt? Managen Sie ein Projekt für eine Abteilung Ihrer Organisation?</p>\n<p><strong>Umfang:</strong> Wie groß ist der Umfang Ihres Projektes? Beeinflusst es nur Ihre Abteilung, Ihren Standort oder sogar die globale Strukutur Ihrer Organisation?</p>\n',NULL,0,'2018-10-23 05:37:43','2018-10-23 05:37:43',NULL,NULL,1,NULL,1558,0,0,NULL,NULL,0.00,0.00,0.00,0.00,NULL,2),(63,NULL,2,1,1558,'Read Me',0,NULL,NULL,NULL,NULL,NULL,NULL,'<p>Herzlichen Glückwunsch - Sie haben Ihr Projekt erfolgreich erstellt! CAMPR ist ein intuitives Programm und diese Einführung beschreibt, wie Sie sich das Tool zu Nutzen machen, um Ihre Erfahrung mit CAMPR so angenehm wie möglich zu machen.</p>\n<p>CAMPR weiß, dass jedes Projekt einzigartig ist und erlaubt es Ihnen, die Einstellungen optimal auf Ihre Arbeit abzustimmen. Unser Adminbereich ist Ihnen als Projektmanager zugängig. Hier könne Sie die relevanten Einstellungen konfigurieren. Es bietet sich an die Einrichtung vor dem Projektstart vorzunehmen, aber Änderunen und Erweiterungen sind auch später jederzeit möglich.</p>\n<p>Sie werden bemerken, dass einige Einträge bereits vorkonfiguriert sind, aber Sie können die Einstellung den Anforderungen Ihres Porjektes im Adminbereich anpassen und individualisieren:</p>\n<ul>\n    <li>Projekteinstellungen</li>\n    <li>Organisation</li>\n    <li>Kosten</li>\n    <li>Optional</li>\n</ul>\n<p>Am besten passen Sie die Einstellung wie bereits erwähnt vor dem Projektstart an, aber Sie können die Einstellungen jederzeit ändern. Diese voreingestellten Aufgaben verschwinden nach 5 Tagen automatisch. Sie haben zudem keinen Einfluss auf den Projektfortschritt - sie dienen lediglich als Gedächtnisstützen.</p>\n<p>\n    <a href=\"/assets/tasks/en/big/Task-Read-Me-Workspace-Super-Admin.jpg\" target=\"_blank\">\n        <img src=\"/assets/tasks/en/Task-Read-Me-Workspace-Super-Admin.jpg\" alt=\"Task-Read-Me-Workspace-Super-Admin\" />\n    </a>\n</p>\n<p>Sie können den Adminbereich als Projektmanager betreten indem Sie das ADMIN-Feld in Ihrem Arbeitsbereich nutzen oder über das Menü in der oberen rechten Ecke.</p>\n<p>\n    <a href=\"/assets/tasks/en/big/Task-Read-Me-Dashboard-Super-Admin.jpg\" target=\"_blank\">\n        <img src=\"/assets/tasks/en/Task-Read-Me-Dashboard-Super-Admin.jpg\" alt=\"Task-Read-Me-Dashboard-Super-Admin\" />\n    </a>\n</p>\n<p><strong>Und jetzt viel Spaß mit CAMPR!</strong></p>\n',NULL,0,'2018-10-23 05:37:43','2018-10-23 05:37:43',NULL,NULL,1,NULL,1558,0,0,NULL,NULL,0.00,0.00,0.00,0.00,NULL,2),(64,NULL,2,1,1,'Planning',0,'2018-10-23','2018-11-04',NULL,NULL,NULL,NULL,'<p>Planning stage of relocationing of machine</p>',NULL,0,'2018-10-23 05:39:10','2018-10-23 05:39:10',NULL,NULL,NULL,NULL,0,0,0,NULL,NULL,0.00,0.00,0.00,0.00,NULL,2),(65,NULL,2,1,2,'Execution',0,'2018-11-05','2018-11-18',NULL,NULL,NULL,NULL,'<p>Machine must be dismatled, transported to the new site, put up and integrated into production line.</p>',NULL,0,'2018-10-23 05:42:07','2018-10-23 05:42:07',NULL,NULL,NULL,NULL,0,0,0,NULL,NULL,0.00,0.00,0.00,0.00,NULL,2),(66,NULL,2,1,3,'Monitoring',0,'2018-11-12','2018-11-25',NULL,NULL,NULL,NULL,'<p>Monitoring of the whole process of relocationing as well as optimization of detected weaknesses</p>',NULL,0,'2018-10-23 05:44:26','2018-10-23 05:44:26',NULL,NULL,NULL,NULL,0,0,0,NULL,NULL,0.00,0.00,0.00,0.00,NULL,2),(67,NULL,2,1,1,'Transport to new site',0,NULL,'2018-11-12',NULL,NULL,NULL,NULL,NULL,NULL,0,'2018-10-23 05:45:40','2018-10-23 05:45:40',NULL,NULL,NULL,NULL,1,0,0,65,NULL,0.00,0.00,0.00,0.00,NULL,2),(68,NULL,2,1,2,'Production ramp up new site',0,NULL,'2018-11-18',NULL,NULL,NULL,NULL,NULL,NULL,0,'2018-10-23 05:46:12','2018-10-23 05:46:28',NULL,NULL,NULL,NULL,1,0,0,65,NULL,0.00,0.00,0.00,0.00,NULL,2),(69,NULL,2,5,1,'Planning Transport',0,'2018-10-23','2018-11-04','2018-10-23','2018-11-04',NULL,NULL,'<p>Date, cost and legal procedure of relocating machine to nearby (20miles) site</p>',NULL,0,'2018-10-23 06:05:18','2018-10-23 06:05:19',NULL,NULL,1,NULL,2,0,0,64,NULL,NULL,NULL,NULL,1600.00,NULL,2),(70,NULL,2,6,2,'Planning Ramp up',0,'2018-10-23','2018-11-04','2018-10-23','2018-11-04',NULL,NULL,'<p>Planning the ramp up at new site and updated logistics chain</p>',NULL,0,'2018-10-23 06:07:00','2018-10-23 06:07:00',NULL,NULL,2,NULL,2,0,0,64,NULL,NULL,NULL,NULL,NULL,NULL,2),(71,NULL,2,6,3,'Dismatling and loading of machine',0,'2018-11-05','2018-11-11','2018-11-05','2018-11-11',NULL,NULL,'<p>Machine must be dismatled in order to fit on truck.  In next step, machine must be loaded on provided trailer.</p>',NULL,0,'2018-10-23 06:11:50','2018-10-23 06:11:50',NULL,NULL,2,NULL,2,0,0,65,NULL,NULL,NULL,NULL,NULL,NULL,2),(72,NULL,2,6,4,'Unloading machine at new site',0,'2018-11-12','2018-11-12','2018-11-12','2018-11-12',NULL,NULL,'<p>Support for unloading must be hired, machine will be unloaded and dismatled parts will be placed close to new position.</p>',NULL,0,'2018-10-23 06:14:58','2018-10-23 06:14:58',NULL,NULL,2,NULL,2,0,0,65,NULL,NULL,NULL,NULL,0.00,NULL,2),(73,NULL,2,6,5,'Set up machine',0,'2018-11-13','2018-11-17','2018-11-13','2018-11-15',NULL,NULL,'<p>Machine must be set up at the designated position.</p>',NULL,0,'2018-10-23 06:17:37','2018-10-23 06:43:10',NULL,NULL,2,NULL,2,0,0,65,NULL,NULL,NULL,NULL,NULL,NULL,2),(74,NULL,2,6,6,'Production Ramp up',0,'2018-11-15','2018-11-18','2018-11-15','2018-11-18',NULL,NULL,'<p>After setup and first test runs, machine must be set up to go live. </p>',NULL,0,'2018-10-23 06:22:27','2018-10-23 06:43:47',NULL,NULL,1,NULL,2,0,0,65,NULL,NULL,NULL,NULL,NULL,NULL,2),(75,NULL,2,1,1,'Register machine and maintence equipment',0,'2018-11-19','2018-11-25','2018-11-19','2018-11-25',NULL,NULL,'<p>Machine and full equipment must be registered in inventory. Contact Industrial Engineering and Maintenace for full list.</p>',NULL,0,'2018-10-23 06:31:05','2018-10-23 06:31:05',NULL,NULL,2,NULL,2,0,0,66,NULL,NULL,NULL,NULL,NULL,NULL,2),(76,NULL,2,8,3,'Change delivery contracts',0,'2018-10-23','2018-11-04','2018-10-23','2018-11-04',NULL,NULL,'<p>Suppliers and logistic operator must be informed about relocationing and contracts must be changed to new site.</p>',NULL,0,'2018-10-23 06:35:20','2018-10-23 06:35:20',NULL,NULL,2,NULL,2,0,0,64,NULL,NULL,NULL,NULL,NULL,NULL,2),(77,NULL,2,1,4,'Kickoff Workshop',0,NULL,'2018-10-23',NULL,NULL,NULL,NULL,'<p>presenting project scope and deliverables</p>',NULL,0,'2018-10-23 06:37:00','2018-10-23 06:37:00',NULL,NULL,NULL,NULL,1,0,0,64,NULL,0.00,0.00,0.00,0.00,NULL,2),(78,NULL,2,7,2,'Workshop Maintenance',0,NULL,'2018-11-19',NULL,NULL,NULL,NULL,'<p>Workshop with head of maintence site A and responsible maintenance crew new site to give detailed instructions</p>',NULL,0,'2018-10-23 06:39:03','2018-10-23 06:39:03',NULL,NULL,NULL,NULL,1,0,0,66,NULL,0.00,0.00,0.00,0.00,NULL,2),(79,NULL,2,5,3,'Monitoring logistics chain',0,'2018-11-12','2018-11-25','2018-11-12','2018-11-25',NULL,NULL,'<p>New logistics chain must be monitored and measures taken if optimization potential detected.</p>',NULL,0,'2018-10-23 06:41:18','2018-10-23 06:41:18',NULL,NULL,2,NULL,2,0,0,66,NULL,NULL,NULL,NULL,NULL,NULL,2),(80,NULL,3,1,1558,'Kostengruppen und Ressourcen',0,NULL,NULL,NULL,NULL,NULL,NULL,'<p>Diese Aufgabe beschäftigt sich mit dem Kosten- und Ressourcenmanagement in Ihrem Projekt. Die Kosten und Ressourcen sind präzise definiert.</p>\n<p><strong>Interne Kosten:</strong> Hier definieren Sie die Kostengruppen Ihrer Organisation. Diese beinhalten tatsächliche Kosten Ihrer internen Projektmitglieder, um ihre Mitwirkung in Ihrem Projekt zu begleichen.</p>\n<p><strong>Externe Kosten:</strong> Definieren die Kostengruppen für externe Ausgaben. Diese können externe Projektmitarbeiter, die temporär im Projekt tätig sind, sein oder andere Dienstleistungen und Güter zur Erfüllung Ihrer Projektziele.</p>\n',NULL,0,'2018-10-23 06:45:22','2018-10-23 06:45:22',NULL,NULL,1,NULL,1558,0,0,NULL,NULL,0.00,0.00,0.00,0.00,NULL,2),(81,NULL,3,1,1558,'Optional',0,NULL,NULL,NULL,NULL,NULL,NULL,'<p>Um Ihre Arbeit in einer komplexen Umgebung mit mehreren Projekten und einer Vielzahl an Aufgaben übersichtlicher zu gestalten, können Sie Farblabels zuordnen. Im Adminbereich können Sie eine beliebige Anzahl Labels benennen und ihnen Farben zuordnen.</p>\n<p>\n    <a href=\"/assets/tasks/en/big/Task-Project-Labels.jpg\" target=\"_blank\">\n        <img src=\"/assets/tasks/en/Task-Project-Labels.jpg\" alt=\"Task-Read-Me-Dashboard-Super-Admin\" />\n    </a>\n</p>\n<p><strong>Label:</strong> Geben Sie einem Label einen Namen, definieren Sie ein Farbe und ordnen Sie es einem Projekt oder einer Aufgabe zu, um einen besseren Überblick zu erhalten.</p>\n',NULL,0,'2018-10-23 06:45:22','2018-10-23 06:45:22',NULL,NULL,1,NULL,1558,0,0,NULL,NULL,0.00,0.00,0.00,0.00,NULL,2),(82,NULL,3,1,1558,'Organisation',0,NULL,NULL,NULL,NULL,NULL,NULL,'<p>Lassen Sie uns nun zum wichtigsten Erfolgsfaktor Ihres Projektes kommen - Ihrem Team.</p>\n<p><strong>Abteilung:</strong> Fügen Sie die Namen der einzelnen Abteiungen oder externen Teams hinzu, welche Teil Ihrer Projektorganisation werden.</p>\n<p><strong>Teams:</strong> Definieren Sie hier die einzelnen Teams innerhalb Ihrer Abteilung.</p>\n<p><strong>Unternehmen:</strong> Wie lautet der Name Ihres Unternehmens und der Ihrer (externen) Partner?</p>\n<p>Abteilungen und Teams können später im Modul Organisation eingerichtet werden.</p>\n',NULL,0,'2018-10-23 06:45:22','2018-10-23 06:45:22',NULL,NULL,1,NULL,1558,0,0,NULL,NULL,0.00,0.00,0.00,0.00,NULL,2),(83,NULL,3,1,1558,'Projekteinstellungen',0,NULL,NULL,NULL,NULL,NULL,NULL,'<p>Hier finden Sie wichtige Einstellungen für Ihr Projekt, welche Sie anpassen können, sodass die den Standards Ihrer Organisation oder PMO entsprechen.</p>\n<p>\n    <a href=\"/assets/tasks/en/big/Task-Project-Settings-Step-1.jpg\" target=\"_blank\">\n        <img src=\"/assets/tasks/en/Task-Project-Settings-Step-1.jpg\" alt=\"Task-Project-Settings-Step-1\" />\n    </a>\n</p>\n<p>\n    <a href=\"/assets/tasks/en/big/Task-Project-Settings-Step-2.jpg\" target=\"_blank\">\n        <img src=\"/assets/tasks/en/Task-Project-Settings-Step-2.jpg\" alt=\"Task-Project-Settings-Step-2\" />\n    </a>\n</p>\n<p><strong>Customer:</strong> Wer ist der tatsächliche Kunde Ihres Projekts? Beispielsweise eine interne Abteilung oder ein externer Kunde?</p>\n<p><strong>Programm:</strong> Gehört Ihr Projekt zu einer größer angelegten Struktur mehrerer Projekte mit ähnlichen Zielen? Hier können Sie Ihre Projekte in einem Programm gruppieren indem Sie den Programmnamen eintragen.</p>\n<p><strong>Portfolio:</strong> Managen Sie mehrere Projekte, die zu einer gemeinsamen Strategie mit großer Wochtigkeit für Ihr Unternehmen oder Ihren Kunden? Dann tragen Sie hier den Namen des Portfolios ein.</p>\n<p>Wie ist Ihr Projekt in Ihrer Organisation integriert? Wie ist der Umfang Ihres Projektes definiert? Nutzen Sie unsere vorkonfigurierten Werte oder erstellen Sie Ihre eigenen.</p>\n<p><strong>Kategorie:</strong> Ist Ihr Projekt ein Kundenprojekt? Managen Sie ein Projekt für eine Abteilung Ihrer Organisation?</p>\n<p><strong>Umfang:</strong> Wie groß ist der Umfang Ihres Projektes? Beeinflusst es nur Ihre Abteilung, Ihren Standort oder sogar die globale Strukutur Ihrer Organisation?</p>\n',NULL,0,'2018-10-23 06:45:22','2018-10-23 06:45:22',NULL,NULL,1,NULL,1558,0,0,NULL,NULL,0.00,0.00,0.00,0.00,NULL,2),(84,NULL,3,1,1558,'Read Me',0,NULL,NULL,NULL,NULL,NULL,NULL,'<p>Herzlichen Glückwunsch - Sie haben Ihr Projekt erfolgreich erstellt! CAMPR ist ein intuitives Programm und diese Einführung beschreibt, wie Sie sich das Tool zu Nutzen machen, um Ihre Erfahrung mit CAMPR so angenehm wie möglich zu machen.</p>\n<p>CAMPR weiß, dass jedes Projekt einzigartig ist und erlaubt es Ihnen, die Einstellungen optimal auf Ihre Arbeit abzustimmen. Unser Adminbereich ist Ihnen als Projektmanager zugängig. Hier könne Sie die relevanten Einstellungen konfigurieren. Es bietet sich an die Einrichtung vor dem Projektstart vorzunehmen, aber Änderunen und Erweiterungen sind auch später jederzeit möglich.</p>\n<p>Sie werden bemerken, dass einige Einträge bereits vorkonfiguriert sind, aber Sie können die Einstellung den Anforderungen Ihres Porjektes im Adminbereich anpassen und individualisieren:</p>\n<ul>\n    <li>Projekteinstellungen</li>\n    <li>Organisation</li>\n    <li>Kosten</li>\n    <li>Optional</li>\n</ul>\n<p>Am besten passen Sie die Einstellung wie bereits erwähnt vor dem Projektstart an, aber Sie können die Einstellungen jederzeit ändern. Diese voreingestellten Aufgaben verschwinden nach 5 Tagen automatisch. Sie haben zudem keinen Einfluss auf den Projektfortschritt - sie dienen lediglich als Gedächtnisstützen.</p>\n<p>\n    <a href=\"/assets/tasks/en/big/Task-Read-Me-Workspace-Super-Admin.jpg\" target=\"_blank\">\n        <img src=\"/assets/tasks/en/Task-Read-Me-Workspace-Super-Admin.jpg\" alt=\"Task-Read-Me-Workspace-Super-Admin\" />\n    </a>\n</p>\n<p>Sie können den Adminbereich als Projektmanager betreten indem Sie das ADMIN-Feld in Ihrem Arbeitsbereich nutzen oder über das Menü in der oberen rechten Ecke.</p>\n<p>\n    <a href=\"/assets/tasks/en/big/Task-Read-Me-Dashboard-Super-Admin.jpg\" target=\"_blank\">\n        <img src=\"/assets/tasks/en/Task-Read-Me-Dashboard-Super-Admin.jpg\" alt=\"Task-Read-Me-Dashboard-Super-Admin\" />\n    </a>\n</p>\n<p><strong>Und jetzt viel Spaß mit CAMPR!</strong></p>\n',NULL,0,'2018-10-23 06:45:22','2018-10-23 06:45:22',NULL,NULL,1,NULL,1558,0,0,NULL,NULL,0.00,0.00,0.00,0.00,NULL,2),(85,NULL,3,1,1,'CAMPR Anniversary party',0,NULL,'2018-11-10',NULL,NULL,NULL,NULL,'<p>Date picked for party</p>',NULL,0,'2018-10-23 06:50:16','2018-10-23 06:50:16',NULL,NULL,NULL,NULL,1,0,0,NULL,NULL,0.00,0.00,0.00,0.00,NULL,2),(86,NULL,3,1,2,'Planning',0,'2018-10-23','2018-11-09',NULL,NULL,NULL,NULL,NULL,NULL,0,'2018-10-23 06:50:40','2018-10-23 06:50:40',NULL,NULL,NULL,NULL,0,0,0,NULL,NULL,0.00,0.00,0.00,0.00,NULL,2),(87,NULL,3,1,1,'Party Committee Meeting',0,NULL,'2018-10-23',NULL,NULL,NULL,NULL,'<p>Discussing size, budget and brainstorming ideas on how to make throw party worth CAMPR!</p>',NULL,1,'2018-10-23 06:53:52','2018-10-23 06:53:52',NULL,NULL,NULL,NULL,1,0,0,86,NULL,0.00,0.00,0.00,0.00,NULL,2),(88,NULL,3,1,2,'Location',0,'2018-10-23','2018-10-26','2018-10-23','2018-10-26',NULL,NULL,'<p>We need a location suitable for whatever size we decide on today!</p>',NULL,0,'2018-10-23 06:58:45','2018-10-23 06:58:45',NULL,NULL,2,NULL,2,0,0,86,NULL,NULL,NULL,NULL,3000.00,NULL,2),(89,NULL,3,2,3,'Programme',0,'2018-10-23','2018-11-02','2018-10-23','2018-11-02',NULL,NULL,'<p>We need a proper programme for the evening. Ideas have to be reviewed and validated once we have a location!</p>',NULL,0,'2018-10-23 07:14:00','2018-10-23 07:14:00',NULL,NULL,2,NULL,2,0,0,86,NULL,NULL,NULL,NULL,NULL,NULL,2),(90,NULL,3,3,4,'Food',0,'2018-10-23','2018-11-09','2018-10-23','2018-11-09',NULL,NULL,'<p>Alright, the important part - food! We have need:</p><ul><li>snacks for the tables and bar</li><li>buffett including vegetarian options</li><li>A big CAMPR-Birthdaycake!</li></ul>',NULL,0,'2018-10-23 07:18:05','2018-10-23 07:18:05',NULL,NULL,2,NULL,2,0,0,86,NULL,NULL,NULL,NULL,5000.00,NULL,2),(91,NULL,3,2,5,'Drinks',0,'2018-10-23','2018-11-10','2018-10-23','2018-11-10',NULL,NULL,'<p>Including:</p><ul><li>variety of beers (IPAs, Pils, Lager, etc)</li><li>Softdrinks</li><li>Cocktail ingredients</li><li>Water!</li></ul><p><br></p><p>Cocktail ingredients based on internal survey ;)</p>',NULL,0,'2018-10-23 07:21:21','2018-10-23 07:21:21',NULL,NULL,2,NULL,2,0,0,86,NULL,NULL,NULL,NULL,NULL,NULL,2),(92,NULL,3,1,3,'Transport',0,'2018-11-01','2018-11-09','2018-11-01','2018-11-09',NULL,NULL,'<p>There must be enough convenient parking spaces available!</p><p>We should also think about renting a bus or taxis..</p>',NULL,0,'2018-10-23 07:25:20','2018-10-23 07:25:20',NULL,NULL,2,NULL,2,0,0,NULL,NULL,NULL,NULL,NULL,1500.00,NULL,2);
/*!40000 ALTER TABLE `work_package` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_package_category`
--

DROP TABLE IF EXISTS `work_package_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `work_package_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C730AC755E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_package_category`
--

LOCK TABLES `work_package_category` WRITE;
/*!40000 ALTER TABLE `work_package_category` DISABLE KEYS */;
INSERT INTO `work_package_category` VALUES (1,'label.default');
/*!40000 ALTER TABLE `work_package_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_package_comment`
--

DROP TABLE IF EXISTS `work_package_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `work_package_comment` (
  `work_package_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  PRIMARY KEY (`work_package_id`,`comment_id`),
  KEY `IDX_89DC4D52EF2F062C` (`work_package_id`),
  KEY `IDX_89DC4D52F8697D13` (`comment_id`),
  CONSTRAINT `FK_89DC4D52EF2F062C` FOREIGN KEY (`work_package_id`) REFERENCES `work_package` (`id`),
  CONSTRAINT `FK_89DC4D52F8697D13` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_package_comment`
--

LOCK TABLES `work_package_comment` WRITE;
/*!40000 ALTER TABLE `work_package_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `work_package_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_package_consulted_user`
--

DROP TABLE IF EXISTS `work_package_consulted_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `work_package_consulted_user` (
  `work_package_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`work_package_id`,`user_id`),
  KEY `IDX_988DBEA9EF2F062C` (`work_package_id`),
  KEY `IDX_988DBEA9A76ED395` (`user_id`),
  CONSTRAINT `FK_988DBEA9A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_988DBEA9EF2F062C` FOREIGN KEY (`work_package_id`) REFERENCES `work_package` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_package_consulted_user`
--

LOCK TABLES `work_package_consulted_user` WRITE;
/*!40000 ALTER TABLE `work_package_consulted_user` DISABLE KEYS */;
INSERT INTO `work_package_consulted_user` VALUES (75,7);
/*!40000 ALTER TABLE `work_package_consulted_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_package_dependency`
--

DROP TABLE IF EXISTS `work_package_dependency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `work_package_dependency` (
  `dependency_id` int(11) NOT NULL,
  `dependant_id` int(11) NOT NULL,
  PRIMARY KEY (`dependant_id`,`dependency_id`),
  KEY `IDX_14FAB8C6C2F67723` (`dependency_id`),
  KEY `IDX_14FAB8C6B3D00E54` (`dependant_id`),
  CONSTRAINT `FK_14FAB8C6B3D00E54` FOREIGN KEY (`dependant_id`) REFERENCES `work_package` (`id`),
  CONSTRAINT `FK_14FAB8C6C2F67723` FOREIGN KEY (`dependency_id`) REFERENCES `work_package` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_package_dependency`
--

LOCK TABLES `work_package_dependency` WRITE;
/*!40000 ALTER TABLE `work_package_dependency` DISABLE KEYS */;
/*!40000 ALTER TABLE `work_package_dependency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_package_informed_user`
--

DROP TABLE IF EXISTS `work_package_informed_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `work_package_informed_user` (
  `work_package_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`work_package_id`,`user_id`),
  KEY `IDX_56D96C9BEF2F062C` (`work_package_id`),
  KEY `IDX_56D96C9BA76ED395` (`user_id`),
  CONSTRAINT `FK_56D96C9BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_56D96C9BEF2F062C` FOREIGN KEY (`work_package_id`) REFERENCES `work_package` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_package_informed_user`
--

LOCK TABLES `work_package_informed_user` WRITE;
/*!40000 ALTER TABLE `work_package_informed_user` DISABLE KEYS */;
INSERT INTO `work_package_informed_user` VALUES (69,1),(70,1),(71,1),(72,1),(73,1),(74,1),(76,1),(79,1);
/*!40000 ALTER TABLE `work_package_informed_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_package_label`
--

DROP TABLE IF EXISTS `work_package_label`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `work_package_label` (
  `work_package_id` int(11) NOT NULL,
  `label_id` int(11) NOT NULL,
  PRIMARY KEY (`work_package_id`,`label_id`),
  KEY `IDX_DB7E2E3BEF2F062C` (`work_package_id`),
  KEY `IDX_DB7E2E3B33B92F39` (`label_id`),
  CONSTRAINT `FK_DB7E2E3B33B92F39` FOREIGN KEY (`label_id`) REFERENCES `label` (`id`),
  CONSTRAINT `FK_DB7E2E3BEF2F062C` FOREIGN KEY (`work_package_id`) REFERENCES `work_package` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_package_label`
--

LOCK TABLES `work_package_label` WRITE;
/*!40000 ALTER TABLE `work_package_label` DISABLE KEYS */;
/*!40000 ALTER TABLE `work_package_label` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_package_media`
--

DROP TABLE IF EXISTS `work_package_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `work_package_media` (
  `work_package_id` int(11) NOT NULL,
  `media_id` int(11) NOT NULL,
  PRIMARY KEY (`work_package_id`,`media_id`),
  KEY `IDX_BFF5DFDFEF2F062C` (`work_package_id`),
  KEY `IDX_BFF5DFDFEA9FDD75` (`media_id`),
  CONSTRAINT `FK_BFF5DFDFEA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_BFF5DFDFEF2F062C` FOREIGN KEY (`work_package_id`) REFERENCES `work_package` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_package_media`
--

LOCK TABLES `work_package_media` WRITE;
/*!40000 ALTER TABLE `work_package_media` DISABLE KEYS */;
/*!40000 ALTER TABLE `work_package_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_package_project_work_cost_type`
--

DROP TABLE IF EXISTS `work_package_project_work_cost_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `work_package_project_work_cost_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `work_package_id` int(11) DEFAULT NULL,
  `project_work_cost_type_id` int(11) DEFAULT NULL,
  `calendar_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Resource',
  `base` decimal(10,2) DEFAULT NULL,
  `change_value` decimal(10,2) DEFAULT NULL,
  `actual` decimal(10,2) DEFAULT NULL,
  `remaining` decimal(10,2) DEFAULT NULL,
  `forecast` decimal(10,2) DEFAULT NULL,
  `is_generic` tinyint(1) NOT NULL DEFAULT '0',
  `is_inactive` tinyint(1) NOT NULL DEFAULT '0',
  `is_enterprise` tinyint(1) NOT NULL DEFAULT '0',
  `is_cost_resource` tinyint(1) NOT NULL DEFAULT '0',
  `is_budget` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `external_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_912BB2C29F75D7B0` (`external_id`),
  KEY `IDX_912BB2C2EF2F062C` (`work_package_id`),
  KEY `IDX_912BB2C2B5830A79` (`project_work_cost_type_id`),
  KEY `IDX_912BB2C2A40A2C8` (`calendar_id`),
  CONSTRAINT `FK_912BB2C2A40A2C8` FOREIGN KEY (`calendar_id`) REFERENCES `calendar` (`id`),
  CONSTRAINT `FK_912BB2C2B5830A79` FOREIGN KEY (`project_work_cost_type_id`) REFERENCES `project_work_cost_type` (`id`),
  CONSTRAINT `FK_912BB2C2EF2F062C` FOREIGN KEY (`work_package_id`) REFERENCES `work_package` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_package_project_work_cost_type`
--

LOCK TABLES `work_package_project_work_cost_type` WRITE;
/*!40000 ALTER TABLE `work_package_project_work_cost_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `work_package_project_work_cost_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_package_status`
--

DROP TABLE IF EXISTS `work_package_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `work_package_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `project_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `progress` int(11) NOT NULL DEFAULT '-1',
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_640F3F0C5E237E06` (`name`),
  UNIQUE KEY `UNIQ_640F3F0C77153098` (`code`),
  KEY `IDX_640F3F0C166D1F9C` (`project_id`),
  CONSTRAINT `FK_640F3F0C166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_package_status`
--

LOCK TABLES `work_package_status` WRITE;
/*!40000 ALTER TABLE `work_package_status` DISABLE KEYS */;
INSERT INTO `work_package_status` VALUES (1,'label.open',0,1,NULL,'2017-01-01 00:00:00','2017-01-01 00:00:00',0,'open',1),(2,'label.pending',1,1,NULL,'2017-01-01 00:00:00','2017-01-01 00:00:00',0,'pending',0),(3,'label.ongoing',2,1,NULL,'2017-01-01 00:00:00','2017-01-01 00:00:00',25,'ongoing',0),(4,'label.completed',3,1,NULL,'2017-01-01 00:00:00','2017-01-01 00:00:00',100,'completed',0),(5,'label.closed',-1,0,NULL,'2017-01-01 00:00:00','2017-01-01 00:00:00',100,'closed',0);
/*!40000 ALTER TABLE `work_package_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_package_support_user`
--

DROP TABLE IF EXISTS `work_package_support_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `work_package_support_user` (
  `work_package_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`work_package_id`,`user_id`),
  KEY `IDX_6BE961F0EF2F062C` (`work_package_id`),
  KEY `IDX_6BE961F0A76ED395` (`user_id`),
  CONSTRAINT `FK_6BE961F0A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_6BE961F0EF2F062C` FOREIGN KEY (`work_package_id`) REFERENCES `work_package` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_package_support_user`
--

LOCK TABLES `work_package_support_user` WRITE;
/*!40000 ALTER TABLE `work_package_support_user` DISABLE KEYS */;
INSERT INTO `work_package_support_user` VALUES (69,6),(75,5);
/*!40000 ALTER TABLE `work_package_support_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `working_time`
--

DROP TABLE IF EXISTS `working_time`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `working_time` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `day_id` int(11) DEFAULT NULL,
  `from_time` time DEFAULT NULL,
  `to_time` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_31EE2ABF9C24126` (`day_id`),
  CONSTRAINT `FK_31EE2ABF9C24126` FOREIGN KEY (`day_id`) REFERENCES `day` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `working_time`
--

LOCK TABLES `working_time` WRITE;
/*!40000 ALTER TABLE `working_time` DISABLE KEYS */;
/*!40000 ALTER TABLE `working_time` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-10-23 12:02:47
