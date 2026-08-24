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

    $start    = !empty($_GET['date_from']) ? $_GET['date_from'] : (!empty($_GET['start']) ? $_GET['start'] : null);
    $end      = !empty($_GET['date_to']) ? $_GET['date_to'] : (!empty($_GET['end']) ? $_GET['end'] : null);
    $barangay = !empty($_GET['barangay']) ? $_GET['barangay'] : null;
    $status   = !empty($_GET['status']) ? $_GET['status'] : null;

    if ($start)    { $where[] = 'operation_date >= ?';      $params[] = $start; }
    if ($end)      { $where[] = 'operation_date <= ?';      $params[] = $end; }
    if ($barangay) { $where[] = 'barangay = ?';             $params[] = $barangay; }
    if ($status)   { $where[] = 'LOWER(status) = LOWER(?)'; $params[] = $status; }

    $sql  = 'SELECT * FROM relief_operations';
    $sql .= $where ? ' WHERE ' . implode(' AND ', $where) : '';
    $sql .= ' ORDER BY operation_date DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $data = $stmt->fetchAll();

    $total_qty = array_sum(array_column($data, 'quantity'));

    success([
        'count'       => count($data),
        'total_items' => $total_qty,
        'filters'     => array_filter([
            'start'    => $start,
            'end'      => $end,
            'barangay' => $barangay,
            'status'   => $status,
        ]),
        'data' => $data,
    ], 'Relief report data retrieved.');
} catch (PDOException $e) {
    error('Database error: ' . $e->getMessage(), 500);
}
