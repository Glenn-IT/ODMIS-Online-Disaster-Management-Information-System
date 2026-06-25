<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('GET');
require_admin();

try {
    $pdo  = Database::connect();
    $stmt = $pdo->query(
        "SELECT barangay,
                COUNT(*) AS total,
                SUM(CASE WHEN status = 'Active'   THEN 1 ELSE 0 END) AS active,
                SUM(CASE WHEN status = 'Resolved' THEN 1 ELSE 0 END) AS resolved,
                SUM(CASE WHEN severity = 'Critical' THEN 1 ELSE 0 END) AS critical,
                SUM(CASE WHEN severity = 'High'     THEN 1 ELSE 0 END) AS high
         FROM incidents
         GROUP BY barangay
         ORDER BY total DESC"
    );
    $data = array_map(fn($r) => [
        'barangay' => $r['barangay'],
        'total'    => (int) $r['total'],
        'active'   => (int) $r['active'],
        'resolved' => (int) $r['resolved'],
        'critical' => (int) $r['critical'],
        'high'     => (int) $r['high'],
    ], $stmt->fetchAll());

    success([
        'data'   => $data,
        'labels' => array_column($data, 'barangay'),
        'values' => array_column($data, 'total'),
    ], 'Incidents by barangay retrieved.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
