<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('PUT');
require_admin();

$id = (int) ($_GET['id'] ?? 0);
if ($id < 1) error('Invalid relief operation ID.');

$body = get_json_body();

$allowed_statuses = ['Pending', 'In Progress', 'Completed'];
if (isset($body['status']) && !in_array($body['status'], $allowed_statuses, true)) error('Invalid status.');

try {
    $pdo   = Database::connect();
    $check = $pdo->prepare('SELECT id FROM relief_operations WHERE id = ? LIMIT 1');
    $check->execute([$id]);
    if (!$check->fetch()) error('Relief operation not found.', 404);

    $fields = [];
    $params = [];
    $map = [
        'operation_date'  => fn($v) => $v,
        'barangay'        => 'sanitize',
        'relief_type'     => 'sanitize',
        'quantity'        => fn($v) => (int) $v,
        'unit'            => 'sanitize',
        'status'          => fn($v) => $v,
        'distributed_by'  => 'sanitize',
        'notes'           => 'sanitize',
    ];

    foreach ($map as $col => $fn) {
        if (array_key_exists($col, $body)) {
            $fields[] = "$col = ?";
            $params[] = $fn === 'sanitize' ? sanitize($body[$col]) : $fn($body[$col]);
        }
    }

    if (!$fields) error('No fields to update.');

    $params[] = $id;
    $stmt = $pdo->prepare('UPDATE relief_operations SET ' . implode(', ', $fields) . ' WHERE id = ?');
    $stmt->execute($params);

    $updated = $pdo->prepare('SELECT * FROM relief_operations WHERE id = ? LIMIT 1');
    $updated->execute([$id]);

    success($updated->fetch(), 'Relief operation updated.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
