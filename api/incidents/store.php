<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('POST');
require_admin();

$body = get_json_body();

$required = ['incident_code', 'disaster_type', 'title', 'location', 'barangay', 'incident_date', 'severity'];
$missing  = array_filter($required, fn($f) => empty(trim($body[$f] ?? '')));
if ($missing) error('Missing required fields.', 400, array_values($missing));

$allowed_types      = ['Flood', 'Typhoon', 'Earthquake', 'Fire', 'Landslide'];
$allowed_severities = ['Low', 'Moderate', 'High', 'Critical'];

if (!in_array($body['disaster_type'], $allowed_types, true))      error('Invalid disaster type.');
if (!in_array($body['severity'],      $allowed_severities, true)) error('Invalid severity level.');

try {
    $pdo  = Database::connect();

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
        $body['incident_time'] ?? null,
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
