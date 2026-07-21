-- ODMIS Database Migration
-- 002_add_user_reports_incident_fields.sql
-- Run against: odmis_db
-- Adds fields the "Report Incident" user form collects (title, barangay,
-- municipality, incident_time) that were missing from user_reports.

ALTER TABLE `user_reports`
    ADD COLUMN `title`         VARCHAR(255) NOT NULL DEFAULT ''                    AFTER `incident_type`,
    ADD COLUMN `barangay`      VARCHAR(100) NOT NULL DEFAULT ''                    AFTER `location`,
    ADD COLUMN `municipality`  VARCHAR(100) NOT NULL DEFAULT 'Sto. Niño, Cagayan'  AFTER `barangay`,
    ADD COLUMN `incident_time` TIME             NULL                              AFTER `report_date`;
