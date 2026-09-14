<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('GET');
require_admin();

try {
    $pdo = Database::connect();

    $where  = ['1=1'];
    $params = [];

    if (!empty($_GET['status'])) {
        $where[]  = 'l.status = ?';
        $params[] = $_GET['status'];
    }

    if (!empty($_GET['barangay'])) {
        $where[]  = 'l.barangay = ?';
        $params[] = $_GET['barangay'];
    }

    if (!empty($_GET['search'])) {
        $like     = '%' . $_GET['search'] . '%';
        $where[]  = '(l.recipient_phone LIKE ? OR l.message LIKE ? OR u.full_name LIKE ?)';
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
    }

    $limit  = isset($_GET['limit']) ? max(1, min(200, (int)$_GET['limit'])) : 50;
    $page   = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $offset = ($page - 1) * $limit;

    // Total count query
    $countSql = "SELECT COUNT(*) FROM sms_logs l LEFT JOIN users u ON l.recipient_user_id = u.id WHERE " . implode(' AND ', $where);
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $totalCount = (int)$countStmt->fetchColumn();

    // Data query
    $sql = "SELECT 
                l.id,
                l.recipient_phone,
                l.recipient_user_id,
                l.barangay,
                l.message,
                l.status,
                l.provider,
                l.provider_message_id,
                l.error_message,
                l.created_at,
                u.full_name AS recipient_name,
                admin.full_name AS sent_by_name
            FROM sms_logs l
            LEFT JOIN users u ON l.recipient_user_id = u.id
            LEFT JOIN users admin ON l.sent_by = admin.id
            WHERE " . implode(' AND ', $where) . "
            ORDER BY l.id DESC
            LIMIT $limit OFFSET $offset";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    success([
        'logs'        => $logs,
        'total'       => $totalCount,
        'page'        => $page,
        'limit'       => $limit,
        'total_pages' => ceil($totalCount / $limit)
    ], 'SMS logs retrieved.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
