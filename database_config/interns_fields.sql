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
-- Table structure for table `interns_fields`
--

DROP TABLE IF EXISTS `interns_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `interns_fields` (
  `name` varchar(255) NOT NULL,
  `step` varchar(255) NOT NULL,
  `details` text NOT NULL,
  `position` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`name`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interns_fields`
--

LOCK TABLES `interns_fields` WRITE;
/*!40000 ALTER TABLE `interns_fields` DISABLE KEYS */;
INSERT INTO `interns_fields` VALUES
('Formation','Step 1: Private Data','element=select;choices=Biotech/ChemBiotech;required=true;ex=Biotech;selective=true;negative=true',3),
('Promotion','Step 1: Private Data','element=input;type=number;ex=2020;min=2005;required=true;placeholder=Your Promotion;selective=true;negative=true',2),
('Last Name','Step 1: Private Data','element=input;type=text;ex=L&#039;Hermite;required=true;placeholder=Last Name',1),
('First Name','Step 1: Private Data','element=input;type=text;ex=Bernard;required=true;placeholder=First Name',0),
('Email','Step 1: Private Data','element=input;type=email;ex=bernard.lhermite@gmail.com;placeholder=Your email to contact you',4),
('Sector','Step 2: Host Organization','element=select;choices=Private/Academic;required=true;ex=Private;selective=true;negative=true',0),
('Organization','Step 2: Host Organization','element=datalist;choices=from database;column=Organization;ex=Novartoss;required=true;placeholder=Organization name, etc;selective=true',1),
('Team','Step 2: Host Organization','element=input;type=text;ex=Department of fights against diseases and poverty;placeholder=Lab or department name',2),
('Website','Step 2: Host Organization','element=input;type=url;ex=http://novartoss.com;placeholder=An useful website',5),
('City','Step 2: Host Organization','element=datalist;choices=from database;column=City;required=true;ex=Attilatown;placeholder=Attilatown',4),
('Country','Step 2: Host Organization','element=select;choices=from file;file=../countries.txt;required=true;ex=Switzerland;selective=true;negative=true',3),
('Supervisor','Step 2: Host Organization','element=input;type=text;ex=Dr. Ethica;placeholder=Somebody helpful;new fields=true',6),
('Contacts','Step 2: Host Organization','element=input;type=email;ex=ethica@turfu.com;placeholder=A contact helpful;new fields=true',8),
('Position','Step 2: Host Organization','element=input;type=text;ex=Head of the department;placeholder=Position in the organization;new fields=true',7),
('ESBS Year','Step 3: Internship','element=select;choices=ESBS 1/ESBS 2/ESBS 3;required=true;ex=ESBS 3;selective=true;negative=true',0),
('Duration','Step 3: Internship','element=input;type=number;ex=24 Weeks;min=1;max=53;required=true;placeholder=weeks;selective=true;negative=true',1),
('Domains','Step 3: Internship','element=datalist;choices=from database;column=Domains;ex=(one per line) Immunology, Systems Biology, Cancerology;placeholder=Main domain(s);new fields=true;required=true;maxlength=29;selective=true;negative=true',2),
('Subject','Step 3: Internship','element=textarea;required=true;placeholder=Title - subject of the internship;ex=Analysis of genetic networks of cancerous B cells among poor people;rows=5;cols=40',3),
('Techniques','Step 3: Internship','element=datalist;choices=from database;column=Techniques;required=true;ex=(one per line) Cell culture, RNA seq, programming;new fields=true;maxlength=29;selective=true;negative=true',4),
('Comments','Step 3: Internship','element=textarea;placeholder=Comments on your internship. Please describe what you think about it;ex=Best internship ever. I learned how to earn money and have no ethic;rows=10;cols=40',5),
('Monthly Salary','Step 3: Internship','element=input;type=number;min=0;max=9999;ex=2345 (€ per month mandatory)',6),
('Financial Comments','Step 3: Internship','element=textarea;placeholder=If you have financial details that you want to share;ex=I lived in 50m², payed by the company;rows=5;cols=40',7),
('Satisfaction','Step 3: Internship','element=select;choices=1/2/3/4/5;required=true;ex=5;selective=true;negative=true',8);
/*!40000 ALTER TABLE `interns_fields` ENABLE KEYS */;
UNLOCK TABLES;