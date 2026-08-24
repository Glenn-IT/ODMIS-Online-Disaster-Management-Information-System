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

    $status   = !empty($_GET['status']) ? $_GET['status'] : null;
    $barangay = !empty($_GET['barangay']) ? $_GET['barangay'] : null;
    $start    = !empty($_GET['date_from']) ? $_GET['date_from'] : (!empty($_GET['start']) ? $_GET['start'] : null);
    $end      = !empty($_GET['date_to']) ? $_GET['date_to'] : (!empty($_GET['end']) ? $_GET['end'] : null);

    if ($status)   { $where[] = 'LOWER(status) = LOWER(?)'; $params[] = $status; }
    if ($barangay) { $where[] = 'barangay = ?';             $params[] = $barangay; }
    if ($start)    { $where[] = 'DATE(created_at) >= ?';    $params[] = $start; }
    if ($end)      { $where[] = 'DATE(created_at) <= ?';    $params[] = $end; }

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
            'status'   => $status,
            'barangay' => $barangay,
            'start'    => $start,
            'end'      => $end,
        ]),
        'data' => $data,
    ], 'Evacuation center report data retrieved.');
} catch (PDOException $e) {
    error('Database error: ' . $e->getMessage(), 500);
}
