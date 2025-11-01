CREATE DATABASE  IF NOT EXISTS `airport_system` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `airport_system`;
-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: airport_system
-- ------------------------------------------------------
-- Server version	8.3.0

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
-- Table structure for table `aircraft_teams`
--

DROP TABLE IF EXISTS `aircraft_teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `aircraft_teams` (
  `aircraft_id` int NOT NULL,
  `team_id` int NOT NULL,
  `assignment_date` date NOT NULL,
  `assignment_type` enum('pilots','technicians','service') NOT NULL,
  PRIMARY KEY (`aircraft_id`,`team_id`,`assignment_type`),
  KEY `team_id` (`team_id`),
  CONSTRAINT `aircraft_teams_ibfk_1` FOREIGN KEY (`aircraft_id`) REFERENCES `aircrafts` (`aircraft_id`) ON DELETE CASCADE,
  CONSTRAINT `aircraft_teams_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aircraft_teams`
--

LOCK TABLES `aircraft_teams` WRITE;
/*!40000 ALTER TABLE `aircraft_teams` DISABLE KEYS */;
INSERT INTO `aircraft_teams` VALUES (1,1,'2024-01-10','pilots'),(1,3,'2024-01-10','technicians'),(2,2,'2024-02-15','pilots'),(3,3,'2024-03-20','technicians'),(4,1,'2024-04-05','pilots');
/*!40000 ALTER TABLE `aircraft_teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aircraft_types`
--

DROP TABLE IF EXISTS `aircraft_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `aircraft_types` (
  `type_id` int NOT NULL AUTO_INCREMENT,
  `type_name` varchar(50) NOT NULL,
  `manufacturer` varchar(100) NOT NULL,
  `capacity` int NOT NULL,
  `max_range_km` int NOT NULL,
  PRIMARY KEY (`type_id`),
  UNIQUE KEY `type_name` (`type_name`),
  CONSTRAINT `aircraft_types_chk_1` CHECK ((`capacity` > 0)),
  CONSTRAINT `aircraft_types_chk_2` CHECK ((`max_range_km` > 0))
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aircraft_types`
--

LOCK TABLES `aircraft_types` WRITE;
/*!40000 ALTER TABLE `aircraft_types` DISABLE KEYS */;
INSERT INTO `aircraft_types` VALUES (1,'Boeing 737-800','Boeing',189,5765),(2,'Airbus A320','Airbus',180,6150),(3,'Sukhoi Superjet 100','Sukhoi',98,4578),(4,'Boeing 777-300','Boeing',550,11100),(5,'Airbus A321','Airbus',220,7400);
/*!40000 ALTER TABLE `aircraft_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aircrafts`
--

DROP TABLE IF EXISTS `aircrafts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `aircrafts` (
  `aircraft_id` int NOT NULL AUTO_INCREMENT,
  `registration_number` varchar(20) NOT NULL,
  `type_id` int NOT NULL,
  `manufacture_date` date NOT NULL,
  `acquisition_date` date NOT NULL,
  `status` enum('active','maintenance','retired') DEFAULT 'active',
  PRIMARY KEY (`aircraft_id`),
  UNIQUE KEY `registration_number` (`registration_number`),
  KEY `type_id` (`type_id`),
  CONSTRAINT `aircrafts_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `aircraft_types` (`type_id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aircrafts`
--

LOCK TABLES `aircrafts` WRITE;
/*!40000 ALTER TABLE `aircrafts` DISABLE KEYS */;
INSERT INTO `aircrafts` VALUES (1,'RA-73001',1,'2018-03-15','2018-04-20','active'),(2,'RA-73002',1,'2019-05-20','2019-06-25','active'),(3,'VP-BCD',2,'2017-08-10','2017-09-15','maintenance'),(4,'RA-73004',3,'2020-01-30','2020-03-05','active'),(5,'RA-73005',4,'2015-11-12','2015-12-20','retired'),(6,'RA-73006',5,'2021-06-08','2021-07-15','active');
/*!40000 ALTER TABLE `aircrafts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `department_managers`
--

DROP TABLE IF EXISTS `department_managers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `department_managers` (
  `department_id` int NOT NULL,
  `employee_id` int NOT NULL,
  `appointment_date` date NOT NULL,
  PRIMARY KEY (`department_id`),
  UNIQUE KEY `employee_id` (`employee_id`),
  CONSTRAINT `department_managers_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE,
  CONSTRAINT `department_managers_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `department_managers`
--

LOCK TABLES `department_managers` WRITE;
/*!40000 ALTER TABLE `department_managers` DISABLE KEYS */;
INSERT INTO `department_managers` VALUES (1,1,'2020-01-15'),(2,7,'2019-03-20'),(3,6,'2021-02-10'),(4,8,'2018-11-05'),(5,9,'2020-06-10');
/*!40000 ALTER TABLE `department_managers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `department_id` int NOT NULL AUTO_INCREMENT,
  `department_name` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`department_id`),
  UNIQUE KEY `department_name` (`department_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Летная служба','Пилоты и летный состав'),(2,'Техническая служба','Обслуживание самолетов'),(3,'Наземная служба','Обслуживание пассажиров'),(4,'Администрация','Управление'),(5,'Безопасность','Обеспечение безопасности');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employees` (
  `employee_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `birth_date` date NOT NULL,
  `gender` enum('M','F') NOT NULL,
  `hire_date` date NOT NULL,
  `position_id` int NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `children_count` int DEFAULT '0',
  `phone_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`employee_id`),
  KEY `position_id` (`position_id`),
  CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`position_id`) REFERENCES `positions` (`position_id`) ON DELETE RESTRICT,
  CONSTRAINT `employees_chk_1` CHECK ((`salary` > 0)),
  CONSTRAINT `employees_chk_2` CHECK ((`children_count` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (1,'Иван','Петров','1980-05-15','M','2015-03-20',1,300000.00,2,'+79161111111','pilot1@airport.ru'),(2,'Мария','Сидорова','1985-08-22','F','2018-07-10',1,280000.00,1,'+79162222222','pilot2@airport.ru'),(3,'Сергей','Иванов','1978-12-03','M','2010-11-15',1,350000.00,3,'+79163333333','pilot3@airport.ru'),(4,'Елена','Кузнецова','1990-03-18','F','2020-02-01',2,180000.00,0,'+79164444444','pilot4@airport.ru'),(5,'Алексей','Смирнов','1982-07-30','M','2016-09-05',2,200000.00,2,'+79165555555','pilot5@airport.ru'),(6,'Ольга','Николаева','1992-04-12','F','2019-06-15',3,60000.00,0,'+79166666666','steward1@airport.ru'),(7,'Дмитрий','Васильев','1988-09-25','M','2017-08-20',4,100000.00,1,'+79167777777','tech1@airport.ru'),(8,'Анна','Павлова','1987-11-08','F','2014-12-10',5,120000.00,2,'+79168888888','manager1@airport.ru'),(9,'Михаил','Федоров','1975-06-14','M','2008-03-05',6,90000.00,3,'+79169999999','security1@airport.ru'),(10,'Татьяна','Морозова','1995-02-28','F','2021-07-01',7,45000.00,0,'+79160000000','cashier1@airport.ru');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `flight_categories`
--

DROP TABLE IF EXISTS `flight_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `flight_categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) NOT NULL,
  `description` text,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_name` (`category_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flight_categories`
--

LOCK TABLES `flight_categories` WRITE;
/*!40000 ALTER TABLE `flight_categories` DISABLE KEYS */;
INSERT INTO `flight_categories` VALUES (1,'Регулярный','Регулярные рейсы'),(2,'Чартерный','Чартерные рейсы'),(3,'Бизнес','Бизнес-класс'),(4,'Эконом','Эконом-класс'),(5,'Международный','Международные рейсы');
/*!40000 ALTER TABLE `flight_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `flight_delays`
--

DROP TABLE IF EXISTS `flight_delays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `flight_delays` (
  `delay_id` int NOT NULL AUTO_INCREMENT,
  `flight_id` int NOT NULL,
  `delay_start` datetime NOT NULL,
  `delay_end` datetime DEFAULT NULL,
  `delay_reason` enum('weather','technical','crew','air_traffic','other') NOT NULL,
  `description` text,
  PRIMARY KEY (`delay_id`),
  KEY `flight_id` (`flight_id`),
  CONSTRAINT `flight_delays_ibfk_1` FOREIGN KEY (`flight_id`) REFERENCES `flights` (`flight_id`) ON DELETE CASCADE,
  CONSTRAINT `flight_delays_chk_1` CHECK (((`delay_start` <= `delay_end`) or (`delay_end` is null)))
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flight_delays`
--

LOCK TABLES `flight_delays` WRITE;
/*!40000 ALTER TABLE `flight_delays` DISABLE KEYS */;
INSERT INTO `flight_delays` VALUES (1,7,'2024-10-22 07:30:00','2024-10-22 09:00:00','weather','Туман'),(2,8,'2024-10-21 09:30:00','2024-10-21 11:00:00','technical','Неисправность'),(3,7,'2024-10-22 06:00:00','2024-10-22 07:00:00','crew','Экипаж'),(4,8,'2024-10-21 08:00:00','2024-10-21 09:00:00','air_traffic','Очередь');
/*!40000 ALTER TABLE `flight_delays` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `flight_services`
--

DROP TABLE IF EXISTS `flight_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `flight_services` (
  `service_id` int NOT NULL AUTO_INCREMENT,
  `flight_id` int NOT NULL,
  `service_type` enum('cleaning','catering','fueling','technical') NOT NULL,
  `service_date` datetime NOT NULL,
  `responsible_team_id` int NOT NULL,
  `status` enum('planned','in_progress','completed') NOT NULL,
  `notes` text,
  PRIMARY KEY (`service_id`),
  KEY `flight_id` (`flight_id`),
  KEY `responsible_team_id` (`responsible_team_id`),
  CONSTRAINT `flight_services_ibfk_1` FOREIGN KEY (`flight_id`) REFERENCES `flights` (`flight_id`) ON DELETE CASCADE,
  CONSTRAINT `flight_services_ibfk_2` FOREIGN KEY (`responsible_team_id`) REFERENCES `teams` (`team_id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flight_services`
--

LOCK TABLES `flight_services` WRITE;
/*!40000 ALTER TABLE `flight_services` DISABLE KEYS */;
INSERT INTO `flight_services` VALUES (1,1,'cleaning','2024-10-25 06:30:00',4,'completed','Уборка'),(2,1,'fueling','2024-10-25 07:00:00',4,'completed','Заправка'),(3,2,'catering','2024-10-25 08:45:00',4,'in_progress','Питание'),(4,3,'technical','2024-10-25 12:00:00',3,'planned','Осмотр');
/*!40000 ALTER TABLE `flight_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `flights`
--

DROP TABLE IF EXISTS `flights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `flights` (
  `flight_id` int NOT NULL AUTO_INCREMENT,
  `flight_number` varchar(10) NOT NULL,
  `route_id` int NOT NULL,
  `aircraft_id` int NOT NULL,
  `category_id` int NOT NULL,
  `departure_time` datetime NOT NULL,
  `arrival_time` datetime NOT NULL,
  `base_ticket_price` decimal(10,2) NOT NULL,
  `minimum_passengers` int NOT NULL,
  `status` enum('scheduled','boarding','departed','arrived','delayed','cancelled') DEFAULT 'scheduled',
  PRIMARY KEY (`flight_id`),
  UNIQUE KEY `flight_number` (`flight_number`),
  KEY `route_id` (`route_id`),
  KEY `aircraft_id` (`aircraft_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `flights_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `routes` (`route_id`) ON DELETE RESTRICT,
  CONSTRAINT `flights_ibfk_2` FOREIGN KEY (`aircraft_id`) REFERENCES `aircrafts` (`aircraft_id`) ON DELETE RESTRICT,
  CONSTRAINT `flights_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `flight_categories` (`category_id`) ON DELETE RESTRICT,
  CONSTRAINT `flights_chk_1` CHECK ((`base_ticket_price` >= 0)),
  CONSTRAINT `flights_chk_2` CHECK ((`minimum_passengers` >= 0)),
  CONSTRAINT `flights_chk_3` CHECK ((`departure_time` < `arrival_time`))
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flights`
--

LOCK TABLES `flights` WRITE;
/*!40000 ALTER TABLE `flights` DISABLE KEYS */;
INSERT INTO `flights` VALUES (1,'SU-1001',1,1,1,'2024-10-25 08:00:00','2024-10-25 09:30:00',5000.00,50,'scheduled'),(2,'SU-1002',2,2,1,'2024-10-25 10:15:00','2024-10-25 12:45:00',8000.00,40,'boarding'),(3,'SU-1003',3,4,1,'2024-10-25 14:30:00','2024-10-25 17:10:00',7500.00,30,'departed'),(4,'SU-1004',6,1,5,'2024-10-25 18:00:00','2024-10-25 20:30:00',15000.00,100,'scheduled'),(5,'SU-2001',4,2,1,'2024-10-24 08:00:00','2024-10-24 16:00:00',12000.00,80,'cancelled'),(6,'SU-2002',5,4,2,'2024-10-23 14:00:00','2024-10-23 16:00:00',9000.00,60,'cancelled'),(7,'SU-3001',1,1,1,'2024-10-22 08:00:00','2024-10-22 09:30:00',5000.00,50,'delayed'),(8,'SU-3002',2,2,1,'2024-10-21 10:00:00','2024-10-21 12:30:00',8000.00,40,'delayed'),(9,'SU-4001',7,6,5,'2024-10-26 09:00:00','2024-10-26 11:30:00',18000.00,120,'scheduled'),(10,'SU-4002',1,4,3,'2024-10-26 12:00:00','2024-10-26 13:30:00',20000.00,40,'scheduled'),(11,'SU-7001',1,1,1,'2024-11-05 08:00:00','2024-11-05 09:30:00',5000.00,50,'cancelled'),(12,'SU-7002',2,2,1,'2024-11-06 10:00:00','2024-11-06 12:30:00',8000.00,40,'cancelled');
/*!40000 ALTER TABLE `flights` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medical_examinations`
--

DROP TABLE IF EXISTS `medical_examinations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medical_examinations` (
  `examination_id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `examination_date` date NOT NULL,
  `next_examination_date` date NOT NULL,
  `result` enum('passed','failed') NOT NULL,
  `doctor_name` varchar(100) DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`examination_id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `medical_examinations_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE,
  CONSTRAINT `medical_examinations_chk_1` CHECK ((`examination_date` < `next_examination_date`))
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medical_examinations`
--

LOCK TABLES `medical_examinations` WRITE;
/*!40000 ALTER TABLE `medical_examinations` DISABLE KEYS */;
INSERT INTO `medical_examinations` VALUES (1,1,'2024-01-15','2024-07-15','passed','Доктор Иванов','Норма'),(2,1,'2023-07-15','2024-01-15','passed','Доктор Петрова','Норма'),(3,2,'2024-02-20','2024-08-20','failed','Доктор Сидоров','Проблемы'),(4,2,'2023-08-20','2024-02-20','passed','Доктор Иванов','Норма'),(5,3,'2023-12-10','2024-06-10','passed','Доктор Петрова','Норма'),(6,4,'2024-03-05','2024-09-05','passed','Доктор Сидоров','Норма'),(7,5,'2023-11-20','2024-05-20','failed','Доктор Иванов','Проблемы');
/*!40000 ALTER TABLE `medical_examinations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `passengers`
--

DROP TABLE IF EXISTS `passengers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `passengers` (
  `passenger_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `birth_date` date NOT NULL,
  `gender` enum('M','F') NOT NULL,
  `passport_number` varchar(20) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`passenger_id`),
  UNIQUE KEY `passport_number` (`passport_number`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `passengers`
--

LOCK TABLES `passengers` WRITE;
/*!40000 ALTER TABLE `passengers` DISABLE KEYS */;
INSERT INTO `passengers` VALUES (1,'Анна','Иванова','1990-03-15','F','4511123456','+79161112233','anna@mail.ru'),(2,'Сергей','Смирнов','1985-07-22','M','4511654321','+79162223344','sergey@mail.ru'),(3,'Елена','Кузнецова','1992-11-08','F','4511789456','+79163334455','elena@mail.ru'),(4,'Александр','Попов','1978-04-30','M','4511321654','+79164445566','alex@mail.ru'),(5,'Ольга','Лебедева','1988-09-12','F','4511987456','+79165556677','olga@mail.ru'),(6,'Дмитрий','Новиков','1995-12-25','M','4511234567','+79166667788','dmitry@mail.ru'),(7,'Ирина','Волкова','1982-06-18','F','4511345678','+79167778899','irina@mail.ru'),(8,'Павел','Соколов','1975-08-03','M','4511456789','+79168889900','pavel@mail.ru');
/*!40000 ALTER TABLE `passengers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `positions`
--

DROP TABLE IF EXISTS `positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `positions` (
  `position_id` int NOT NULL AUTO_INCREMENT,
  `position_name` varchar(100) NOT NULL,
  `department_id` int NOT NULL,
  `salary_range_min` decimal(10,2) DEFAULT NULL,
  `salary_range_max` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`position_id`),
  UNIQUE KEY `position_name` (`position_name`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `positions_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `positions`
--

LOCK TABLES `positions` WRITE;
/*!40000 ALTER TABLE `positions` DISABLE KEYS */;
INSERT INTO `positions` VALUES (1,'Пилот',1,250000.00,400000.00),(2,'Второй пилот',1,150000.00,250000.00),(3,'Стюардесса',3,50000.00,80000.00),(4,'Авиатехник',2,80000.00,120000.00),(5,'Менеджер',4,100000.00,150000.00),(6,'Специалист безопасности',5,70000.00,100000.00),(7,'Кассир',3,40000.00,60000.00),(8,'Диспетчер',1,90000.00,130000.00);
/*!40000 ALTER TABLE `positions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `repairs`
--

DROP TABLE IF EXISTS `repairs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `repairs` (
  `repair_id` int NOT NULL AUTO_INCREMENT,
  `aircraft_id` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `repair_type` varchar(100) NOT NULL,
  `description` text,
  `cost` decimal(10,2) DEFAULT NULL,
  `technician_team_id` int NOT NULL,
  PRIMARY KEY (`repair_id`),
  KEY `aircraft_id` (`aircraft_id`),
  KEY `technician_team_id` (`technician_team_id`),
  CONSTRAINT `repairs_ibfk_1` FOREIGN KEY (`aircraft_id`) REFERENCES `aircrafts` (`aircraft_id`) ON DELETE CASCADE,
  CONSTRAINT `repairs_ibfk_2` FOREIGN KEY (`technician_team_id`) REFERENCES `teams` (`team_id`) ON DELETE RESTRICT,
  CONSTRAINT `repairs_chk_1` CHECK ((`cost` >= 0)),
  CONSTRAINT `repairs_chk_2` CHECK (((`start_date` <= `end_date`) or (`end_date` is null)))
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `repairs`
--

LOCK TABLES `repairs` WRITE;
/*!40000 ALTER TABLE `repairs` DISABLE KEYS */;
INSERT INTO `repairs` VALUES (1,3,'2024-10-11','2024-10-20','Капитальный','Замена двигателя',500000.00,3),(2,2,'2024-09-16','2024-09-25','Текущий','Ремонт шасси',150000.00,3),(3,1,'2024-07-13','2024-07-15','Профилактика','Обслуживание',80000.00,3),(4,3,'2023-12-01','2023-12-10','Капитальный','Ремонт корпуса',300000.00,3),(5,2,'2023-08-10','2023-08-12','Текущий','Электрооборудование',120000.00,3);
/*!40000 ALTER TABLE `repairs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `routes`
--

DROP TABLE IF EXISTS `routes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `routes` (
  `route_id` int NOT NULL AUTO_INCREMENT,
  `departure_airport` varchar(100) NOT NULL,
  `arrival_airport` varchar(100) NOT NULL,
  `transit_airport` varchar(100) DEFAULT NULL,
  `distance_km` int NOT NULL,
  `estimated_duration_min` int NOT NULL,
  PRIMARY KEY (`route_id`),
  UNIQUE KEY `unique_route` (`departure_airport`,`arrival_airport`,`transit_airport`),
  CONSTRAINT `routes_chk_1` CHECK ((`distance_km` > 0)),
  CONSTRAINT `routes_chk_2` CHECK ((`estimated_duration_min` > 0))
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `routes`
--

LOCK TABLES `routes` WRITE;
/*!40000 ALTER TABLE `routes` DISABLE KEYS */;
INSERT INTO `routes` VALUES (1,'Шереметьево (Moscow)','Пулково (St Petersburg)',NULL,650,90),(2,'Домодедово (Moscow)','Сочи (Sochi)',NULL,1350,150),(3,'Внуково (Moscow)','Екатеринбург (Yekaterinburg)',NULL,1420,160),(4,'Шереметьево (Moscow)','Владивосток (Vladivostok)','Новосибирск',6420,480),(5,'Пулково (St Petersburg)','Калининград (Kaliningrad)',NULL,950,120),(6,'Шереметьево (Moscow)','Прага (Prague)',NULL,1650,180),(7,'Домодедово (Moscow)','Стамбул (Istanbul)',NULL,1750,190);
/*!40000 ALTER TABLE `routes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_members`
--

DROP TABLE IF EXISTS `team_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_members` (
  `team_id` int NOT NULL,
  `employee_id` int NOT NULL,
  `join_date` date NOT NULL,
  PRIMARY KEY (`team_id`,`employee_id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `team_members_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`) ON DELETE CASCADE,
  CONSTRAINT `team_members_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_members`
--

LOCK TABLES `team_members` WRITE;
/*!40000 ALTER TABLE `team_members` DISABLE KEYS */;
INSERT INTO `team_members` VALUES (1,1,'2020-01-15'),(1,3,'2020-01-15'),(1,6,'2020-01-15'),(2,2,'2020-03-20'),(2,4,'2020-03-20'),(2,5,'2020-03-20'),(3,7,'2019-05-10'),(3,10,'2021-07-01'),(4,6,'2021-02-15'),(4,10,'2021-07-01'),(5,9,'2020-08-01');
/*!40000 ALTER TABLE `team_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teams` (
  `team_id` int NOT NULL AUTO_INCREMENT,
  `team_name` varchar(100) NOT NULL,
  `department_id` int NOT NULL,
  `team_leader_id` int DEFAULT NULL,
  `created_date` date NOT NULL,
  PRIMARY KEY (`team_id`),
  KEY `department_id` (`department_id`),
  KEY `team_leader_id` (`team_leader_id`),
  CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE,
  CONSTRAINT `teams_ibfk_2` FOREIGN KEY (`team_leader_id`) REFERENCES `employees` (`employee_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teams`
--

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
INSERT INTO `teams` VALUES (1,'Летный экипаж A',1,1,'2020-01-15'),(2,'Летный экипаж B',1,2,'2020-03-20'),(3,'Техническая бригада 1',2,7,'2019-05-10'),(4,'Обслуживание рейсов 1',3,6,'2021-02-15'),(5,'Служба безопасности A',5,9,'2020-08-01');
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `technical_inspections`
--

DROP TABLE IF EXISTS `technical_inspections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `technical_inspections` (
  `inspection_id` int NOT NULL AUTO_INCREMENT,
  `aircraft_id` int NOT NULL,
  `inspection_date` datetime NOT NULL,
  `inspector_id` int NOT NULL,
  `inspection_type` enum('routine','preflight','special') NOT NULL,
  `result` enum('passed','failed','requires_repair') NOT NULL,
  `notes` text,
  PRIMARY KEY (`inspection_id`),
  KEY `aircraft_id` (`aircraft_id`),
  KEY `inspector_id` (`inspector_id`),
  CONSTRAINT `technical_inspections_ibfk_1` FOREIGN KEY (`aircraft_id`) REFERENCES `aircrafts` (`aircraft_id`) ON DELETE CASCADE,
  CONSTRAINT `technical_inspections_ibfk_2` FOREIGN KEY (`inspector_id`) REFERENCES `employees` (`employee_id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technical_inspections`
--

LOCK TABLES `technical_inspections` WRITE;
/*!40000 ALTER TABLE `technical_inspections` DISABLE KEYS */;
INSERT INTO `technical_inspections` VALUES (1,1,'2024-10-01 08:00:00',7,'routine','passed','Плановый'),(2,1,'2024-10-20 09:00:00',7,'preflight','passed','Предполетный'),(3,2,'2024-09-15 10:30:00',7,'routine','failed','Неисправности'),(4,3,'2024-10-10 14:00:00',7,'special','requires_repair','Ремонт'),(5,4,'2024-08-05 11:00:00',7,'routine','passed','Плановый'),(6,1,'2024-07-12 16:00:00',7,'special','passed','Специальный');
/*!40000 ALTER TABLE `technical_inspections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket_refunds`
--

DROP TABLE IF EXISTS `ticket_refunds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ticket_refunds` (
  `refund_id` int NOT NULL AUTO_INCREMENT,
  `ticket_id` int NOT NULL,
  `refund_date` datetime NOT NULL,
  `refund_amount` decimal(10,2) NOT NULL,
  `refund_reason` text,
  `employee_id` int NOT NULL,
  PRIMARY KEY (`refund_id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `ticket_refunds_ibfk_2` (`employee_id`),
  CONSTRAINT `ticket_refunds_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`ticket_id`) ON DELETE RESTRICT,
  CONSTRAINT `ticket_refunds_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE RESTRICT,
  CONSTRAINT `ticket_refunds_chk_1` CHECK ((`refund_amount` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_refunds`
--

LOCK TABLES `ticket_refunds` WRITE;
/*!40000 ALTER TABLE `ticket_refunds` DISABLE KEYS */;
INSERT INTO `ticket_refunds` VALUES (1,8,'2024-10-20 14:30:00',5000.00,'Изменение планов',8),(2,9,'2024-10-19 10:15:00',8000.00,'Болезнь',8),(3,8,'2024-10-18 16:45:00',5000.00,'Отмена поездки',8),(4,9,'2024-10-17 09:20:00',8000.00,'Смена рейса',8);
/*!40000 ALTER TABLE `ticket_refunds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tickets` (
  `ticket_id` int NOT NULL AUTO_INCREMENT,
  `flight_id` int NOT NULL,
  `passenger_id` int NOT NULL,
  `seat_number` varchar(10) NOT NULL,
  `ticket_price` decimal(10,2) NOT NULL,
  `purchase_date` datetime NOT NULL,
  `status` enum('booked','confirmed','cancelled','refunded') NOT NULL,
  `has_baggage` tinyint(1) DEFAULT '0',
  `baggage_weight_kg` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`ticket_id`),
  UNIQUE KEY `unique_seat` (`flight_id`,`seat_number`),
  KEY `passenger_id` (`passenger_id`),
  CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`flight_id`) REFERENCES `flights` (`flight_id`) ON DELETE RESTRICT,
  CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`passenger_id`) REFERENCES `passengers` (`passenger_id`) ON DELETE CASCADE,
  CONSTRAINT `tickets_chk_1` CHECK ((`ticket_price` >= 0)),
  CONSTRAINT `tickets_chk_2` CHECK ((`baggage_weight_kg` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tickets`
--

LOCK TABLES `tickets` WRITE;
/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;
INSERT INTO `tickets` VALUES (1,1,1,'12A',5200.00,'2024-10-20 10:30:00','confirmed',1,23.50),(2,1,2,'12B',5200.00,'2024-10-21 14:15:00','confirmed',1,18.00),(3,1,3,'8C',5000.00,'2024-10-19 09:45:00','booked',0,NULL),(4,2,4,'15D',8300.00,'2024-10-22 16:20:00','confirmed',1,15.00),(5,2,5,'22F',8000.00,'2024-10-18 11:10:00','confirmed',0,NULL),(6,5,6,'10A',12500.00,'2024-10-15 12:00:00','cancelled',1,20.00),(7,6,7,'18B',9500.00,'2024-10-14 15:30:00','cancelled',0,NULL),(8,7,8,'5C',5200.00,'2024-10-12 08:20:00','refunded',1,25.00),(9,8,1,'7D',8500.00,'2024-10-11 10:45:00','refunded',0,NULL),(10,3,2,'20A',7800.00,'2024-10-23 14:00:00','confirmed',1,22.00),(11,4,3,'14B',15500.00,'2024-10-22 16:30:00','confirmed',1,18.50),(12,9,4,'1A',22000.00,'2024-10-21 11:20:00','confirmed',1,30.00),(13,10,5,'2B',21000.00,'2024-10-20 09:15:00','confirmed',0,NULL),(14,11,1,'10A',5200.00,'2024-11-01 10:30:00','cancelled',1,20.00),(15,11,2,'10B',5200.00,'2024-11-02 14:15:00','cancelled',0,NULL),(16,12,3,'5C',8300.00,'2024-10-31 09:45:00','cancelled',1,25.00),(17,12,4,'5D',8300.00,'2024-11-03 16:20:00','cancelled',1,15.00);
/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-01 22:15:41
