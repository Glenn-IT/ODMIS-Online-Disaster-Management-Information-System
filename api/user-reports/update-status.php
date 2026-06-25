<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('PATCH');
$token_user = require_admin();

$id = (int) ($_GET['id'] ?? 0);
if ($id < 1) error('Invalid report ID.');

$body           = get_json_body();
$allowed        = ['Pending', 'Reviewed', 'Resolved'];
$status         = $body['status'] ?? '';

if (!in_array($status, $allowed, true)) error('Invalid status. Must be: Pending, Reviewed, or Resolved.');

try {
    $pdo  = Database::connect();
    $stmt = $pdo->prepare('UPDATE user_reports SET status = ?, reviewed_by = ? WHERE id = ?');
    $stmt->execute([$status, $token_user->sub, $id]);

    if ($stmt->rowCount() === 0) error('Report not found.', 404);

    $updated = $pdo->prepare('SELECT * FROM user_reports WHERE id = ? LIMIT 1');
    $updated->execute([$id]);

    success($updated->fetch(), "Report marked as {$status}.");
} catch (PDOException $e) {
    error('Database error.', 500);
}
