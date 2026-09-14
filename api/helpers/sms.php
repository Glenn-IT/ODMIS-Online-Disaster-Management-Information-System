<?php
/**
 * ODMIS SMS Service Helper
 * Integration with PhilSMS API v3 and Mock/Simulation fallback.
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/env.php';

if (!defined('SMS_ENABLED')) {
    define('SMS_ENABLED', false);
}
if (!defined('SMS_PROVIDER')) {
    define('SMS_PROVIDER', 'mock');
}
if (!defined('PHILSMS_API_URL')) {
    define('PHILSMS_API_URL', 'https://dashboard.philsms.com/api/v3');
}
if (!defined('PHILSMS_API_TOKEN')) {
    define('PHILSMS_API_TOKEN', '');
}
if (!defined('PHILSMS_SENDER_ID')) {
    define('PHILSMS_SENDER_ID', 'PhilSMS');
}

/**
 * Format Philippine mobile numbers into canonical format: 09XXXXXXXXX
 */
function sms_format_phone(string $phone): string {
    $cleaned = preg_replace('/[^0-9]/', '', $phone);
    if (str_starts_with($cleaned, '639') && strlen($cleaned) === 12) {
        return '0' . substr($cleaned, 2);
    }
    if (str_starts_with($cleaned, '9') && strlen($cleaned) === 10) {
        return '0' . $cleaned;
    }
    return $cleaned;
}

/**
 * Validate Philippine mobile number (09XXXXXXXXX)
 */
function sms_is_valid_ph_number(string $phone): bool {
    $formatted = sms_format_phone($phone);
    return (bool) preg_match('/^09\d{9}$/', $formatted);
}

/**
 * Format Philippine mobile number to international format 639XXXXXXXXX (required by PhilSMS)
 */
function sms_format_international(string $phone): string {
    $canonical = sms_format_phone($phone);
    if (str_starts_with($canonical, '09') && strlen($canonical) === 11) {
        return '63' . substr($canonical, 1);
    }
    return $canonical;
}

/**
 * Check PhilSMS account balance
 * @return array ['success' => bool, 'balance' => string, 'message' => string]
 */
function sms_get_balance(): array {
    if (!defined('SMS_ENABLED') || !SMS_ENABLED || SMS_PROVIDER === 'mock') {
        return [
            'success' => true,
            'provider' => 'mock',
            'remaining_balance' => 'Simulation Mode (Unlimited)',
            'expired_on' => 'N/A'
        ];
    }

    if (empty(PHILSMS_API_TOKEN)) {
        return [
            'success' => false,
            'provider' => 'philsms',
            'remaining_balance' => 'N/A',
            'message' => 'PHILSMS_API_TOKEN is not configured.'
        ];
    }

    $ch = curl_init(rtrim(PHILSMS_API_URL, '/') . '/balance');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . PHILSMS_API_TOKEN,
            'Accept: application/json'
        ]
    ]);

    $raw = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr = curl_error($ch);
    curl_close($ch);

    if ($curlErr) {
        return [
            'success' => false,
            'provider' => 'philsms',
            'remaining_balance' => 'N/A',
            'message' => 'Network error: ' . $curlErr
        ];
    }

    $res = json_decode($raw, true);
    if ($httpCode === 200 && is_array($res) && ($res['status'] ?? '') === 'success') {
        return [
            'success'           => true,
            'provider'          => 'philsms',
            'remaining_balance' => $res['data']['remaining_balance'] ?? 'Unknown',
            'expired_on'        => $res['data']['expired_on'] ?? 'N/A'
        ];
    }

    $errMsg = $res['message'] ?? ('HTTP ' . $httpCode . ' response from PhilSMS');
    return [
        'success'           => false,
        'provider'          => 'philsms',
        'remaining_balance' => 'N/A',
        'message'           => $errMsg
    ];
}

/**
 * Send an SMS to a single recipient and record it in sms_logs
 *
 * @param string $phone
 * @param string $message
 * @param int|null $userId
 * @param int|null $sentBy
 * @param string|null $barangay
 * @return array ['success' => bool, 'status' => string, 'log_id' => int, 'message' => string]
 */
function sms_send(string $phone, string $message, ?int $userId = null, ?int $sentBy = null, ?string $barangay = null): array {
    $formattedPhone = sms_format_phone($phone);
    $pdo = Database::connect();

    if (!sms_is_valid_ph_number($formattedPhone)) {
        // Log failure due to invalid phone number format
        $stmt = $pdo->prepare(
            'INSERT INTO sms_logs (recipient_phone, recipient_user_id, barangay, message, status, provider, error_message, sent_by, created_at)
             VALUES (?, ?, ?, ?, \'Failed\', ?, ?, ?, NOW())'
        );
        $stmt->execute([
            $formattedPhone ?: $phone,
            $userId,
            $barangay,
            $message,
            SMS_PROVIDER,
            'Invalid Philippine mobile number format (must be 09XXXXXXXXX).',
            $sentBy
        ]);

        return [
            'success' => false,
            'status'  => 'Failed',
            'log_id'  => (int) $pdo->lastInsertId(),
            'message' => 'Invalid mobile number format: ' . $phone
        ];
    }

    // 1. Simulation / Mock Mode
    if (!SMS_ENABLED || SMS_PROVIDER === 'mock') {
        $stmt = $pdo->prepare(
            'INSERT INTO sms_logs (recipient_phone, recipient_user_id, barangay, message, status, provider, provider_message_id, sent_by, created_at)
             VALUES (?, ?, ?, ?, \'Simulated\', \'Mock\', ?, ?, NOW())'
        );
        $mockId = 'SIM-' . strtoupper(bin2hex(random_bytes(4)));
        $stmt->execute([
            $formattedPhone,
            $userId,
            $barangay,
            $message,
            $mockId,
            $sentBy
        ]);

        return [
            'success' => true,
            'status'  => 'Simulated',
            'log_id'  => (int) $pdo->lastInsertId(),
            'message' => 'SMS simulated successfully (Simulation Mode).'
        ];
    }

    // 2. PhilSMS Gateway Integration
    $payload = [
        'recipient' => sms_format_international($formattedPhone),
        'sender_id' => PHILSMS_SENDER_ID,
        'type'      => 'plain',
        'message'   => $message
    ];

    $ch = curl_init(rtrim(PHILSMS_API_URL, '/') . '/sms/send');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . PHILSMS_API_TOKEN,
            'Accept: application/json',
            'Content-Type: application/json'
        ]
    ]);

    $raw = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr = curl_error($ch);
    curl_close($ch);

    $status = 'Failed';
    $providerMsgId = null;
    $errorMessage = null;

    if ($curlErr) {
        $errorMessage = 'cURL Error: ' . $curlErr;
    } else {
        $res = json_decode($raw, true);
        if (is_array($res) && ($res['status'] ?? '') === 'success') {
            $status = 'Sent';
            $providerMsgId = $res['data']['uid'] ?? ($res['data']['id'] ?? null);
        } else {
            $errorMessage = $res['message'] ?? ('HTTP ' . $httpCode . ': ' . $raw);
        }
    }

    // Record in database
    $stmt = $pdo->prepare(
        'INSERT INTO sms_logs (recipient_phone, recipient_user_id, barangay, message, status, provider, provider_message_id, error_message, sent_by, created_at)
         VALUES (?, ?, ?, ?, ?, \'PhilSMS\', ?, ?, ?, NOW())'
    );
    $stmt->execute([
        $formattedPhone,
        $userId,
        $barangay,
        $message,
        $status,
        $providerMsgId,
        $errorMessage,
        $sentBy
    ]);
    $logId = (int) $pdo->lastInsertId();

    return [
        'success'             => ($status === 'Sent'),
        'status'              => $status,
        'log_id'              => $logId,
        'provider_message_id' => $providerMsgId,
        'message'             => ($status === 'Sent') ? 'SMS dispatched successfully.' : ($errorMessage ?? 'Transmission failed.')
    ];
}

/**
 * Broadcast an SMS to an array of recipients
 * Each recipient can be an associative array with keys: 'phone', 'user_id', 'barangay'
 * or a plain phone string.
 *
 * @param array $recipients
 * @param string $message
 * @param int|null $sentBy
 * @param string|null $defaultBarangay
 * @return array Summary of dispatch results
 */
function sms_broadcast(array $recipients, string $message, ?int $sentBy = null, ?string $defaultBarangay = null): array {
    $total     = count($recipients);
    $sent      = 0;
    $failed    = 0;
    $simulated = 0;
    $logs      = [];

    foreach ($recipients as $recipient) {
        if (is_array($recipient)) {
            $phone    = $recipient['phone'] ?? ($recipient['contact_number'] ?? '');
            $userId   = isset($recipient['id']) ? (int)$recipient['id'] : (isset($recipient['user_id']) ? (int)$recipient['user_id'] : null);
            $barangay = $recipient['barangay'] ?? $defaultBarangay;
        } else {
            $phone    = (string) $recipient;
            $userId   = null;
            $barangay = $defaultBarangay;
        }

        if (empty(trim($phone))) {
            continue;
        }

        $result = sms_send($phone, $message, $userId, $sentBy, $barangay);
        $logs[] = $result;

        if ($result['status'] === 'Sent') {
            $sent++;
        } elseif ($result['status'] === 'Simulated') {
            $simulated++;
        } else {
            $failed++;
        }
    }

    return [
        'total'     => $total,
        'sent'      => $sent,
        'simulated' => $simulated,
        'failed'    => $failed,
        'logs'      => $logs
    ];
}
