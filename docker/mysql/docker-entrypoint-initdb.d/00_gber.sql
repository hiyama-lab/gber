-- MySQL dump 10.13  Distrib 5.7.20, for Linux (x86_64)
--
-- Host: localhost    Database: gber_new
-- ------------------------------------------------------
-- Server version	5.7.20

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
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_logs` (
  `activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `userno` int(11) NOT NULL,
  `queryname` text NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `caretakerlist`
--

DROP TABLE IF EXISTS `caretakerlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `caretakerlist` (
  `caretakerid` int(11) NOT NULL AUTO_INCREMENT,
  `giver` int(11) NOT NULL,
  `taker` int(11) NOT NULL,
  PRIMARY KEY (`caretakerid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caretakerlist`
--

LOCK TABLES `caretakerlist` WRITE;
/*!40000 ALTER TABLE `caretakerlist` DISABLE KEYS */;
/*!40000 ALTER TABLE `caretakerlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `db_user`
--

DROP TABLE IF EXISTS `db_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `db_user` (
  `userno` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `nickname` varchar(20) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `birthyear` int(11) DEFAULT NULL,
  `pass` varchar(255) DEFAULT NULL,
  `intro` text,
  `address_string` varchar(100) DEFAULT NULL,
  `mylat` float DEFAULT NULL,
  `mylng` float DEFAULT NULL,
  `master` int(11) NOT NULL DEFAULT '0',
  `adminmemo` text NOT NULL,
  `registered_date` date DEFAULT NULL,
  PRIMARY KEY (`userno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `db_user`
--

LOCK TABLES `db_user` WRITE;
/*!40000 ALTER TABLE `db_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `db_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emaillog`
--

DROP TABLE IF EXISTS `emaillog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emaillog` (
  `emailid` int(11) NOT NULL AUTO_INCREMENT,
  `groupno` int(11) NOT NULL,
  `workid` int(11) NOT NULL,
  `subject` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`emailid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emaillog`
--

LOCK TABLES `emaillog` WRITE;
/*!40000 ALTER TABLE `emaillog` DISABLE KEYS */;
/*!40000 ALTER TABLE `emaillog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grouplist`
--

DROP TABLE IF EXISTS `grouplist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grouplist` (
  `registeredid` int(11) NOT NULL AUTO_INCREMENT,
  `groupno` int(11) NOT NULL,
  `userno` int(11) NOT NULL,
  `admin` int(11) NOT NULL DEFAULT '0',
  `eval` int(11) NOT NULL DEFAULT '0',
  `memo` text NOT NULL,
  PRIMARY KEY (`registeredid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grouplist`
--

LOCK TABLES `grouplist` WRITE;
/*!40000 ALTER TABLE `grouplist` DISABLE KEYS */;
/*!40000 ALTER TABLE `grouplist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groupnamelist`
--

DROP TABLE IF EXISTS `groupnamelist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groupnamelist` (
  `groupnameid` int(11) NOT NULL AUTO_INCREMENT,
  `groupno` int(11) DEFAULT NULL,
  `groupname` varchar(50) DEFAULT NULL,
  `groupmemo` text NOT NULL,
  `mitsumori` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`groupnameid`),
  UNIQUE KEY `groupno` (`groupno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groupnamelist`
--

LOCK TABLES `groupnamelist` WRITE;
/*!40000 ALTER TABLE `groupnamelist` DISABLE KEYS */;
INSERT INTO `groupnamelist` VALUES (0,0,'全体','',0);
/*!40000 ALTER TABLE `groupnamelist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `helpchousei`
--

DROP TABLE IF EXISTS `helpchousei`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `helpchousei` (
  `chouseiid` int(11) NOT NULL AUTO_INCREMENT,
  `helpdateid` int(11) NOT NULL,
  `workerno` int(11) NOT NULL,
  `attendance` tinyint(1) NOT NULL,
  PRIMARY KEY (`chouseiid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `helpchousei`
--

LOCK TABLES `helpchousei` WRITE;
/*!40000 ALTER TABLE `helpchousei` DISABLE KEYS */;
/*!40000 ALTER TABLE `helpchousei` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `helpdate`
--

DROP TABLE IF EXISTS `helpdate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `helpdate` (
  `helpdateid` int(11) NOT NULL AUTO_INCREMENT,
  `workid` int(11) NOT NULL,
  `workdate` date NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`helpdateid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `helpdate`
--

LOCK TABLES `helpdate` WRITE;
/*!40000 ALTER TABLE `helpdate` DISABLE KEYS */;
/*!40000 ALTER TABLE `helpdate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `helplist`
--

DROP TABLE IF EXISTS `helplist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `helplist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userno` int(11) DEFAULT NULL,
  `lat` decimal(18,12) DEFAULT NULL,
  `lng` decimal(18,12) DEFAULT NULL,
  `address` text NOT NULL,
  `status` int(11) DEFAULT '0',
  `worktitle` text,
  `content` text,
  `summary` text,
  `price` text,
  `workernum` text,
  `contact` text NOT NULL,
  `workgenre` text NOT NULL,
  `groupgenre` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `helplist`
--

LOCK TABLES `helplist` WRITE;
/*!40000 ALTER TABLE `helplist` DISABLE KEYS */;
/*!40000 ALTER TABLE `helplist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `helpmatching`
--

DROP TABLE IF EXISTS `helpmatching`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `helpmatching` (
  `applylistid` int(11) NOT NULL AUTO_INCREMENT,
  `workid` int(11) DEFAULT NULL,
  `applyuserno` int(11) DEFAULT NULL,
  `interest` tinyint(4) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `evaluation` int(11) NOT NULL DEFAULT '0',
  `comment` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`applylistid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `helpmatching`
--

LOCK TABLES `helpmatching` WRITE;
/*!40000 ALTER TABLE `helpmatching` DISABLE KEYS */;
/*!40000 ALTER TABLE `helpmatching` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `interest_user_list`
--

DROP TABLE IF EXISTS `interest_user_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `interest_user_list` (
  `socialactivityid` int(11) NOT NULL AUTO_INCREMENT,
  `userno` int(11) DEFAULT '0',
  `age` int(11) NOT NULL DEFAULT '0',
  `workobject_money_1` tinyint(4) NOT NULL DEFAULT '0',
  `workobject_money_2` tinyint(4) NOT NULL DEFAULT '0',
  `workobject_purposeoflife` tinyint(4) NOT NULL DEFAULT '0',
  `workobject_health` tinyint(4) NOT NULL DEFAULT '0',
  `workobject_contribution` tinyint(4) NOT NULL DEFAULT '0',
  `workobject_asked` tinyint(4) NOT NULL DEFAULT '0',
  `workobject_sparetime` tinyint(4) NOT NULL DEFAULT '0',
  `workobject_skill` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_prune` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_agriculture` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_cleaning` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_housework` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_shopping` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_repair` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_caretaking` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_teaching` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_consulting` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_eigyou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_kikakukeiei` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_kanrijimu` tinyint(4) DEFAULT '0',
  `shokushu_dai_hanbaifoodentertainment` tinyint(4) DEFAULT '0',
  `shokushu_dai_biyoubridalhotelkoutsuu` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_iryouhukushi` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_hoikukyouikutuuyaku` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_creative` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_webinternetgame` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_consultantkinyuuhudousan` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_koukyouservice` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_itengineer` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_denkidenshikikaihandotai` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_kenchikudoboku` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_iyakushokuhinkagakusozai` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_ginoukousetsubihaisounourin` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_eigyou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_coordinator` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_callcenter` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_marketing` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_md` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_jigyoukikaku` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_fcowner` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_koubai` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_keiri` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_jinji` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_jimu` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_supervisor` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_kouri` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_food` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_este` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_bridal` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_ryokou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_koutsu` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_iryou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_hukushi` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_hoiku` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_kyoushi` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_tuuyaku` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_koukoku` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_henshu` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_insatsu` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_fashion` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_kougyoudesign` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_housou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_website` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_game` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_webshop` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_consultant` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_shigyou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_kinyuusenmon1` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_kinyuusenmon2` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_hudousan` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_koumuin` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_gakkou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_consultantanalyst` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_se1` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_se2` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_se3` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_packagesoftware` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_network` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_techsupport` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_shanaise` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_patent` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_research` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_circuit` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_seigyo` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_kikai` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_seisan` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_hinshitsuhosho` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_salesengineer` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_serviceengineer` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_cad` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_hyoukakensa` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_sekkei` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_sekoukanri` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_research2` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_chemistry` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_shokuhin` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_iyakuhin` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_ginoukou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_sisetsu` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_haisou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_nourinnsuisan` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_it` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_kikai` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_sozai` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_juutaku` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_seikatsu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_shousha` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_service` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_leisure` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_ryuutsu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_food` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_mascomi` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_kinnyuu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_consulting` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_hudousan` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_unyu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_kankyou` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_kouteki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_software` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_internet` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_game` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_tsushin` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sougoudenki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_computer` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kaden` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_gameamuse` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_seimitsu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_tsushinkiki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_handotai` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_iryouyoukiki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_yusouyoukiki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_jayden` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_plant` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sonotadenki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_mining` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_hitetsukinzoku` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_glass` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_paper` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_fabric` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_celamic` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_rubber` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_cement` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_housing` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_interior` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_food` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_cosmetics` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_commodity` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_toy` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_apparel` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sport` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_stationary` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_jewelry` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_othermaker` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sougoushousha` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmonshousha` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_jinzaihaken` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_outsourcing` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_education` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_iryou` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kankonsousai` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_security` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_buildingmaintenance` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_esthetic` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_fitnessclub` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_otherservice` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_leisureservice` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_hotel` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_tourism` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_departmentstore` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_ryuutsuu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_conveniencestore` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_drugstore` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_homecenter` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontensougou` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontenshokuhin` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontenjidousha` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontencamera` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontendenki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontenbookmusic` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontenglasses` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontenfashion` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontensport` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmonteninterior` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_tsushinhanbai` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_foodbusinesswashoku` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_foodbusinessyoushoku` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_foodbusinessasia` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_foodbusinessfast` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_broadcast` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_newspaper` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_advertisement` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_displaydesign` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_art` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kinyusougou` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_gaishikinyu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_seihukeikinyu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_bank` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_gaishibank` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sinyoukumiai` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sintaku` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_toushisintaku` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_shoken` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_shouhintorihiki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_vc` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_jigyoushakinyu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_credit` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_rental` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_seimeihoken` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kyousai` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sonotakinyu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_thinktank` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmonconsultant` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kojinjimusho` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kensetsuconsul` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kensetsu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sekkei` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_setsubi` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_reform` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_hudousan` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kaiun` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_buturyu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_environment` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kankyoukanrensetsubi` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_electricity` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_police` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kankouchou` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_koueki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_seikyou` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_noukyou` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_nourinsuisan` tinyint(4) NOT NULL DEFAULT '0',
  `study_english` tinyint(4) NOT NULL DEFAULT '0',
  `study_foreignlanguage` tinyint(4) NOT NULL DEFAULT '0',
  `study_it` tinyint(4) NOT NULL DEFAULT '0',
  `study_business` tinyint(4) NOT NULL DEFAULT '0',
  `study_caretaking` tinyint(4) NOT NULL DEFAULT '0',
  `study_housework` tinyint(4) NOT NULL DEFAULT '0',
  `study_liberalarts` tinyint(4) NOT NULL DEFAULT '0',
  `study_art` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_health` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_elderly` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_disable` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_children` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_sport` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_town` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_safety` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_nature` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_disaster` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_international` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_musicalinstrument` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_chorus` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_dance` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_shodo` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_kado` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_sado` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_wasai` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_knit` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_cooking` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_gardening` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_diy` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_painting` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_pottery` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_photo` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_writing` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_go` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_camp` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_watchsport` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_watchperformance` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_watchmovie` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_listenmusic` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_reading` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_pachinko` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_karaoke` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_game` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_attraction` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_train` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_car` tinyint(4) NOT NULL DEFAULT '0',
  `sport_baseball` tinyint(4) NOT NULL DEFAULT '0',
  `sport_tabletennis` tinyint(4) NOT NULL DEFAULT '0',
  `sport_tennis` tinyint(4) NOT NULL DEFAULT '0',
  `sport_badminton` tinyint(4) NOT NULL DEFAULT '0',
  `sport_golf` tinyint(4) NOT NULL DEFAULT '0',
  `sport_gateball` tinyint(4) NOT NULL DEFAULT '0',
  `sport_bowling` tinyint(4) NOT NULL DEFAULT '0',
  `sport_fishing` tinyint(4) NOT NULL DEFAULT '0',
  `sport_swimming` tinyint(4) NOT NULL DEFAULT '0',
  `sport_skiing` tinyint(4) NOT NULL DEFAULT '0',
  `sport_climbing` tinyint(4) NOT NULL DEFAULT '0',
  `sport_cycling` tinyint(4) NOT NULL DEFAULT '0',
  `sport_jogging` tinyint(4) NOT NULL DEFAULT '0',
  `sport_walking` tinyint(4) NOT NULL DEFAULT '0',
  `sport_volleyball` tinyint(4) NOT NULL DEFAULT '0',
  `sport_basketball` tinyint(4) NOT NULL DEFAULT '0',
  `sport_football` tinyint(4) NOT NULL DEFAULT '0',
  `sport_judo` tinyint(4) NOT NULL DEFAULT '0',
  `sport_kendo` tinyint(4) NOT NULL DEFAULT '0',
  `text_workobject` text NOT NULL,
  `text_gyoushu` text NOT NULL,
  `text_shokushu` text NOT NULL,
  `text_study` text NOT NULL,
  `text_volunteer` text NOT NULL,
  `text_hobby` text NOT NULL,
  `text_sport` text NOT NULL,
  PRIMARY KEY (`socialactivityid`),
  UNIQUE KEY `userno` (`userno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interest_user_list`
--

LOCK TABLES `interest_user_list` WRITE;
/*!40000 ALTER TABLE `interest_user_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `interest_user_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lancers_tag_list`
--

DROP TABLE IF EXISTS `lancers_tag_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lancers_tag_list` (
  `socialactivityid` int(11) NOT NULL AUTO_INCREMENT,
  `username` text,
  `infoid` int(11) NOT NULL DEFAULT '0',
  `workobject_money_1` tinyint(4) NOT NULL DEFAULT '0',
  `workobject_money_2` tinyint(4) NOT NULL DEFAULT '0',
  `workobject_purposeoflife` tinyint(4) NOT NULL DEFAULT '0',
  `workobject_health` tinyint(4) NOT NULL DEFAULT '0',
  `workobject_contribution` tinyint(4) NOT NULL DEFAULT '0',
  `workobject_asked` tinyint(4) NOT NULL DEFAULT '0',
  `workobject_sparetime` tinyint(4) NOT NULL DEFAULT '0',
  `workobject_skill` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_prune` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_agriculture` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_cleaning` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_housework` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_shopping` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_repair` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_caretaking` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_teaching` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_consulting` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_eigyou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_kikakukeiei` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_kanrijimu` tinyint(4) DEFAULT '0',
  `shokushu_dai_hanbaifoodentertainment` tinyint(4) DEFAULT '0',
  `shokushu_dai_biyoubridalhotelkoutsuu` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_iryouhukushi` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_hoikukyouikutuuyaku` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_creative` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_webinternetgame` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_consultantkinyuuhudousan` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_koukyouservice` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_itengineer` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_denkidenshikikaihandotai` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_kenchikudoboku` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_iyakushokuhinkagakusozai` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_ginoukousetsubihaisounourin` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_eigyou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_coordinator` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_callcenter` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_marketing` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_md` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_jigyoukikaku` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_fcowner` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_koubai` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_keiri` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_jinji` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_jimu` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_supervisor` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_kouri` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_food` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_este` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_bridal` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_ryokou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_koutsu` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_iryou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_hukushi` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_hoiku` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_kyoushi` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_tuuyaku` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_koukoku` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_henshu` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_insatsu` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_fashion` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_kougyoudesign` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_housou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_website` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_game` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_webshop` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_consultant` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_shigyou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_kinyuusenmon1` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_kinyuusenmon2` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_hudousan` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_koumuin` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_gakkou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_consultantanalyst` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_se1` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_se2` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_se3` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_packagesoftware` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_network` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_techsupport` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_shanaise` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_patent` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_research` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_circuit` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_seigyo` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_kikai` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_seisan` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_hinshitsuhosho` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_salesengineer` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_serviceengineer` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_cad` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_hyoukakensa` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_sekkei` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_sekoukanri` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_research2` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_chemistry` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_shokuhin` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_iyakuhin` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_ginoukou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_sisetsu` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_haisou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_nourinnsuisan` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_it` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_kikai` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_sozai` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_juutaku` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_seikatsu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_shousha` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_service` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_leisure` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_ryuutsu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_food` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_mascomi` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_kinnyuu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_consulting` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_hudousan` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_unyu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_kankyou` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_kouteki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_software` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_internet` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_game` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_tsushin` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sougoudenki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_computer` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kaden` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_gameamuse` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_seimitsu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_tsushinkiki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_handotai` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_iryouyoukiki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_yusouyoukiki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_jayden` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_plant` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sonotadenki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_mining` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_hitetsukinzoku` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_glass` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_paper` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_fabric` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_celamic` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_rubber` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_cement` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_housing` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_interior` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_food` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_cosmetics` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_commodity` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_toy` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_apparel` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sport` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_stationary` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_jewelry` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_othermaker` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sougoushousha` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmonshousha` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_jinzaihaken` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_outsourcing` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_education` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_iryou` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kankonsousai` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_security` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_buildingmaintenance` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_esthetic` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_fitnessclub` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_otherservice` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_leisureservice` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_hotel` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_tourism` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_departmentstore` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_ryuutsuu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_conveniencestore` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_drugstore` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_homecenter` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontensougou` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontenshokuhin` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontenjidousha` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontencamera` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontendenki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontenbookmusic` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontenglasses` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontenfashion` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontensport` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmonteninterior` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_tsushinhanbai` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_foodbusinesswashoku` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_foodbusinessyoushoku` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_foodbusinessasia` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_foodbusinessfast` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_broadcast` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_newspaper` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_advertisement` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_displaydesign` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_art` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kinyusougou` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_gaishikinyu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_seihukeikinyu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_bank` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_gaishibank` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sinyoukumiai` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sintaku` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_toushisintaku` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_shoken` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_shouhintorihiki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_vc` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_jigyoushakinyu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_credit` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_rental` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_seimeihoken` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kyousai` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sonotakinyu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_thinktank` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmonconsultant` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kojinjimusho` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kensetsuconsul` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kensetsu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sekkei` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_setsubi` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_reform` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_hudousan` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kaiun` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_buturyu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_environment` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kankyoukanrensetsubi` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_electricity` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_police` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kankouchou` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_koueki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_seikyou` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_noukyou` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_nourinsuisan` tinyint(4) NOT NULL DEFAULT '0',
  `study_english` tinyint(4) NOT NULL DEFAULT '0',
  `study_foreignlanguage` tinyint(4) NOT NULL DEFAULT '0',
  `study_it` tinyint(4) NOT NULL DEFAULT '0',
  `study_business` tinyint(4) NOT NULL DEFAULT '0',
  `study_caretaking` tinyint(4) NOT NULL DEFAULT '0',
  `study_housework` tinyint(4) NOT NULL DEFAULT '0',
  `study_liberalarts` tinyint(4) NOT NULL DEFAULT '0',
  `study_art` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_health` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_elderly` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_disable` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_children` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_sport` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_town` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_safety` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_nature` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_disaster` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_international` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_musicalinstrument` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_chorus` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_dance` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_shodo` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_kado` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_sado` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_wasai` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_knit` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_cooking` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_gardening` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_diy` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_painting` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_pottery` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_photo` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_writing` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_go` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_camp` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_watchsport` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_watchperformance` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_watchmovie` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_listenmusic` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_reading` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_pachinko` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_karaoke` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_game` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_attraction` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_train` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_car` tinyint(4) NOT NULL DEFAULT '0',
  `sport_baseball` tinyint(4) NOT NULL DEFAULT '0',
  `sport_tabletennis` tinyint(4) NOT NULL DEFAULT '0',
  `sport_tennis` tinyint(4) NOT NULL DEFAULT '0',
  `sport_badminton` tinyint(4) NOT NULL DEFAULT '0',
  `sport_golf` tinyint(4) NOT NULL DEFAULT '0',
  `sport_gateball` tinyint(4) NOT NULL DEFAULT '0',
  `sport_bowling` tinyint(4) NOT NULL DEFAULT '0',
  `sport_fishing` tinyint(4) NOT NULL DEFAULT '0',
  `sport_swimming` tinyint(4) NOT NULL DEFAULT '0',
  `sport_skiing` tinyint(4) NOT NULL DEFAULT '0',
  `sport_climbing` tinyint(4) NOT NULL DEFAULT '0',
  `sport_cycling` tinyint(4) NOT NULL DEFAULT '0',
  `sport_jogging` tinyint(4) NOT NULL DEFAULT '0',
  `sport_walking` tinyint(4) NOT NULL DEFAULT '0',
  `sport_volleyball` tinyint(4) NOT NULL DEFAULT '0',
  `sport_basketball` tinyint(4) NOT NULL DEFAULT '0',
  `sport_football` tinyint(4) NOT NULL DEFAULT '0',
  `sport_judo` tinyint(4) NOT NULL DEFAULT '0',
  `sport_kendo` tinyint(4) NOT NULL DEFAULT '0',
  `text_workobject` text NOT NULL,
  `text_gyoushu` text NOT NULL,
  `text_shokushu` text NOT NULL,
  `text_study` text NOT NULL,
  `text_volunteer` text NOT NULL,
  `text_hobby` text NOT NULL,
  `text_sport` text NOT NULL,
  PRIMARY KEY (`socialactivityid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lancers_tag_list`
--

LOCK TABLES `lancers_tag_list` WRITE;
/*!40000 ALTER TABLE `lancers_tag_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `lancers_tag_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lancers_user_list`
--

DROP TABLE IF EXISTS `lancers_user_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lancers_user_list` (
  `socialactivityid` int(11) NOT NULL AUTO_INCREMENT,
  `username` text,
  `age` int(11) NOT NULL DEFAULT '0',
  `workobject_money_1` tinyint(4) NOT NULL DEFAULT '0',
  `workobject_money_2` tinyint(4) NOT NULL DEFAULT '0',
  `workobject_purposeoflife` tinyint(4) NOT NULL DEFAULT '0',
  `workobject_health` tinyint(4) NOT NULL DEFAULT '0',
  `workobject_contribution` tinyint(4) NOT NULL DEFAULT '0',
  `workobject_asked` tinyint(4) NOT NULL DEFAULT '0',
  `workobject_sparetime` tinyint(4) NOT NULL DEFAULT '0',
  `workobject_skill` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_prune` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_agriculture` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_cleaning` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_housework` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_shopping` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_repair` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_caretaking` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_teaching` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_consulting` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_eigyou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_kikakukeiei` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_kanrijimu` tinyint(4) DEFAULT '0',
  `shokushu_dai_hanbaifoodentertainment` tinyint(4) DEFAULT '0',
  `shokushu_dai_biyoubridalhotelkoutsuu` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_iryouhukushi` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_hoikukyouikutuuyaku` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_creative` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_webinternetgame` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_consultantkinyuuhudousan` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_koukyouservice` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_itengineer` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_denkidenshikikaihandotai` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_kenchikudoboku` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_iyakushokuhinkagakusozai` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_dai_ginoukousetsubihaisounourin` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_eigyou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_coordinator` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_callcenter` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_marketing` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_md` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_jigyoukikaku` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_fcowner` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_koubai` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_keiri` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_jinji` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_jimu` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_supervisor` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_kouri` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_food` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_este` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_bridal` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_ryokou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_koutsu` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_iryou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_hukushi` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_hoiku` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_kyoushi` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_tuuyaku` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_koukoku` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_henshu` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_insatsu` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_fashion` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_kougyoudesign` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_housou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_website` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_game` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_webshop` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_consultant` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_shigyou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_kinyuusenmon1` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_kinyuusenmon2` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_hudousan` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_koumuin` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_gakkou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_consultantanalyst` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_se1` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_se2` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_se3` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_packagesoftware` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_network` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_techsupport` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_shanaise` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_patent` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_research` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_circuit` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_seigyo` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_kikai` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_seisan` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_hinshitsuhosho` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_salesengineer` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_serviceengineer` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_cad` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_hyoukakensa` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_sekkei` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_sekoukanri` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_research2` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_chemistry` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_shokuhin` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_iyakuhin` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_ginoukou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_sisetsu` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_haisou` tinyint(4) NOT NULL DEFAULT '0',
  `shokushu_chu_nourinnsuisan` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_it` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_kikai` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_sozai` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_juutaku` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_seikatsu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_shousha` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_service` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_leisure` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_ryuutsu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_food` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_mascomi` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_kinnyuu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_consulting` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_hudousan` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_unyu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_kankyou` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_dai_kouteki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_software` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_internet` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_game` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_tsushin` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sougoudenki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_computer` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kaden` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_gameamuse` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_seimitsu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_tsushinkiki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_handotai` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_iryouyoukiki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_yusouyoukiki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_jayden` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_plant` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sonotadenki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_mining` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_hitetsukinzoku` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_glass` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_paper` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_fabric` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_celamic` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_rubber` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_cement` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_housing` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_interior` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_food` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_cosmetics` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_commodity` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_toy` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_apparel` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sport` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_stationary` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_jewelry` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_othermaker` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sougoushousha` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmonshousha` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_jinzaihaken` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_outsourcing` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_education` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_iryou` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kankonsousai` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_security` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_buildingmaintenance` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_esthetic` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_fitnessclub` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_otherservice` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_leisureservice` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_hotel` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_tourism` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_departmentstore` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_ryuutsuu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_conveniencestore` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_drugstore` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_homecenter` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontensougou` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontenshokuhin` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontenjidousha` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontencamera` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontendenki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontenbookmusic` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontenglasses` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontenfashion` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmontensport` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmonteninterior` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_tsushinhanbai` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_foodbusinesswashoku` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_foodbusinessyoushoku` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_foodbusinessasia` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_foodbusinessfast` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_broadcast` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_newspaper` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_advertisement` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_displaydesign` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_art` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kinyusougou` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_gaishikinyu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_seihukeikinyu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_bank` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_gaishibank` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sinyoukumiai` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sintaku` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_toushisintaku` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_shoken` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_shouhintorihiki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_vc` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_jigyoushakinyu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_credit` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_rental` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_seimeihoken` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kyousai` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sonotakinyu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_thinktank` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_senmonconsultant` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kojinjimusho` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kensetsuconsul` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kensetsu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_sekkei` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_setsubi` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_reform` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_hudousan` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kaiun` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_buturyu` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_environment` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kankyoukanrensetsubi` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_electricity` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_police` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_kankouchou` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_koueki` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_seikyou` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_noukyou` tinyint(4) NOT NULL DEFAULT '0',
  `gyoushu_chu_nourinsuisan` tinyint(4) NOT NULL DEFAULT '0',
  `study_english` tinyint(4) NOT NULL DEFAULT '0',
  `study_foreignlanguage` tinyint(4) NOT NULL DEFAULT '0',
  `study_it` tinyint(4) NOT NULL DEFAULT '0',
  `study_business` tinyint(4) NOT NULL DEFAULT '0',
  `study_caretaking` tinyint(4) NOT NULL DEFAULT '0',
  `study_housework` tinyint(4) NOT NULL DEFAULT '0',
  `study_liberalarts` tinyint(4) NOT NULL DEFAULT '0',
  `study_art` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_health` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_elderly` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_disable` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_children` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_sport` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_town` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_safety` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_nature` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_disaster` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_international` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_musicalinstrument` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_chorus` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_dance` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_shodo` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_kado` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_sado` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_wasai` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_knit` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_cooking` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_gardening` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_diy` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_painting` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_pottery` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_photo` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_writing` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_go` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_camp` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_watchsport` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_watchperformance` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_watchmovie` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_listenmusic` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_reading` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_pachinko` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_karaoke` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_game` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_attraction` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_train` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_car` tinyint(4) NOT NULL DEFAULT '0',
  `sport_baseball` tinyint(4) NOT NULL DEFAULT '0',
  `sport_tabletennis` tinyint(4) NOT NULL DEFAULT '0',
  `sport_tennis` tinyint(4) NOT NULL DEFAULT '0',
  `sport_badminton` tinyint(4) NOT NULL DEFAULT '0',
  `sport_golf` tinyint(4) NOT NULL DEFAULT '0',
  `sport_gateball` tinyint(4) NOT NULL DEFAULT '0',
  `sport_bowling` tinyint(4) NOT NULL DEFAULT '0',
  `sport_fishing` tinyint(4) NOT NULL DEFAULT '0',
  `sport_swimming` tinyint(4) NOT NULL DEFAULT '0',
  `sport_skiing` tinyint(4) NOT NULL DEFAULT '0',
  `sport_climbing` tinyint(4) NOT NULL DEFAULT '0',
  `sport_cycling` tinyint(4) NOT NULL DEFAULT '0',
  `sport_jogging` tinyint(4) NOT NULL DEFAULT '0',
  `sport_walking` tinyint(4) NOT NULL DEFAULT '0',
  `sport_volleyball` tinyint(4) NOT NULL DEFAULT '0',
  `sport_basketball` tinyint(4) NOT NULL DEFAULT '0',
  `sport_football` tinyint(4) NOT NULL DEFAULT '0',
  `sport_judo` tinyint(4) NOT NULL DEFAULT '0',
  `sport_kendo` tinyint(4) NOT NULL DEFAULT '0',
  `text_workobject` text NOT NULL,
  `text_gyoushu` text NOT NULL,
  `text_shokushu` text NOT NULL,
  `text_study` text NOT NULL,
  `text_volunteer` text NOT NULL,
  `text_hobby` text NOT NULL,
  `text_sport` text NOT NULL,
  PRIMARY KEY (`socialactivityid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lancers_user_list`
--

LOCK TABLES `lancers_user_list` WRITE;
/*!40000 ALTER TABLE `lancers_user_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `lancers_user_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matchingparam_human`
--

DROP TABLE IF EXISTS `matchingparam_human`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matchingparam_human` (
  `matchingparamid` int(11) NOT NULL AUTO_INCREMENT,
  `userno` int(11) NOT NULL,
  `worktype_prune` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_agriculture` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_cleaning` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_housework` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_shopping` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_repair` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_caretaking` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_teaching` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_consulting` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_english` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_foreignlanguage` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_it` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_business` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_caretaking` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_housework` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_liberalarts` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_art` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_health` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_elderly` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_disable` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_children` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_sport` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_town` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_safety` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_nature` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_disaster` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_international` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_musicalinstrument` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_chorus` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_dance` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_shodo` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_kado` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_sado` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_wasai` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_knit` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_cooking` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_gardening` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_diy` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_painting` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_pottery` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_photo` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_writing` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_go` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_camp` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_watchsport` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_watchperformance` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_watchmovie` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_listenmusic` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_reading` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_pachinko` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_karaoke` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_game` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_attraction` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_train` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_car` decimal(5,1) NOT NULL DEFAULT '0.0',
  `trip_daytrip` decimal(5,1) NOT NULL DEFAULT '0.0',
  `trip_domestic` decimal(5,1) NOT NULL DEFAULT '0.0',
  `trip_international` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_baseball` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_tabletennis` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_tennis` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_badminton` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_golf` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_gateball` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_bowling` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_fishing` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_swimming` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_skiing` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_climbing` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_cycling` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_jogging` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_walking` decimal(5,1) NOT NULL DEFAULT '0.0',
  PRIMARY KEY (`matchingparamid`),
  UNIQUE KEY `userno` (`userno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matchingparam_human`
--

LOCK TABLES `matchingparam_human` WRITE;
/*!40000 ALTER TABLE `matchingparam_human` DISABLE KEYS */;
/*!40000 ALTER TABLE `matchingparam_human` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matchingparam_work`
--

DROP TABLE IF EXISTS `matchingparam_work`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matchingparam_work` (
  `matchingparamid` int(11) NOT NULL AUTO_INCREMENT,
  `groupno` int(11) NOT NULL,
  `workid` int(11) NOT NULL,
  `worktype_prune` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_agriculture` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_cleaning` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_housework` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_shopping` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_repair` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_caretaking` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_teaching` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_consulting` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_english` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_foreignlanguage` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_it` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_business` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_caretaking` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_housework` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_liberalarts` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_art` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_health` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_elderly` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_disable` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_children` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_sport` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_town` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_safety` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_nature` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_disaster` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_international` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_musicalinstrument` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_chorus` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_dance` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_shodo` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_kado` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_sado` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_wasai` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_knit` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_cooking` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_gardening` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_diy` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_painting` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_pottery` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_photo` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_writing` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_go` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_camp` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_watchsport` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_watchperformance` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_watchmovie` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_listenmusic` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_reading` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_pachinko` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_karaoke` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_game` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_attraction` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_train` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_car` decimal(5,1) NOT NULL DEFAULT '0.0',
  `trip_daytrip` decimal(5,1) NOT NULL DEFAULT '0.0',
  `trip_domestic` decimal(5,1) NOT NULL DEFAULT '0.0',
  `trip_international` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_baseball` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_tabletennis` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_tennis` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_badminton` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_golf` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_gateball` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_bowling` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_fishing` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_swimming` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_skiing` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_climbing` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_cycling` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_jogging` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_walking` decimal(5,1) NOT NULL DEFAULT '0.0',
  PRIMARY KEY (`matchingparamid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matchingparam_work`
--

LOCK TABLES `matchingparam_work` WRITE;
/*!40000 ALTER TABLE `matchingparam_work` DISABLE KEYS */;
/*!40000 ALTER TABLE `matchingparam_work` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matchingparam_worktemp`
--

DROP TABLE IF EXISTS `matchingparam_worktemp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matchingparam_worktemp` (
  `matchingparamid` int(11) NOT NULL AUTO_INCREMENT,
  `groupno` int(11) NOT NULL,
  `workid` int(11) NOT NULL,
  `userno` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `worktype_prune` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_agriculture` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_cleaning` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_housework` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_shopping` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_repair` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_caretaking` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_teaching` decimal(5,1) NOT NULL DEFAULT '0.0',
  `worktype_consulting` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_english` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_foreignlanguage` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_it` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_business` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_caretaking` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_housework` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_liberalarts` decimal(5,1) NOT NULL DEFAULT '0.0',
  `study_art` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_health` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_elderly` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_disable` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_children` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_sport` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_town` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_safety` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_nature` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_disaster` decimal(5,1) NOT NULL DEFAULT '0.0',
  `volunteer_international` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_musicalinstrument` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_chorus` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_dance` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_shodo` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_kado` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_sado` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_wasai` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_knit` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_cooking` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_gardening` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_diy` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_painting` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_pottery` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_photo` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_writing` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_go` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_camp` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_watchsport` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_watchperformance` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_watchmovie` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_listenmusic` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_reading` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_pachinko` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_karaoke` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_game` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_attraction` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_train` decimal(5,1) NOT NULL DEFAULT '0.0',
  `hobby_car` decimal(5,1) NOT NULL DEFAULT '0.0',
  `trip_daytrip` decimal(5,1) NOT NULL DEFAULT '0.0',
  `trip_domestic` decimal(5,1) NOT NULL DEFAULT '0.0',
  `trip_international` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_baseball` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_tabletennis` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_tennis` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_badminton` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_golf` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_gateball` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_bowling` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_fishing` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_swimming` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_skiing` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_climbing` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_cycling` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_jogging` decimal(5,1) NOT NULL DEFAULT '0.0',
  `sport_walking` decimal(5,1) NOT NULL DEFAULT '0.0',
  PRIMARY KEY (`matchingparamid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matchingparam_worktemp`
--

LOCK TABLES `matchingparam_worktemp` WRITE;
/*!40000 ALTER TABLE `matchingparam_worktemp` DISABLE KEYS */;
/*!40000 ALTER TABLE `matchingparam_worktemp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message` (
  `messageid` int(11) NOT NULL AUTO_INCREMENT,
  `messagename` text NOT NULL,
  `workid` int(11) NOT NULL DEFAULT '0',
  `nameedited` int(11) NOT NULL DEFAULT '0',
  `lastupdate` datetime NOT NULL,
  PRIMARY KEY (`messageid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message`
--

LOCK TABLES `message` WRITE;
/*!40000 ALTER TABLE `message` DISABLE KEYS */;
/*!40000 ALTER TABLE `message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messageeach`
--

DROP TABLE IF EXISTS `messageeach`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messageeach` (
  `messageeachid` int(11) NOT NULL AUTO_INCREMENT,
  `messageid` int(11) NOT NULL,
  `senderid` int(11) NOT NULL,
  `message` text NOT NULL,
  `messagedate` datetime NOT NULL,
  PRIMARY KEY (`messageeachid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messageeach`
--

LOCK TABLES `messageeach` WRITE;
/*!40000 ALTER TABLE `messageeach` DISABLE KEYS */;
/*!40000 ALTER TABLE `messageeach` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messagemember`
--

DROP TABLE IF EXISTS `messagemember`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messagemember` (
  `messagememberid` int(11) NOT NULL AUTO_INCREMENT,
  `messageid` int(11) NOT NULL,
  `memberid` int(11) NOT NULL,
  PRIMARY KEY (`messagememberid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messagemember`
--

LOCK TABLES `messagemember` WRITE;
/*!40000 ALTER TABLE `messagemember` DISABLE KEYS */;
/*!40000 ALTER TABLE `messagemember` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `photodata`
--

DROP TABLE IF EXISTS `photodata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photodata` (
  `photodataid` int(11) NOT NULL AUTO_INCREMENT,
  `userno` int(11) NOT NULL,
  `photodata` mediumblob,
  `mime` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`photodataid`),
  UNIQUE KEY `userno` (`userno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `photodata`
--

LOCK TABLES `photodata` WRITE;
/*!40000 ALTER TABLE `photodata` DISABLE KEYS */;
/*!40000 ALTER TABLE `photodata` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questionnaire_demographic`
--

DROP TABLE IF EXISTS `questionnaire_demographic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questionnaire_demographic` (
  `answerid` int(11) NOT NULL AUTO_INCREMENT,
  `userno` int(11) NOT NULL,
  `gakureki` text NOT NULL,
  `gyoushu` text NOT NULL,
  `gyoushudetail` text NOT NULL,
  `shokushu` text NOT NULL,
  `shokushudetail` text NOT NULL,
  `doukyo` text,
  `undou_light` text NOT NULL,
  `undou_medium` text NOT NULL,
  `undou_heavy` text NOT NULL,
  `shikaku` text,
  PRIMARY KEY (`answerid`),
  UNIQUE KEY `userno` (`userno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questionnaire_demographic`
--

LOCK TABLES `questionnaire_demographic` WRITE;
/*!40000 ALTER TABLE `questionnaire_demographic` DISABLE KEYS */;
/*!40000 ALTER TABLE `questionnaire_demographic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questionnaire_socialactivity`
--

DROP TABLE IF EXISTS `questionnaire_socialactivity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questionnaire_socialactivity` (
  `socialactivityid` int(11) NOT NULL AUTO_INCREMENT,
  `userno` int(11) NOT NULL,
  `answered` int(11) NOT NULL DEFAULT '0',
  `worktype_prune` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_agriculture` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_cleaning` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_housework` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_shopping` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_repair` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_caretaking` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_teaching` tinyint(4) NOT NULL DEFAULT '0',
  `worktype_consulting` tinyint(4) NOT NULL DEFAULT '0',
  `study_english` tinyint(4) NOT NULL DEFAULT '0',
  `study_foreignlanguage` tinyint(4) NOT NULL DEFAULT '0',
  `study_it` tinyint(4) NOT NULL DEFAULT '0',
  `study_business` tinyint(4) NOT NULL DEFAULT '0',
  `study_caretaking` tinyint(4) NOT NULL DEFAULT '0',
  `study_housework` tinyint(4) NOT NULL DEFAULT '0',
  `study_liberalarts` tinyint(4) NOT NULL DEFAULT '0',
  `study_art` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_health` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_elderly` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_disable` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_children` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_sport` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_town` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_safety` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_nature` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_disaster` tinyint(4) NOT NULL DEFAULT '0',
  `volunteer_international` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_musicalinstrument` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_chorus` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_dance` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_shodo` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_kado` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_sado` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_wasai` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_knit` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_cooking` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_gardening` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_diy` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_painting` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_pottery` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_photo` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_writing` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_go` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_camp` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_watchsport` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_watchperformance` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_watchmovie` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_listenmusic` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_reading` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_pachinko` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_karaoke` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_game` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_attraction` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_train` tinyint(4) NOT NULL DEFAULT '0',
  `hobby_car` tinyint(4) NOT NULL DEFAULT '0',
  `trip_daytrip` tinyint(4) NOT NULL DEFAULT '0',
  `trip_domestic` tinyint(4) NOT NULL DEFAULT '0',
  `trip_international` tinyint(4) NOT NULL DEFAULT '0',
  `sport_baseball` tinyint(4) NOT NULL DEFAULT '0',
  `sport_tabletennis` tinyint(4) NOT NULL DEFAULT '0',
  `sport_tennis` tinyint(4) NOT NULL DEFAULT '0',
  `sport_badminton` tinyint(4) NOT NULL DEFAULT '0',
  `sport_golf` tinyint(4) NOT NULL DEFAULT '0',
  `sport_gateball` tinyint(4) NOT NULL DEFAULT '0',
  `sport_bowling` tinyint(4) NOT NULL DEFAULT '0',
  `sport_fishing` tinyint(4) NOT NULL DEFAULT '0',
  `sport_swimming` tinyint(4) NOT NULL DEFAULT '0',
  `sport_skiing` tinyint(4) NOT NULL DEFAULT '0',
  `sport_climbing` tinyint(4) NOT NULL DEFAULT '0',
  `sport_cycling` tinyint(4) NOT NULL DEFAULT '0',
  `sport_jogging` tinyint(4) NOT NULL DEFAULT '0',
  `sport_walking` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`socialactivityid`),
  UNIQUE KEY `userno` (`userno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questionnaire_socialactivity`
--

LOCK TABLES `questionnaire_socialactivity` WRITE;
/*!40000 ALTER TABLE `questionnaire_socialactivity` DISABLE KEYS */;
/*!40000 ALTER TABLE `questionnaire_socialactivity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questionnaire_workstyle`
--

DROP TABLE IF EXISTS `questionnaire_workstyle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questionnaire_workstyle` (
  `workstyleid` int(11) NOT NULL AUTO_INCREMENT,
  `userno` int(11) NOT NULL,
  `workdayperweek` text NOT NULL,
  `worktimeperday` text NOT NULL,
  `commutetime` text NOT NULL,
  `transit_car` tinyint(4) NOT NULL,
  `transit_train` tinyint(4) NOT NULL,
  `transit_bus` tinyint(4) NOT NULL,
  `transit_bicycle` tinyint(4) NOT NULL,
  `transit_onfoot` tinyint(4) NOT NULL,
  `transit_other` tinyint(4) NOT NULL,
  `workobject_money_1` tinyint(4) NOT NULL,
  `workobject_money_2` tinyint(4) NOT NULL,
  `workobject_purposeoflife` tinyint(4) NOT NULL,
  `workobject_health` tinyint(4) NOT NULL,
  `workobject_contribution` tinyint(4) NOT NULL,
  `workobject_asked` tinyint(4) NOT NULL,
  `workobject_sparetime` tinyint(4) NOT NULL,
  `workobject_skill` tinyint(4) NOT NULL,
  `workobject_other` tinyint(4) NOT NULL,
  PRIMARY KEY (`workstyleid`),
  UNIQUE KEY `userno` (`userno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questionnaire_workstyle`
--

LOCK TABLES `questionnaire_workstyle` WRITE;
/*!40000 ALTER TABLE `questionnaire_workstyle` DISABLE KEYS */;
/*!40000 ALTER TABLE `questionnaire_workstyle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedule`
--

DROP TABLE IF EXISTS `schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedule` (
  `scheduleid` int(11) NOT NULL AUTO_INCREMENT,
  `userno` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `lastupdated` date NOT NULL DEFAULT '2000-01-01',
  `d1_am` int(11) NOT NULL DEFAULT '0',
  `d1_pm` int(11) NOT NULL DEFAULT '0',
  `d2_am` int(11) NOT NULL DEFAULT '0',
  `d2_pm` int(11) NOT NULL DEFAULT '0',
  `d3_am` int(11) NOT NULL DEFAULT '0',
  `d3_pm` int(11) NOT NULL DEFAULT '0',
  `d4_am` int(11) NOT NULL DEFAULT '0',
  `d4_pm` int(11) NOT NULL DEFAULT '0',
  `d5_am` int(11) NOT NULL DEFAULT '0',
  `d5_pm` int(11) NOT NULL DEFAULT '0',
  `d6_am` int(11) NOT NULL DEFAULT '0',
  `d6_pm` int(11) NOT NULL DEFAULT '0',
  `d7_am` int(11) NOT NULL DEFAULT '0',
  `d7_pm` int(11) NOT NULL DEFAULT '0',
  `d8_am` int(11) NOT NULL DEFAULT '0',
  `d8_pm` int(11) NOT NULL DEFAULT '0',
  `d9_am` int(11) NOT NULL DEFAULT '0',
  `d9_pm` int(11) NOT NULL DEFAULT '0',
  `d10_am` int(11) NOT NULL DEFAULT '0',
  `d10_pm` int(11) NOT NULL DEFAULT '0',
  `d11_am` int(11) NOT NULL DEFAULT '0',
  `d11_pm` int(11) NOT NULL DEFAULT '0',
  `d12_am` int(11) NOT NULL DEFAULT '0',
  `d12_pm` int(11) NOT NULL DEFAULT '0',
  `d13_am` int(11) NOT NULL DEFAULT '0',
  `d13_pm` int(11) NOT NULL DEFAULT '0',
  `d14_am` int(11) NOT NULL DEFAULT '0',
  `d14_pm` int(11) NOT NULL DEFAULT '0',
  `d15_am` int(11) NOT NULL DEFAULT '0',
  `d15_pm` int(11) NOT NULL DEFAULT '0',
  `d16_am` int(11) NOT NULL DEFAULT '0',
  `d16_pm` int(11) NOT NULL DEFAULT '0',
  `d17_am` int(11) NOT NULL DEFAULT '0',
  `d17_pm` int(11) NOT NULL DEFAULT '0',
  `d18_am` int(11) NOT NULL DEFAULT '0',
  `d18_pm` int(11) NOT NULL DEFAULT '0',
  `d19_am` int(11) NOT NULL DEFAULT '0',
  `d19_pm` int(11) NOT NULL DEFAULT '0',
  `d20_am` int(11) NOT NULL DEFAULT '0',
  `d20_pm` int(11) NOT NULL DEFAULT '0',
  `d21_am` int(11) NOT NULL DEFAULT '0',
  `d21_pm` int(11) NOT NULL DEFAULT '0',
  `d22_am` int(11) NOT NULL DEFAULT '0',
  `d22_pm` int(11) NOT NULL DEFAULT '0',
  `d23_am` int(11) NOT NULL DEFAULT '0',
  `d23_pm` int(11) NOT NULL DEFAULT '0',
  `d24_am` int(11) NOT NULL DEFAULT '0',
  `d24_pm` int(11) NOT NULL DEFAULT '0',
  `d25_am` int(11) NOT NULL DEFAULT '0',
  `d25_pm` int(11) NOT NULL DEFAULT '0',
  `d26_am` int(11) NOT NULL DEFAULT '0',
  `d26_pm` int(11) NOT NULL DEFAULT '0',
  `d27_am` int(11) NOT NULL DEFAULT '0',
  `d27_pm` int(11) NOT NULL DEFAULT '0',
  `d28_am` int(11) NOT NULL DEFAULT '0',
  `d28_pm` int(11) NOT NULL DEFAULT '0',
  `d29_am` int(11) NOT NULL DEFAULT '0',
  `d29_pm` int(11) NOT NULL DEFAULT '0',
  `d30_am` int(11) NOT NULL DEFAULT '0',
  `d30_pm` int(11) NOT NULL DEFAULT '0',
  `d31_am` int(11) NOT NULL DEFAULT '0',
  `d31_pm` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`scheduleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedule`
--

LOCK TABLES `schedule` WRITE;
/*!40000 ALTER TABLE `schedule` DISABLE KEYS */;
/*!40000 ALTER TABLE `schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bbs_group`
--

DROP TABLE IF EXISTS `bbs_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bbs_group` (
  `messageid` int(11) NOT NULL AUTO_INCREMENT,
  `groupno` int(11) NOT NULL,
  `senderid` int(11) DEFAULT NULL,
  `message` text,
  `datetime` datetime DEFAULT NULL,
  `jobpost` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`messageid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bbs_group`
--

LOCK TABLES `bbs_group` WRITE;
/*!40000 ALTER TABLE `bbs_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `bbs_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientinfo`
--

DROP TABLE IF EXISTS `clientinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientinfo` (
  `infoid` int(11) NOT NULL AUTO_INCREMENT,
  `clientid` int(11) NOT NULL,
  `workid` int(11) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`infoid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientinfo`
--

LOCK TABLES `clientinfo` WRITE;
/*!40000 ALTER TABLE `clientinfo` DISABLE KEYS */;
/*!40000 ALTER TABLE `clientinfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workcancel`
--

DROP TABLE IF EXISTS `workcancel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workcancel` (
  `cancelid` int(11) NOT NULL AUTO_INCREMENT,
  `workerno` int(11) NOT NULL,
  `workid` int(11) NOT NULL,
  `workday` date NOT NULL,
  `am` tinyint(4) NOT NULL,
  `pm` tinyint(4) NOT NULL,
  PRIMARY KEY (`cancelid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workcancel`
--

LOCK TABLES `workcancel` WRITE;
/*!40000 ALTER TABLE `workcancel` DISABLE KEYS */;
/*!40000 ALTER TABLE `workcancel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workdate`
--

DROP TABLE IF EXISTS `workdate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workdate` (
  `dateid` int(11) NOT NULL AUTO_INCREMENT,
  `workid` int(11) NOT NULL,
  `workday` date NOT NULL,
  `am` int(11) NOT NULL DEFAULT '0',
  `pm` int(11) NOT NULL DEFAULT '0',
  `workerno` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `reportflag` int(11) DEFAULT '0',
  `workreport` varchar(200) DEFAULT 'クリックして日報を入力してください',
  `worktime` decimal(3,1) NOT NULL DEFAULT '0.0',
  PRIMARY KEY (`dateid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workdate`
--

LOCK TABLES `workdate` WRITE;
/*!40000 ALTER TABLE `workdate` DISABLE KEYS */;
/*!40000 ALTER TABLE `workdate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workeval`
--

DROP TABLE IF EXISTS `workeval`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workeval` (
  `evalid` int(11) NOT NULL AUTO_INCREMENT,
  `workid` int(11) NOT NULL,
  `workerno` int(11) NOT NULL,
  `selfeval` int(11) DEFAULT '0',
  `clientinfo` int(11) NOT NULL DEFAULT '0',
  `evaluation` int(11) DEFAULT '0',
  `comment` text,
  PRIMARY KEY (`evalid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workeval`
--

LOCK TABLES `workeval` WRITE;
/*!40000 ALTER TABLE `workeval` DISABLE KEYS */;
/*!40000 ALTER TABLE `workeval` ENABLE KEYS */;
UNLOCK TABLES;

-- Table structure for table `workinterest`
--

DROP TABLE IF EXISTS `workinterest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workinterest` (
  `interestid` int(11) NOT NULL AUTO_INCREMENT,
  `workid` int(11) NOT NULL,
  `userno` int(11) NOT NULL,
  `interest` int(11) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`interestid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workinterest`
--

LOCK TABLES `workinterest` WRITE;
/*!40000 ALTER TABLE `workinterest` DISABLE KEYS */;
/*!40000 ALTER TABLE `workinterest` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `worklist`
--

DROP TABLE IF EXISTS `worklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `worklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupno` int(11) NOT NULL,
  `userno` int(11) DEFAULT NULL,
  `clientid` int(11) NOT NULL DEFAULT '0',
  `lat` decimal(18,12) DEFAULT NULL,
  `lng` decimal(18,12) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `worktitle` varchar(50) DEFAULT NULL,
  `content` text,
  `workdatetime` text,
  `contact` varchar(100) DEFAULT NULL,
  `price` text,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `worklist`
--

LOCK TABLES `worklist` WRITE;
/*!40000 ALTER TABLE `worklist` DISABLE KEYS */;
/*!40000 ALTER TABLE `worklist` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-12-17 17:07:38
