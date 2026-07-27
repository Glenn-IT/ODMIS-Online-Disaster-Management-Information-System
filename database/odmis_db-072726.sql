-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 27, 2026 at 03:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `odmis_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(300) NOT NULL,
  `body` longtext NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `published_by` int(10) UNSIGNED DEFAULT NULL,
  `published_at` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `body`, `category`, `published_by`, `published_at`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Mandatory Evacuation Order — Lubo Hillside Zone', 'The Municipal Disaster Risk Reduction and Management Office (MDRRMO) hereby issues a MANDATORY EVACUATION ORDER for all residents living within the identified landslide risk zone in the hillside area of Barangay Lubo. Affected families must proceed to the Lubo Barangay Hall Emergency Wing immediately. Failure to comply may endanger lives. MDRRMO personnel will assist in evacuation.', 'Evacuation Order', 1, '2025-01-22', 1, '2026-06-26 02:24:08', '2026-06-26 02:24:08'),
(2, 'Tropical Storm Domeng Advisory — Preparedness Reminder', 'Tropical Storm Domeng is expected to bring heavy rains and strong winds to Santo Niño (Faire) and surrounding municipalities from January 15–17, 2025. Residents are advised to: (1) Store adequate food and water supply; (2) Secure loose objects; (3) Stay informed via PAGASA updates; (4) Know your evacuation routes. The MDRRMO is on full alert. For emergencies, call the MDRRMO Hotline: 0917-XXX-XXXX.', 'Weather Advisory', 1, '2025-01-14', 1, '2026-06-26 02:24:08', '2026-06-26 02:24:08'),
(3, 'Community DRRM Training — Barangay Level (February 2025)', 'The Municipal DRRMO, in partnership with the Philippine Red Cross, will conduct Barangay-Level DRRM Training sessions in February 2025. Topics include: Basic Life Support (BLS), Search and Rescue, Community Early Warning Systems, and Evacuation Drills. Schedule: Minanga — Feb 3, Lubo — Feb 5, Sto. Niño — Feb 7, Poblacion — Feb 10. All Barangay Emergency Response Teams (BERTs) are required to attend.', 'Training & Capacity Building', 1, '2025-01-18', 1, '2026-06-26 02:24:08', '2026-06-26 02:24:08'),
(4, 'Relief Distribution — Sto. Niño Flood Victims', 'Food packs and non-food items will be distributed to flood-affected families in Barangay Sto. Niño on January 10, 2025, from 8:00 AM to 5:00 PM at the Sto. Niño Multi-Purpose Hall. Affected families must bring their Barangay Identification and Disaster Assistance Family Access Cards (DAFAC) for verification. Queries may be directed to the MDRRMO office.', 'Relief Distribution', 1, '2025-01-09', 1, '2026-06-26 02:24:08', '2026-06-26 02:24:08'),
(5, 'Post-Typhoon Carina Damage Assessment Results', 'The MDRRMO has completed the damage and needs assessment following Typhoon Carina (October 20, 2024). Summary: Total affected families — 1,247; Total affected individuals — 4,988; Houses totally damaged — 38; Houses partially damaged — 212; Agricultural damage (rice/corn) — 145 hectares; Infrastructure damage — Php 3.2 million estimated. LGU is coordinating with national government agencies for additional assistance.', 'Damage Assessment Report', 1, '2024-10-28', 1, '2026-06-26 02:24:08', '2026-06-26 02:24:08'),
(6, 'Geohazard Awareness Month — December 2024', 'In observance of Geohazard Awareness Month, the MDRRMO encourages all residents to familiarize themselves with the municipal geohazard maps. Know if your area is in a flood-prone, landslide-prone, or storm surge-prone zone. Barangay-level community mapping sessions are scheduled throughout December. Contact your barangay council for the schedule in your area. Stay safe and be prepared!', 'Awareness Campaign', 1, '2024-12-01', 1, '2026-06-26 02:24:08', '2026-06-26 02:24:08');

-- --------------------------------------------------------

--
-- Table structure for table `disaster_alerts`
--

CREATE TABLE `disaster_alerts` (
  `id` int(10) UNSIGNED NOT NULL,
  `alert_type` enum('Flood','Typhoon','Earthquake','Fire','Landslide') NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `affected_areas` text DEFAULT NULL,
  `severity` enum('Low','Moderate','High','Critical') NOT NULL,
  `status` enum('Active','Resolved') NOT NULL DEFAULT 'Active',
  `issued_by` int(10) UNSIGNED DEFAULT NULL,
  `issued_at` datetime NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `disaster_alerts`
--

INSERT INTO `disaster_alerts` (`id`, `alert_type`, `title`, `description`, `affected_areas`, `severity`, `status`, `issued_by`, `issued_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'Flood', 'FLOOD ALERT — Sto. Niño Low-Lying Areas', 'Continuous rainfall has raised river levels to near-critical. Residents in low-lying areas of Sto. Niño are advised to move to higher ground. Monitor water levels closely.', 'Sto. Niño', 'High', 'Active', 1, '2025-01-08 05:45:00', NULL, '2026-06-26 02:24:08', '2026-06-26 02:24:08'),
(2, 'Typhoon', 'TROPICAL STORM WARNING — Domeng', 'Tropical Storm Domeng is approaching. PAGASA has raised Tropical Cyclone Wind Signal No. 1 over Santo Niño (Faire). Expect strong winds and heavy to intense rainfall.', 'Minanga, Lubo, Sto. Niño, Poblacion', 'Moderate', 'Active', 1, '2025-01-14 06:00:00', NULL, '2026-06-26 02:24:08', '2026-06-26 02:24:08'),
(3, 'Landslide', 'CRITICAL LANDSLIDE RISK — Lubo Hillside', 'Geohazard assessment reveals critical landslide risk on the Lubo hillside. Mandatory evacuation order issued. All residents in the risk zone must evacuate immediately.', 'Lubo', 'Critical', 'Active', 1, '2025-01-22 07:00:00', NULL, '2026-06-26 02:24:08', '2026-06-26 02:24:08'),
(4, 'Earthquake', 'AFTERSHOCK ADVISORY — M5.8 Earthquake Zone', 'Aftershocks from the September 2024 M5.8 earthquake continue to be recorded. Residents in Poblacion and nearby barangays are advised to inspect structures for cracks and avoid damaged buildings.', 'Poblacion', 'Low', 'Active', 1, '2025-01-20 09:00:00', NULL, '2026-06-26 02:24:08', '2026-06-26 02:24:08'),
(5, 'Flood', 'FLOOD WATCH — Minanga River Area', 'River levels in Minanga are being monitored closely due to persistent rainfall in the upstream catchment area. Residents near the river are advised to be on standby for possible evacuation.', 'Minanga', 'Moderate', 'Active', 1, '2025-01-15 10:00:00', NULL, '2026-06-26 02:24:08', '2026-06-26 02:24:08'),
(6, 'Typhoon', 'TYPHOON CARINA — All Clear Issued', 'Typhoon Carina has moved out of the Philippine Area of Responsibility. All clear is issued. Residents may return to their homes after assessment by barangay officials.', 'Minanga, Lubo, Sto. Niño, Poblacion', 'Low', 'Resolved', 1, '2024-10-22 14:00:00', NULL, '2026-06-26 02:24:08', '2026-06-26 02:24:08'),
(7, 'Typhoon', 'Test Alert', 'Test desc', 'Test Area', 'Low', 'Resolved', 1, '2026-06-26 03:35:04', '2026-12-31 00:00:00', '2026-06-26 03:35:04', '2026-06-26 03:35:04');

-- --------------------------------------------------------

--
-- Table structure for table `evacuation_centers`
--

CREATE TABLE `evacuation_centers` (
  `id` int(10) UNSIGNED NOT NULL,
  `center_code` varchar(20) NOT NULL,
  `center_name` varchar(200) NOT NULL,
  `location` varchar(300) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `capacity` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `occupied_slots` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `contact_person` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `status` enum('Open','Closed') NOT NULL DEFAULT 'Open',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incidents`
--

CREATE TABLE `incidents` (
  `id` int(10) UNSIGNED NOT NULL,
  `incident_code` varchar(20) NOT NULL,
  `disaster_type` enum('Flood','Typhoon','Earthquake','Fire','Landslide') NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(200) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `municipality` varchar(100) NOT NULL DEFAULT 'Santo Niño (Faire)',
  `incident_date` date NOT NULL,
  `incident_time` time DEFAULT NULL,
  `severity` enum('Low','Moderate','High','Critical') NOT NULL,
  `status` enum('Active','Resolved') NOT NULL DEFAULT 'Active',
  `reported_by` varchar(150) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `incidents`
--

INSERT INTO `incidents` (`id`, `incident_code`, `disaster_type`, `title`, `description`, `location`, `barangay`, `municipality`, `incident_date`, `incident_time`, `severity`, `status`, `reported_by`, `created_at`, `updated_at`) VALUES
(16, 'INC-001', 'Flood', 'sample', 'sample', 'sample', 'Dungao', 'Sto. Niño, Cagayan', '2026-07-21', '17:13:00', 'Low', 'Resolved', '', '2026-07-21 17:13:07', '2026-07-21 17:13:12'),
(18, 'INC-002', 'Flood', 'Overflow Bridge', 'samplea asdklas dljasda sdjlkasd', 'Palusao Bridge', 'Palusao', 'Sto. Niño, Cagayan', '2026-07-23', '00:27:00', 'High', 'Active', '', '2026-07-23 00:27:13', '2026-07-23 00:27:48');

-- --------------------------------------------------------

--
-- Table structure for table `relief_operations`
--

CREATE TABLE `relief_operations` (
  `id` int(10) UNSIGNED NOT NULL,
  `batch_number` varchar(20) NOT NULL,
  `operation_date` date NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `relief_type` varchar(100) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `unit` varchar(50) DEFAULT NULL,
  `status` enum('Pending','In Progress','Completed') NOT NULL DEFAULT 'Pending',
  `distributed_by` varchar(150) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `relief_operations`
--

INSERT INTO `relief_operations` (`id`, `batch_number`, `operation_date`, `barangay`, `relief_type`, `quantity`, `unit`, `status`, `distributed_by`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'BATCH-001', '2024-10-22', 'Poblacion', 'Food Pack', 250, 'packs', 'Completed', 'MDRRMO Team Alpha', 'Post-Typhoon Carina relief. Food packs contain 3-day supply of canned goods and rice.', '2026-06-26 02:24:08', '2026-06-26 02:24:08'),
(2, 'BATCH-002', '2024-10-23', 'Minanga', 'Food Pack', 150, 'packs', 'Completed', 'MDRRMO Team Beta', 'Post-Typhoon Carina relief distribution in Minanga.', '2026-06-26 02:24:08', '2026-06-26 02:24:08'),
(3, 'BATCH-003', '2024-10-24', 'Lubo', 'Non-Food Items (NFI)', 80, 'family kits', 'Completed', 'DSWD Santo Niño (Faire)', 'NFI kits include sleeping mats, blankets, and hygiene kits.', '2026-06-26 02:24:08', '2026-06-26 02:24:08'),
(4, 'BATCH-004', '2024-11-14', 'Minanga', 'Food Pack', 90, 'packs', 'Completed', 'MDRRMO / BFP', 'Relief for flood victims in Minanga riverside area (INC-001).', '2026-06-26 02:24:08', '2026-06-26 02:24:08'),
(5, 'BATCH-005', '2024-12-03', 'Lubo', 'Shelter Materials', 15, 'sets', 'Completed', 'MDRRMO / LGU', 'Roofing materials (GI sheets, lumber) for fire victims in Purok 3, Lubo (INC-004).', '2026-06-26 02:24:08', '2026-06-26 02:24:08'),
(6, 'BATCH-006', '2025-01-09', 'Sto. Niño', 'Food Pack', 60, 'packs', 'In Progress', 'MDRRMO Team Alpha', 'Ongoing relief for flood-affected families in Sto. Niño (INC-006).', '2026-06-26 02:24:08', '2026-06-26 02:24:08'),
(7, 'BATCH-007', '2025-01-22', 'Lubo', 'Food Pack', 24, 'packs', 'Pending', 'MDRRMO', 'Prepared for pre-emptively evacuated families from landslide risk zone (INC-010). Awaiting deployment.', '2026-06-26 02:24:08', '2026-06-26 02:24:08'),
(8, 'BATCH-008', '2025-01-23', 'Minanga', 'Non-Food Items (NFI)', 40, 'family kits', 'Pending', 'DSWD / MDRRMO', 'Hygiene and NFI kits for tropical storm Domeng-affected families in Minanga and Lubo.', '2026-06-26 02:24:08', '2026-06-26 02:24:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `full_name` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `security_question` varchar(255) DEFAULT NULL,
  `security_answer_hash` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `full_name`, `contact_number`, `date_of_birth`, `address`, `status`, `security_question`, `security_answer_hash`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@odmis.gov.ph', '$2y$10$5i1gI8ZgeM/X/NpdzcyPWeSWVqAg.B3IjaCPZLH4hbErRRPozYL5W', 'admin', 'Administrator', '09171234567', '1985-03-15', 'Municipal Hall, Poblacion, Santo Niño (Faire), Cagayan, Region II', 'active', 'What is your mother&#039;s maiden name?', '$2y$10$ltI4LeIXHWG2miSrGOEs5uLxDD/MOZ1AAlpwqw5KxX2vUAtV83ZHi', '2024-01-01 08:00:00', '2026-07-12 02:30:41'),
(12, 'glenn', 'glen@gmail.com', '$2y$10$dDYpnl9Paj8V.exkb8q5IeUe7ceRRy4As25NIk3m.93OKAgCKPZ7i', 'user', 'Glenn Pagurayan', '09545646545', '2000-08-02', 'Sample', 'active', 'What is your favorite book?', '$2y$10$cKMTSo1zJD9SQTPAKTBqbe02UYUSiKkNw.0XFAI8IeFIvQc5FP2aS', '2026-07-07 20:54:49', '2026-07-09 11:14:22'),
(13, 'ravier', 'ravier@gmail.com', '$2y$10$SfSxyr7cXM3Dc0pw3dGVIuCq/C8WqP5.yWWWabweRLV5dmHRbDsFS', 'user', 'Ravier All', '09557997409', '2000-08-23', 'Sample', 'active', 'What is your mother&#039;s maiden name?', '$2y$10$yA9HIPWqABSq9X0Z8BizHOCh46fKFL/4W0DHwp6.NSlbAbX8GXlG.', '2026-07-27 20:49:49', '2026-07-27 20:51:53');

-- --------------------------------------------------------

--
-- Table structure for table `user_reports`
--

CREATE TABLE `user_reports` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `incident_type` enum('Flood','Typhoon','Earthquake','Fire','Landslide','Other') NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `location` varchar(300) NOT NULL,
  `barangay` varchar(100) NOT NULL DEFAULT '',
  `municipality` varchar(100) NOT NULL DEFAULT 'Sto. Ni├▒o, Cagayan',
  `report_date` date NOT NULL,
  `incident_time` time DEFAULT NULL,
  `photo_path` varchar(300) DEFAULT NULL,
  `status` enum('Pending','Reviewed','Resolved') NOT NULL DEFAULT 'Pending',
  `reviewed_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_reports`
--

INSERT INTO `user_reports` (`id`, `user_id`, `incident_type`, `title`, `description`, `location`, `barangay`, `municipality`, `report_date`, `incident_time`, `photo_path`, `status`, `reviewed_by`, `created_at`, `updated_at`) VALUES
(2, 12, 'Flood', 'sample', 'asdkjlj asdkjaslkd askdjkqjwe', 'near bridge', 'Abariongan Ruar', 'Sto. Niño, Cagayan', '2026-07-21', '17:15:00', 'uploads/reports/report_6a5f38c9c18899.98599571.png', 'Resolved', 1, '2026-07-21 17:15:53', '2026-07-21 17:51:30'),
(5, 12, 'Earthquake', 'lakas ng olindol', 'sdmkwd asd asd asdas asd', 'werf', 'Abariongan Uneg', 'Sto. Niño, Cagayan', '2026-07-21', '17:49:00', 'uploads/reports/report_6a5f40d1d77501.32875695.png', 'Reviewed', 1, '2026-07-21 17:50:09', '2026-07-21 17:52:00'),
(6, 12, 'Earthquake', 'Lindol po', 'dsadas asdasda ssad asdas asd asd', 'Gymnasium', 'Centro Sur', 'Sto. Niño, Cagayan', '2026-07-22', '00:30:00', 'uploads/reports/report_6a60f023ae8414.99916392.png', 'Reviewed', 1, '2026-07-23 00:30:27', '2026-07-23 00:31:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ann_active` (`is_active`),
  ADD KEY `idx_ann_published` (`published_at`),
  ADD KEY `fk_ann_published_by` (`published_by`);

--
-- Indexes for table `disaster_alerts`
--
ALTER TABLE `disaster_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_alerts_status` (`status`),
  ADD KEY `idx_alerts_type` (`alert_type`),
  ADD KEY `idx_alerts_severity` (`severity`),
  ADD KEY `fk_alerts_issued_by` (`issued_by`);

--
-- Indexes for table `evacuation_centers`
--
ALTER TABLE `evacuation_centers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_evac_code` (`center_code`),
  ADD KEY `idx_evac_barangay` (`barangay`),
  ADD KEY `idx_evac_status` (`status`);

--
-- Indexes for table `incidents`
--
ALTER TABLE `incidents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_incidents_code` (`incident_code`),
  ADD KEY `idx_incidents_type` (`disaster_type`),
  ADD KEY `idx_incidents_barangay` (`barangay`),
  ADD KEY `idx_incidents_status` (`status`),
  ADD KEY `idx_incidents_date` (`incident_date`);

--
-- Indexes for table `relief_operations`
--
ALTER TABLE `relief_operations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_relief_batch` (`batch_number`),
  ADD KEY `idx_relief_barangay` (`barangay`),
  ADD KEY `idx_relief_status` (`status`),
  ADD KEY `idx_relief_date` (`operation_date`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_username` (`username`),
  ADD UNIQUE KEY `uq_users_email` (`email`);

--
-- Indexes for table `user_reports`
--
ALTER TABLE `user_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ureports_user` (`user_id`),
  ADD KEY `idx_ureports_status` (`status`),
  ADD KEY `idx_ureports_date` (`report_date`),
  ADD KEY `fk_ureports_reviewed_by` (`reviewed_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `disaster_alerts`
--
ALTER TABLE `disaster_alerts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `evacuation_centers`
--
ALTER TABLE `evacuation_centers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `incidents`
--
ALTER TABLE `incidents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `relief_operations`
--
ALTER TABLE `relief_operations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_reports`
--
ALTER TABLE `user_reports`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `fk_ann_published_by` FOREIGN KEY (`published_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `disaster_alerts`
--
ALTER TABLE `disaster_alerts`
  ADD CONSTRAINT `fk_alerts_issued_by` FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_reports`
--
ALTER TABLE `user_reports`
  ADD CONSTRAINT `fk_ureports_reviewed_by` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_ureports_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
