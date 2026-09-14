<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/helpers/sms.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

$admin = require_admin();

$pdo = Database::connect();

// Support GET or POST preview_count
if ($_SERVER['REQUEST_METHOD'] === 'GET' || !empty($_GET['preview_count'])) {
    $target   = $_GET['target'] ?? 'all';
    $barangay = trim((string)($_GET['barangay'] ?? ''));

    $sql = "SELECT COUNT(*) as count FROM users WHERE role = 'user' AND status = 'active' AND contact_number IS NOT NULL AND contact_number != ''";
    $params = [];

    if ($target === 'barangay' && !empty($barangay)) {
        $sql .= " AND address LIKE ?";
        $params[] = '%' . $barangay . '%';
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $count = (int)$stmt->fetchColumn();

    success(['count' => $count], 'Recipient count retrieved.');
}

method_required('POST');
$body     = get_json_body();
$target   = $body['target'] ?? 'all'; // 'all' | 'barangay' | 'custom'
$barangay = trim((string)($body['barangay'] ?? ''));
$message  = trim((string)($body['message'] ?? ''));

if (empty($message)) {
    error('Message content is required.', 400);
}

$recipients = [];

if ($target === 'all') {
    $stmt = $pdo->prepare(
        "SELECT id, full_name, contact_number, address 
         FROM users 
         WHERE role = 'user' AND status = 'active' AND contact_number IS NOT NULL AND contact_number != ''"
    );
    $stmt->execute();
    $recipients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} elseif ($target === 'barangay') {
    if (empty($barangay)) {
        error('Barangay name is required when target is barangay.', 400);
    }
    $stmt = $pdo->prepare(
        "SELECT id, full_name, contact_number, address 
         FROM users 
         WHERE role = 'user' AND status = 'active' AND contact_number IS NOT NULL AND contact_number != '' 
           AND address LIKE ?"
    );
    $stmt->execute(['%' . $barangay . '%']);
    $recipients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} elseif ($target === 'custom') {
    $numbers = $body['custom_numbers'] ?? [];
    if (!is_array($numbers) || empty($numbers)) {
        error('Custom numbers list cannot be empty.', 400);
    }
    foreach ($numbers as $num) {
        $cleaned = trim((string)$num);
        if (!empty($cleaned)) {
            $recipients[] = [
                'id'             => null,
                'contact_number' => $cleaned,
                'barangay'       => $barangay ?: null
            ];
        }
    }
} else {
    error('Invalid broadcast target. Must be "all", "barangay", or "custom".', 400);
}

if (empty($recipients)) {
    error('No active registered recipients found for the selected criteria.', 404);
}

$summary = sms_broadcast($recipients, $message, (int)$admin->sub, $barangay ?: null);

success($summary, 'Broadcast completed: ' . $summary['sent'] . ' sent, ' . $summary['simulated'] . ' simulated, ' . $summary['failed'] . ' failed.');
