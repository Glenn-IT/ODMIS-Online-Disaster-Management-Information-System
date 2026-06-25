<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('GET');

$token_user = require_auth();

try {
    $pdo  = Database::connect();
    $stmt = $pdo->prepare(
        'SELECT id, username, email, role, full_name, contact_number, date_of_birth, address, status, created_at
         FROM users WHERE id = ? LIMIT 1'
    );
    $stmt->execute([$token_user->sub]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    error('Database error.', 500);
}

if (!$user) {
    error('User not found.', 404);
}

success($user, 'User profile retrieved.');
