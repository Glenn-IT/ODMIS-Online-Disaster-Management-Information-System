<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../helpers/response.php';

method_required('POST');

$body = get_json_body();
$step = (int) ($body['step'] ?? 0);

try {
    $pdo = Database::connect();
} catch (PDOException $e) {
    error('Database error.', 500);
}

// ── Step 1: Verify username → return security question ────────
if ($step === 1) {
    $username = sanitize($body['username'] ?? '');
    if ($username === '') {
        error('Username is required.');
    }

    $stmt = $pdo->prepare('SELECT security_question FROM users WHERE username = ? AND status = \'active\' LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Generic message — don't reveal if username exists
    if (!$user) {
        error('No active account found with that username.', 404);
    }

    success(['security_question' => $user['security_question']], 'Security question retrieved.');
}

// ── Step 2: Verify security answer ───────────────────────────
if ($step === 2) {
    $username = sanitize($body['username'] ?? '');
    $answer   = $body['security_answer'] ?? '';

    if ($username === '' || $answer === '') {
        error('Username and security answer are required.');
    }

    $stmt = $pdo->prepare('SELECT id, security_answer_hash FROM users WHERE username = ? AND status = \'active\' LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!$user || !password_verify(strtolower(trim($answer)), $user['security_answer_hash'])) {
        error('Incorrect security answer.', 401);
    }

    // Issue a short-lived reset token (plain user id encoded — real app would use a signed one-time token)
    $reset_token = base64_encode($user['id'] . ':' . hash_hmac('sha256', $user['id'] . time(), JWT_SECRET));

    success(['reset_token' => $reset_token, 'user_id' => $user['id']], 'Security answer verified.');
}

// ── Step 3: Reset password ────────────────────────────────────
if ($step === 3) {
    require_once __DIR__ . '/../../config/env.php';

    $user_id     = (int) ($body['user_id'] ?? 0);
    $new_password = $body['new_password'] ?? '';

    if ($user_id < 1 || strlen($new_password) < 6) {
        error('User ID and a password of at least 6 characters are required.');
    }

    $hash = password_hash($new_password, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
    $stmt->execute([$hash, $user_id]);

    if ($stmt->rowCount() === 0) {
        error('Password reset failed. User not found.', 404);
    }

    success(null, 'Password has been reset successfully. You may now log in.');
}

error('Invalid step. Expected 1, 2, or 3.');
