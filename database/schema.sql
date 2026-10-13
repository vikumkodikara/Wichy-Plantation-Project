-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: coconut_shop_simple
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
INSERT INTO `cart` VALUES (32,84,16,1);
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,'samintha','Best Products','2025-09-12 01:23:55'),(2,'Anjana','Superb Products','2025-09-12 09:26:08'),(3,'Anjana','best','2025-09-12 11:42:11'),(4,'samintha','good Products','2026-03-17 03:13:02');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
INSERT INTO `contact_messages` VALUES (1,'nipun','nipun@gmail.com','For Get refund','I have some refund money to get back','2025-09-12 01:56:17'),(4,'pahan','pahan@gmail.com','For ask New Item','When will new stocks will Come','2025-09-12 02:09:56'),(5,'nipun','aloka@gmail.com','For Get refund','nice','2025-09-12 09:30:45'),(6,'nipun','aloka@gmail.com','For Get refund','failed refund','2025-09-12 11:44:11');
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_phones`
--

DROP TABLE IF EXISTS `employee_phones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_phones` (
  `Phone_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Emp_ID` int(11) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  PRIMARY KEY (`Phone_ID`),
  KEY `Emp_ID` (`Emp_ID`),
  CONSTRAINT `employee_phones_ibfk_1` FOREIGN KEY (`Emp_ID`) REFERENCES `employees` (`Emp_ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_phones`
--

LOCK TABLES `employee_phones` WRITE;
/*!40000 ALTER TABLE `employee_phones` DISABLE KEYS */;
INSERT INTO `employee_phones` VALUES (9,9,'0715872536'),(10,10,'0715872536');
/*!40000 ALTER TABLE `employee_phones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees` (
  `Emp_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Role` varchar(50) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Address` varchar(255) NOT NULL,
  PRIMARY KEY (`Emp_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (9,'Software Engineer','pasan','kaluthara'),(10,'Software Engineer','pasan','kaluthara');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fatrate10`
--

DROP TABLE IF EXISTS `fatrate10`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fatrate10` (
  `Ingredient` varchar(50) DEFAULT NULL,
  `Or_Food_and_Cafe` int(11) DEFAULT NULL,
  `Derrimon` int(11) DEFAULT NULL,
  `CLF` int(11) DEFAULT NULL,
  `Foodie` int(11) DEFAULT NULL,
  `Cocofina` int(11) DEFAULT NULL,
  `KLF` int(11) DEFAULT NULL,
  `CoconutCompany` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fatrate10`
--

LOCK TABLES `fatrate10` WRITE;
/*!40000 ALTER TABLE `fatrate10` DISABLE KEYS */;
INSERT INTO `fatrate10` VALUES ('CMC(g)',700,800,700,700,700,700,700),('Guar gum(g)',300,200,200,200,300,200,300),('Xantham gum(g)',200,0,100,200,200,200,100),('Sugar(g)',100,200,100,200,0,100,0),('Polysorbate(g)',0,0,25,25,0,25,0);
/*!40000 ALTER TABLE `fatrate10` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fatrate12`
--

DROP TABLE IF EXISTS `fatrate12`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fatrate12` (
  `Ingredient` varchar(50) DEFAULT NULL,
  `Or_Food_and_Cafe` int(11) DEFAULT NULL,
  `Derrimon` int(11) DEFAULT NULL,
  `KTC` int(11) DEFAULT NULL,
  `CLF` int(11) DEFAULT NULL,
  `Foodie` int(11) DEFAULT NULL,
  `Cocofina` int(11) DEFAULT NULL,
  `Binzagr` int(11) DEFAULT NULL,
  `EDS` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fatrate12`
--

LOCK TABLES `fatrate12` WRITE;
/*!40000 ALTER TABLE `fatrate12` DISABLE KEYS */;
INSERT INTO `fatrate12` VALUES ('CMC(g)',600,600,600,600,600,600,600,600),('Guar gum(g)',100,200,200,300,0,300,300,200),('Xantham gum(g)',100,100,100,150,200,100,100,100),('Sugar(g)',0,0,0,0,0,0,0,0),('Polysorbate(g)',25,50,25,50,50,0,0,50);
/*!40000 ALTER TABLE `fatrate12` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fatrate18`
--

DROP TABLE IF EXISTS `fatrate18`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fatrate18` (
  `Ingredient` varchar(50) DEFAULT NULL,
  `Derrimon` int(11) DEFAULT NULL,
  `KTC` int(11) DEFAULT NULL,
  `CLF` int(11) DEFAULT NULL,
  `Cocofina` int(11) DEFAULT NULL,
  `Binzagr` int(11) DEFAULT NULL,
  `EDS` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fatrate18`
--

LOCK TABLES `fatrate18` WRITE;
/*!40000 ALTER TABLE `fatrate18` DISABLE KEYS */;
INSERT INTO `fatrate18` VALUES ('CMC(g)',600,500,500,600,600,500),('Guar gum(g)',0,300,100,150,200,200),('Xantham gum(g)',200,100,100,100,100,100),('Sugar(g)',0,0,0,0,0,0),('Polysorbate(g)',25,0,50,25,25,25);
/*!40000 ALTER TABLE `fatrate18` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fatrate22`
--

DROP TABLE IF EXISTS `fatrate22`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fatrate22` (
  `Ingredient` varchar(50) DEFAULT NULL,
  `Or_Food_and_Cafe` int(11) DEFAULT NULL,
  `Derrimon` int(11) DEFAULT NULL,
  `KTC` int(11) DEFAULT NULL,
  `CLF` int(11) DEFAULT NULL,
  `Foodie` int(11) DEFAULT NULL,
  `KLF` int(11) DEFAULT NULL,
  `CoconutCompany` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fatrate22`
--

LOCK TABLES `fatrate22` WRITE;
/*!40000 ALTER TABLE `fatrate22` DISABLE KEYS */;
INSERT INTO `fatrate22` VALUES ('CMC(g)',300,300,300,300,300,300,300),('Guar gum(g)',200,200,100,200,200,100,200),('Xantham gum(g)',200,150,200,200,200,200,200),('Sugar(g)',0,0,0,0,0,0,0),('Polysorbate(g)',50,0,50,50,50,25,0);
/*!40000 ALTER TABLE `fatrate22` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fatrate30`
--

DROP TABLE IF EXISTS `fatrate30`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fatrate30` (
  `Ingredient` varchar(50) DEFAULT NULL,
  `Or_Food_and_Cafe` int(11) DEFAULT NULL,
  `Derrimon` int(11) DEFAULT NULL,
  `CLF` int(11) DEFAULT NULL,
  `Cocofina` int(11) DEFAULT NULL,
  `Binzagr` int(11) DEFAULT NULL,
  `KLF` int(11) DEFAULT NULL,
  `CoconutCompany` int(11) DEFAULT NULL,
  `EDS` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fatrate30`
--

LOCK TABLES `fatrate30` WRITE;
/*!40000 ALTER TABLE `fatrate30` DISABLE KEYS */;
INSERT INTO `fatrate30` VALUES ('CMC(g)',600,600,500,600,600,600,600,600),('Guar gum(g)',100,100,100,100,100,100,100,100),('Sugar(g)',0,0,0,0,0,0,0,0),('Polysorbate(g)',0,25,0,50,0,25,0,50);
/*!40000 ALTER TABLE `fatrate30` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fatrate6`
--

DROP TABLE IF EXISTS `fatrate6`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fatrate6` (
  `Ingredient` varchar(50) DEFAULT NULL,
  `Or_Food_and_Cafe` int(11) DEFAULT NULL,
  `Derrimon` int(11) DEFAULT NULL,
  `KTC` int(11) DEFAULT NULL,
  `CLF` int(11) DEFAULT NULL,
  `Binzagr` int(11) DEFAULT NULL,
  `KLF` int(11) DEFAULT NULL,
  `EDS` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fatrate6`
--

LOCK TABLES `fatrate6` WRITE;
/*!40000 ALTER TABLE `fatrate6` DISABLE KEYS */;
INSERT INTO `fatrate6` VALUES ('Xantham gum(g)',200,100,200,200,250,0,300),('Sugar(g)',100,0,100,200,100,200,100),('Polysorbate(g)',50,50,50,50,25,50,0),('vinergar',56,34,42,32,34,54,56);
/*!40000 ALTER TABLE `fatrate6` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fatrate9`
--

DROP TABLE IF EXISTS `fatrate9`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fatrate9` (
  `Ingredient` varchar(50) DEFAULT NULL,
  `Or_Food_and_Cafe` int(11) DEFAULT NULL,
  `KTC` int(11) DEFAULT NULL,
  `CLF` int(11) DEFAULT NULL,
  `Foodie` int(11) DEFAULT NULL,
  `Cocofina` int(11) DEFAULT NULL,
  `Binzagr` int(11) DEFAULT NULL,
  `KLF` int(11) DEFAULT NULL,
  `CoconutCompany` int(11) DEFAULT NULL,
  `EDS` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fatrate9`
--

LOCK TABLES `fatrate9` WRITE;
/*!40000 ALTER TABLE `fatrate9` DISABLE KEYS */;
INSERT INTO `fatrate9` VALUES ('CMC(g)',800,800,800,700,800,800,800,700,700),('Guar gum(g)',300,100,0,150,0,100,200,200,100),('Xantham gum(g)',100,200,300,250,300,100,100,100,200),('Sugar(g)',100,100,0,100,0,0,100,100,0),('Polysorbate(g)',50,50,50,50,50,25,0,50,25);
/*!40000 ALTER TABLE `fatrate9` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `user_email` varchar(150) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(150) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `payment_method` enum('COD','CARD') DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (5,1,'test@example.com',18,'Organic Virgin Coconut Oil',1,'kolukaarapitiya','COD','Placed','2025-09-11 16:30:34'),(6,1,'test@example.com',18,'Organic Virgin Coconut Oil',1,'jayantha, Kuliyapitiya, Ampara, Eastern - 60000, Mobile: 0783459076','COD','Placed','2025-09-11 17:27:22'),(7,1,'nipun@gmail.com',10,'Coconut Water Drink',3,'Aloka, Kuliyapitiya, Kilinochchi, Northern - 60000, Mobile: 0783459076','CARD','Paid','2025-09-11 23:51:15'),(8,1,'nipun@gmail.com',18,'Organic Virgin Coconut Oil',2,'Aloka, Kurunegala, Kurunegala, North Western - 60000, Mobile: 0783459076','CARD','Paid','2025-09-12 03:07:36'),(9,1,'nipun@gmail.com',18,'Organic Virgin Coconut Oil',1,'darsha, makola, Ampara, Eastern - 23456, Mobile: 0783456789','COD','Placed','2025-09-12 03:27:05'),(12,1,'nipun@gmail.com',18,'Organic Virgin Coconut Oil',1,'pasan, kaluthara, Kalutara, Western - 12000, Mobile: 0715872536','CARD','Paid','2025-09-12 05:14:57'),(13,1,'nipun@gmail.com',16,'Organic Coconut Water with Soursop & Lime',2,'nimantha, kaluthara, Colombo, Western - 12000, Mobile: 0715872536','CARD','Paid','2025-09-12 09:36:01'),(14,1,'nipun@gmail.com',17,'Organic King Coconut Water',1,'pasan, makola, Anuradhapura, North Central - 23456, Mobile: 0783456789','CARD','Paid','2025-09-12 11:46:52'),(15,82,'pathum@gmail.com',12,'Coconut Powder 250g',2,'Pathum, Kuliyapitiya, Kurunegala, North Western - 60000, Mobile: 0783459076','COD','Placed','2026-03-17 03:15:52');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(150) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `product_price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (8,'Coconut Milk 150ML','images/img_68c2c240362ee2.49104489.png',300.00,'Escape to a tropical paradise with our all-natural, pure Coconut Milk, conveniently packaged in a 150ml bottle. Sourced from the finest coconuts, our rich and creamy coconut milk is the perfect plant-based addition to your pantry.\r\n\r\nKey Features\r\nPure & Natural: Made from 100% pure coconut extract, with no added preservatives, sugars, or artificial flavors.\r\n\r\nVersatile Use: The perfect size for single servings. Use it to create delicious curries, enriching soups, smoothies, desserts, or as a dairy-free creamer for your coffee or tea.\r\n\r\nA Taste of the Tropics: Experience the smooth, naturally sweet, and aromatic flavor that will transport you to a sun-drenched beach with every sip.\r\n\r\nConvenient & Compact: Our 150ml bottle is easy to store and ideal for on-the-go use, ensuring freshness and no waste.'),(9,'Pure Coconut Oil 1L','images/img_68c2c449de4676.71803794.png',750.00,'Discover the multi-purpose magic of our Pure Coconut Oil, available in a generous 1L bottle. This natural powerhouse is a must-have for every kitchen and home, offering incredible benefits for your cooking, skin, and hair.\r\n\r\nThe Pure Difference\r\nOur coconut oil is crafted from the finest coconuts, ensuring it is 100% pure, unrefined, and cold-pressed. This gentle process preserves its rich aroma, delicious flavor, and natural nutrients, including beneficial Medium-Chain Triglycerides (MCTs), with no chemicals, preservatives, or additives.\r\n\r\nCulinary Uses\r\nElevate your cooking with a healthy, natural oil that adds a subtle, nutty sweetness to any dish.\r\n\r\nHigh Smoke Point: Perfect for high-heat frying, saut├⌐ing, and baking.\r\n\r\nHealthy Alternative: A fantastic substitute for butter and other cooking oils in your favorite recipes.\r\n\r\nVersatile: Use it for everything from popping popcorn to adding richness to curries and stir-fries.\r\n\r\nBeauty & Wellness Benefits\r\nUnlock the incredible benefits of coconut oil for your daily beauty and wellness routine.\r\n\r\nDeep Moisturizer: Naturally hydrates and softens dry skin from head to toe.\r\n\r\nHair Care Hero: Condition your hair, tame frizz, and add a healthy, vibrant shine.\r\n\r\nNatural Makeup Remover: Effortlessly removes makeup, even waterproof mascara, while nourishing your skin.\r\n\r\nOil Pulling: A traditional practice for supporting oral health and hygiene'),(10,'Coconut Water Drink','images/img_68c2c5cce2d7e3.44011630.png',350.00,'Quench your thirst and revitalize your body with our Pure Coconut Water, conveniently packaged in a sleek tin. Sourced from the finest young green coconuts, this is your tropical escape in a can, offering natural hydration that\'s perfect for any occasion.\r\n\r\nA Natural Refreshment\r\nOur coconut water is minimally processed to retain its authentic, crisp, and refreshing taste. We capture the delicate flavor of fresh coconut water and seal it immediately, ensuring that every can tastes as if it was just cracked open.\r\n\r\nKey Benefits\r\nNatural Electrolytes: Packed with five essential electrolytesΓÇöpotassium, magnesium, sodium, calcium, and phosphorusΓÇöto help you rehydrate and restore balance after a workout or on a hot day.\r\n\r\nPure & Simple: Made from 100% coconut water with no added sugars, preservatives, or artificial flavors. ItΓÇÖs naturally fat-free and cholesterol-free.\r\n\r\nOn-the-Go Convenience: The durable tin can makes it easy to take with you anywhereΓÇöto the gym, on a hike, or in your lunch bagΓÇöwithout the risk of spills or breakage.\r\n\r\nVersatile: Enjoy it chilled straight from the can, mix it into your favorite smoothie, or use it as a base for refreshing cocktails and mocktails.'),(11,'Coconut Milk 450ML','images/img_68c2c6cf00a3e7.22161721.png',550.00,'Bring the rich, authentic taste of the tropics to your kitchen with our Creamy Coconut Milk, now in a convenient 450ml tin. Sourced from the finest coconuts, this versatile ingredient is the secret to creating delicious, velvety-smooth dishes.\r\n\r\nThe Perfect Culinary Companion\r\nOur coconut milk is the ideal choice for both sweet and savory recipes. Its rich, full-fat content and smooth texture make it a go-to for chefs and home cooks alike.\r\n\r\nRich & Creamy: The perfect balance of thickness and flavor, adding a luscious body to curries, soups, and sauces.\r\n\r\nNaturally Delicious: Made from pure coconut extract with no added preservatives, colors, or artificial flavors.\r\n\r\nHighly Versatile: Essential for a wide range of cuisines, from Thai curries and Indian kormas to dairy-free desserts, smoothies, and baked goods.'),(12,'Coconut Powder 250g','images/img_68c2c7da95fe36.51265495.png',475.00,'Bring the rich, authentic taste of the tropics to your kitchen with our Creamy Coconut Milk, now in a convenient 450ml tin. Sourced from the finest coconuts, this versatile ingredient is the secret to creating delicious, velvety-smooth dishes.\r\n\r\nThe Perfect Culinary Companion\r\nOur coconut milk is the ideal choice for both sweet and savory recipes. Its rich, full-fat content and smooth texture make it a go-to for chefs and home cooks alike.\r\n\r\nRich & Creamy: The perfect balance of thickness and flavor, adding a luscious body to curries, soups, and sauces.\r\n\r\nNaturally Delicious: Made from pure coconut extract with no added preservatives, colors, or artificial flavors.\r\n\r\nHighly Versatile: Essential for a wide range of cuisines, from Thai curries and Indian kormas to dairy-free desserts, smoothies, and baked goods.'),(13,'Organic Pi├▒a Colada Drink','images/img_68c2d133885e46.17131826.png',250.00,'Escape to a sun-drenched beach with WICHY COCONUT Organic Pi├▒a Colada. This 250ml tin is a guilt-free indulgence, blending our pure organic coconut with the bright, juicy flavor of real pineapple. ItΓÇÖs a luscious, creamy, and exotic treat that instantly transports you to paradise.\r\n\r\nPure & Simple Ingredients: Made with organic coconut, pineapple juice, and a touch of sweetness.\r\n\r\nEffortless Enjoyment: Pop the tin and instantly enjoy a refreshing taste of the tropicsΓÇöperfect for on-the-go moments.\r\n\r\nVersatile: Delicious on its own, it also makes a great mixer for cocktails or a flavorful base for smoothies.'),(14,'Sweetened Creamed Coconut','images/img_68c2d184bcf1e7.94644922.png',450.00,'The Secret to Lusciously Rich Dishes.\r\n\r\nElevate your cooking with the creamy magic of WICHY COCONUT Sweetened Creamed Coconut. This 480g tin is a culinary secret weapon, delivering a velvety-smooth texture and a decadent, naturally sweet flavor. It melts effortlessly into any recipe, making it a must-have for creating authentic, rich flavors.\r\n\r\nVelvety Texture: Adds a thick, creamy body to curries, soups, and sauces.\r\n\r\nNatural Sweetness: Perfect for baking, desserts, and smoothies without the need for extra sugar.\r\n\r\nPure & Wholesome: A vegan, gluten-free, and preservative-free way to enrich your meals.'),(15,'Organic Coconut Flour','images/img_68c2d1be569c74.02023089.png',375.00,'Your Healthy Baker\'s Best Friend.\r\n\r\nBake smarter, not harder, with WICHY COCONUT Organic Coconut Flour. In this 500g pouch, youΓÇÖll find a fine, gluten-free flour that is naturally high in fiber and protein, and low in carbs. ItΓÇÖs the ideal alternative for anyone on a keto, paleo, or gluten-sensitive diet, delivering a delicious, subtle coconut flavor to all your creations.\r\n\r\nNutrient-Rich: A wholesome, high-fiber flour that adds nutritional value to any recipe.\r\n\r\nVersatile for Baking: Use it for fluffy pancakes, delicious cookies, or to thicken sauces and gravies.\r\n\r\nSealed for Freshness: The resealable pouch keeps your flour fresh and ready for your next baking adventure.'),(16,'Organic Coconut Water with Soursop & Lime','images/img_68c2d1fd827477.75231160.png',425.00,'Exotic Hydration, Naturally Restored.\r\n\r\nExperience an invigorating new way to hydrate with WICHY COCONUT Organic Coconut Water with Soursop & Lime. This 250ml tin is a blend of pure coconut water, the tangy-sweet flavors of soursop, and a zesty twist of lime. ItΓÇÖs a natural electrolyte restorer thatΓÇÖs both delicious and functional.\r\n\r\nElectrolyte Powerhouse: Replenish your body with essential electrolytes after a workout or on a hot day.\r\n\r\nUnique Flavor Profile: An exotic and refreshing blend that stands out from ordinary coconut water.\r\n\r\nSimply Clean: Free from artificial colors, sweeteners, and preservatives.'),(17,'Organic King Coconut Water','images/img_68c2d237cc9a71.74280677.png',250.00,'The Gold Standard of Natural Hydration.\r\n\r\nQuench your thirst with WICHY COCONUT Organic King Coconut Water, the premium choice for pure hydration. Known as the \"King\" of coconuts, this golden variety offers a naturally sweeter and richer flavor profile. Our 250ml tin captures this exquisite taste, giving you a powerful dose of natural electrolytes straight from the source.\r\n\r\nSuperior Hydration: Naturally sweeter than regular coconut water with a higher electrolyte content.\r\n\r\nAuthentic Taste: Enjoy the pure, unadulterated flavor of freshly harvested King Coconuts.\r\n\r\nOn-the-Go Refreshment: The perfect way to cool down and rehydrate anytime, anywhere.'),(18,'Organic Virgin Coconut Oil','images/img_68c2d49fdfe991.76416778.png',455.00,'The All-in-One Natural Wonder.\r\n\r\nUnlock the incredible power of the coconut with WICHY COCONUT Organic Virgin Coconut Oil. Our 200ml jar contains 100% pure, cold-pressed oil that is a true multi-purpose product. It\'s a healthy, unrefined fat for your kitchen and a potent, natural moisturizer for your body.\r\n\r\nFor Cooking: Its high smoke point makes it ideal for healthy saut├⌐ing, frying, and baking.\r\n\r\nFor Beauty: Use it as a deep conditioner for hair, a nourishing skin moisturizer, or a natural makeup remover.\r\n\r\nPure Potency: Cold-pressed to preserve its beneficial MCTs, rich aroma, and pure flavor.');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quality_control`
--

DROP TABLE IF EXISTS `quality_control`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quality_control` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(50) NOT NULL,
  `check_type` varchar(50) NOT NULL,
  `inspector` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `qc_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `added_via_panel` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quality_control`
--

LOCK TABLES `quality_control` WRITE;
/*!40000 ALTER TABLE `quality_control` DISABLE KEYS */;
INSERT INTO `quality_control` VALUES (7,'FatRate12','Fat %','J.K.Dissanayake','Pass','Good quality','2025-08-29','2025-08-29 07:09:17',0),(8,'FatRate12','Ingredients','S.R.Kumara','Pass','High quality Ingredients','2025-08-29','2025-08-29 07:09:17',0),(9,'FatRate18','Fat %','M.P.De Silva','Pass','Correct fat percentage','2025-08-29','2025-08-29 07:09:17',0),(10,'FatRate18','Ingredients','N.T.Perera','Pass','Quality checked ingredients','2025-08-29','2025-08-29 07:09:17',0),(11,'FatRate22','Fat %','L.D.Kulasekara','Pass','Fat content verified','2025-08-29','2025-08-29 07:09:17',0),(12,'FatRate22','Ingredients','H.C.Rajapaksa','Pass','High grade ingredients','2025-08-29','2025-08-29 07:09:17',0),(13,'FatRate30','Fat %','K.W.Senanayake','Pass','Fat level correct','2025-08-29','2025-08-29 07:09:17',0),(14,'FatRate30','Ingredients','D.S.Premaratne','Pass','Imported quality ingredients','2025-08-29','2025-08-29 07:09:17',0),(21,'FatRate10','Fat %',' A.M.Balaraj   ','Pass',' Correct Percentage ','2025-09-01','2025-08-29 12:29:51',0),(33,'FatRate10','Ingredients','P.D.Sriyananda ','Pass','Each Particles of Ingredients maintains the best quality','2025-08-29','2025-08-29 14:05:47',0),(50,'FatRate6','Fat %',' R.M.Sahabandhu ','Pass','Accurate Percentage','2025-08-31','2025-08-30 23:09:33',0),(51,'FatRate6','Ingredients',' I.W.Weththamuni ','Pass',' High quality Ingredients  ','2025-08-31','2025-08-30 23:09:33',0),(52,'FatRate9','Fat %',' T.M.Sankalpa','Pass','Greate Percentage Level','2025-08-31','2025-08-30 23:11:27',0),(53,'FatRate9','Ingredients',' S.M.Bandaranayake','Pass','All ingredients has included in correct weight','2025-08-31','2025-08-30 23:11:27',0),(54,'FatRate6','Fat %','dasun','Pass','good','2025-09-12','2025-09-12 09:43:32',0),(56,'FatRate6','Fat %','dasun','Pass','good','2025-09-13','2025-09-12 11:50:03',0);
/*!40000 ALTER TABLE `quality_control` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `role` enum('admin','customer','employee') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_username` (`username`),
  KEY `idx_email` (`email`),
  KEY `idx_role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'nipun','nipun@gmail.com','$2y$10$erPq.KxyDz6Aa2TqLPHCgupsEzAcYi6/9AC7P5WA0fgYFRXPQ31pW','public/images/profiles/profile_68c3173c2fc262.28912858.jpg','customer','2025-09-11 18:38:52','2025-09-11 18:38:52'),(3,'Nimantha','nimantha@gmail.com','$2y$10$ta85ZfibqU0nAz3SvBr8EeKQ/OaZyK1jdY7Q5PXgTcTbQwak5WkZm','public/images/profiles/profile_68c31dc9308604.15474519.jpg','admin','2025-09-11 19:06:49','2026-10-13 20:12:38'),(4,'Dasun','dasun@gmail.com','$2y$10$1MTWjjOhKHgOWcq.qivdO.db6rzFGFvwKL.2gAwfuFbYHpvKE30m2','public/images/profiles/profile_68c31e58309567.56134543.jpg','employee','2025-09-11 19:09:12','2025-09-11 19:09:12'),(5,'Pasan','pasan@gmail.com','$2y$10$rLEEkIpnd2rh2U0GHQR0XeMKbyHhaqjJ1XL2zEGGJE/RvXGWvIW/K','public/images/profiles/profile_68c31e8c8868f4.97784954.jpg','employee','2025-09-11 19:10:04','2025-09-11 19:10:04'),(6,'Adheesha','adheesha@gmail.com','$2y$10$mNY90oheYASvWssdgM384us77dOue3vR9BMy2aZeDSklV49M.Xjti','public/images/profiles/profile_68c326966ec2f8.28067816.jpg','customer','2025-09-11 19:44:22','2025-09-11 19:44:22'),(7,'Ishan','ishan@gmail.com','$2y$10$5hiNNsNt5S0hhjQkAXMX6uYC2grBdPNr9nnSZYz5/SiLV1qOhC6bK','public/images/profiles/profile_68c32787d107c3.03157603.jpg','customer','2025-09-11 19:48:23','2025-09-11 19:48:23'),(8,'Nethmina','bathmina@gmail.com','$2y$10$vK0i4YSYBcd/PPdZ72CSlO4R0vh.HiBgFvrfudwYKnJjPk/Z/VWuS','public/images/profiles/profile_68c399ec036263.93247507.jpg','customer','2025-09-12 03:56:28','2025-09-12 03:56:28'),(78,'Hiranya','hiranya@gmail.com','$2y$10$Lv6VQFTS1BIIbFH02sRwoO6gTKQ02N9R74hRiFNaDtSHRE.0TmzNO','public/images/profiles/profile_68c3e330840a59.37187792.jpg','customer','2025-09-12 09:09:04','2025-09-12 09:09:04'),(80,'Lodeesha','lodeesha@gmail.com','$2y$10$ZRUZ5iO16OYn16lBHMOucepi1oBKSBgpR3VEBouExwwGGxjh2cWES','public/images/profiles/profile_68c413c9c74e69.04327008.jpg','customer','2025-09-12 12:36:25','2025-09-12 12:36:25'),(81,'anjana','anjanam@gmail.com','$2y$10$HBLLPJLeblWhILSV0QVZYOFWNQnqs43XmILnD4BkSA5g0X/6K7ZDG','public/images/profiles/profile_68c414206332d1.60953720.jpg','customer','2025-09-12 12:37:52','2025-09-12 12:37:52'),(82,'Pathum','pathum@gmail.com','$2y$10$LqOfvq4nm/7pMXtS0F.J1O4l/EVOayWqPcF.gP.TSwMnG6B0wUgda','public/images/profiles/profile_69b8c71b7180f4.91016973.jpg','customer','2026-03-17 03:14:35','2026-03-17 03:14:35'),(83,'pathi','pathi@gmail.com','$2y$10$j/oF8MQ2g4IuHoNZeaAc6.6zDt3alzVfooylW1R3/T69gmOVBN5Mi',NULL,'customer','2026-10-13 19:21:34','2026-10-13 19:21:34'),(84,'elama','elama@gmail.com','$2y$10$UGYcSvgKuAEhI.1NAkCo2u71seLUIB87ih0xhZmgqDcjRyQimilj6',NULL,'customer','2026-10-13 19:22:36','2026-10-13 19:22:36');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-14  1:42:55
