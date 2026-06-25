<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('GET');
require_admin();

try {
    $pdo = Database::connect();

    $where  = [];
    $params = [];

    if (!empty($_GET['type'])) {
        $where[]  = 'disaster_type = ?';
        $params[] = $_GET['type'];
    }
    if (!empty($_GET['barangay'])) {
        $where[]  = 'barangay = ?';
        $params[] = $_GET['barangay'];
    }
    if (!empty($_GET['status'])) {
        $where[]  = 'status = ?';
        $params[] = $_GET['status'];
    }
    if (!empty($_GET['start'])) {
        $where[]  = 'incident_date >= ?';
        $params[] = $_GET['start'];
    }
    if (!empty($_GET['end'])) {
        $where[]  = 'incident_date <= ?';
        $params[] = $_GET['end'];
    }

    $sql  = 'SELECT * FROM incidents';
    $sql .= $where ? ' WHERE ' . implode(' AND ', $where) : '';
    $sql .= ' ORDER BY incident_date DESC, created_at DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    success($rows, 'Incidents retrieved.', 200);
} catch (PDOException $e) {
    error('Database error.', 500);
}
