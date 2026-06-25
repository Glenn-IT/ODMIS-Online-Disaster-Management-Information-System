<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('DELETE');
require_admin();

$id = (int) ($_GET['id'] ?? 0);
if ($id < 1) error('Invalid center ID.');

try {
    $pdo  = Database::connect();
    $stmt = $pdo->prepare('DELETE FROM evacuation_centers WHERE id = ?');
    $stmt->execute([$id]);

    if ($stmt->rowCount() === 0) error('Evacuation center not found.', 404);

    success(null, 'Evacuation center deleted.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
