<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../helpers/response.php';

use Firebase\JWT\JWT;

method_required('POST');

// ── Parse body ────────────────────────────────────────────────
$body     = get_json_body();
$username = sanitize($body['username'] ?? '');
$password = $body['password'] ?? '';

if ($username === '' || $password === '') {
    error('Username and password are required.');
}

// ── Query user ────────────────────────────────────────────────
try {
    $pdo  = Database::connect();
    $stmt = $pdo->prepare('SELECT id, username, email, password_hash, role, full_name, status FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    error('Database error.', 500);
}

// ── Validate credentials (generic message — no username hint) ─
if (!$user || !password_verify($password, $user['password_hash'])) {
    error('Invalid username or password.', 401);
}

if ($user['status'] !== 'active') {
    error('Your account is inactive. Please contact the administrator.', 403);
}

// ── Issue JWT ─────────────────────────────────────────────────
$now     = time();
$payload = [
    'sub'       => $user['id'],
    'username'  => $user['username'],
    'role'      => $user['role'],
    'full_name' => $user['full_name'],
    'iat'       => $now,
    'exp'       => $now + JWT_EXPIRY,
];
$token = JWT::encode($payload, JWT_SECRET, 'HS256');

success([
    'token'     => $token,
    'role'      => $user['role'],
    'username'  => $user['username'],
    'full_name' => $user['full_name'],
    'email'     => $user['email'],
    'expires_in' => JWT_EXPIRY,
], 'Login successful.');
