<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('PUT');
require_admin();

$id = (int) ($_GET['id'] ?? 0);
if ($id < 1) error('Invalid alert ID.');

$body = get_json_body();

$allowed_types      = ['Flood', 'Typhoon', 'Earthquake', 'Fire', 'Landslide'];
$allowed_severities = ['Low', 'Moderate', 'High', 'Critical'];
$allowed_statuses   = ['Active', 'Resolved'];

if (isset($body['alert_type']) && !in_array($body['alert_type'], $allowed_types, true))      error('Invalid alert type.');
if (isset($body['severity'])   && !in_array($body['severity'],   $allowed_severities, true)) error('Invalid severity.');
if (isset($body['status'])     && !in_array($body['status'],     $allowed_statuses, true))   error('Invalid status.');

try {
    $pdo   = Database::connect();
    $check = $pdo->prepare('SELECT id FROM disaster_alerts WHERE id = ? LIMIT 1');
    $check->execute([$id]);
    if (!$check->fetch()) error('Alert not found.', 404);

    $fields = [];
    $params = [];
    $map = [
        'alert_type'     => fn($v) => $v,
        'title'          => 'sanitize',
        'description'    => 'sanitize',
        'affected_areas' => 'sanitize',
        'severity'       => fn($v) => $v,
        'status'         => fn($v) => $v,
        'expires_at'     => fn($v) => $v,
    ];

    foreach ($map as $col => $fn) {
        if (array_key_exists($col, $body)) {
            $fields[] = "$col = ?";
            $params[] = $fn === 'sanitize' ? sanitize($body[$col]) : $fn($body[$col]);
        }
    }

    if (!$fields) error('No fields to update.');

    $params[] = $id;
    $stmt = $pdo->prepare('UPDATE disaster_alerts SET ' . implode(', ', $fields) . ' WHERE id = ?');
    $stmt->execute($params);

    $updated = $pdo->prepare('SELECT * FROM disaster_alerts WHERE id = ? LIMIT 1');
    $updated->execute([$id]);

    success($updated->fetch(), 'Alert updated.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
