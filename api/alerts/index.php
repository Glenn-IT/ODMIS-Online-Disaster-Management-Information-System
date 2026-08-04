<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';

method_required('GET');

try {
    $pdo    = Database::connect();
    $where  = [];
    $params = [];

    // Public: default to active only; admin can pass ?all=1
    if (empty($_GET['all'])) {
        $where[] = "status = 'Active'";
    }
    if (!empty($_GET['type'])) {
        $where[]  = 'alert_type = ?';
        $params[] = $_GET['type'];
    }

    // Residents see one combined feed: alerts issued by DRRM plus the
    // disaster incidents logged by admins. Both are normalised to the
    // disaster_alerts column names so the UI reads a single shape.
    $union = "
        SELECT da.id,
               'alert'                AS source,
               CONCAT('alert-', da.id) AS uid,
               da.alert_type,
               da.title,
               da.description,
               da.affected_areas,
               da.severity,
               da.status,
               da.issued_at,
               da.created_at,
               u.full_name            AS issued_by_name
          FROM disaster_alerts da
          LEFT JOIN users u ON u.id = da.issued_by

        UNION ALL

        SELECT i.id,
               'incident'                 AS source,
               CONCAT('incident-', i.id)  AS uid,
               i.disaster_type            AS alert_type,
               i.title,
               i.description,
               CONCAT_WS(', ', NULLIF(i.location, ''), NULLIF(i.barangay, '')) AS affected_areas,
               i.severity,
               i.status,
               TIMESTAMP(i.incident_date, COALESCE(NULLIF(i.incident_time, ''), '00:00:00')) AS issued_at,
               i.created_at,
               NULLIF(i.reported_by, '')  AS issued_by_name
          FROM incidents i
    ";

    $sql  = 'SELECT * FROM (' . $union . ') AS merged';
    $sql .= $where ? ' WHERE ' . implode(' AND ', $where) : '';
    $sql .= ' ORDER BY issued_at DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    success($stmt->fetchAll(), 'Alerts retrieved.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
