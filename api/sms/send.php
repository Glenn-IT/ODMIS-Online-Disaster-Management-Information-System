<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/helpers/sms.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('POST');
$admin = require_admin();

$body     = get_json_body();
$phone    = trim((string)($body['recipient'] ?? ($body['phone'] ?? '')));
$message  = trim((string)($body['message'] ?? ''));
$userId   = !empty($body['user_id']) ? (int)$body['user_id'] : null;
$barangay = !empty($body['barangay']) ? trim((string)$body['barangay']) : null;

if (empty($phone)) {
    error('Recipient phone number is required.', 400);
}
if (empty($message)) {
    error('Message content is required.', 400);
}

if (!sms_is_valid_ph_number($phone)) {
    error('Invalid Philippine mobile number format (must be 09XXXXXXXXX or +639XXXXXXXXX).', 422);
}

$result = sms_send($phone, $message, $userId, (int)$admin->sub, $barangay);

if ($result['success'] || $result['status'] === 'Simulated') {
    success($result, 'SMS processed: ' . $result['message'], 200);
} else {
    error('Failed to send SMS: ' . $result['message'], 502, $result);
}
