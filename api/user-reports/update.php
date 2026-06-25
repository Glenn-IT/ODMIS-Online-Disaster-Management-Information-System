<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('PUT');
$token_user = require_auth();

$id = (int) ($_GET['id'] ?? 0);
if ($id < 1) error('Invalid report ID.');

$body = get_json_body();

try {
    $pdo  = Database::connect();
    $stmt = $pdo->prepare('SELECT user_id, status FROM user_reports WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $report = $stmt->fetch();

    if (!$report) error('Report not found.', 404);

    // Only the owner can edit; only if still Pending
    if ((int) $report['user_id'] !== $token_user->sub && $token_user->role !== 'admin') {
        error('Forbidden.', 403);
    }
    if ($report['status'] !== 'Pending' && $token_user->role !== 'admin') {
        error('Only pending reports can be edited.');
    }

    $allowed_types = ['Flood', 'Typhoon', 'Earthquake', 'Fire', 'Landslide', 'Other'];
    if (isset($body['incident_type']) && !in_array($body['incident_type'], $allowed_types, true)) {
        error('Invalid incident type.');
    }

    $fields = [];
    $params = [];
    $map = [
        'incident_type' => fn($v) => $v,
        'description'   => 'sanitize',
        'location'      => 'sanitize',
        'report_date'   => fn($v) => $v,
    ];

    foreach ($map as $col => $fn) {
        if (array_key_exists($col, $body)) {
            $fields[] = "$col = ?";
            $params[] = $fn === 'sanitize' ? sanitize($body[$col]) : $fn($body[$col]);
        }
    }

    if (!$fields) error('No fields to update.');

    $params[] = $id;
    $update = $pdo->prepare('UPDATE user_reports SET ' . implode(', ', $fields) . ' WHERE id = ?');
    $update->execute($params);

    $updated = $pdo->prepare('SELECT * FROM user_reports WHERE id = ? LIMIT 1');
    $updated->execute([$id]);

    success($updated->fetch(), 'Report updated.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
