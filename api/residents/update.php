<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('PUT');
require_admin();

$id = (int) ($_GET['id'] ?? 0);
if ($id < 1) error('Invalid resident ID.');

$body = get_json_body();

if (isset($body['email']) && !filter_var($body['email'], FILTER_VALIDATE_EMAIL)) {
    error('Invalid email address.');
}
if (isset($body['contact_number']) && !preg_match('/^09\d{9}$/', $body['contact_number'])) {
    error('Contact number must be in format 09XXXXXXXXX.');
}

try {
    $pdo   = Database::connect();
    $check = $pdo->prepare("SELECT id FROM users WHERE id = ? AND role = 'user' LIMIT 1");
    $check->execute([$id]);
    if (!$check->fetch()) error('Resident not found.', 404);

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

    if (!$fields) error('No fields to update.');

    $params[] = $id;
    $stmt = $pdo->prepare('UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = ?');
    $stmt->execute($params);

    $updated = $pdo->prepare(
        'SELECT id, username, email, full_name, contact_number, date_of_birth, address, status FROM users WHERE id = ? LIMIT 1'
    );
    $updated->execute([$id]);

    success($updated->fetch(), 'Resident updated.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
