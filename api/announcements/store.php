<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('POST');
$token_user = require_admin();

$body     = get_json_body();
$required = ['title', 'body'];
$missing  = array_filter($required, fn($f) => empty(trim((string)($body[$f] ?? ''))));
if ($missing) error('Missing required fields: ' . implode(', ', array_values($missing)), 400, array_values($missing));

try {
    $pdo  = Database::connect();

    // Safely check published_by user exists in users table (respect foreign key constraint)
    $userId = (int)($token_user->sub ?? 0);
    $publishedBy = null;
    if ($userId > 0) {
        $userCheck = $pdo->prepare('SELECT id FROM users WHERE id = ? LIMIT 1');
        $userCheck->execute([$userId]);
        if ($userCheck->fetch()) {
            $publishedBy = $userId;
        }
    }

    $stmt = $pdo->prepare(
        'INSERT INTO announcements (title, body, category, published_by, published_at, is_active)
         VALUES (?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        sanitize($body['title']),
        sanitize($body['body']),
        sanitize($body['category'] ?? 'General'),
        $publishedBy,
        !empty($body['published_at']) ? $body['published_at'] : date('Y-m-d'),
        isset($body['is_active']) ? (int)$body['is_active'] : 1
    ]);

    $new = $pdo->prepare('SELECT a.*, u.full_name AS published_by_name FROM announcements a LEFT JOIN users u ON u.id = a.published_by WHERE a.id = ? LIMIT 1');
    $new->execute([(int) $pdo->lastInsertId()]);

    success($new->fetch(), 'Announcement created.', 201);
} catch (PDOException $e) {
    error('Database error: ' . $e->getMessage(), 500);
}
