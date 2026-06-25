<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('GET');
require_admin();

try {
    $pdo = Database::connect();

    // All disaster types seeded — ensure every type appears even with 0 count
    $types = ['Flood', 'Typhoon', 'Earthquake', 'Fire', 'Landslide'];

    $stmt = $pdo->query(
        "SELECT disaster_type, COUNT(*) AS total,
                SUM(CASE WHEN status = 'Active'   THEN 1 ELSE 0 END) AS active,
                SUM(CASE WHEN status = 'Resolved' THEN 1 ELSE 0 END) AS resolved
         FROM incidents
         GROUP BY disaster_type"
    );
    $rows = $stmt->fetchAll();

    // Index by type so we can fill zeroes
    $indexed = [];
    foreach ($rows as $row) {
        $indexed[$row['disaster_type']] = [
            'type'     => $row['disaster_type'],
            'total'    => (int) $row['total'],
            'active'   => (int) $row['active'],
            'resolved' => (int) $row['resolved'],
        ];
    }

    $data = [];
    foreach ($types as $type) {
        $data[] = $indexed[$type] ?? ['type' => $type, 'total' => 0, 'active' => 0, 'resolved' => 0];
    }

    // Also include chart-ready labels and values arrays
    success([
        'data'   => $data,
        'labels' => array_column($data, 'type'),
        'values' => array_column($data, 'total'),
    ], 'Incidents by type retrieved.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
