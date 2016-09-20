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
-- Table structure for table `platform_user`
--

DROP TABLE IF EXISTS `platform_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `platform_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_name` varchar(50) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `account` varchar(30) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `hash` varchar(120) NOT NULL,
  `salt` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `domainName` (`domain_name`),
  KEY `email` (`email`),
  KEY `appID` (`group_id`,`account`,`hash`),
  CONSTRAINT `platform_user_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `platform_user_group` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `platform_user`
--

LOCK TABLES `platform_user` WRITE;
/*!40000 ALTER TABLE `platform_user` DISABLE KEYS */;
INSERT INTO `platform_user` VALUES (1,'www.aems.com',1,'admin','admin','admin@skygo.com','934d552d036678adc50d88586ed38bee37217087','SGp1U'),(7,'www.xdashboard.com',1,'test','test1','test@test.com','8f9da0888c70572a49320a5180192a5ed33ad762','1AGTR'),(8,'www.xdashboard.com',1,'apple','apple','f81120116@gmail.com','d911fbbabb1a861fabbdef97ee474a90d744e80f','ZmJf3'),(9,'www.xdashboard.com',1,'rich','rich','rich@hotmail.com','f18b9ee8c382a147182fa2534f38f74c6682bdae','bjZuj'),(10,'www.xdashboard.com',1,'rossi','rossi','123@hotmail.com','b485bb266c93f1506422605e0d28291c3b40defe','g3aXF'),(11,'www.xdashboard.com',1,'planty','planty','123@hotmail.com','ff940bb8f23ffdab098155216771c22f2fc83710','qnlMN'),(12,'www.xdashboard.com',1,'rossichen','rossichen','123@hotmail.com','44826b56eb508f2759885fb6f4169fb53b68bc20','ztkXz'),(13,'www.xdashboard.com',1,'rossi123','rossi123','123@hotmail.com','d3fa67f6da030f19edb871bfa43ca5b4b0b3d201','ER2d9'),(14,'www.xdashboard.com',1,'PengKC','kc','pengkctest@gmail.com','4eac97a2fa18353847d858f8370ae3e398d74630','heTKq'),(15,'www.xdashboard.com',1,'PengKC','PKC','pengkc.kc.test@gmail.com.tw','af4cf01a0a2bd71010c3929899f52ca08647ace2','1Dmyt'),(16,'www.xdashboard.com',1,'frt','frtadmin','frt@frt.com.tw','6539351dca143e1a42690603c9944586bbd0f957','xhtwm'),(17,'www.xdashboard.com',1,'Damon Li','Damon','damon.li@tcit.com.tw','e8d77b05de71b8b7c6cab9ad34eddea5dc1dd55a','bC12S'),(18,'www.xdashboard.com',1,'小林眼鏡','kobayashi','may@kobayashi.com.tw','91cd558957ed31e30c057773ecdaf8448145dcf6','jvqrv'),(19,'www.xdashboard.com',1,'test','test2','test@test.com','22fef0f4dc36638348d95e2344c294d9f9349b41','eCc8L'),(20,'www.xdashboard.com',1,'test','test123','test123@test.com','d6825f7854e7580526cbc65d6175452566744eb4','aR1l0'),(21,'www.xdashboard.com',1,'steven','steven1996','steven1996@steven.com','7535da458bbc9f12fcfe26921807b34111589c63','PAncW'),(22,'www.xdashboard.com',1,'chris','steven1995','chris.wu@tcit.com.tw','ccfe671b7c9983f3adf601dbf272a8a53e125901','cLCpG'),(23,'www.xdashboard.com',1,'高雄R7','R7','jason.lee@tcit.com.tw','b3e2ebac561e11d51d737c01f2a82e789d2e7ba7','JbuHu'),(24,'www.xdashboard.com',1,'mangochacha','mangochacha','mangochacha@tcit.com.tw','2761ca75778950ca6f071126bcf681f9293903bc','N9WRc'),(25,'www.xdashboard.com',1,'86shop','86shop','86shop@86.com','e57c0048252ae6fdbd4db06bbe1bacc4b02ae598','qSjL5'),(26,'www.xdashboard.com',1,'aa','aa','aa@a','f3820cca154a3a323b65da692d62feb026328887','7fP7Y');
/*!40000 ALTER TABLE `platform_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-09-19 14:58:00
