-- ODMIS Database Migration
-- 004_create_sms_logs_table.sql
-- Run against: odmis_db

CREATE TABLE IF NOT EXISTS `sms_logs` (
    `id`                    INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `recipient_phone`       VARCHAR(20)         NOT NULL,
    `recipient_user_id`     INT UNSIGNED            NULL,
    `barangay`              VARCHAR(100)            NULL,
    `message`               TEXT                NOT NULL,
    `status`                ENUM('Sent','Failed','Simulated') NOT NULL DEFAULT 'Sent',
    `provider`              VARCHAR(50)         NOT NULL DEFAULT 'PhilSMS',
    `provider_message_id`   VARCHAR(100)            NULL,
    `error_message`         TEXT                    NULL,
    `sent_by`               INT UNSIGNED            NULL,
    `created_at`            DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_sms_phone`     (`recipient_phone`),
    KEY `idx_sms_status`    (`status`),
    KEY `idx_sms_barangay`  (`barangay`),
    KEY `idx_sms_created`   (`created_at`),
    KEY `idx_sms_recipient` (`recipient_user_id`),
    CONSTRAINT `fk_sms_recipient` FOREIGN KEY (`recipient_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_sms_sent_by`   FOREIGN KEY (`sent_by`)           REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
