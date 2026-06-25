<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('GET');
require_admin();

try {
    $pdo = Database::connect();

    $total_residents = (int) $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
    $total_reports   = (int) $pdo->query("SELECT COUNT(*) FROM incidents")->fetchColumn();
    $active_incidents   = (int) $pdo->query("SELECT COUNT(*) FROM incidents WHERE status = 'Active'")->fetchColumn();
    $resolved_incidents = (int) $pdo->query("SELECT COUNT(*) FROM incidents WHERE status = 'Resolved'")->fetchColumn();
    $active_alerts   = (int) $pdo->query("SELECT COUNT(*) FROM disaster_alerts WHERE status = 'Active'")->fetchColumn();
    $total_evac      = (int) $pdo->query("SELECT COUNT(*) FROM evacuation_centers WHERE status = 'Open'")->fetchColumn();
    $pending_reports = (int) $pdo->query("SELECT COUNT(*) FROM user_reports WHERE status = 'Pending'")->fetchColumn();
    $relief_ongoing  = (int) $pdo->query("SELECT COUNT(*) FROM relief_operations WHERE status = 'In Progress'")->fetchColumn();

    success([
        'total_residents'    => $total_residents,
        'total_reports'      => $total_reports,
        'active_incidents'   => $active_incidents,
        'resolved_incidents' => $resolved_incidents,
        'active_alerts'      => $active_alerts,
        'open_evac_centers'  => $total_evac,
        'pending_user_reports' => $pending_reports,
        'relief_in_progress' => $relief_ongoing,
    ], 'Summary retrieved.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
