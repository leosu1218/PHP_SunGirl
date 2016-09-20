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
-- Table structure for table `sungirlbb_list`
--

DROP TABLE IF EXISTS `sungirlbb_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sungirlbb_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL,
  `category` varchar(45) NOT NULL COMMENT 'video:影音,photo:圖片',
  `banner_name` varchar(45) NOT NULL COMMENT '上架圖片',
  `home_state` int(11) NOT NULL COMMENT '0:上首頁 1:不上首頁',
  `video_url` varchar(100) DEFAULT NULL,
  `ready_time` date DEFAULT NULL COMMENT '上架日期',
  `create_time` datetime DEFAULT NULL COMMENT '建立日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sungirlbb_list`
--

LOCK TABLES `sungirlbb_list` WRITE;
/*!40000 ALTER TABLE `sungirlbb_list` DISABLE KEYS */;
INSERT INTO `sungirlbb_list` VALUES (20,'casdcasc','video','Desert.jpg',0,'https://www.youtube.com/embed/PD7-Hb3mXho?rel=0&amp;controls=0&amp;showinfo=0','2016-09-05','2016-09-05 17:03:33'),(23,'dfcasdc','photo','Chrysanthemum.jpg',0,NULL,'2016-09-13','2016-09-13 14:55:31'),(24,'wqeqeqw','photo','Koala.jpg',0,NULL,'2016-09-13','2016-09-13 14:55:51'),(25,'fvsdfvsf','video','Hydrangeas.jpg',0,'https://www.youtube.com/embed/jHpeM0mTod0?rel=0&amp;controls=0&amp;showinfo=0','2016-09-04','2016-09-14 12:01:15'),(26,'vsvfvsv','video','Jellyfish.jpg',0,'https://www.youtube.com/embed/Scbzp-qGye0?rel=0&amp;controls=0&amp;showinfo=0','2016-09-13','2016-09-14 12:02:12');
/*!40000 ALTER TABLE `sungirlbb_list` ENABLE KEYS */;
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
