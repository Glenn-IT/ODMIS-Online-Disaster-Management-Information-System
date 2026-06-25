<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('POST');
$token_user = require_admin();

$body     = get_json_body();
$required = ['alert_type', 'title', 'severity'];
$missing  = array_filter($required, fn($f) => empty(trim($body[$f] ?? '')));
if ($missing) error('Missing required fields.', 400, array_values($missing));

$allowed_types      = ['Flood', 'Typhoon', 'Earthquake', 'Fire', 'Landslide'];
$allowed_severities = ['Low', 'Moderate', 'High', 'Critical'];

if (!in_array($body['alert_type'], $allowed_types, true))      error('Invalid alert type.');
if (!in_array($body['severity'],   $allowed_severities, true)) error('Invalid severity.');

try {
    $pdo  = Database::connect();
    $stmt = $pdo->prepare(
        'INSERT INTO disaster_alerts (alert_type, title, description, affected_areas, severity, status, issued_by, issued_at, expires_at)
         VALUES (?, ?, ?, ?, ?, \'Active\', ?, NOW(), ?)'
    );
    $stmt->execute([
        $body['alert_type'],
        sanitize($body['title']),
        sanitize($body['description'] ?? ''),
        sanitize($body['affected_areas'] ?? ''),
        $body['severity'],
        $token_user->sub,
        $body['expires_at'] ?? null,
    ]);

    $new = $pdo->prepare('SELECT * FROM disaster_alerts WHERE id = ? LIMIT 1');
    $new->execute([(int) $pdo->lastInsertId()]);

    success($new->fetch(), 'Alert issued.', 201);
} catch (PDOException $e) {
    error('Database error.', 500);
}
