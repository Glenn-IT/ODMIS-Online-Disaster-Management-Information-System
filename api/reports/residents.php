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

    $start    = !empty($_GET['date_from']) ? $_GET['date_from'] : (!empty($_GET['start']) ? $_GET['start'] : null);
    $end      = !empty($_GET['date_to']) ? $_GET['date_to'] : (!empty($_GET['end']) ? $_GET['end'] : null);
    $status   = !empty($_GET['status']) ? $_GET['status'] : null;
    $barangay = !empty($_GET['barangay']) ? $_GET['barangay'] : null;

    if ($status)   { $where[] = 'LOWER(status) = LOWER(?)'; $params[] = $status; }
    if ($barangay) { $where[] = 'address LIKE ?';           $params[] = '%' . $barangay . '%'; }
    if ($start)    { $where[] = 'DATE(created_at) >= ?';    $params[] = $start; }
    if ($end)      { $where[] = 'DATE(created_at) <= ?';    $params[] = $end; }

    $sql  = 'SELECT id, username, full_name, email, contact_number, date_of_birth, address, status, created_at FROM users';
    $sql .= ' WHERE ' . implode(' AND ', $where);
    $sql .= ' ORDER BY full_name ASC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $data = $stmt->fetchAll();

    $active   = count(array_filter($data, fn($r) => strtolower($r['status'] ?? '') === 'active'));
    $inactive = count($data) - $active;

    success([
        'count'    => count($data),
        'active'   => $active,
        'inactive' => $inactive,
        'filters'  => array_filter([
            'status'   => $status,
            'barangay' => $barangay,
            'start'    => $start,
            'end'      => $end,
        ]),
        'data'     => $data,
    ], 'Resident report data retrieved.');
} catch (PDOException $e) {
    error('Database error: ' . $e->getMessage(), 500);
}
