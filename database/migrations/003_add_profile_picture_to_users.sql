-- ODMIS Database Migration
-- 003_add_profile_picture_to_users.sql
-- Run against: odmis_db

ALTER TABLE `users`
ADD COLUMN `profile_picture` VARCHAR(300) NULL AFTER `address`;
