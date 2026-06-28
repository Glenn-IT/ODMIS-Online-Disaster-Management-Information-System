<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';

method_required('GET');

try {
    $pdo    = Database::connect();
    $where  = [];
    $params = [];

    // Public: default to active alerts only; admin can pass ?all=1
    if (empty($_GET['all'])) {
        $where[] = "da.status = 'Active'";
    }
    if (!empty($_GET['type'])) {
        $where[]  = 'alert_type = ?';
        $params[] = $_GET['type'];
    }

    $sql  = 'SELECT da.*, u.full_name AS issued_by_name FROM disaster_alerts da LEFT JOIN users u ON u.id = da.issued_by';
    $sql .= $where ? ' WHERE ' . implode(' AND ', $where) : '';
    $sql .= ' ORDER BY issued_at DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    success($stmt->fetchAll(), 'Alerts retrieved.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
