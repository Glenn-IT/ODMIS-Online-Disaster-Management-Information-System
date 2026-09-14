<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/helpers/sms.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('GET');
require_admin();

$balanceData = sms_get_balance();

success([
    'enabled'           => defined('SMS_ENABLED') && SMS_ENABLED,
    'provider'          => defined('SMS_PROVIDER') ? SMS_PROVIDER : 'mock',
    'sender_id'         => defined('PHILSMS_SENDER_ID') ? PHILSMS_SENDER_ID : 'PhilSMS',
    'remaining_balance' => $balanceData['remaining_balance'] ?? 'N/A',
    'expired_on'        => $balanceData['expired_on'] ?? 'N/A',
    'is_live'           => ($balanceData['provider'] ?? '') === 'philsms' && ($balanceData['success'] ?? false),
    'message'           => $balanceData['message'] ?? null
], 'SMS balance retrieved.');
