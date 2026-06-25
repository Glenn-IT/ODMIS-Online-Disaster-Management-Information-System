<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('GET');
require_admin();

$report = $_GET['report'] ?? '';
$allowed_reports = ['incidents', 'residents', 'relief', 'evacuation'];
if (!in_array($report, $allowed_reports, true)) {
    error('Invalid report type. Use: incidents, residents, relief, evacuation.');
}

try {
    $pdo = Database::connect();
} catch (PDOException $e) {
    error('Database error.', 500);
}

$headers = [];
$rows    = [];

switch ($report) {
    case 'incidents':
        $where  = [];
        $params = [];
        if (!empty($_GET['start']))    { $where[] = 'incident_date >= ?'; $params[] = $_GET['start']; }
        if (!empty($_GET['end']))      { $where[] = 'incident_date <= ?'; $params[] = $_GET['end']; }
        if (!empty($_GET['type']))     { $where[] = 'disaster_type = ?';  $params[] = $_GET['type']; }
        if (!empty($_GET['barangay'])) { $where[] = 'barangay = ?';       $params[] = $_GET['barangay']; }
        if (!empty($_GET['status']))   { $where[] = 'status = ?';         $params[] = $_GET['status']; }

        $sql  = 'SELECT incident_code, disaster_type, title, description, location, barangay, municipality, incident_date, incident_time, severity, status, reported_by, created_at FROM incidents';
        $sql .= $where ? ' WHERE ' . implode(' AND ', $where) : '';
        $sql .= ' ORDER BY incident_date DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();
        $headers = ['Incident Code', 'Disaster Type', 'Title', 'Description', 'Location', 'Barangay', 'Municipality', 'Date', 'Time', 'Severity', 'Status', 'Reported By', 'Created At'];
        foreach ($data as $r) {
            $rows[] = [$r['incident_code'], $r['disaster_type'], $r['title'], $r['description'],
                       $r['location'], $r['barangay'], $r['municipality'],
                       $r['incident_date'], $r['incident_time'], $r['severity'], $r['status'],
                       $r['reported_by'], $r['created_at']];
        }
        break;

    case 'residents':
        $where  = ["role = 'user'"];
        $params = [];
        if (!empty($_GET['status'])) { $where[] = 'status = ?'; $params[] = $_GET['status']; }
        $stmt = $pdo->prepare('SELECT full_name, username, email, contact_number, date_of_birth, address, status, created_at FROM users WHERE ' . implode(' AND ', $where) . ' ORDER BY full_name ASC');
        $stmt->execute($params);
        $data = $stmt->fetchAll();
        $headers = ['Full Name', 'Username', 'Email', 'Contact Number', 'Date of Birth', 'Address', 'Status', 'Registered At'];
        foreach ($data as $r) {
            $rows[] = [$r['full_name'], $r['username'], $r['email'], $r['contact_number'],
                       $r['date_of_birth'], $r['address'], $r['status'], $r['created_at']];
        }
        break;

    case 'relief':
        $where  = [];
        $params = [];
        if (!empty($_GET['start']))    { $where[] = 'operation_date >= ?'; $params[] = $_GET['start']; }
        if (!empty($_GET['end']))      { $where[] = 'operation_date <= ?'; $params[] = $_GET['end']; }
        if (!empty($_GET['barangay'])) { $where[] = 'barangay = ?';        $params[] = $_GET['barangay']; }
        if (!empty($_GET['status']))   { $where[] = 'status = ?';          $params[] = $_GET['status']; }
        $sql  = 'SELECT batch_number, operation_date, barangay, relief_type, quantity, unit, status, distributed_by, notes, created_at FROM relief_operations';
        $sql .= $where ? ' WHERE ' . implode(' AND ', $where) : '';
        $sql .= ' ORDER BY operation_date DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();
        $headers = ['Batch Number', 'Date', 'Barangay', 'Relief Type', 'Quantity', 'Unit', 'Status', 'Distributed By', 'Notes', 'Created At'];
        foreach ($data as $r) {
            $rows[] = [$r['batch_number'], $r['operation_date'], $r['barangay'],
                       $r['relief_type'], $r['quantity'], $r['unit'],
                       $r['status'], $r['distributed_by'], $r['notes'], $r['created_at']];
        }
        break;

    case 'evacuation':
        $where  = [];
        $params = [];
        if (!empty($_GET['status']))   { $where[] = 'status = ?';   $params[] = $_GET['status']; }
        if (!empty($_GET['barangay'])) { $where[] = 'barangay = ?'; $params[] = $_GET['barangay']; }
        $sql  = 'SELECT center_code, center_name, location, barangay, capacity, occupied_slots, (capacity - occupied_slots) AS available_slots, contact_person, contact_number, status, created_at FROM evacuation_centers';
        $sql .= $where ? ' WHERE ' . implode(' AND ', $where) : '';
        $sql .= ' ORDER BY center_name ASC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();
        $headers = ['Center Code', 'Center Name', 'Location', 'Barangay', 'Capacity', 'Occupied', 'Available', 'Contact Person', 'Contact No.', 'Status', 'Created At'];
        foreach ($data as $r) {
            $rows[] = [$r['center_code'], $r['center_name'], $r['location'], $r['barangay'],
                       $r['capacity'], $r['occupied_slots'], $r['available_slots'],
                       $r['contact_person'], $r['contact_number'], $r['status'], $r['created_at']];
        }
        break;
}

// ── Stream CSV ────────────────────────────────────────────────
$filename = 'ODMIS_' . ucfirst($report) . '_' . date('Ymd_His') . '.csv';

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');

// UTF-8 BOM so Excel opens it correctly
echo "\xEF\xBB\xBF";

$out = fopen('php://output', 'w');

// Meta header rows
fputcsv($out, ['ODMIS — ' . ucfirst($report) . ' Report']);
fputcsv($out, ['Generated:', date('Y-m-d H:i:s')]);
fputcsv($out, ['MDRRMO — Santo Niño (Faire), Cagayan']);
fputcsv($out, []);

// Column headers
fputcsv($out, $headers);

// Data rows
foreach ($rows as $row) {
    fputcsv($out, $row);
}

fclose($out);
exit;
