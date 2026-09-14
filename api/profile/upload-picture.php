<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

use Firebase\JWT\JWT;

method_required('POST');
$token_user = require_auth();

$fileKey = null;
if (!empty($_FILES['profile_picture']['name'])) {
    $fileKey = 'profile_picture';
} elseif (!empty($_FILES['photo']['name'])) {
    $fileKey = 'photo';
} elseif (!empty($_FILES['picture']['name'])) {
    $fileKey = 'picture';
}

if (!$fileKey || empty($_FILES[$fileKey]['tmp_name'])) {
    error('No profile picture file was provided.', 400);
}

$file = $_FILES[$fileKey];

if ($file['error'] !== UPLOAD_ERR_OK) {
    error('File upload failed with error code: ' . $file['error'], 400);
}

if ($file['size'] > UPLOAD_MAX_SIZE) {
    error('Photo exceeds maximum allowed size of 5MB.', 400);
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime  = $finfo->file($file['tmp_name']);
$allowedMimes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

if (!array_key_exists($mime, $allowedMimes)) {
    error('Invalid file type. Only JPEG, PNG, and WebP images are permitted.', 400);
}

$ext = $allowedMimes[$mime];
$filename = 'profile_' . $token_user->sub . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

$targetDir = defined('PROFILE_UPLOAD_DIR') ? PROFILE_UPLOAD_DIR : (__DIR__ . '/../../uploads/profiles/');
if (!is_dir($targetDir)) {
    if (!mkdir($targetDir, 0755, true) && !is_dir($targetDir)) {
        error('Failed to prepare upload destination directory.', 500);
    }
}

$targetPath = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $filename;

if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    error('Failed to save uploaded picture.', 500);
}

$dbRelPath = 'uploads/profiles/' . $filename;

try {
    $pdo = Database::connect();

    // Query current profile picture to remove old file
    $stmtOld = $pdo->prepare('SELECT profile_picture FROM users WHERE id = ? LIMIT 1');
    $stmtOld->execute([$token_user->sub]);
    $oldPic = $stmtOld->fetchColumn();

    if ($oldPic && str_starts_with($oldPic, 'uploads/profiles/')) {
        $oldFile = __DIR__ . '/../../' . $oldPic;
        if (file_exists($oldFile) && is_file($oldFile)) {
            @unlink($oldFile);
        }
    }

    // Update database
    $stmtUpdate = $pdo->prepare('UPDATE users SET profile_picture = ? WHERE id = ?');
    $stmtUpdate->execute([$dbRelPath, $token_user->sub]);

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

    // Generate refreshed JWT with new profile_picture
    $now = time();
    $payload = [
        'sub'             => $user['id'],
        'username'        => $user['username'],
        'role'            => $user['role'],
        'full_name'       => $user['full_name'],
        'profile_picture' => $user['profile_picture'],
        'iat'             => $now,
        'exp'             => $now + JWT_EXPIRY,
    ];
    $token = JWT::encode($payload, JWT_SECRET, 'HS256');

    success([
        'profile_picture' => $dbRelPath,
        'user'            => $user,
        'token'           => $token,
    ], 'Profile picture updated successfully.');

} catch (PDOException $e) {
    // If DB fails, clean up the newly uploaded file
    if (file_exists($targetPath)) {
        @unlink($targetPath);
    }
    error('Database error: ' . $e->getMessage(), 500);
}
