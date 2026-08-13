<?php
// Local development credentials — change all values for production
define('DB_HOST', 'localhost');
define('DB_NAME', 'odmis_db');
define('DB_USER', 'root');
define('DB_PASS', '');

define('JWT_SECRET', 'odmis_jwt_secret_key_2026_change_in_prod');
define('JWT_EXPIRY', 86400);        // 24 hours in seconds

define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024);  // 5 MB
define('UPLOAD_DIR', __DIR__ . '/../uploads/reports/');
define('UPLOAD_ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/webp']);

define('APP_ENV', 'development');   // 'development' | 'production'
define('APP_NAME', 'ODMIS');
define('APP_URL', 'http://localhost/ODMIS-Online-Disaster-Management-Information-System');
