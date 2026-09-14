<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('POST');
$token_user = require_admin();

$body     = get_json_body();
$required = ['alert_type', 'title', 'severity'];
$missing  = array_filter($required, fn($f) => empty(trim((string)($body[$f] ?? ''))));
if ($missing) error('Missing required fields: ' . implode(', ', array_values($missing)), 400, array_values($missing));

$allowed_types      = ['Flood', 'Typhoon', 'Earthquake', 'Fire', 'Landslide'];
$allowed_severities = ['Low', 'Moderate', 'High', 'Critical'];

if (!in_array($body['alert_type'], $allowed_types, true))      error('Invalid alert type.');
if (!in_array($body['severity'],   $allowed_severities, true)) error('Invalid severity.');

try {
    $pdo  = Database::connect();
    $stmt = $pdo->prepare(
        'INSERT INTO disaster_alerts (alert_type, title, description, affected_areas, severity, status, issued_by, issued_at, expires_at)
         VALUES (?, ?, ?, ?, ?, \'Active\', ?, NOW(), ?)'
    );
    $stmt->execute([
        $body['alert_type'],
        sanitize($body['title']),
        sanitize($body['description'] ?? ''),
        sanitize($body['affected_areas'] ?? ''),
        $body['severity'],
        $token_user->sub,
        $body['expires_at'] ?? null,
    ]);

    $newId = (int) $pdo->lastInsertId();
    $new = $pdo->prepare('SELECT * FROM disaster_alerts WHERE id = ? LIMIT 1');
    $new->execute([$newId]);
    $alertRecord = $new->fetch();

    // Optional SMS Broadcast to affected residents
    if (!empty($body['broadcast_sms'])) {
        try {
            require_once __DIR__ . '/../../api/helpers/sms.php';
            $affected = trim((string)($body['affected_areas'] ?? ''));
            $alertMsg = "[ODMIS ALERT] " . strtoupper($body['severity']) . " - " . $body['alert_type'] . ": " . $body['title'] . ". Please stay alert and follow safety guidelines.";

            $recipSql = "SELECT id, contact_number, address FROM users WHERE role = 'user' AND status = 'active' AND contact_number IS NOT NULL AND contact_number != ''";
            $recipParams = [];
            if (!empty($affected)) {
                $areas = array_filter(array_map('trim', explode(',', $affected)));
                if (!empty($areas)) {
                    $clauses = [];
                    foreach ($areas as $a) {
                        $clauses[] = "address LIKE ?";
                        $recipParams[] = '%' . $a . '%';
                    }
                    $recipSql .= " AND (" . implode(' OR ', $clauses) . ")";
                }
            }
            $rStmt = $pdo->prepare($recipSql);
            $rStmt->execute($recipParams);
            $recips = $rStmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($recips)) {
                $smsSummary = sms_broadcast($recips, $alertMsg, (int)$token_user->sub, $affected ?: null);
                $alertRecord['sms_broadcast'] = $smsSummary;
            }
        } catch (Throwable $smsEx) {
            error_log('SMS Broadcast Error: ' . $smsEx->getMessage());
        }
    }

    success($alertRecord, 'Alert issued.', 201);
} catch (PDOException $e) {
    error('Database error.', 500);
}
