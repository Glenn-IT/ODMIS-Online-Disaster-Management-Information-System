<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('POST');
require_admin();

$body     = get_json_body();
$required = ['center_code', 'center_name', 'location', 'barangay', 'capacity'];
$missing  = array_filter($required, fn($f) => empty(trim($body[$f] ?? '')));
if ($missing) error('Missing required fields.', 400, array_values($missing));

$capacity = (int) $body['capacity'];
$occupied = (int) ($body['occupied_slots'] ?? 0);
if ($capacity < 1)          error('Capacity must be greater than 0.');
if ($occupied > $capacity)  error('Occupied slots cannot exceed capacity.');

try {
    $pdo   = Database::connect();
    $check = $pdo->prepare('SELECT id FROM evacuation_centers WHERE center_code = ? LIMIT 1');
    $check->execute([sanitize($body['center_code'])]);
    if ($check->fetch()) error('Center code already exists.', 409);

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
        $body['status'] ?? 'Open',
    ]);

    $new = $pdo->prepare('SELECT *, (capacity - occupied_slots) AS available_slots FROM evacuation_centers WHERE id = ? LIMIT 1');
    $new->execute([(int) $pdo->lastInsertId()]);

    success($new->fetch(), 'Evacuation center created.', 201);
} catch (PDOException $e) {
    error('Database error.', 500);
}
