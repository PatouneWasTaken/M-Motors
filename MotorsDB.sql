-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: fp469140-001.eu.clouddb.ovh.net:35963
-- Generation Time: Jun 19, 2026 at 07:23 PM
-- Server version: 8.4.8-8
-- PHP Version: 8.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `MotorsDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int NOT NULL,
  `user_id` bigint NOT NULL,
  `vehicle_id` bigint NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `document` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('pending','accepted','refused') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `is_admin` tinyint(1) DEFAULT NULL,
  `entry_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint NOT NULL,
  `brand` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `model` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `price` int NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `entry_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `entry_by` bigint NOT NULL,
  `type` enum('sale','rent') COLLATE utf8mb4_general_ci NOT NULL,
  `kms` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `brand`, `model`, `price`, `description`, `photo`, `entry_date`, `entry_by`, `type`, `kms`) VALUES
(13, 'Lexus', 'GS', 24530, 'Caractéristiques principales :\r\n\r\nAnnée : 2019\r\nMotorisation : Hybride Essence / Électrique 2.5L\r\nPuissance : 223 ch\r\nBoîte de vitesses : Automatique\r\nCouleur extérieure : Gris Titane Métallisé\r\nIntérieur : Cuir noir premium\r\nNombre de places : 5\r\nCrit\'Air : 1\r\n\r\nÉquipements et options :\r\n\r\nSellerie cuir chauffante et ventilée\r\nSièges avant à réglages électriques avec mémoire\r\nÉcran multimédia avec navigation GPS\r\nCaméra de recul et radars avant/arrière\r\nSystème audio premium\r\nRégulateur de vitesse adaptatif\r\nAide au maintien dans la voie\r\nDétection des angles morts\r\nToit ouvrant électrique\r\nJantes alliage 18 pouces\r\n\r\nÉtat du véhicule :\r\nVéhicule entretenu avec soin, carnet d\'entretien à jour et contrôle technique récent. Aucun frais à prévoir. Carrosserie et intérieur en excellent état.\r\n\r\nAnnonce fictive créée à titre d\'exemple pour un site de vente et de location de véhicules d\'occasion.', 'veh_6a35672f6ba5c8.35063176.jpg', '2026-06-19 15:58:39', 3, 'sale', 180000),
(14, 'Volkswagen', 'Beetle', 180, 'Caractéristiques principales :\r\n\r\nAnnée : 2018\r\nMotorisation : Essence 1.4 TSI\r\nPuissance : 150 ch\r\nBoîte de vitesses : Automatique DSG\r\nCouleur extérieure : Blanc Nacré\r\nIntérieur : Sellerie tissu et cuir noir\r\nNombre de places : 4\r\nÉnergie : Essence\r\n\r\nÉquipements et options :\r\n\r\nToit cabriolet électrique\r\nClimatisation automatique bi-zone\r\nÉcran tactile multimédia avec Bluetooth\r\nCompatibilité Apple CarPlay et Android Auto\r\nRégulateur et limiteur de vitesse\r\nCapteurs de stationnement arrière\r\nJantes alliage 17 pouces\r\nVolant multifonction gainé de cuir\r\nAllumage automatique des feux\r\nRétroviseurs électriques chauffants\r\n\r\nÉtat du véhicule :\r\nVéhicule soigneusement entretenu, présentant un très bel état général. Intérieur propre et soigné, mécanique fiable et suivi d\'entretien rigoureux. Contrôle technique conforme et aucun frais immédiat à prévoir.\r\n\r\nAnnonce fictive créée à titre d\'exemple pour un site de vente et de location de véhicules d\'occasion.', 'veh_6a3567f8c49d14.27037190.jpg', '2026-06-19 16:02:00', 3, 'rent', 260000),
(15, 'Audi', 'RS3', 32200, 'Caractéristiques principales :\r\n\r\nAnnée : 2022\r\nMotorisation : Essence 2.5 TFSI 5 cylindres\r\nPuissance : 400 ch\r\nBoîte de vitesses : Automatique S tronic 7 rapports\r\nTransmission : Quattro intégrale\r\nCouleur extérieure : Noir Mythic Métallisé\r\nIntérieur : Sellerie cuir Nappa avec surpiqûres contrastées\r\nNombre de places : 5\r\nÉnergie : Essence\r\n\r\nÉquipements et options :\r\n\r\nCockpit virtuel Audi\r\nSystème multimédia avec navigation GPS\r\nCompatibilité Apple CarPlay et Android Auto\r\nCaméra de recul et aides au stationnement\r\nRégulateur de vitesse adaptatif\r\nÉclairage Matrix LED\r\nSièges sport RS chauffants\r\nVolant sport multifonction à méplat\r\nModes de conduite Audi Drive Select\r\nSystème audio premium\r\nJantes alliage RS 19 pouces\r\nÉchappement sport à valves\r\n\r\nÉtat du véhicule :\r\nVéhicule entretenu avec le plus grand soin et bénéficiant d\'un suivi rigoureux. Présentation irréprochable aussi bien à l\'extérieur qu\'à l\'intérieur. Aucun frais à prévoir, contrôle technique conforme et historique d\'entretien disponible.\r\n\r\nAnnonce fictive créée à titre d\'exemple pour un site de vente et de location de véhicules d\'occasion.', 'veh_6a3568c361d820.82551341.jpg', '2026-06-19 16:05:23', 3, 'sale', 120500),
(16, 'Audi', 'sedan', 140, 'Caractéristiques principales :\r\n\r\nAnnée : 2021\r\nMotorisation : 2.0 TDI\r\nPuissance : 204 ch\r\nBoîte de vitesses : Automatique S tronic\r\nTransmission : Traction avant\r\nCouleur extérieure : Gris Daytona Métallisé\r\nIntérieur : Sellerie cuir noir\r\nNombre de places : 5\r\nÉnergie : Diesel\r\n\r\nÉquipements et options :\r\n\r\nAudi Virtual Cockpit\r\nSystème de navigation GPS avec écran tactile\r\nApple CarPlay et Android Auto\r\nCaméra de recul\r\nRadars de stationnement avant et arrière\r\nClimatisation automatique 4 zones\r\nRégulateur de vitesse adaptatif\r\nAide au maintien dans la voie\r\nÉclairage LED haute performance\r\nSièges avant chauffants à réglages électriques\r\nVolant multifonction gainé de cuir\r\nJantes alliage 18 pouces\r\n\r\nÉtat du véhicule :\r\nVéhicule soigneusement entretenu avec historique d\'entretien disponible. Intérieur et carrosserie en excellent état, offrant une présentation soignée et un confort optimal. Aucun frais à prévoir et contrôle technique conforme.\r\n\r\nAnnonce fictive créée à titre d\'exemple pour un site de vente et de location de véhicules d\'occasion.', 'veh_6a3569561e2b30.39728743.jpg', '2026-06-19 16:07:50', 3, 'rent', 60400),
(17, 'Volkswagen', 'GTI', 60, 'Caractéristiques principales :\r\n\r\nAnnée : 1989\r\nMotorisation : Essence 1.8L\r\nPuissance : 112 ch\r\nBoîte de vitesses : Manuelle 5 rapports\r\nCouleur extérieure : Rouge Tornado\r\nIntérieur : Sellerie tissu à motif d\'origine\r\nNombre de places : 5\r\nÉnergie : Essence\r\n\r\nÉquipements et options :\r\n\r\nJantes alliage d\'époque\r\nVolant sport GTI\r\nToit ouvrant\r\nVitres électriques avant\r\nRétroviseurs réglables électriquement\r\nBanquette arrière rabattable\r\nInstrumentation complète avec compte-tours\r\nAutoradio au style rétro\r\nFeux antibrouillard avant\r\nSuspension sport\r\n\r\nÉtat du véhicule :\r\nVéhicule préservé avec soin et respectueux de son esprit d\'origine. Présentation très soignée, intérieur en excellent état et carrosserie bien conservée. Mécanique entretenue régulièrement avec factures de suivi disponibles. Prête à prendre la route ou à rejoindre une collection.\r\n\r\nAnnonce fictive créée à titre d\'exemple pour un site de vente et de location de véhicules d\'occasion.', 'veh_6a356e70224e29.23057693.jpg', '2026-06-19 16:11:01', 3, 'rent', 240000),
(18, 'Audi', 'sedan', 12600, 'Caractéristiques principales :\r\n\r\nAnnée : 2022\r\nMotorisation : 2.0 TFSI\r\nPuissance : 190 ch\r\nBoîte de vitesses : Automatique S tronic 7 rapports\r\nTransmission : Traction avant\r\nCouleur extérieure : Bleu Navarre Métallisé\r\nIntérieur : Sellerie cuir et alcantara noir\r\nNombre de places : 5\r\nÉnergie : Essence\r\n\r\nÉquipements et options :\r\n\r\nAudi Virtual Cockpit\r\nÉcran tactile multimédia haute résolution\r\nNavigation GPS intégrée\r\nApple CarPlay et Android Auto\r\nCaméra de recul\r\nAide au stationnement avant et arrière\r\nRégulateur de vitesse adaptatif\r\nClimatisation automatique tri-zone\r\nÉclairage LED\r\nSièges avant chauffants\r\nVolant multifonction gainé de cuir\r\nJantes alliage 18 pouces\r\nDémarrage sans clé\r\n\r\nÉtat du véhicule :\r\nVéhicule soigneusement entretenu et présenté dans un excellent état général. Habitacle propre et soigné, carrosserie en très bon état et entretien suivi selon les préconisations constructeur. Aucun frais à prévoir.\r\n\r\nAnnonce fictive créée à titre d\'exemple pour un site de vente et de location de véhicules d\'occasion.', 'veh_6a356a914ccd36.74003977.jpg', '2026-06-19 16:13:05', 3, 'sale', 180400),
(19, 'BMW', 'E30', 22600, 'Caractéristiques principales :\r\n\r\nAnnée : 1990\r\nMotorisation : Essence 2.5L 6 cylindres\r\nPuissance : 170 ch\r\nBoîte de vitesses : Manuelle 5 rapports\r\nTransmission : Propulsion\r\nCouleur extérieure : Noir Brillant\r\nIntérieur : Sellerie tissu sport d\'origine\r\nNombre de places : 5\r\nÉnergie : Essence\r\n\r\nÉquipements et options :\r\n\r\nJantes alliage BMW d\'époque\r\nDirection assistée\r\nToit ouvrant électrique\r\nVitres électriques avant\r\nVolant sport BMW\r\nOrdinateur de bord\r\nAntibrouillards avant\r\nBanquette arrière rabattable\r\nSuspension sport\r\nAutoradio au style rétro\r\n\r\nÉtat du véhicule :\r\nVéhicule entretenu avec soin et conservé dans le respect de son authenticité. Carrosserie en très bel état, intérieur propre et préservé, mécanique suivie régulièrement. Dossier d\'entretien disponible et nombreux éléments d\'origine conservés.\r\n\r\nAnnonce fictive créée à titre d\'exemple pour un site de vente et de location de véhicules d\'occasion.', 'veh_6a356df9542f35.15487524.jpg', '2026-06-19 16:27:37', 3, 'sale', 195000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entry_by` (`entry_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `fk_application_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_application_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`entry_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
