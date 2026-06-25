<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('PUT');
require_admin();

$id = (int) ($_GET['id'] ?? 0);
if ($id < 1) error('Invalid announcement ID.');

$body = get_json_body();

try {
    $pdo   = Database::connect();
    $check = $pdo->prepare('SELECT id FROM announcements WHERE id = ? LIMIT 1');
    $check->execute([$id]);
    if (!$check->fetch()) error('Announcement not found.', 404);

    $fields = [];
    $params = [];
    $map = [
        'title'        => 'sanitize',
        'body'         => 'sanitize',
        'category'     => 'sanitize',
        'published_at' => fn($v) => $v,
        'is_active'    => fn($v) => (int) $v,
    ];

    foreach ($map as $col => $fn) {
        if (array_key_exists($col, $body)) {
            $fields[] = "$col = ?";
            $params[] = $fn === 'sanitize' ? sanitize($body[$col]) : $fn($body[$col]);
        }
    }

    if (!$fields) error('No fields to update.');

    $params[] = $id;
    $stmt = $pdo->prepare('UPDATE announcements SET ' . implode(', ', $fields) . ' WHERE id = ?');
    $stmt->execute($params);

    $updated = $pdo->prepare('SELECT * FROM announcements WHERE id = ? LIMIT 1');
    $updated->execute([$id]);

    success($updated->fetch(), 'Announcement updated.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
