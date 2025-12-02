-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: localhost    Database: mapa
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tbl_mapa`
--

DROP TABLE IF EXISTS `tbl_mapa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_mapa` (
  `codigo` int NOT NULL AUTO_INCREMENT,
  `datareserva` date DEFAULT NULL,
  `sala` int DEFAULT '0',
  `codigo_horario` int DEFAULT '0',
  `codigo_turma` int DEFAULT '0',
  `codigo_professor` int DEFAULT '0',
  `estatus` char(1) DEFAULT '',
  PRIMARY KEY (`codigo`),
  KEY `sala` (`sala`),
  KEY `codigo_horario` (`codigo_horario`),
  KEY `codigo_turma` (`codigo_turma`),
  KEY `codigo_professor` (`codigo_professor`),
  CONSTRAINT `tbl_mapa_ibfk_1` FOREIGN KEY (`sala`) REFERENCES `tbl_sala` (`codigo`),
  CONSTRAINT `tbl_mapa_ibfk_2` FOREIGN KEY (`codigo_horario`) REFERENCES `tbl_horario` (`codigo`),
  CONSTRAINT `tbl_mapa_ibfk_3` FOREIGN KEY (`codigo_turma`) REFERENCES `tbl_turma` (`codigo`),
  CONSTRAINT `tbl_mapa_ibfk_4` FOREIGN KEY (`codigo_professor`) REFERENCES `tbl_professor` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_mapa`
--

LOCK TABLES `tbl_mapa` WRITE;
/*!40000 ALTER TABLE `tbl_mapa` DISABLE KEYS */;
INSERT INTO `tbl_mapa` VALUES (1,'2025-12-24',1,1,1,1,''),(2,'2025-12-24',1,2,2,2,''),(3,'2025-12-24',2,3,3,1,'');
/*!40000 ALTER TABLE `tbl_mapa` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-02 16:49:40
