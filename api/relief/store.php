<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('POST');
require_admin();

$body     = get_json_body();
$required = ['batch_number', 'operation_date', 'barangay', 'relief_type', 'quantity'];
$missing  = array_filter($required, fn($f) => empty(trim($body[$f] ?? '')));
if ($missing) error('Missing required fields.', 400, array_values($missing));

$allowed_statuses = ['Pending', 'In Progress', 'Completed'];
$status = $body['status'] ?? 'Pending';
if (!in_array($status, $allowed_statuses, true)) error('Invalid status.');

try {
    $pdo   = Database::connect();
    $check = $pdo->prepare('SELECT id FROM relief_operations WHERE batch_number = ? LIMIT 1');
    $check->execute([sanitize($body['batch_number'])]);
    if ($check->fetch()) error('Batch number already exists.', 409);

    $stmt = $pdo->prepare(
        'INSERT INTO relief_operations (batch_number, operation_date, barangay, relief_type, quantity, unit, status, distributed_by, notes)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        sanitize($body['batch_number']),
        $body['operation_date'],
        sanitize($body['barangay']),
        sanitize($body['relief_type']),
        (int) $body['quantity'],
        sanitize($body['unit'] ?? ''),
        $status,
        sanitize($body['distributed_by'] ?? ''),
        sanitize($body['notes'] ?? ''),
    ]);

    $new = $pdo->prepare('SELECT * FROM relief_operations WHERE id = ? LIMIT 1');
    $new->execute([(int) $pdo->lastInsertId()]);

    success($new->fetch(), 'Relief operation created.', 201);
} catch (PDOException $e) {
    error('Database error.', 500);
}
