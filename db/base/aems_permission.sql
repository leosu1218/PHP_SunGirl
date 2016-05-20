-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: aems
-- ------------------------------------------------------
-- Server version	5.6.25

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
-- Table structure for table `permission`
--

DROP TABLE IF EXISTS `permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity` varchar(45) NOT NULL,
  `entity_id` int(11) NOT NULL DEFAULT '0',
  `action` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission`
--

LOCK TABLES `permission` WRITE;
/*!40000 ALTER TABLE `permission` DISABLE KEYS */;
INSERT INTO `permission` VALUES (1,'platform_user',0,'read','read',NULL),(2,'platform_user',0,'list','list',NULL),(3,'platform_user',0,'create','create',NULL),(4,'platform_user',0,'delete','delete',NULL),(5,'platform_user',0,'update','update',NULL),(6,'platform_user_group',0,'read','read',NULL),(7,'platform_user_group',0,'list','list',NULL),(8,'platform_user_group',0,'create','create',NULL),(9,'platform_user_group',0,'delete','delete',NULL),(10,'platform_user_group',0,'update','update',NULL),(11,'platform_user_group_has_permission',0,'read','read',NULL),(12,'platform_user_group_has_permission',0,'list','list',NULL),(13,'platform_user_group_has_permission',0,'create','create',NULL),(14,'platform_user_group_has_permission',0,'delete','delete',NULL),(15,'platform_user_group_has_permission',0,'update','update',NULL),(16,'platform_user_has_group_permission',0,'read','read',NULL),(17,'platform_user_has_group_permission',0,'list','list',NULL),(18,'platform_user_has_group_permission',0,'create','create',NULL),(19,'platform_user_has_group_permission',0,'delete','delete',NULL),(20,'platform_user_has_group_permission',0,'update','update',NULL),(21,'platform_user_has_permission',0,'read','read',NULL),(22,'platform_user_has_permission',0,'list','list',NULL),(23,'platform_user_has_permission',0,'create','create',NULL),(24,'platform_user_has_permission',0,'delete','delete',NULL),(25,'platform_user_has_permission',0,'update','update',NULL),(26,'raw_file',0,'read','read',NULL),(27,'raw_file',0,'list','list',NULL),(28,'raw_file',0,'create','create',NULL),(29,'raw_file',0,'delete','delete',NULL),(30,'raw_file',0,'update','update',NULL),(31,'imedia_event_raw',0,'read','read',NULL),(32,'imedia_event_raw',0,'list','list',NULL),(33,'imedia_event_raw',0,'create','create',NULL),(34,'imedia_event_raw',0,'delete','delete',NULL),(35,'imedia_event_raw',0,'update','update',NULL);
/*!40000 ALTER TABLE `permission` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-05-20 16:13:00
