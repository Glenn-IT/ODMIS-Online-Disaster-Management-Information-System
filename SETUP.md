# ODMIS Setup Guide for New Devices

This guide explains how to set up the **ODMIS (Online Disaster Management Information System)** project after cloning it onto a new device.

---

## Quick Setup Summary

| Step | Task | Command / Action |
|------|------|------------------|
| 1 | Clone Repository | `git clone <repository-url>` |
| 2 | Place in Web Root | Move project to `C:\xampp\htdocs\ODMIS-Online-Disaster-Management-Information-System` |
| 3 | Install Dependencies | `composer install` |
| 4 | Setup Environment | Copy `config/env.example.php` to `config/env.php` |
| 5 | Database Setup | Create MySQL database `odmis_db` & import `database/odmis_db-072726.sql` |
| 6 | Enable PHP Extensions | Enable `gd`, `mbstring`, `pdo_mysql` in `php.ini` |
| 7 | Launch Application | Start Apache & MySQL in XAMPP and visit `http://localhost/ODMIS-Online-Disaster-Management-Information-System/` |

---

## Detailed Step-by-Step Instructions

### Step 1: Install Composer Dependencies
Git ignores the `vendor/` folder where PHP libraries are installed.

1. Open PowerShell or Command Prompt in the project root directory:
   ```bash
   cd C:\xampp\htdocs\ODMIS-Online-Disaster-Management-Information-System
   ```
2. Run Composer install:
   ```bash
   composer install
   ```
   > **Note:** If `composer` is not installed on your system, download and install **Composer for Windows** from [getcomposer.org](https://getcomposer.org/).

---

### Step 2: Configure Environment (`config/env.php`)
Git ignores `config/env.php` to keep credentials secure.

1. Copy the example file `config/env.example.php` and rename it to `config/env.php`:
   - **Command Line:**
     ```bash
     copy config\env.example.php config\env.php
     ```
   - Or manually duplicate `config/env.example.php` as `config/env.php`.
2. Open `config/env.php` and verify the settings:
   - `DB_HOST`: default `localhost`
   - `DB_NAME`: default `odmis_db`
   - `DB_USER`: default `root`
   - `DB_PASS`: default `""` (empty for default XAMPP setup)
   - `APP_URL`: `http://localhost/ODMIS-Online-Disaster-Management-Information-System`

---

### Step 3: MySQL Database Setup
1. Open **XAMPP Control Panel** and start **Apache** and **MySQL**.
2. Open your browser and go to phpMyAdmin: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
3. Click **Databases** tab and create a new database:
   - Database Name: `odmis_db`
   - Collation: `utf8mb4_unicode_ci`
4. Select `odmis_db` from the left sidebar.
5. Click the **Import** tab at the top.
6. Click **Choose File** and select `database/odmis_db-072726.sql` from the project folder.
7. Scroll to the bottom and click **Go** (or Import).

---

### Step 4: Verify PHP Extensions in XAMPP
The PDF export feature (mPDF) requires specific PHP extensions enabled in XAMPP.

1. In XAMPP Control Panel, click **Config** next to Apache -> select `php.ini`.
2. Ensure the following lines are NOT commented out (remove any leading `;`):
   ```ini
   extension=gd
   extension=mbstring
   extension=pdo_mysql
   extension=openssl
   ```
3. Save `php.ini` and restart Apache in XAMPP.

---

### Step 5: Test the System
Open your web browser and navigate to:
`http://localhost/ODMIS-Online-Disaster-Management-Information-System/`

#### Demo Credentials:
- **Admin Login:** Username: `admin` | Password: `admin123`
- **User Login:** Username: `juan` | Password: `user123`

---

## Troubleshooting Checklist

| Symptom | Cause | Solution |
|---------|-------|----------|
| `Fatal error: Uncaught Error: Class "mPDF" not found` or `autoload.php missing` | Composer dependencies not installed | Run `composer install` in project folder |
| `Fatal error: require_once(...env.php): Failed to open stream` | Missing `config/env.php` | Copy `config/env.example.php` to `config/env.php` |
| `PDOException: SQLSTATE[HY000] [1049] Unknown database 'odmis_db'` | Database not created or named incorrectly | Create `odmis_db` database in phpMyAdmin |
| `PDOException: SQLSTATE[HY000] [2002] Connection refused` | MySQL service stopped | Start MySQL service in XAMPP |
