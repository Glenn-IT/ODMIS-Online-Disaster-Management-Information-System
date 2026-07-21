<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('PUT', 'PATCH');
require_admin();

$id = (int) ($_GET['id'] ?? 0);
if ($id < 1) error('Invalid incident ID.');

$body = get_json_body();

$allowed_types      = ['Flood', 'Typhoon', 'Earthquake', 'Fire', 'Landslide'];
$allowed_severities = ['Low', 'Moderate', 'High', 'Critical'];
$allowed_statuses   = ['Active', 'Resolved'];

if (isset($body['disaster_type']) && !in_array($body['disaster_type'], $allowed_types, true))      error('Invalid disaster type.');
if (isset($body['severity'])      && !in_array($body['severity'],      $allowed_severities, true)) error('Invalid severity.');
if (isset($body['status'])        && !in_array($body['status'],        $allowed_statuses, true))   error('Invalid status.');

try {
    $pdo  = Database::connect();

    $check = $pdo->prepare('SELECT id, status FROM incidents WHERE id = ? LIMIT 1');
    $check->execute([$id]);
    $existing = $check->fetch();
    if (!$existing) error('Incident not found.', 404);

    if (!empty($body['toggle_status'])) {
        $body['status'] = $existing['status'] === 'Resolved' ? 'Active' : 'Resolved';
    }

    $fields = [];
    $params = [];
    $map = [
        'disaster_type' => fn($v) => $v,
        'title'         => 'sanitize',
        'description'   => 'sanitize',
        'location'      => 'sanitize',
        'barangay'      => 'sanitize',
        'municipality'  => 'sanitize',
        'incident_date' => fn($v) => $v,
        'incident_time' => fn($v) => $v,
        'severity'      => fn($v) => $v,
        'status'        => fn($v) => $v,
        'reported_by'   => 'sanitize',
    ];

    foreach ($map as $col => $fn) {
        if (array_key_exists($col, $body)) {
            $fields[] = "$col = ?";
            $params[] = $fn === 'sanitize' ? sanitize($body[$col]) : $fn($body[$col]);
        }
    }

    if (!$fields) error('No fields to update.');

    $params[] = $id;
    $stmt = $pdo->prepare('UPDATE incidents SET ' . implode(', ', $fields) . ' WHERE id = ?');
    $stmt->execute($params);

    $updated = $pdo->prepare('SELECT * FROM incidents WHERE id = ? LIMIT 1');
    $updated->execute([$id]);

    success($updated->fetch(), 'Incident updated.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
