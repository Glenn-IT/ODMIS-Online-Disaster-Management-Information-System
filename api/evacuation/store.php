<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('POST');
require_admin();

$body = get_json_body();
$pdo  = Database::connect();

// Helper to find the next available center code
function get_next_center_code(PDO $pdo): string {
    $stmt = $pdo->query("SELECT center_code FROM evacuation_centers WHERE center_code LIKE 'EVC-%'");
    $codes = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $maxNum = 0;
    foreach ($codes as $code) {
        if (preg_match('/^EVC-(\d+)$/', $code, $m)) {
            $num = (int)$m[1];
            if ($num > $maxNum) $maxNum = $num;
        }
    }
    $maxId = (int)$pdo->query("SELECT IFNULL(MAX(id), 0) FROM evacuation_centers")->fetchColumn();
    $next = max($maxNum, $maxId) + 1;

    do {
        $candidate = 'EVC-' . str_pad((string)$next, 3, '0', STR_PAD_LEFT);
        $check = $pdo->prepare('SELECT id FROM evacuation_centers WHERE center_code = ? LIMIT 1');
        $check->execute([$candidate]);
        $exists = (bool)$check->fetch();
        if ($exists) $next++;
    } while ($exists);

    return $candidate;
}

// Auto-generate center_code if missing or default placeholder or collision
if (empty($body['center_code']) || trim($body['center_code']) === '(auto-generated)') {
    $body['center_code'] = get_next_center_code($pdo);
} else {
    $check = $pdo->prepare('SELECT id FROM evacuation_centers WHERE center_code = ? LIMIT 1');
    $check->execute([sanitize($body['center_code'])]);
    if ($check->fetch()) {
        $body['center_code'] = get_next_center_code($pdo);
    }
}

$required = ['center_code', 'center_name', 'location', 'barangay', 'capacity'];
$missing  = array_filter($required, fn($f) => empty(trim((string)($body[$f] ?? ''))));
if ($missing) error('Missing required fields: ' . implode(', ', array_values($missing)), 400, array_values($missing));

$capacity = (int) $body['capacity'];
$occupied = (int) ($body['occupied_slots'] ?? 0);
if ($capacity < 1)          error('Capacity must be greater than 0.');
if ($occupied < 0)          error('Occupied slots cannot be negative.');
if ($occupied > $capacity)  error('Occupied slots cannot exceed capacity.');

// Automatically set status to Closed if occupied is equal to capacity
$status = $body['status'] ?? 'Open';
if ($occupied >= $capacity) {
    $status = 'Closed';
}

try {

    $stmt = $pdo->prepare(
        'INSERT INTO evacuation_centers (center_code, center_name, location, barangay, capacity, occupied_slots, contact_person, contact_number, status)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        sanitize($body['center_code']),
        sanitize($body['center_name']),
        sanitize($body['location']),
        sanitize($body['barangay']),
        $capacity,
        $occupied,
        sanitize($body['contact_person'] ?? ''),
        sanitize($body['contact_number'] ?? ''),
        $status,
    ]);

    $new = $pdo->prepare('SELECT *, (capacity - occupied_slots) AS available_slots FROM evacuation_centers WHERE id = ? LIMIT 1');
    $new->execute([(int) $pdo->lastInsertId()]);

    success($new->fetch(), 'Evacuation center created.', 201);
} catch (PDOException $e) {
    error('Database error.', 500);
}
