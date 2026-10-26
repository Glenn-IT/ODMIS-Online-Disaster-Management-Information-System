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

$is_landscape     = count($headers) > 6;
$header_pdf_path  = __DIR__ . '/../../docs/HEADER.pdf';
$use_pdf_template = (!$is_landscape && file_exists($header_pdf_path));

$seal_path    = str_replace('\\', '/', realpath(__DIR__ . '/../../img/seal_stonino.png'));
$mdrrmo_path  = str_replace('\\', '/', realpath(__DIR__ . '/../../img/logo_mdrrmo.jpg'));
$header_bg    = str_replace('\\', '/', realpath(__DIR__ . '/../../img/header_bg.png'));
$bp_path      = str_replace('\\', '/', realpath(__DIR__ . '/../../img/logo_bagong_pilipinas.png'));
$partner_path = str_replace('\\', '/', realpath(__DIR__ . '/../../img/logo_partner.png'));

$landscape_header = '';
$footer_content   = '';

if ($is_landscape) {
    $landscape_header = <<<HTML
<htmlpageheader name="ReportHeader">
  <div style="position:relative; width:100%; border-bottom:2px solid #283F24; padding-bottom:4px;">
    <div style="position:absolute; top:0; left:0; width:100%; height:32mm; z-index:-1;">
      <img src="{$header_bg}" style="width:100%; height:32mm; opacity:0.85;" />
    </div>
    <table style="width:100%; border-collapse:collapse;">
      <tr>
        <td style="width:60px; vertical-align:middle; text-align:center;">
          <img src="{$seal_path}" style="width:54px; height:54px;" />
        </td>
        <td style="vertical-align:middle; text-align:center; line-height:1.2;">
          <div style="font-size:9.5pt; color:#333;">Republic of the Philippines</div>
          <div style="font-size:9.5pt; color:#333;">Province of Cagayan</div>
          <div style="font-size:9.5pt; font-weight:bold; color:#111;">Municipality of Sto. Niño</div>
          <div style="font-size:11.5pt; font-weight:bold; color:#1c3d1c; letter-spacing:0.5px;">
            OFFICE OF THE MUNICIPAL DISASTER RISK REDUCTION AND MANAGEMENT
          </div>
        </td>
        <td style="width:75px; vertical-align:middle; text-align:center;">
          <img src="{$mdrrmo_path}" style="width:70px; height:42px;" />
        </td>
      </tr>
    </table>
  </div>
</htmlpageheader>
HTML;

    $footer_content = <<<HTML
<htmlpagefooter name="ReportFooter">
  <div style="width:100%; border-top:1px solid #ccc; padding-top:4px; font-size:7.5pt; color:#555;">
    <table style="width:100%; border-collapse:collapse;">
      <tr>
        <td style="width:120px; vertical-align:middle;">
          <img src="{$bp_path}" style="height:18px;" />
          &nbsp;
          <img src="{$partner_path}" style="height:18px;" />
        </td>
        <td style="vertical-align:middle; text-align:center;">
          Email: <strong>ldrrmosto.nino@gmail.com</strong> &nbsp;|&nbsp; Contact: <strong>0917 5448880</strong><br>
          <em>Online Disaster Management Information System (ODMIS) — Official Report</em>
        </td>
        <td style="width:70px; vertical-align:middle; text-align:right;">
          Page {PAGENO} of {nbpg}
        </td>
      </tr>
    </table>
  </div>
</htmlpagefooter>
HTML;
} else {
    // Portrait footer (header and footer graphic are drawn by HEADER.pdf template)
    $footer_content = <<<HTML
<htmlpagefooter name="ReportFooter">
  <div style="text-align:right; font-size:7.5pt; color:#666; padding-right:12mm; margin-bottom:5mm;">
    Page {PAGENO} of {nbpg}
  </div>
</htmlpagefooter>
HTML;
}

$page_rules = $is_landscape
    ? "@page { margin-top: 46mm; margin-bottom: 22mm; margin-left: 12mm; margin-right: 12mm; header: html_ReportHeader; footer: html_ReportFooter; }"
    : "@page { margin-top: 48mm; margin-bottom: 26mm; margin-left: 12mm; margin-right: 12mm; footer: html_ReportFooter; }";

$html = <<<HTML
<!DOCTYPE html>
<html>
<head>
<style>
  {$page_rules}
  body        { font-family: Arial, sans-serif; font-size: 9.5pt; color: #222; }
  .report-heading { text-align: center; margin-bottom: 12px; }
  .report-heading h2 { margin: 0; font-size: 13pt; color: #283F24; text-transform: uppercase; letter-spacing: 0.5px; }
  .report-heading h3 { margin: 3px 0 0; font-size: 10pt; color: #467235; font-weight: 600; }
  .meta       { font-size: 8.5pt; color: #555; margin-bottom: 8px; }
  .summary    { font-size: 8.5pt; font-weight: bold; color: #283F24; margin-bottom: 8px; background: #f0f7ee; padding: 4px 8px; border-left: 3px solid #467235; }
  table       { width: 100%; border-collapse: collapse; font-size: 8pt; }
  th          { background-color: #467235; color: #fff; padding: 5px 4px; text-align: left; font-weight: bold; }
  td          { padding: 4px; border-bottom: 1px solid #ddd; vertical-align: top; }
  tr:nth-child(even) td { background-color: #f5f9f4; }
</style>
</head>
<body>
{$landscape_header}
{$footer_content}

<div class="report-heading">
  <h2>ODMIS &mdash; {$title}</h2>
  <h3>Municipality of Santo Niño (Faire), Cagayan</h3>
</div>
<div class="meta">
  <strong>Generated:</strong> {$generated} &nbsp;&nbsp;
  {$filter_str}
</div>
<div class="summary">{$summary}</div>
<table>
  <thead><tr>{$th_html}</tr></thead>
  <tbody>{$tr_html}</tbody>
</table>
</body>
</html>
HTML;

// ── Generate PDF ──────────────────────────────────────────────
try {
    $orientation = $is_landscape ? 'L' : 'P';
    $marginTop   = $is_landscape ? 46 : 48;
    $marginBottom = $is_landscape ? 22 : 26;

    $mpdf = new \Mpdf\Mpdf([
        'margin_top'    => $marginTop,
        'margin_bottom' => $marginBottom,
        'margin_left'   => 12,
        'margin_right'  => 12,
        'orientation'   => $orientation,
    ]);

    if ($use_pdf_template) {
        $mpdf->SetDocTemplate($header_pdf_path, true);
    }

    $mpdf->SetTitle("ODMIS — $title");
    $mpdf->SetAuthor('MDRRMO ODMIS');
    $mpdf->WriteHTML($html);

    $filename = 'ODMIS_' . ucfirst($report) . '_' . date('Ymd_His') . '.pdf';
    $mpdf->Output($filename, 'D'); // D = force download
} catch (\Mpdf\MpdfException $e) {
    error('PDF generation failed: ' . $e->getMessage(), 500);
}
