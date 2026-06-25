<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../helpers/response.php';

use Firebase\JWT\JWT;

method_required('POST');

$body = get_json_body();

// ── Required fields ───────────────────────────────────────────
$required = ['username', 'email', 'password', 'full_name', 'contact_number', 'security_question', 'security_answer'];
$missing  = [];
foreach ($required as $field) {
    if (empty(trim($body[$field] ?? ''))) {
        $missing[] = $field;
    }
}
if ($missing) {
    error('Missing required fields.', 400, $missing);
}

$username        = sanitize($body['username']);
$email           = sanitize($body['email']);
$password        = $body['password'];
$full_name       = sanitize($body['full_name']);
$contact_number  = sanitize($body['contact_number']);
$date_of_birth   = sanitize($body['date_of_birth'] ?? '');
$address         = sanitize($body['address'] ?? '');
$security_q      = sanitize($body['security_question']);
$security_ans    = $body['security_answer'];

// ── Validation ────────────────────────────────────────────────
$errors = [];

if (strlen($username) < 3 || strlen($username) > 50) {
    $errors['username'] = 'Username must be 3–50 characters.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Invalid email address.';
}
if (strlen($password) < 6) {
    $errors['password'] = 'Password must be at least 6 characters.';
}
if (!preg_match('/^09\d{9}$/', $contact_number)) {
    $errors['contact_number'] = 'Contact number must be in format 09XXXXXXXXX.';
}
if ($errors) {
    error('Validation failed.', 422, $errors);
}

// ── Uniqueness check ──────────────────────────────────────────
try {
    $pdo = Database::connect();

    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
    $stmt->execute([$username, $email]);
    $existing = $stmt->fetch();

    if ($existing) {
        $stmt2 = $pdo->prepare('SELECT username, email FROM users WHERE username = ? OR email = ? LIMIT 1');
        $stmt2->execute([$username, $email]);
        $dupe = $stmt2->fetch();
        if ($dupe['username'] === $username) {
            error('Username is already taken.', 409);
        }
        error('Email is already registered.', 409);
    }
} catch (PDOException $e) {
    error('Database error.', 500);
}

// ── Hash and insert ───────────────────────────────────────────
$password_hash      = password_hash($password, PASSWORD_BCRYPT);
$security_ans_hash  = password_hash(strtolower(trim($security_ans)), PASSWORD_BCRYPT);
$dob                = $date_of_birth ?: null;

try {
    $stmt = $pdo->prepare(
        'INSERT INTO users (username, email, password_hash, role, full_name, contact_number, date_of_birth, address, status, security_question, security_answer_hash)
         VALUES (?, ?, ?, \'user\', ?, ?, ?, ?, \'active\', ?, ?)'
    );
    $stmt->execute([$username, $email, $password_hash, $full_name, $contact_number, $dob, $address, $security_q, $security_ans_hash]);
    $new_id = (int) $pdo->lastInsertId();
} catch (PDOException $e) {
    error('Failed to create account.', 500);
}

// ── Issue JWT ─────────────────────────────────────────────────
$now     = time();
$payload = [
    'sub'       => $new_id,
    'username'  => $username,
    'role'      => 'user',
    'full_name' => $full_name,
    'iat'       => $now,
    'exp'       => $now + JWT_EXPIRY,
];
$token = JWT::encode($payload, JWT_SECRET, 'HS256');

success([
    'token'     => $token,
    'role'      => 'user',
    'username'  => $username,
    'full_name' => $full_name,
    'email'     => $email,
    'expires_in' => JWT_EXPIRY,
], 'Account created successfully.', 201);
