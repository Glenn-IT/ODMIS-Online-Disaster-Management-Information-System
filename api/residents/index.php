<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('GET');
require_admin();

try {
    $pdo    = Database::connect();
    $where  = ["role = 'user'"];
    $params = [];

    if (!empty($_GET['status'])) {
        $where[]  = 'status = ?';
        $params[] = $_GET['status'];
    }
    if (!empty($_GET['search'])) {
        $like     = '%' . $_GET['search'] . '%';
        $where[]  = '(full_name LIKE ? OR username LIKE ? OR email LIKE ?)';
        $params   = array_merge($params, [$like, $like, $like]);
    }

    $sql  = 'SELECT id, username, email, role, full_name, contact_number, date_of_birth, address, status, created_at FROM users';
    $sql .= ' WHERE ' . implode(' AND ', $where);
    $sql .= ' ORDER BY full_name ASC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    success($stmt->fetchAll(), 'Residents retrieved.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
