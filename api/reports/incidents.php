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
    $type     = !empty($_GET['disaster_type']) ? $_GET['disaster_type'] : (!empty($_GET['type']) ? $_GET['type'] : null);
    $barangay = !empty($_GET['barangay']) ? $_GET['barangay'] : null;
    $status   = !empty($_GET['status']) ? $_GET['status'] : null;
    $severity = !empty($_GET['severity']) ? $_GET['severity'] : null;

    if ($start)    { $where[] = 'incident_date >= ?';       $params[] = $start; }
    if ($end)      { $where[] = 'incident_date <= ?';       $params[] = $end; }
    if ($type)     { $where[] = 'disaster_type = ?';        $params[] = $type; }
    if ($barangay) { $where[] = 'barangay = ?';             $params[] = $barangay; }
    if ($status)   { $where[] = 'LOWER(status) = LOWER(?)'; $params[] = $status; }
    if ($severity) { $where[] = 'severity = ?';             $params[] = $severity; }

    $sql  = 'SELECT * FROM incidents';
    $sql .= $where ? ' WHERE ' . implode(' AND ', $where) : '';
    $sql .= ' ORDER BY incident_date DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $data = $stmt->fetchAll();

    success([
        'count'   => count($data),
        'filters' => array_filter([
            'start'         => $start,
            'end'           => $end,
            'disaster_type' => $type,
            'barangay'      => $barangay,
            'status'        => $status,
            'severity'      => $severity,
        ]),
        'data' => $data,
    ], 'Incident report data retrieved.');
} catch (PDOException $e) {
    error('Database error: ' . $e->getMessage(), 500);
}
