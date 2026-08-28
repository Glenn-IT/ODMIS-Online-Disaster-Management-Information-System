<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('POST');
require_admin();

$body = get_json_body();
$pdo  = Database::connect();

// Helper to find the next available batch number
function get_next_batch_number(PDO $pdo): string {
    $stmt = $pdo->query("SELECT batch_number FROM relief_operations WHERE batch_number LIKE 'BATCH-%'");
    $codes = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $maxNum = 0;
    foreach ($codes as $code) {
        if (preg_match('/^BATCH-(\d+)$/', $code, $m)) {
            $num = (int)$m[1];
            if ($num > $maxNum) $maxNum = $num;
        }
    }
    $maxId = (int)$pdo->query("SELECT IFNULL(MAX(id), 0) FROM relief_operations")->fetchColumn();
    $next = max($maxNum, $maxId) + 1;

    do {
        $candidate = 'BATCH-' . str_pad((string)$next, 3, '0', STR_PAD_LEFT);
        $check = $pdo->prepare('SELECT id FROM relief_operations WHERE batch_number = ? LIMIT 1');
        $check->execute([$candidate]);
        $exists = (bool)$check->fetch();
        if ($exists) $next++;
    } while ($exists);

    return $candidate;
}

// Auto-generate batch_number if missing or default placeholder or collision
if (empty($body['batch_number']) || trim($body['batch_number']) === '(auto-generated)') {
    $body['batch_number'] = get_next_batch_number($pdo);
} else {
    $check = $pdo->prepare('SELECT id FROM relief_operations WHERE batch_number = ? LIMIT 1');
    $check->execute([sanitize($body['batch_number'])]);
    if ($check->fetch()) {
        $body['batch_number'] = get_next_batch_number($pdo);
    }
}

$required = ['batch_number', 'operation_date', 'barangay', 'relief_type', 'quantity'];
$missing  = array_filter($required, fn($f) => empty(trim((string)($body[$f] ?? ''))));
if ($missing) error('Missing required fields: ' . implode(', ', array_values($missing)), 400, array_values($missing));

$allowed_statuses = ['Pending', 'In Progress', 'Completed'];
$status = $body['status'] ?? 'Pending';
if (!in_array($status, $allowed_statuses, true)) error('Invalid status.');

try {

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
