<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('PUT');
$token_user = require_auth();

$body         = get_json_body();
$old_password = $body['current_password'] ?? '';
$new_password = $body['new_password'] ?? '';

if ($old_password === '' || $new_password === '') {
    error('Current password and new password are required.');
}
if (strlen($new_password) < 6) {
    error('New password must be at least 6 characters.');
}
if ($old_password === $new_password) {
    error('New password must be different from the current password.');
}

try {
    $pdo  = Database::connect();
    $stmt = $pdo->prepare('SELECT password_hash FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$token_user->sub]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($old_password, $user['password_hash'])) {
        error('Current password is incorrect.', 401);
    }

    $new_hash = password_hash($new_password, PASSWORD_BCRYPT);
    $update   = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
    $update->execute([$new_hash, $token_user->sub]);

    success(null, 'Password changed successfully.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
