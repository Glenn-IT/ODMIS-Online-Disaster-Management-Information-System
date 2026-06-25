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

    if (!empty($_GET['status']))   { $where[] = 'status = ?';   $params[] = $_GET['status']; }
    if (!empty($_GET['barangay'])) { $where[] = 'barangay = ?'; $params[] = $_GET['barangay']; }

    $sql  = 'SELECT *, (capacity - occupied_slots) AS available_slots FROM evacuation_centers';
    $sql .= $where ? ' WHERE ' . implode(' AND ', $where) : '';
    $sql .= ' ORDER BY center_name ASC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $data = $stmt->fetchAll();

    $total_capacity = array_sum(array_column($data, 'capacity'));
    $total_occupied = array_sum(array_column($data, 'occupied_slots'));

    success([
        'count'           => count($data),
        'total_capacity'  => $total_capacity,
        'total_occupied'  => $total_occupied,
        'total_available' => $total_capacity - $total_occupied,
        'filters'         => array_filter([
            'status'   => $_GET['status']   ?? null,
            'barangay' => $_GET['barangay'] ?? null,
        ]),
        'data' => $data,
    ], 'Evacuation center report data retrieved.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
