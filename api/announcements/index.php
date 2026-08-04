<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';

method_required('GET');

try {
    $pdo    = Database::connect();
    $where  = [];
    $params = [];

    if (empty($_GET['all'])) {
        $where[] = 'a.is_active = 1';
    }

    if (!empty($_GET['category'])) {
        $where[]  = 'a.category = ?';
        $params[] = $_GET['category'];
    }

    $sql = 'SELECT a.id, a.title, a.body, a.category, a.published_at, a.is_active,
                   u.full_name AS published_by_name
            FROM announcements a
            LEFT JOIN users u ON u.id = a.published_by';
    if ($where) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }
    $sql .= ' ORDER BY a.published_at DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    success($stmt->fetchAll(), 'Announcements retrieved.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
