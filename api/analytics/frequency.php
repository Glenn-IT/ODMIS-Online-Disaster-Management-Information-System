<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('GET');
require_admin();

// Optional range filter: ?start=2024-01-01&end=2025-12-31
$start = $_GET['start'] ?? null;
$end   = $_GET['end']   ?? null;

try {
    $pdo    = Database::connect();
    $where  = [];
    $params = [];

    if ($start) { $where[] = 'incident_date >= ?'; $params[] = $start; }
    if ($end)   { $where[] = 'incident_date <= ?'; $params[] = $end;   }

    $where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    // Monthly frequency per disaster type — feeds a multi-line or stacked Chart.js chart
    $stmt = $pdo->prepare(
        "SELECT DATE_FORMAT(incident_date, '%Y-%m') AS period,
                disaster_type,
                COUNT(*) AS total
         FROM incidents
         $where_sql
         GROUP BY period, disaster_type
         ORDER BY period ASC, disaster_type ASC"
    );
    $stmt->execute($params);
    $raw = $stmt->fetchAll();

    // Pivot: { period => { type => count } }
    $periods = [];
    $types   = ['Flood', 'Typhoon', 'Earthquake', 'Fire', 'Landslide'];

    foreach ($raw as $row) {
        $periods[$row['period']] ??= array_fill_keys($types, 0);
        $periods[$row['period']][$row['disaster_type']] = (int) $row['total'];
    }

    $data = [];
    foreach ($periods as $period => $counts) {
        $data[] = array_merge(['period' => $period], $counts);
    }

    // Also return series format for Chart.js datasets
    $series = [];
    foreach ($types as $type) {
        $series[] = [
            'label' => $type,
            'data'  => array_map(fn($d) => $d[$type], $data),
        ];
    }

    success([
        'labels' => array_column($data, 'period'),
        'data'   => $data,
        'series' => $series,
    ], 'Disaster frequency retrieved.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
