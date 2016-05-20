-- MySQL dump 10.13  Distrib 5.5.8, for Linux (x86_64)
--
-- Host: localhost    Database: whoiscrawler
-- ------------------------------------------------------
-- Server version	5.5.8-log

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
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact` (
  `contact_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) DEFAULT NULL,
  `organization` varchar(256) DEFAULT NULL,
  `street1` varchar(256) DEFAULT NULL,
  `street2` varchar(256) DEFAULT NULL,
  `street3` varchar(256) DEFAULT NULL,
  `street4` varchar(256) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `postal_code` varchar(45) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `telephone` varchar(45) DEFAULT NULL,
  `telephone_ext` varchar(45) DEFAULT NULL,
  `fax` varchar(45) DEFAULT NULL,
  `fax_ext` varchar(45) DEFAULT NULL,
  `parse_code` smallint(6) DEFAULT NULL,
  `raw_text` longtext,
  `unparsable` longtext,
  `audit_created_date` varchar(45) DEFAULT NULL,
  `audit_updated_date` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`contact_id`),
  KEY `audit_updated_date` (`audit_updated_date`)
) ENGINE=InnoDB AUTO_INCREMENT=1266744490 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `domain_names_whoisdatacollector`
--

DROP TABLE IF EXISTS `domain_names_whoisdatacollector`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `domain_names_whoisdatacollector` (
  `domain_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `domain_name` varchar(70) NOT NULL,
  `reshoot` smallint(6) DEFAULT '0',
  PRIMARY KEY (`domain_id`),
  KEY `domain_name` (`domain_name`),
  KEY `reshoot` (`reshoot`)
) ENGINE=InnoDB AUTO_INCREMENT=166217702 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `network`
--

DROP TABLE IF EXISTS `network`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `network` (
  `network_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_date` varchar(200) DEFAULT NULL,
  `updated_date` varchar(200) DEFAULT NULL,
  `expires_date` varchar(200) DEFAULT NULL,
  `admin_contact_id` bigint(11) DEFAULT NULL,
  `registrant_id` bigint(11) DEFAULT NULL,
  `technical_contact_id` bigint(11) DEFAULT NULL,
  `zone_contact_id` bigint(11) DEFAULT NULL,
  `billing_contact_id` bigint(11) DEFAULT NULL,
  `domain_name` varchar(70) DEFAULT NULL,
  `name_servers` text,
  `status` text,
  `raw_text` longtext,
  `audit_created_date` timestamp NULL DEFAULT NULL,
  `audit_updated_date` timestamp NULL DEFAULT NULL,
  `unparsable` longtext,
  `parse_code` smallint(6) DEFAULT NULL,
  `header_text` longtext,
  `clean_text` longtext,
  `footer_text` longtext,
  `net_range` varchar(2048) DEFAULT NULL,
  `CIDR` varchar(2048) DEFAULT NULL,
  `origin_as` varchar(2048) DEFAULT NULL,
  `net_name` varchar(2048) DEFAULT NULL,
  `net_handle` varchar(2048) DEFAULT NULL,
  `parent` varchar(2048) DEFAULT NULL,
  `ref` varchar(2048) DEFAULT NULL,
  `registry_data_id` bigint(20) NOT NULL,
  `data_error` smallint(6) DEFAULT '0',
  PRIMARY KEY (`network_id`),
  KEY `domain_name_index` (`domain_name`),
  KEY `audit_updated_date` (`audit_updated_date`),
  KEY `registry_data_id` (`registry_data_id`),
  KEY `technical_contact_id` (`technical_contact_id`),
  KEY `billing_contact_id` (`billing_contact_id`),
  KEY `admin_contact_id` (`admin_contact_id`),
  KEY `registrant_id` (`registrant_id`),
  KEY `zone_contact_id` (`zone_contact_id`),
  KEY `FK6DE15A2E7B556202` (`technical_contact_id`),
  KEY `FK6DE15A2E79B00024` (`billing_contact_id`),
  KEY `FK6DE15A2EB8CF12D0` (`admin_contact_id`),
  KEY `FK6DE15A2ED0C7A375` (`registrant_id`),
  KEY `FK6DE15A2E20710653` (`zone_contact_id`),
  KEY `FK6DE15A2EC7212EEF` (`registry_data_id`),
  CONSTRAINT `FK6DE15A2E20710653` FOREIGN KEY (`zone_contact_id`) REFERENCES `contact` (`contact_id`),
  CONSTRAINT `FK6DE15A2E79B00024` FOREIGN KEY (`billing_contact_id`) REFERENCES `contact` (`contact_id`),
  CONSTRAINT `FK6DE15A2E7B556202` FOREIGN KEY (`technical_contact_id`) REFERENCES `contact` (`contact_id`),
  CONSTRAINT `FK6DE15A2EB8CF12D0` FOREIGN KEY (`admin_contact_id`) REFERENCES `contact` (`contact_id`),
  CONSTRAINT `FK6DE15A2EC7212EEF` FOREIGN KEY (`registry_data_id`) REFERENCES `registry_data` (`registry_data_id`),
  CONSTRAINT `FK6DE15A2ED0C7A375` FOREIGN KEY (`registrant_id`) REFERENCES `contact` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `registry_data`
--

DROP TABLE IF EXISTS `registry_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registry_data` (
  `registry_data_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_date` varchar(200) DEFAULT NULL,
  `updated_date` varchar(200) DEFAULT NULL,
  `expires_date` varchar(200) DEFAULT NULL,
  `admin_contact_id` bigint(11) DEFAULT NULL,
  `registrant_id` bigint(11) DEFAULT NULL,
  `technical_contact_id` bigint(11) DEFAULT NULL,
  `zone_contact_id` bigint(11) DEFAULT NULL,
  `billing_contact_id` bigint(11) DEFAULT NULL,
  `domain_name` varchar(70) DEFAULT NULL,
  `name_servers` text,
  `status` text,
  `raw_text` longtext,
  `audit_created_date` timestamp NULL DEFAULT NULL,
  `audit_updated_date` timestamp NULL DEFAULT NULL,
  `unparsable` longtext,
  `parse_code` smallint(6) DEFAULT NULL,
  `header_text` longtext,
  `clean_text` longtext,
  `footer_text` longtext,
  `registrar_name` varchar(512) DEFAULT NULL,
  `whois_server` varchar(512) DEFAULT NULL,
  `referral_url` varchar(512) DEFAULT NULL,
  `data_error` smallint(6) DEFAULT '0',
  PRIMARY KEY (`registry_data_id`),
  KEY `domain_name_index` (`domain_name`),
  KEY `audit_updated_date` (`audit_updated_date`),
  KEY `FK68C3166C7B556202` (`technical_contact_id`),
  KEY `FK68C3166C79B00024` (`billing_contact_id`),
  KEY `FK68C3166CB8CF12D0` (`admin_contact_id`),
  KEY `FK68C3166CD0C7A375` (`registrant_id`),
  KEY `FK68C3166C20710653` (`zone_contact_id`),
  KEY `data_error` (`data_error`),
  CONSTRAINT `FK68C3166C20710653` FOREIGN KEY (`zone_contact_id`) REFERENCES `contact` (`contact_id`),
  CONSTRAINT `FK68C3166C79B00024` FOREIGN KEY (`billing_contact_id`) REFERENCES `contact` (`contact_id`),
  CONSTRAINT `FK68C3166C7B556202` FOREIGN KEY (`technical_contact_id`) REFERENCES `contact` (`contact_id`),
  CONSTRAINT `FK68C3166CB8CF12D0` FOREIGN KEY (`admin_contact_id`) REFERENCES `contact` (`contact_id`),
  CONSTRAINT `FK68C3166CD0C7A375` FOREIGN KEY (`registrant_id`) REFERENCES `contact` (`contact_id`)
) ENGINE=InnoDB AUTO_INCREMENT=136690885 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `whois_record`
--

DROP TABLE IF EXISTS `whois_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `whois_record` (
  `whois_record_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_date` varchar(200) DEFAULT NULL,
  `updated_date` varchar(200) DEFAULT NULL,
  `expires_date` varchar(200) DEFAULT NULL,
  `admin_contact_id` bigint(11) DEFAULT NULL,
  `registrant_id` bigint(11) DEFAULT NULL,
  `technical_contact_id` bigint(11) DEFAULT NULL,
  `zone_contact_id` bigint(11) DEFAULT NULL,
  `billing_contact_id` bigint(11) DEFAULT NULL,
  `domain_name` varchar(70) DEFAULT NULL,
  `name_servers` text,
  `registry_data_id` bigint(11) DEFAULT NULL,
  `status` text,
  `raw_text` longtext,
  `audit_created_date` timestamp NULL DEFAULT NULL,
  `audit_updated_date` timestamp NULL DEFAULT NULL,
  `unparsable` longtext,
  `parse_code` smallint(6) DEFAULT NULL,
  `header_text` longtext,
  `clean_text` longtext,
  `footer_text` longtext,
  `registrar_name` varchar(512) DEFAULT NULL,
  `data_error` smallint(6) DEFAULT '0',
  PRIMARY KEY (`whois_record_id`),
  KEY `domain_name_index` (`domain_name`),
  KEY `audit_updated_date` (`audit_updated_date`),
  KEY `audit_created_date` (`audit_created_date`),
  KEY `FKE043A3087B556202` (`technical_contact_id`),
  KEY `FKE043A30879B00024` (`billing_contact_id`),
  KEY `FKE043A308C7212EEF` (`registry_data_id`),
  KEY `FKE043A308B8CF12D0` (`admin_contact_id`),
  KEY `FKE043A308D0C7A375` (`registrant_id`),
  KEY `FKE043A30820710653` (`zone_contact_id`),
  KEY `data_error` (`data_error`),
  CONSTRAINT `FKE043A30820710653` FOREIGN KEY (`zone_contact_id`) REFERENCES `contact` (`contact_id`),
  CONSTRAINT `FKE043A30879B00024` FOREIGN KEY (`billing_contact_id`) REFERENCES `contact` (`contact_id`),
  CONSTRAINT `FKE043A3087B556202` FOREIGN KEY (`technical_contact_id`) REFERENCES `contact` (`contact_id`),
  CONSTRAINT `FKE043A308B8CF12D0` FOREIGN KEY (`admin_contact_id`) REFERENCES `contact` (`contact_id`),
  CONSTRAINT `FKE043A308C7212EEF` FOREIGN KEY (`registry_data_id`) REFERENCES `registry_data` (`registry_data_id`),
  CONSTRAINT `FKE043A308D0C7A375` FOREIGN KEY (`registrant_id`) REFERENCES `contact` (`contact_id`)
) ENGINE=InnoDB AUTO_INCREMENT=135996455 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-01-10  5:12:00
