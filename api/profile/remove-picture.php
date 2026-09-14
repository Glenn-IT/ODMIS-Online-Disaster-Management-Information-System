<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

use Firebase\JWT\JWT;

// Allow POST or DELETE
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    error('Method not allowed.', 405);
}

$token_user = require_auth();

try {
    $pdo = Database::connect();

    // Query current profile picture
    $stmtOld = $pdo->prepare('SELECT profile_picture FROM users WHERE id = ? LIMIT 1');
    $stmtOld->execute([$token_user->sub]);
    $oldPic = $stmtOld->fetchColumn();

    if ($oldPic && str_starts_with($oldPic, 'uploads/profiles/')) {
        $oldFile = __DIR__ . '/../../' . $oldPic;
        if (file_exists($oldFile) && is_file($oldFile)) {
            @unlink($oldFile);
        }
    }

    // Set to NULL
    $stmtUpdate = $pdo->prepare('UPDATE users SET profile_picture = NULL WHERE id = ?');
    $stmtUpdate->execute([$token_user->sub]);

    // Fetch updated user
    $stmtUser = $pdo->prepare(
        'SELECT id, username, email, role, full_name, contact_number, date_of_birth, address, profile_picture, status, created_at
         FROM users WHERE id = ? LIMIT 1'
    );
    $stmtUser->execute([$token_user->sub]);
    $user = $stmtUser->fetch();

    if (!$user) {
        error('User not found.', 404);
    }

    // Generate refreshed JWT with null profile_picture
    $now = time();
    $payload = [
        'sub'             => $user['id'],
        'username'        => $user['username'],
        'role'            => $user['role'],
        'full_name'       => $user['full_name'],
        'profile_picture' => null,
        'iat'             => $now,
        'exp'             => $now + JWT_EXPIRY,
    ];
    $token = JWT::encode($payload, JWT_SECRET, 'HS256');

    success([
        'profile_picture' => null,
        'user'            => $user,
        'token'           => $token,
    ], 'Profile picture removed successfully.');

} catch (PDOException $e) {
    error('Database error: ' . $e->getMessage(), 500);
}
