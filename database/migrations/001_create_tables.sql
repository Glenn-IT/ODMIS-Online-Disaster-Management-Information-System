-- ODMIS Database Migration
-- 001_create_tables.sql
-- Run against: odmis_db

SET FOREIGN_KEY_CHECKS = 0;

-- ─────────────────────────────────────────
-- 1. USERS
-- ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `users` (
    `id`                    INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `username`              VARCHAR(50)         NOT NULL,
    `email`                 VARCHAR(100)        NOT NULL,
    `password_hash`         VARCHAR(255)        NOT NULL,
    `role`                  ENUM('admin','user') NOT NULL DEFAULT 'user',
    `full_name`             VARCHAR(100)        NOT NULL,
    `contact_number`        VARCHAR(20)         NOT NULL,
    `date_of_birth`         DATE                    NULL,
    `address`               TEXT                    NULL,
    `status`                ENUM('active','inactive') NOT NULL DEFAULT 'active',
    `security_question`     VARCHAR(255)            NULL,
    `security_answer_hash`  VARCHAR(255)            NULL,
    `created_at`            DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`            DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_users_username` (`username`),
    UNIQUE KEY `uq_users_email`    (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────
-- 2. DISASTER INCIDENTS
-- ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `incidents` (
    `id`                INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `incident_code`     VARCHAR(20)         NOT NULL,
    `disaster_type`     ENUM('Flood','Typhoon','Earthquake','Fire','Landslide') NOT NULL,
    `title`             VARCHAR(200)        NOT NULL,
    `description`       TEXT                    NULL,
    `location`          VARCHAR(200)        NOT NULL,
    `barangay`          VARCHAR(100)        NOT NULL,
    `municipality`      VARCHAR(100)        NOT NULL DEFAULT 'Santo Niño (Faire)',
    `incident_date`     DATE                NOT NULL,
    `incident_time`     TIME                    NULL,
    `severity`          ENUM('Low','Moderate','High','Critical') NOT NULL,
    `status`            ENUM('Active','Resolved') NOT NULL DEFAULT 'Active',
    `reported_by`       VARCHAR(150)            NULL,
    `created_at`        DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_incidents_code` (`incident_code`),
    KEY `idx_incidents_type`      (`disaster_type`),
    KEY `idx_incidents_barangay`  (`barangay`),
    KEY `idx_incidents_status`    (`status`),
    KEY `idx_incidents_date`      (`incident_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────
-- 3. EVACUATION CENTERS
-- ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `evacuation_centers` (
    `id`                INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `center_code`       VARCHAR(20)         NOT NULL,
    `center_name`       VARCHAR(200)        NOT NULL,
    `location`          VARCHAR(300)        NOT NULL,
    `barangay`          VARCHAR(100)        NOT NULL,
    `capacity`          INT UNSIGNED        NOT NULL DEFAULT 0,
    `occupied_slots`    INT UNSIGNED        NOT NULL DEFAULT 0,
    `contact_person`    VARCHAR(100)            NULL,
    `contact_number`    VARCHAR(20)             NULL,
    `status`            ENUM('Open','Closed') NOT NULL DEFAULT 'Open',
    `created_at`        DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_evac_code` (`center_code`),
    KEY `idx_evac_barangay` (`barangay`),
    KEY `idx_evac_status`   (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────
-- 4. RELIEF OPERATIONS
-- ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `relief_operations` (
    `id`                INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `batch_number`      VARCHAR(20)         NOT NULL,
    `operation_date`    DATE                NOT NULL,
    `barangay`          VARCHAR(100)        NOT NULL,
    `relief_type`       VARCHAR(100)        NOT NULL,
    `quantity`          INT UNSIGNED        NOT NULL DEFAULT 0,
    `unit`              VARCHAR(50)             NULL,
    `status`            ENUM('Pending','In Progress','Completed') NOT NULL DEFAULT 'Pending',
    `distributed_by`    VARCHAR(150)            NULL,
    `notes`             TEXT                    NULL,
    `created_at`        DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_relief_batch` (`batch_number`),
    KEY `idx_relief_barangay` (`barangay`),
    KEY `idx_relief_status`   (`status`),
    KEY `idx_relief_date`     (`operation_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────
-- 5. ANNOUNCEMENTS
-- ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `announcements` (
    `id`                INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `title`             VARCHAR(300)        NOT NULL,
    `body`              LONGTEXT            NOT NULL,
    `category`          VARCHAR(100)            NULL,
    `published_by`      INT UNSIGNED            NULL,
    `published_at`      DATE                    NULL,
    `is_active`         TINYINT(1)          NOT NULL DEFAULT 1,
    `created_at`        DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_ann_active`      (`is_active`),
    KEY `idx_ann_published`   (`published_at`),
    CONSTRAINT `fk_ann_published_by` FOREIGN KEY (`published_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────
-- 6. DISASTER ALERTS
-- ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `disaster_alerts` (
    `id`                INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `alert_type`        ENUM('Flood','Typhoon','Earthquake','Fire','Landslide') NOT NULL,
    `title`             VARCHAR(200)        NOT NULL,
    `description`       TEXT                    NULL,
    `affected_areas`    TEXT                    NULL,
    `severity`          ENUM('Low','Moderate','High','Critical') NOT NULL,
    `status`            ENUM('Active','Resolved') NOT NULL DEFAULT 'Active',
    `issued_by`         INT UNSIGNED            NULL,
    `issued_at`         DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `expires_at`        DATETIME                NULL,
    `created_at`        DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_alerts_status`   (`status`),
    KEY `idx_alerts_type`     (`alert_type`),
    KEY `idx_alerts_severity` (`severity`),
    CONSTRAINT `fk_alerts_issued_by` FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────
-- 7. USER REPORTS
-- ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `user_reports` (
    `id`                INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `user_id`           INT UNSIGNED        NOT NULL,
    `incident_type`     ENUM('Flood','Typhoon','Earthquake','Fire','Landslide','Other') NOT NULL,
    `description`       TEXT                NOT NULL,
    `location`          VARCHAR(300)        NOT NULL,
    `report_date`       DATE                NOT NULL,
    `photo_path`        VARCHAR(300)            NULL,
    `status`            ENUM('Pending','Reviewed','Resolved') NOT NULL DEFAULT 'Pending',
    `reviewed_by`       INT UNSIGNED            NULL,
    `created_at`        DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_ureports_user`   (`user_id`),
    KEY `idx_ureports_status` (`status`),
    KEY `idx_ureports_date`   (`report_date`),
    CONSTRAINT `fk_ureports_user`        FOREIGN KEY (`user_id`)     REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_ureports_reviewed_by` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
