-- ODMIS Sample Data Seed
-- 001_sample_data.sql
-- Ported from assets/js/mock-data.js (DATA_VERSION = 2)
-- Passwords and security answers are bcrypt-hashed (PHP PASSWORD_BCRYPT, lowercased before hashing)

SET FOREIGN_KEY_CHECKS = 0;

-- ─────────────────────────────────────────
-- USERS (5 accounts)
-- Passwords: admin=admin123, users=user123
-- Security answers lowercased before hashing
-- ─────────────────────────────────────────
INSERT INTO `users`
    (`id`, `username`, `email`, `password_hash`, `role`, `full_name`, `contact_number`, `date_of_birth`, `address`, `status`, `security_question`, `security_answer_hash`, `created_at`)
VALUES
(1, 'admin', 'admin@odmis.gov.ph',
    '$2y$10$zAxjYS30859.nNCF2WsIlOyZ6pboR.bE.1sYum3PC.d2quthccsLe',
    'admin', 'Administrator', '09171234567', '1985-03-15',
    'Municipal Hall, Poblacion, Santo Niño (Faire), Cagayan, Region II',
    'active', 'What is your mother\'s maiden name?',
    '$2y$10$KebIGxUafW4W5VVxDm1fTO0RWQ2Aau06yo8JRfC/b1oj1DCBn52ha',
    '2024-01-01 08:00:00'),

(2, 'juan', 'juan.delacruz@email.com',
    '$2y$10$/n23x5f6.tr5U/hhWA6uDutdJ7rsGjcS5rxJTD9tuV3n3SN2h8omC',
    'user', 'Juan Dela Cruz', '09181234568', '1990-06-22',
    'Blk 5 Lot 3, Minanga, Santo Niño (Faire), Cagayan',
    'active', 'What is the name of your first pet?',
    '$2y$10$1DO5d4.fbPaYe7SnaLizQeTmgnUHGCaiM/E.EkJR0JJLNsWrwdo/G',
    '2024-02-10 09:15:00'),

(3, 'maria', 'maria.santos@email.com',
    '$2y$10$/n23x5f6.tr5U/hhWA6uDutdJ7rsGjcS5rxJTD9tuV3n3SN2h8omC',
    'user', 'Maria Santos', '09191234569', '1993-11-05',
    '123 Rizal Street, Lubo, Santo Niño (Faire), Cagayan',
    'active', 'What is the name of your elementary school?',
    '$2y$10$Wc64Ytdws0QAz.p4r26EQueCi0p9kmLFqFX0A7vQzXF5ZAxafd40W',
    '2024-02-14 10:30:00'),

(4, 'pedro', 'pedro.reyes@email.com',
    '$2y$10$/n23x5f6.tr5U/hhWA6uDutdJ7rsGjcS5rxJTD9tuV3n3SN2h8omC',
    'user', 'Pedro Reyes', '09201234570', '1988-08-17',
    'Purok 2, Sto. Niño, Santo Niño (Faire), Cagayan',
    'active', 'What is your mother\'s maiden name?',
    '$2y$10$jXvbyBiIPRs6OkrrV1koIOWLGZDGAGnDBTgmMsjQOVZ/zV1veZSsK',
    '2024-03-01 07:45:00'),

(5, 'ana', 'ana.garcia@email.com',
    '$2y$10$/n23x5f6.tr5U/hhWA6uDutdJ7rsGjcS5rxJTD9tuV3n3SN2h8omC',
    'user', 'Ana Garcia', '09211234571', '1995-04-30',
    'Purok 5, Poblacion, Santo Niño (Faire), Cagayan',
    'inactive', 'What city were you born in?',
    '$2y$10$DvPuxMZwH4pBT2xbDeFlRef7Ba2VqeJeMWcnqXPTBKBH1Dd2UeJ3W',
    '2024-03-20 11:00:00');

-- ─────────────────────────────────────────
-- DISASTER INCIDENTS (10 records)
-- ─────────────────────────────────────────
INSERT INTO `incidents`
    (`id`, `incident_code`, `disaster_type`, `title`, `description`, `location`, `barangay`, `municipality`, `incident_date`, `incident_time`, `severity`, `status`, `reported_by`, `created_at`)
VALUES
(1,  'INC-001', 'Flood',      'Flash Flood in Minanga Barangay',
    'Heavy rainfall caused flash flooding along the riverbanks of Minanga affecting approximately 45 families. Water levels rose to 1.5 meters in low-lying areas. Several houses partially submerged.',
    'Riverside Area, Minanga', 'Minanga', 'Santo Niño (Faire)', '2024-11-12', '03:30:00', 'High', 'Resolved',
    'Barangay Captain Rodrigo Villanueva', '2024-11-12 03:45:00'),

(2,  'INC-002', 'Typhoon',    'Typhoon Carina Landfall Effects',
    'Super typhoon Carina made landfall bringing sustained winds of 180 kph and heavy rains. Significant damage to infrastructure, agricultural crops, and residential structures across all barangays.',
    'All Barangays, Santo Niño (Faire)', 'Poblacion', 'Santo Niño (Faire)', '2024-10-20', '08:00:00', 'Critical', 'Resolved',
    'MDRRMO Office', '2024-10-20 08:00:00'),

(3,  'INC-003', 'Earthquake', 'Magnitude 5.8 Earthquake',
    'A magnitude 5.8 earthquake with epicenter approximately 12 km northeast of Santo Niño (Faire) caused moderate structural damage to several buildings in Poblacion and surrounding barangays. No casualties reported.',
    'Poblacion and Nearby Barangays', 'Poblacion', 'Santo Niño (Faire)', '2024-09-05', '14:22:00', 'Moderate', 'Resolved',
    'PHIVOLCS / MDRRMO', '2024-09-05 14:30:00'),

(4,  'INC-004', 'Fire',       'House Fire — Purok 3, Lubo',
    'A residential fire broke out in Purok 3, Lubo at approximately 2:00 AM. The fire consumed three houses before fire fighters contained the blaze. One family of five left homeless.',
    'Purok 3, Lubo', 'Lubo', 'Santo Niño (Faire)', '2024-12-01', '02:00:00', 'High', 'Resolved',
    'BFP Santo Niño (Faire) Fire Station', '2024-12-01 02:15:00'),

(5,  'INC-005', 'Landslide',  'Landslide Blocks National Highway',
    'Heavy rains triggered a significant landslide on the slope adjacent to the national highway near Sto. Niño. Approximately 200 cubic meters of soil and debris blocked the road, cutting off access to three barangays.',
    'National Highway near Sto. Niño', 'Sto. Niño', 'Santo Niño (Faire)', '2024-11-28', '17:45:00', 'High', 'Resolved',
    'Brgy. Sto. Niño Officials', '2024-11-28 18:00:00'),

(6,  'INC-006', 'Flood',      'Flooding in Low-Lying Areas of Sto. Niño',
    'Continuous rainfall over 48 hours resulted in flooding in the low-lying areas of Sto. Niño. Water reached knee-deep levels in some areas. Approximately 30 families sought temporary shelter.',
    'Low-lying areas, Sto. Niño', 'Sto. Niño', 'Santo Niño (Faire)', '2025-01-08', '06:00:00', 'Moderate', 'Active',
    'Sto. Niño Barangay Council', '2025-01-08 06:30:00'),

(7,  'INC-007', 'Typhoon',    'Tropical Storm Domeng — Damage Assessment',
    'Tropical Storm Domeng caused strong winds and heavy rainfall affecting the northern barangays. Roofing damage reported in Minanga and Lubo. Rice farms flooded.',
    'Minanga and Lubo Barangays', 'Minanga', 'Santo Niño (Faire)', '2025-01-15', '11:00:00', 'Moderate', 'Active',
    'MDRRMO Monitoring Team', '2025-01-15 11:30:00'),

(8,  'INC-008', 'Fire',       'Grass Fire in Agricultural Area',
    'A grass fire broke out in the agricultural area on the eastern side of Minanga. The fire threatened nearby residential structures before being contained. Approximately 3 hectares of farmland were affected.',
    'Eastern Agricultural Zone, Minanga', 'Minanga', 'Santo Niño (Faire)', '2024-12-15', '13:00:00', 'Low', 'Resolved',
    'Concerned Farmer — Domingo Lim', '2024-12-15 13:20:00'),

(9,  'INC-009', 'Earthquake', 'Aftershocks Following M5.8 Event',
    'A series of aftershocks (ranging M2.1–M3.8) were recorded following the September earthquake. Minor cracking observed in weakened structures in Poblacion. Residents advised to remain vigilant.',
    'Poblacion', 'Poblacion', 'Santo Niño (Faire)', '2025-01-20', '09:15:00', 'Low', 'Active',
    'PHIVOLCS Regional Office', '2025-01-20 09:30:00'),

(10, 'INC-010', 'Landslide',  'Critical Landslide Risk — Lubo Hillside',
    'Geo-hazard monitoring teams identified a critical landslide risk zone on the hillside area of Lubo. Soil saturation levels are dangerously high. Pre-emptive evacuation of 12 families along the slope has been ordered.',
    'Hillside Zone, Lubo', 'Lubo', 'Santo Niño (Faire)', '2025-01-22', '07:00:00', 'Critical', 'Active',
    'MDRRMO Geohazard Assessment Team', '2025-01-22 07:15:00');

-- ─────────────────────────────────────────
-- EVACUATION CENTERS (5 records)
-- ─────────────────────────────────────────
INSERT INTO `evacuation_centers`
    (`id`, `center_code`, `center_name`, `location`, `barangay`, `capacity`, `occupied_slots`, `contact_person`, `contact_number`, `status`, `created_at`)
VALUES
(1, 'EVC-001', 'Santo Niño Municipal Gymnasium',
    'Poblacion, Santo Niño (Faire), Cagayan',
    'Poblacion', 500, 180, 'Ricardo Bautista', '09221234572', 'Open', '2024-01-15 08:00:00'),

(2, 'EVC-002', 'Minanga Elementary School',
    'Purok 1, Minanga, Santo Niño (Faire), Cagayan',
    'Minanga', 200, 60, 'Leonora Espinosa', '09231234573', 'Open', '2024-01-15 08:00:00'),

(3, 'EVC-003', 'Lubo Barangay Hall (Emergency Wing)',
    'Purok 4, Lubo, Santo Niño (Faire), Cagayan',
    'Lubo', 150, 0, 'Cornelio Manalo', '09241234574', 'Closed', '2024-01-20 08:00:00'),

(4, 'EVC-004', 'Sto. Niño Multi-Purpose Hall',
    'Purok 2, Sto. Niño, Santo Niño (Faire), Cagayan',
    'Sto. Niño', 300, 120, 'Erlinda Pascual', '09251234575', 'Open', '2024-02-01 08:00:00'),

(5, 'EVC-005', 'Santo Niño National High School Covered Court',
    'Poblacion, Santo Niño (Faire), Cagayan',
    'Poblacion', 400, 95, 'Principal Felix Navarro', '09261234576', 'Open', '2024-02-10 08:00:00');

-- ─────────────────────────────────────────
-- RELIEF OPERATIONS (8 records)
-- ─────────────────────────────────────────
INSERT INTO `relief_operations`
    (`id`, `batch_number`, `operation_date`, `barangay`, `relief_type`, `quantity`, `unit`, `status`, `distributed_by`, `notes`)
VALUES
(1, 'BATCH-001', '2024-10-22', 'Poblacion', 'Food Pack', 250, 'packs', 'Completed',
    'MDRRMO Team Alpha', 'Post-Typhoon Carina relief. Food packs contain 3-day supply of canned goods and rice.'),

(2, 'BATCH-002', '2024-10-23', 'Minanga', 'Food Pack', 150, 'packs', 'Completed',
    'MDRRMO Team Beta', 'Post-Typhoon Carina relief distribution in Minanga.'),

(3, 'BATCH-003', '2024-10-24', 'Lubo', 'Non-Food Items (NFI)', 80, 'family kits', 'Completed',
    'DSWD Santo Niño (Faire)', 'NFI kits include sleeping mats, blankets, and hygiene kits.'),

(4, 'BATCH-004', '2024-11-14', 'Minanga', 'Food Pack', 90, 'packs', 'Completed',
    'MDRRMO / BFP', 'Relief for flood victims in Minanga riverside area (INC-001).'),

(5, 'BATCH-005', '2024-12-03', 'Lubo', 'Shelter Materials', 15, 'sets', 'Completed',
    'MDRRMO / LGU', 'Roofing materials (GI sheets, lumber) for fire victims in Purok 3, Lubo (INC-004).'),

(6, 'BATCH-006', '2025-01-09', 'Sto. Niño', 'Food Pack', 60, 'packs', 'In Progress',
    'MDRRMO Team Alpha', 'Ongoing relief for flood-affected families in Sto. Niño (INC-006).'),

(7, 'BATCH-007', '2025-01-22', 'Lubo', 'Food Pack', 24, 'packs', 'Pending',
    'MDRRMO', 'Prepared for pre-emptively evacuated families from landslide risk zone (INC-010). Awaiting deployment.'),

(8, 'BATCH-008', '2025-01-23', 'Minanga', 'Non-Food Items (NFI)', 40, 'family kits', 'Pending',
    'DSWD / MDRRMO', 'Hygiene and NFI kits for tropical storm Domeng-affected families in Minanga and Lubo.');

-- ─────────────────────────────────────────
-- ANNOUNCEMENTS (6 records)
-- published_by = 1 (admin user)
-- ─────────────────────────────────────────
INSERT INTO `announcements`
    (`id`, `title`, `body`, `category`, `published_by`, `published_at`, `is_active`)
VALUES
(1, 'Mandatory Evacuation Order — Lubo Hillside Zone',
    'The Municipal Disaster Risk Reduction and Management Office (MDRRMO) hereby issues a MANDATORY EVACUATION ORDER for all residents living within the identified landslide risk zone in the hillside area of Barangay Lubo. Affected families must proceed to the Lubo Barangay Hall Emergency Wing immediately. Failure to comply may endanger lives. MDRRMO personnel will assist in evacuation.',
    'Evacuation Order', 1, '2025-01-22', 1),

(2, 'Tropical Storm Domeng Advisory — Preparedness Reminder',
    'Tropical Storm Domeng is expected to bring heavy rains and strong winds to Santo Niño (Faire) and surrounding municipalities from January 15–17, 2025. Residents are advised to: (1) Store adequate food and water supply; (2) Secure loose objects; (3) Stay informed via PAGASA updates; (4) Know your evacuation routes. The MDRRMO is on full alert. For emergencies, call the MDRRMO Hotline: 0917-XXX-XXXX.',
    'Weather Advisory', 1, '2025-01-14', 1),

(3, 'Community DRRM Training — Barangay Level (February 2025)',
    'The Municipal DRRMO, in partnership with the Philippine Red Cross, will conduct Barangay-Level DRRM Training sessions in February 2025. Topics include: Basic Life Support (BLS), Search and Rescue, Community Early Warning Systems, and Evacuation Drills. Schedule: Minanga — Feb 3, Lubo — Feb 5, Sto. Niño — Feb 7, Poblacion — Feb 10. All Barangay Emergency Response Teams (BERTs) are required to attend.',
    'Training & Capacity Building', 1, '2025-01-18', 1),

(4, 'Relief Distribution — Sto. Niño Flood Victims',
    'Food packs and non-food items will be distributed to flood-affected families in Barangay Sto. Niño on January 10, 2025, from 8:00 AM to 5:00 PM at the Sto. Niño Multi-Purpose Hall. Affected families must bring their Barangay Identification and Disaster Assistance Family Access Cards (DAFAC) for verification. Queries may be directed to the MDRRMO office.',
    'Relief Distribution', 1, '2025-01-09', 1),

(5, 'Post-Typhoon Carina Damage Assessment Results',
    'The MDRRMO has completed the damage and needs assessment following Typhoon Carina (October 20, 2024). Summary: Total affected families — 1,247; Total affected individuals — 4,988; Houses totally damaged — 38; Houses partially damaged — 212; Agricultural damage (rice/corn) — 145 hectares; Infrastructure damage — Php 3.2 million estimated. LGU is coordinating with national government agencies for additional assistance.',
    'Damage Assessment Report', 1, '2024-10-28', 1),

(6, 'Geohazard Awareness Month — December 2024',
    'In observance of Geohazard Awareness Month, the MDRRMO encourages all residents to familiarize themselves with the municipal geohazard maps. Know if your area is in a flood-prone, landslide-prone, or storm surge-prone zone. Barangay-level community mapping sessions are scheduled throughout December. Contact your barangay council for the schedule in your area. Stay safe and be prepared!',
    'Awareness Campaign', 1, '2024-12-01', 1);

-- ─────────────────────────────────────────
-- DISASTER ALERTS (6 records)
-- affected_areas stored as comma-separated string
-- issued_by = 1 (admin user)
-- ─────────────────────────────────────────
INSERT INTO `disaster_alerts`
    (`id`, `alert_type`, `title`, `description`, `affected_areas`, `severity`, `status`, `issued_by`, `issued_at`)
VALUES
(1, 'Flood', 'FLOOD ALERT — Sto. Niño Low-Lying Areas',
    'Continuous rainfall has raised river levels to near-critical. Residents in low-lying areas of Sto. Niño are advised to move to higher ground. Monitor water levels closely.',
    'Sto. Niño', 'High', 'Active', 1, '2025-01-08 05:45:00'),

(2, 'Typhoon', 'TROPICAL STORM WARNING — Domeng',
    'Tropical Storm Domeng is approaching. PAGASA has raised Tropical Cyclone Wind Signal No. 1 over Santo Niño (Faire). Expect strong winds and heavy to intense rainfall.',
    'Minanga, Lubo, Sto. Niño, Poblacion', 'Moderate', 'Active', 1, '2025-01-14 06:00:00'),

(3, 'Landslide', 'CRITICAL LANDSLIDE RISK — Lubo Hillside',
    'Geohazard assessment reveals critical landslide risk on the Lubo hillside. Mandatory evacuation order issued. All residents in the risk zone must evacuate immediately.',
    'Lubo', 'Critical', 'Active', 1, '2025-01-22 07:00:00'),

(4, 'Earthquake', 'AFTERSHOCK ADVISORY — M5.8 Earthquake Zone',
    'Aftershocks from the September 2024 M5.8 earthquake continue to be recorded. Residents in Poblacion and nearby barangays are advised to inspect structures for cracks and avoid damaged buildings.',
    'Poblacion', 'Low', 'Active', 1, '2025-01-20 09:00:00'),

(5, 'Flood', 'FLOOD WATCH — Minanga River Area',
    'River levels in Minanga are being monitored closely due to persistent rainfall in the upstream catchment area. Residents near the river are advised to be on standby for possible evacuation.',
    'Minanga', 'Moderate', 'Active', 1, '2025-01-15 10:00:00'),

(6, 'Typhoon', 'TYPHOON CARINA — All Clear Issued',
    'Typhoon Carina has moved out of the Philippine Area of Responsibility. All clear is issued. Residents may return to their homes after assessment by barangay officials.',
    'Minanga, Lubo, Sto. Niño, Poblacion', 'Low', 'Resolved', 1, '2024-10-22 14:00:00');

SET FOREIGN_KEY_CHECKS = 1;
