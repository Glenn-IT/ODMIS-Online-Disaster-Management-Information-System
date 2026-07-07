<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('PUT');
$token_user = require_auth();

$body = get_json_body();

if (isset($body['email']) && !filter_var($body['email'], FILTER_VALIDATE_EMAIL)) {
    error('Invalid email address.');
}
if (isset($body['contact_number']) && !preg_match('/^09\d{9}$/', $body['contact_number'])) {
    error('Contact number must be in format 09XXXXXXXXX.');
}

try {
    $pdo = Database::connect();

    // Check email uniqueness if changing it
    if (!empty($body['email'])) {
        $check = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1');
        $check->execute([sanitize($body['email']), $token_user->sub]);
        if ($check->fetch()) error('Email is already in use by another account.', 409);
    }

    // Security question change requires verifying the current answer
    if (isset($body['security_question']) || isset($body['security_answer'])) {
        if (empty($body['security_question']) || empty($body['security_answer'])) {
            error('Security question and answer are required.');
        }

        $current = $pdo->prepare('SELECT security_answer_hash FROM users WHERE id = ? LIMIT 1');
        $current->execute([$token_user->sub]);
        $row = $current->fetch();

        if ($row && $row['security_answer_hash']) {
            $current_answer = $body['current_security_answer'] ?? '';
            if ($current_answer === '' || !password_verify(strtolower(trim($current_answer)), $row['security_answer_hash'])) {
                error('Current security answer is incorrect.', 401);
            }
        }
    }

    $fields = [];
    $params = [];
    $map = [
        'full_name'      => 'sanitize',
        'email'          => 'sanitize',
        'contact_number' => 'sanitize',
        'date_of_birth'  => fn($v) => $v,
        'address'        => 'sanitize',
    ];

    foreach ($map as $col => $fn) {
        if (array_key_exists($col, $body)) {
            $fields[] = "$col = ?";
            $params[] = $fn === 'sanitize' ? sanitize($body[$col]) : $fn($body[$col]);
        }
    }

    if (isset($body['security_question']) && isset($body['security_answer'])) {
        $fields[] = 'security_question = ?';
        $params[] = sanitize($body['security_question']);
        $fields[] = 'security_answer_hash = ?';
        $params[] = password_hash(strtolower(trim($body['security_answer'])), PASSWORD_BCRYPT);
    }

    if (!$fields) error('No fields to update.');

    $params[] = $token_user->sub;
    $stmt = $pdo->prepare('UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = ?');
    $stmt->execute($params);

    $updated = $pdo->prepare(
        'SELECT id, username, email, full_name, contact_number, date_of_birth, address, status FROM users WHERE id = ? LIMIT 1'
    );
    $updated->execute([$token_user->sub]);

    success($updated->fetch(), 'Profile updated.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
