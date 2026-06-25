<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('PUT');
require_admin();

$id = (int) ($_GET['id'] ?? 0);
if ($id < 1) error('Invalid center ID.');

$body = get_json_body();

try {
    $pdo   = Database::connect();
    $check = $pdo->prepare('SELECT capacity FROM evacuation_centers WHERE id = ? LIMIT 1');
    $check->execute([$id]);
    $existing = $check->fetch();
    if (!$existing) error('Evacuation center not found.', 404);

    $capacity = isset($body['capacity'])       ? (int) $body['capacity']       : (int) $existing['capacity'];
    $occupied = isset($body['occupied_slots']) ? (int) $body['occupied_slots'] : null;

    if ($occupied !== null && $occupied > $capacity) error('Occupied slots cannot exceed capacity.');

    $fields = [];
    $params = [];
    $map = [
        'center_name'    => 'sanitize',
        'location'       => 'sanitize',
        'barangay'       => 'sanitize',
        'contact_person' => 'sanitize',
        'contact_number' => 'sanitize',
        'status'         => fn($v) => $v,
        'capacity'       => fn($v) => (int) $v,
        'occupied_slots' => fn($v) => (int) $v,
    ];

    foreach ($map as $col => $fn) {
        if (array_key_exists($col, $body)) {
            $fields[] = "$col = ?";
            $params[] = $fn === 'sanitize' ? sanitize($body[$col]) : $fn($body[$col]);
        }
    }

    if (!$fields) error('No fields to update.');

    $params[] = $id;
    $stmt = $pdo->prepare('UPDATE evacuation_centers SET ' . implode(', ', $fields) . ' WHERE id = ?');
    $stmt->execute($params);

    $updated = $pdo->prepare('SELECT *, (capacity - occupied_slots) AS available_slots FROM evacuation_centers WHERE id = ? LIMIT 1');
    $updated->execute([$id]);

    success($updated->fetch(), 'Evacuation center updated.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
