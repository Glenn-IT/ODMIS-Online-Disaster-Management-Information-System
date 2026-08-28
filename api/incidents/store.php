<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('POST');
require_admin();

$body = get_json_body();
$pdo  = Database::connect();

// Helper to find the next available incident code
function get_next_incident_code(PDO $pdo): string {
    $stmt = $pdo->query("SELECT incident_code FROM incidents WHERE incident_code LIKE 'INC-%'");
    $codes = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $maxNum = 0;
    foreach ($codes as $code) {
        if (preg_match('/^INC-(\d+)$/', $code, $m)) {
            $num = (int)$m[1];
            if ($num > $maxNum) {
                $maxNum = $num;
            }
        }
    }
    $maxId = (int)$pdo->query("SELECT IFNULL(MAX(id), 0) FROM incidents")->fetchColumn();
    $next = max($maxNum, $maxId) + 1;

    do {
        $candidate = 'INC-' . str_pad((string)$next, 3, '0', STR_PAD_LEFT);
        $check = $pdo->prepare('SELECT id FROM incidents WHERE incident_code = ? LIMIT 1');
        $check->execute([$candidate]);
        $exists = (bool)$check->fetch();
        if ($exists) {
            $next++;
        }
    } while ($exists);

    return $candidate;
}

// Auto-generate incident_code if missing, placeholder, or already existing
if (empty($body['incident_code']) || trim($body['incident_code']) === '(auto-generated)') {
    $body['incident_code'] = get_next_incident_code($pdo);
} else {
    $check = $pdo->prepare('SELECT id FROM incidents WHERE incident_code = ? LIMIT 1');
    $check->execute([sanitize($body['incident_code'])]);
    if ($check->fetch()) {
        $body['incident_code'] = get_next_incident_code($pdo);
    }
}

$required = ['incident_code', 'disaster_type', 'title', 'location', 'barangay', 'incident_date', 'severity'];
$missing  = array_filter($required, fn($f) => empty(trim((string)($body[$f] ?? ''))));
if ($missing) error('Missing required fields: ' . implode(', ', array_values($missing)), 400, array_values($missing));

$allowed_types      = ['Flood', 'Typhoon', 'Earthquake', 'Fire', 'Landslide'];
$allowed_severities = ['Low', 'Moderate', 'High', 'Critical'];

if (!in_array($body['disaster_type'], $allowed_types, true))      error('Invalid disaster type.');
if (!in_array($body['severity'],      $allowed_severities, true)) error('Invalid severity level.');

try {
    $stmt = $pdo->prepare(
        'INSERT INTO incidents (incident_code, disaster_type, title, description, location, barangay, municipality, incident_date, incident_time, severity, status, reported_by)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        sanitize($body['incident_code']),
        $body['disaster_type'],
        sanitize($body['title']),
        sanitize($body['description'] ?? ''),
        sanitize($body['location']),
        sanitize($body['barangay']),
        sanitize($body['municipality'] ?? 'Santo Niño (Faire)'),
        $body['incident_date'],
        !empty($body['incident_time']) ? $body['incident_time'] : null,
        $body['severity'],
        $body['status'] ?? 'Active',
        sanitize($body['reported_by'] ?? ''),
    ]);

    $new = $pdo->prepare('SELECT * FROM incidents WHERE id = ? LIMIT 1');
    $new->execute([(int) $pdo->lastInsertId()]);

    success($new->fetch(), 'Incident created.', 201);
} catch (PDOException $e) {
    error('Database error.', 500);
}
