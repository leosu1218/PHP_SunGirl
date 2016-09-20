-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: leo_sungirlbb
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
-- Table structure for table `sungirl_download`
--

DROP TABLE IF EXISTS `sungirl_download`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sungirl_download` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL,
  `banner_name` varchar(45) NOT NULL DEFAULT '0',
  `pc_img1` varchar(45) NOT NULL COMMENT '1024x768',
  `pc_img2` varchar(45) NOT NULL COMMENT '1280x1024',
  `pc_img3` varchar(45) NOT NULL COMMENT '1366x768',
  `pc_img4` varchar(45) NOT NULL COMMENT '1920x1080',
  `mobile_img1` varchar(45) NOT NULL,
  `mobile_img2` varchar(45) NOT NULL,
  `mobile_img3` varchar(45) NOT NULL,
  `mobile_img4` varchar(45) NOT NULL,
  `ready_time` date NOT NULL COMMENT '上架日期',
  `create_time` datetime NOT NULL COMMENT '建立日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sungirl_download`
--

LOCK TABLES `sungirl_download` WRITE;
/*!40000 ALTER TABLE `sungirl_download` DISABLE KEYS */;
INSERT INTO `sungirl_download` VALUES (1,'hghhfh','Chrysanthemum.jpg','asdc','asdc','asdc','asdc','asdc','asdc','asdc','asdc','2016-09-08','2016-09-08 17:30:08');
/*!40000 ALTER TABLE `sungirl_download` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-09-19 14:58:01
