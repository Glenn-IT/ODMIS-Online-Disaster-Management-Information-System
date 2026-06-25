<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('PATCH');
require_admin();

$id = (int) ($_GET['id'] ?? 0);
if ($id < 1) error('Invalid resident ID.');

try {
    $pdo  = Database::connect();
    $stmt = $pdo->prepare("SELECT id, status FROM users WHERE id = ? AND role = 'user' LIMIT 1");
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if (!$user) error('Resident not found.', 404);

    $new_status = $user['status'] === 'active' ? 'inactive' : 'active';

    $update = $pdo->prepare('UPDATE users SET status = ? WHERE id = ?');
    $update->execute([$new_status, $id]);

    success(['id' => $id, 'status' => $new_status], "Resident {$new_status}.");
} catch (PDOException $e) {
    error('Database error.', 500);
}
