<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('GET');
require_auth();

try {
    $pdo    = Database::connect();
    $where  = [];
    $params = [];

    if (!empty($_GET['status'])) {
        $where[]  = 'status = ?';
        $params[] = $_GET['status'];
    }
    if (!empty($_GET['barangay'])) {
        $where[]  = 'barangay = ?';
        $params[] = $_GET['barangay'];
    }

    $sql  = 'SELECT *, (capacity - occupied_slots) AS available_slots FROM evacuation_centers';
    $sql .= $where ? ' WHERE ' . implode(' AND ', $where) : '';
    $sql .= ' ORDER BY center_name ASC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    success($stmt->fetchAll(), 'Evacuation centers retrieved.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
