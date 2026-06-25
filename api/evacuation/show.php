<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('GET');
require_auth();

$id = (int) ($_GET['id'] ?? 0);
if ($id < 1) error('Invalid center ID.');

try {
    $pdo  = Database::connect();
    $stmt = $pdo->prepare('SELECT *, (capacity - occupied_slots) AS available_slots FROM evacuation_centers WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $row  = $stmt->fetch();
} catch (PDOException $e) {
    error('Database error.', 500);
}

if (!$row) error('Evacuation center not found.', 404);

success($row, 'Evacuation center retrieved.');
