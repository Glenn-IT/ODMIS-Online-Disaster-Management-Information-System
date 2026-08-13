<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('POST');
require_admin();

$body = get_json_body();
$pdo  = Database::connect();

// Auto-generate incident_code if missing or default placeholder
if (empty($body['incident_code']) || trim($body['incident_code']) === '(auto-generated)') {
    $maxStmt = $pdo->query('SELECT MAX(id) AS max_id FROM incidents');
    $maxRow  = $maxStmt->fetch();
    $nextId  = ($maxRow['max_id'] ?? 0) + 1;
    $body['incident_code'] = 'INC-' . str_pad((string)$nextId, 3, '0', STR_PAD_LEFT);
}

$required = ['incident_code', 'disaster_type', 'title', 'location', 'barangay', 'incident_date', 'severity'];
$missing  = array_filter($required, fn($f) => empty(trim((string)($body[$f] ?? ''))));
if ($missing) error('Missing required fields: ' . implode(', ', array_values($missing)), 400, array_values($missing));

$allowed_types      = ['Flood', 'Typhoon', 'Earthquake', 'Fire', 'Landslide'];
$allowed_severities = ['Low', 'Moderate', 'High', 'Critical'];

if (!in_array($body['disaster_type'], $allowed_types, true))      error('Invalid disaster type.');
if (!in_array($body['severity'],      $allowed_severities, true)) error('Invalid severity level.');

try {
    $check = $pdo->prepare('SELECT id FROM incidents WHERE incident_code = ? LIMIT 1');
    $check->execute([sanitize($body['incident_code'])]);
    if ($check->fetch()) error('Incident code already exists.', 409);

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
