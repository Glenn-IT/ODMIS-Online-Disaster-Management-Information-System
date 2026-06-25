<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('GET');
require_admin();

$year = (int) ($_GET['year'] ?? date('Y'));
if ($year < 2000 || $year > 2100) error('Invalid year.');

try {
    $pdo  = Database::connect();
    $stmt = $pdo->prepare(
        "SELECT MONTH(incident_date) AS month_num,
                MONTHNAME(incident_date) AS month_name,
                COUNT(*) AS total,
                SUM(CASE WHEN status = 'Active'   THEN 1 ELSE 0 END) AS active,
                SUM(CASE WHEN status = 'Resolved' THEN 1 ELSE 0 END) AS resolved
         FROM incidents
         WHERE YEAR(incident_date) = ?
         GROUP BY MONTH(incident_date), MONTHNAME(incident_date)
         ORDER BY month_num ASC"
    );
    $stmt->execute([$year]);
    $rows = $stmt->fetchAll();

    // Build a full 12-month scaffold so Chart.js always gets 12 data points
    $months = ['January','February','March','April','May','June',
               'July','August','September','October','November','December'];

    $indexed = [];
    foreach ($rows as $row) {
        $indexed[(int) $row['month_num']] = $row;
    }

    $data = [];
    foreach ($months as $i => $name) {
        $num    = $i + 1;
        $data[] = isset($indexed[$num]) ? [
            'month'    => $name,
            'total'    => (int) $indexed[$num]['total'],
            'active'   => (int) $indexed[$num]['active'],
            'resolved' => (int) $indexed[$num]['resolved'],
        ] : ['month' => $name, 'total' => 0, 'active' => 0, 'resolved' => 0];
    }

    success([
        'year'   => $year,
        'data'   => $data,
        'labels' => array_column($data, 'month'),
        'values' => array_column($data, 'total'),
    ], "Monthly incidents for $year retrieved.");
} catch (PDOException $e) {
    error('Database error.', 500);
}
