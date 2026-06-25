<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('POST');
$token_user = require_admin();

$body     = get_json_body();
$required = ['title', 'body'];
$missing  = array_filter($required, fn($f) => empty(trim($body[$f] ?? '')));
if ($missing) error('Missing required fields.', 400, array_values($missing));

try {
    $pdo  = Database::connect();
    $stmt = $pdo->prepare(
        'INSERT INTO announcements (title, body, category, published_by, published_at, is_active)
         VALUES (?, ?, ?, ?, ?, 1)'
    );
    $stmt->execute([
        sanitize($body['title']),
        sanitize($body['body']),
        sanitize($body['category'] ?? ''),
        $token_user->sub,
        $body['published_at'] ?? date('Y-m-d'),
    ]);

    $new = $pdo->prepare('SELECT * FROM announcements WHERE id = ? LIMIT 1');
    $new->execute([(int) $pdo->lastInsertId()]);

    success($new->fetch(), 'Announcement created.', 201);
} catch (PDOException $e) {
    error('Database error.', 500);
}
