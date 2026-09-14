<?php
/**
 * ODMIS Automated System Synchronization & Health Checker
 * Usage: php scripts/sync-check.php
 */

$rootDir = realpath(__DIR__ . '/..');
echo "======================================================\n";
echo "   ODMIS System Synchronization & Integrity Check     \n";
echo "======================================================\n\n";

$errors = 0;
$warnings = 0;

function pass(string $msg): void {
    echo "  [PASS] $msg\n";
}

function fail(string $msg): void {
    global $errors;
    $errors++;
    echo "  [FAIL] $msg\n";
}

function warn(string $msg): void {
    global $warnings;
    $warnings++;
    echo "  [WARN] $msg\n";
}

// ── 1. Configuration & Database Connectivity ───────────────────
echo "1. Checking Database Connectivity & Config:\n";

if (!file_exists("$rootDir/config/database.php")) {
    fail("Missing config/database.php");
} else {
    require_once "$rootDir/config/database.php";
    try {
        $pdo = Database::connect();
        pass("Database connection established to odmis_db.");
    } catch (Exception $e) {
        fail("Database connection failed: " . $e->getMessage());
        exit(1);
    }
}

if (!file_exists("$rootDir/config/env.php")) {
    fail("Missing config/env.php");
} else {
    require_once "$rootDir/config/env.php";
    if (defined('JWT_SECRET') && !empty(JWT_SECRET)) {
        pass("JWT_SECRET is configured.");
    } else {
        fail("JWT_SECRET is missing or empty.");
    }
}

// ── 2. Table Schema Verification ──────────────────────────────
echo "\n2. Verifying Table Schemas (8 Required Tables):\n";

$expectedTables = [
    'users' => ['id', 'username', 'email', 'password_hash', 'role', 'full_name', 'contact_number', 'status'],
    'incidents' => ['id', 'incident_code', 'disaster_type', 'title', 'location', 'barangay', 'incident_date', 'severity', 'status'],
    'evacuation_centers' => ['id', 'center_code', 'center_name', 'location', 'barangay', 'capacity', 'occupied_slots', 'status'],
    'relief_operations' => ['id', 'batch_number', 'operation_date', 'barangay', 'relief_type', 'quantity', 'status'],
    'announcements' => ['id', 'title', 'body', 'category', 'published_at', 'is_active'],
    'disaster_alerts' => ['id', 'alert_type', 'title', 'severity', 'status', 'issued_at'],
    'user_reports' => ['id', 'user_id', 'incident_type', 'title', 'description', 'location', 'barangay', 'report_date', 'status'],
    'sms_logs' => ['id', 'recipient_phone', 'message', 'status', 'provider', 'created_at']
];

foreach ($expectedTables as $table => $requiredCols) {
    try {
        $stmt = $pdo->query("DESCRIBE `$table`");
        $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $missingCols = array_diff($requiredCols, $cols);
        if ($missingCols) {
            fail("Table '$table' is missing columns: " . implode(', ', $missingCols));
        } else {
            pass("Table '$table' has all required core columns (" . count($cols) . " total).");
        }
    } catch (PDOException $e) {
        fail("Table '$table' does not exist in database.");
    }
}

// ── 3. Apache Authorization Header Forwarding ────────────────
echo "\n3. Checking Apache .htaccess Directives:\n";
$htaccessPath = "$rootDir/api/.htaccess";
if (!file_exists($htaccessPath)) {
    fail("api/.htaccess is missing! Authenticated API endpoints will fail on XAMPP.");
} else {
    $content = file_get_contents($htaccessPath);
    if (str_contains($content, 'HTTP_AUTHORIZATION:%{HTTP:Authorization}')) {
        pass("api/.htaccess properly forwards Authorization header to PHP.");
    } else {
        fail("api/.htaccess is missing Authorization rewrite rule: RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]");
    }
}

// ── 4. Client Assets Check ────────────────────────────────────
echo "\n4. Verifying Client Assets:\n";
$requiredAssets = [
    'assets/js/api.js',
    'assets/js/auth.js',
    'assets/js/app.js',
    'assets/css/style.css'
];
foreach ($requiredAssets as $asset) {
    if (file_exists("$rootDir/$asset")) {
        pass("Found $asset (" . filesize("$rootDir/$asset") . " bytes).");
    } else {
        fail("Missing critical client asset: $asset");
    }
}

// ── 5. Field Synchronization Scan (Deprecated Mock Names) ────
echo "\n5. Scanning UI Files for Field Name Mismatches:\n";
$uiFiles = array_merge(
    glob("$rootDir/admin/*.php"),
    glob("$rootDir/user/*.php"),
    glob("$rootDir/*.php")
);

$deprecations = [
    'announcement.content' => 'announcements use .body (not .content)',
    'announcement.date_posted' => 'announcements use .published_at (not .date_posted)',
    'announcement.posted_by' => 'announcements use .published_by_name (not .posted_by)',
    'incident.date' => 'incidents API returns .incident_date (except when normalized in JS)',
];

foreach ($uiFiles as $f) {
    $content = file_get_contents($f);
    $rel = str_replace($rootDir . DIRECTORY_SEPARATOR, '', $f);
    foreach ($deprecations as $dep => $fix) {
        if (str_contains($content, $dep)) {
            warn("File '$rel' contains potentially desynchronized field '$dep' — $fix");
        }
    }
}
pass("UI synchronization scan complete.");

// ── 6. PHP Syntax Linting ─────────────────────────────────────
echo "\n6. Checking PHP Syntax (Lint):\n";
$phpFiles = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootDir, RecursiveDirectoryIterator::SKIP_DOTS)
);

$lintErrors = 0;
$checkedCount = 0;
foreach ($phpFiles as $file) {
    if ($file->getExtension() === 'php' && !str_contains($file->getPathname(), 'vendor')) {
        $checkedCount++;
        $out = [];
        $res = 0;
        exec("php -l " . escapeshellarg($file->getPathname()) . " 2>&1", $out, $res);
        if ($res !== 0) {
            $lintErrors++;
            fail("Syntax error in " . $file->getFilename() . ": " . implode(' ', $out));
        }
    }
}
if ($lintErrors === 0) {
    pass("All $checkedCount PHP files passed syntax check.");
}

// ── Summary ───────────────────────────────────────────────────
echo "\n======================================================\n";
echo "   Integrity Check Summary: $errors error(s), $warnings warning(s)\n";
echo "======================================================\n";

if ($errors > 0) {
    echo "Status: FAILED. Please resolve the errors listed above.\n\n";
    exit(1);
} else {
    echo "Status: SUCCESS! All systems are synchronized and healthy.\n\n";
    exit(0);
}
