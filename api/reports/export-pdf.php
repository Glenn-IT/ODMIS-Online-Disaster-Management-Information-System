<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/env.php';
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

// ── Fetch data based on report type ──────────────────────────
$title   = '';
$headers = [];
$rows    = [];
$summary = '';

$start    = !empty($_GET['date_from']) ? $_GET['date_from'] : (!empty($_GET['start']) ? $_GET['start'] : null);
$end      = !empty($_GET['date_to']) ? $_GET['date_to'] : (!empty($_GET['end']) ? $_GET['end'] : null);
$type     = !empty($_GET['disaster_type']) ? $_GET['disaster_type'] : (!empty($_GET['type']) ? $_GET['type'] : null);
$barangay = !empty($_GET['barangay']) ? $_GET['barangay'] : null;
$status   = !empty($_GET['status']) ? $_GET['status'] : null;
$severity = !empty($_GET['severity']) ? $_GET['severity'] : null;

switch ($report) {
    case 'incidents':
        $title = 'Disaster Incident Report';
        $where  = [];
        $params = [];
        if ($start)    { $where[] = 'incident_date >= ?';       $params[] = $start; }
        if ($end)      { $where[] = 'incident_date <= ?';       $params[] = $end; }
        if ($type)     { $where[] = 'disaster_type = ?';        $params[] = $type; }
        if ($barangay) { $where[] = 'barangay = ?';             $params[] = $barangay; }
        if ($status)   { $where[] = 'LOWER(status) = LOWER(?)'; $params[] = $status; }
        if ($severity) { $where[] = 'severity = ?';             $params[] = $severity; }

        $sql  = 'SELECT incident_code, disaster_type, title, barangay, incident_date, incident_time, severity, status, reported_by FROM incidents';
        $sql .= $where ? ' WHERE ' . implode(' AND ', $where) : '';
        $sql .= ' ORDER BY incident_date DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $data    = $stmt->fetchAll();
        $headers = ['Code', 'Type', 'Title', 'Barangay', 'Date', 'Time', 'Severity', 'Status', 'Reported By'];
        foreach ($data as $r) {
            $rows[] = [$r['incident_code'], $r['disaster_type'], $r['title'], $r['barangay'],
                       $r['incident_date'], $r['incident_time'] ?? '—', $r['severity'], $r['status'], $r['reported_by']];
        }
        $summary = 'Total Records: ' . count($data);
        break;

    case 'residents':
        $title   = 'Registered Residents Report';
        $where   = ["role = 'user'"];
        $params  = [];
        if ($status)   { $where[] = 'LOWER(status) = LOWER(?)'; $params[] = $status; }
        if ($barangay) { $where[] = 'address LIKE ?';           $params[] = '%' . $barangay . '%'; }
        if ($start)    { $where[] = 'DATE(created_at) >= ?';    $params[] = $start; }
        if ($end)      { $where[] = 'DATE(created_at) <= ?';    $params[] = $end; }
        $stmt = $pdo->prepare('SELECT full_name, username, email, contact_number, address, status, created_at FROM users WHERE ' . implode(' AND ', $where) . ' ORDER BY full_name ASC');
        $stmt->execute($params);
        $data    = $stmt->fetchAll();
        $headers = ['Full Name', 'Username', 'Email', 'Contact No.', 'Address', 'Status', 'Registered'];
        foreach ($data as $r) {
            $rows[] = [$r['full_name'], $r['username'], $r['email'], $r['contact_number'],
                       $r['address'], ucfirst($r['status']), date('Y-m-d', strtotime($r['created_at']))];
        }
        $active  = count(array_filter($data, fn($r) => strtolower($r['status'] ?? '') === 'active'));
        $summary = "Total: " . count($data) . "  |  Active: $active  |  Inactive: " . (count($data) - $active);
        break;

    case 'relief':
        $title  = 'Relief Operations Report';
        $where  = [];
        $params = [];
        if ($start)    { $where[] = 'operation_date >= ?';      $params[] = $start; }
        if ($end)      { $where[] = 'operation_date <= ?';      $params[] = $end; }
        if ($barangay) { $where[] = 'barangay = ?';             $params[] = $barangay; }
        if ($status)   { $where[] = 'LOWER(status) = LOWER(?)'; $params[] = $status; }
        $sql  = 'SELECT * FROM relief_operations';
        $sql .= $where ? ' WHERE ' . implode(' AND ', $where) : '';
        $sql .= ' ORDER BY operation_date DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $data    = $stmt->fetchAll();
        $headers = ['Batch No.', 'Date', 'Barangay', 'Relief Type', 'Qty', 'Unit', 'Status', 'Distributed By'];
        foreach ($data as $r) {
            $rows[] = [$r['batch_number'], $r['operation_date'], $r['barangay'],
                       $r['relief_type'], $r['quantity'], $r['unit'], $r['status'], $r['distributed_by']];
        }
        $summary = 'Total Batches: ' . count($data) . '  |  Total Items: ' . array_sum(array_column($data, 'quantity'));
        break;

    case 'evacuation':
        $title   = 'Evacuation Centers Report';
        $where   = [];
        $params  = [];
        if ($status)   { $where[] = 'LOWER(status) = LOWER(?)'; $params[] = $status; }
        if ($barangay) { $where[] = 'barangay = ?';             $params[] = $barangay; }
        if ($start)    { $where[] = 'DATE(created_at) >= ?';    $params[] = $start; }
        if ($end)      { $where[] = 'DATE(created_at) <= ?';    $params[] = $end; }
        $sql  = 'SELECT *, (capacity - occupied_slots) AS available_slots FROM evacuation_centers';
        $sql .= $where ? ' WHERE ' . implode(' AND ', $where) : '';
        $sql .= ' ORDER BY center_name ASC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $data    = $stmt->fetchAll();
        $headers = ['Center Name', 'Barangay', 'Capacity', 'Occupied', 'Available', 'Contact Person', 'Contact No.', 'Status'];
        foreach ($data as $r) {
            $rows[] = [$r['center_name'], $r['barangay'], $r['capacity'],
                       $r['occupied_slots'], $r['available_slots'],
                       $r['contact_person'], $r['contact_number'], $r['status']];
        }
        $summary = 'Centers: ' . count($data) . '  |  Total Capacity: ' . array_sum(array_column($data, 'capacity'))
                 . '  |  Total Occupied: ' . array_sum(array_column($data, 'occupied_slots'));
        break;
}

// ── Build HTML for mPDF ───────────────────────────────────────
$generated = date('F d, Y  h:i A');
$filter_str = '';
$filter_items = [
    'Date From'     => $start,
    'Date To'       => $end,
    'Disaster Type' => $type,
    'Barangay'      => $barangay,
    'Status'        => $status,
    'Severity'      => $severity,
];
foreach ($filter_items as $k => $v) {
    if (!empty($v)) $filter_str .= $k . ': ' . htmlspecialchars($v) . '&nbsp;&nbsp;';
}

$th_html = '';
foreach ($headers as $h) $th_html .= "<th>$h</th>";

$tr_html = '';
foreach ($rows as $row) {
    $tr_html .= '<tr>';
    foreach ($row as $cell) $tr_html .= '<td>' . htmlspecialchars((string)$cell) . '</td>';
    $tr_html .= '</tr>';
}

$html = <<<HTML
<!DOCTYPE html>
<html>
<head>
<style>
  body        { font-family: Arial, sans-serif; font-size: 10pt; color: #222; }
  .header     { text-align: center; margin-bottom: 16px; border-bottom: 2px solid #467235; padding-bottom: 10px; }
  .header h2  { margin: 0; font-size: 14pt; color: #283F24; }
  .header h3  { margin: 4px 0 0; font-size: 11pt; color: #467235; }
  .header p   { margin: 2px 0; font-size: 9pt; color: #555; }
  .meta       { font-size: 9pt; color: #555; margin-bottom: 10px; }
  .summary    { font-size: 9pt; font-weight: bold; color: #283F24; margin-bottom: 8px; }
  table       { width: 100%; border-collapse: collapse; font-size: 8.5pt; }
  th          { background-color: #467235; color: #fff; padding: 5px 4px; text-align: left; }
  td          { padding: 4px; border-bottom: 1px solid #ddd; vertical-align: top; }
  tr:nth-child(even) td { background-color: #f5f9f4; }
  .footer     { text-align: center; font-size: 8pt; color: #888; margin-top: 16px; }
</style>
</head>
<body>
<div class="header">
  <h2>Municipal Disaster Risk Reduction &amp; Management Office</h2>
  <h3>ODMIS — $title</h3>
  <p>Santo Niño (Faire), Cagayan, Philippines</p>
</div>
<div class="meta">
  <strong>Generated:</strong> $generated &nbsp;&nbsp;
  $filter_str
</div>
<div class="summary">$summary</div>
<table>
  <thead><tr>$th_html</tr></thead>
  <tbody>$tr_html</tbody>
</table>
<div class="footer">
  This report was generated by the Online Disaster Management Information System (ODMIS).<br>
  For official use only — MDRRMO, Municipal Government of Santo Niño (Faire).
</div>
</body>
</html>
HTML;

// ── Generate PDF ──────────────────────────────────────────────
try {
    $mpdf = new \Mpdf\Mpdf([
        'margin_top'    => 10,
        'margin_bottom' => 15,
        'margin_left'   => 12,
        'margin_right'  => 12,
        'orientation'   => count($headers) > 6 ? 'L' : 'P',
    ]);
    $mpdf->SetTitle("ODMIS — $title");
    $mpdf->SetAuthor('MDRRMO ODMIS');
    $mpdf->WriteHTML($html);

    $filename = 'ODMIS_' . ucfirst($report) . '_' . date('Ymd_His') . '.pdf';
    $mpdf->Output($filename, 'D'); // D = force download
} catch (\Mpdf\MpdfException $e) {
    error('PDF generation failed: ' . $e->getMessage(), 500);
}
