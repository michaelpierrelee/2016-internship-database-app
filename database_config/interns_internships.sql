-- MariaDB dump 10.19  Distrib 10.11.6-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: amicaleesbs
-- ------------------------------------------------------
-- Server version	10.11.6-MariaDB-0+deb12u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


--
-- Table structure for table `interns_internships`
--

DROP TABLE IF EXISTS `interns_internships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `interns_internships` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `submit date` date NOT NULL,
  `validated` tinyint(1) NOT NULL DEFAULT 0,
  `First Name` text DEFAULT NULL,
  `Last Name` text DEFAULT NULL,
  `Promotion` text DEFAULT NULL,
  `Formation` text DEFAULT NULL,
  `Email` text DEFAULT NULL,
  `Sector` text DEFAULT NULL,
  `Organization` text DEFAULT NULL,
  `Team` text DEFAULT NULL,
  `Country` text DEFAULT NULL,
  `City` text DEFAULT NULL,
  `Website` text DEFAULT NULL,
  `Supervisor` text DEFAULT NULL,
  `Contacts` text DEFAULT NULL,
  `Position` text DEFAULT NULL,
  `ESBS Year` text DEFAULT NULL,
  `Duration` text DEFAULT NULL,
  `Domains` text DEFAULT NULL,
  `Subject` text DEFAULT NULL,
  `Techniques` text DEFAULT NULL,
  `Comments` text DEFAULT NULL,
  `Monthly Salary` text DEFAULT NULL,
  `Financial Comments` text DEFAULT NULL,
  `Satisfaction` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=987 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interns_internships`
--

LOCK TABLES `interns_internships` WRITE;
/*!40000 ALTER TABLE `interns_internships` DISABLE KEYS */;
INSERT INTO `interns_internships` (`id`, `submit date`, `validated`, `First Name`, `Last Name`, `Promotion`, `Formation`, `Email`, `Sector`, `Organization`, `Team`, `Country`, `City`, `Website`, `Supervisor`, `Contacts`, `Position`, `ESBS Year`, `Duration`, `Domains`, `Subject`, `Techniques`, `Comments`, `Monthly Salary`, `Financial Comments`, `Satisfaction`) VALUES
(1, '2024-08-31', 1, '[\"Prenom\"]', '[\"Nom\"]', '[\"2024\"]', '[\"Biotech\"]', NULL, '[\"Academic\"]', '[\"Univ\"]', '[\"Group\"]', '[\"France\\r\\n\"]', '[\"Strasbourg\"]', NULL, NULL, NULL, NULL, '[\"Esbs 2\"]', '[\"24\"]', '[\"Domain\"]', '[\"Title\"]', '[\"Technique\"]', NULL, NULL, NULL, '[\"4\"]');
/*!40000 ALTER TABLE `interns_internships` ENABLE KEYS */;
UNLOCK TABLES;
