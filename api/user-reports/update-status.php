<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('PATCH');
$token_user = require_admin();

$id = (int) ($_GET['id'] ?? 0);
if ($id < 1) error('Invalid report ID.');

$body           = get_json_body();
$allowed        = ['Pending', 'Reviewed', 'Resolved'];
$status         = $body['status'] ?? '';

if (!in_array($status, $allowed, true)) error('Invalid status. Must be: Pending, Reviewed, or Resolved.');

try {
    $pdo  = Database::connect();
    $stmt = $pdo->prepare('UPDATE user_reports SET status = ?, reviewed_by = ? WHERE id = ?');
    $stmt->execute([$status, $token_user->sub, $id]);

    if ($stmt->rowCount() === 0) error('Report not found.', 404);

    $updated = $pdo->prepare('SELECT * FROM user_reports WHERE id = ? LIMIT 1');
    $updated->execute([$id]);

    $reportData = $updated->fetch();

    if (!empty($body['notify_resident'])) {
        try {
            require_once __DIR__ . '/../../api/helpers/sms.php';
            $uStmt = $pdo->prepare('SELECT contact_number, full_name, address FROM users WHERE id = ? LIMIT 1');
            $uStmt->execute([(int)$reportData['user_id']]);
            $resident = $uStmt->fetch(PDO::FETCH_ASSOC);

            if ($resident && !empty($resident['contact_number'])) {
                $statusMsg = "Hello " . ($resident['full_name'] ?? 'Resident') . ", your incident report (#" . $id . " - " . $reportData['incident_type'] . ") has been marked as " . $status . " by MDRRMO Santo Niño.";
                sms_send($resident['contact_number'], $statusMsg, (int)$reportData['user_id'], (int)$token_user->sub, $reportData['barangay'] ?? null);
            }
        } catch (Throwable $smsEx) {
            error_log('Resident Report SMS Notification Error: ' . $smsEx->getMessage());
        }
    }

    success($reportData, "Report marked as {$status}.");
} catch (PDOException $e) {
    error('Database error.', 500);
}
