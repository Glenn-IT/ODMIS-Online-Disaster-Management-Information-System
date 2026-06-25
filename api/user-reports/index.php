<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('GET');
$token_user = require_auth();

try {
    $pdo    = Database::connect();
    $where  = [];
    $params = [];

    if ($token_user->role === 'admin') {
        // Admin sees all reports; optionally filter by status or user
        if (!empty($_GET['status'])) {
            $where[]  = 'r.status = ?';
            $params[] = $_GET['status'];
        }
        if (!empty($_GET['user_id'])) {
            $where[]  = 'r.user_id = ?';
            $params[] = (int) $_GET['user_id'];
        }
    } else {
        // Users see only their own reports
        $where[]  = 'r.user_id = ?';
        $params[] = $token_user->sub;
    }

    $sql = 'SELECT r.*, u.full_name AS submitted_by FROM user_reports r
            LEFT JOIN users u ON u.id = r.user_id';
    $sql .= $where ? ' WHERE ' . implode(' AND ', $where) : '';
    $sql .= ' ORDER BY r.created_at DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    success($stmt->fetchAll(), 'Reports retrieved.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
