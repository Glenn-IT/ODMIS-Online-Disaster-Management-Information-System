<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('GET');
require_admin();

try {
    $pdo    = Database::connect();
    $where  = [];
    $params = [];

    if (!empty($_GET['barangay'])) {
        $where[]  = 'barangay = ?';
        $params[] = $_GET['barangay'];
    }
    if (!empty($_GET['status'])) {
        $where[]  = 'status = ?';
        $params[] = $_GET['status'];
    }
    if (!empty($_GET['start'])) {
        $where[]  = 'operation_date >= ?';
        $params[] = $_GET['start'];
    }
    if (!empty($_GET['end'])) {
        $where[]  = 'operation_date <= ?';
        $params[] = $_GET['end'];
    }

    $sql  = 'SELECT * FROM relief_operations';
    $sql .= $where ? ' WHERE ' . implode(' AND ', $where) : '';
    $sql .= ' ORDER BY operation_date DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    success($stmt->fetchAll(), 'Relief operations retrieved.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
