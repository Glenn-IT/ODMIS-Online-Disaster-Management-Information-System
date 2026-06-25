<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('GET');
$token_user = require_auth();

$id = (int) ($_GET['id'] ?? 0);
if ($id < 1) error('Invalid report ID.');

try {
    $pdo  = Database::connect();
    $stmt = $pdo->prepare(
        'SELECT r.*, u.full_name AS submitted_by FROM user_reports r
         LEFT JOIN users u ON u.id = r.user_id WHERE r.id = ? LIMIT 1'
    );
    $stmt->execute([$id]);
    $row = $stmt->fetch();
} catch (PDOException $e) {
    error('Database error.', 500);
}

if (!$row) error('Report not found.', 404);

// Users can only view their own reports
if ($token_user->role !== 'admin' && (int) $row['user_id'] !== $token_user->sub) {
    error('Forbidden.', 403);
}

success($row, 'Report retrieved.');
