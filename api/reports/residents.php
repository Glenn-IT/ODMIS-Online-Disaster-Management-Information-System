<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('GET');
require_admin();

try {
    $pdo    = Database::connect();
    $where  = ["role = 'user'"];
    $params = [];

    if (!empty($_GET['status'])) { $where[] = 'status = ?'; $params[] = $_GET['status']; }

    $sql  = 'SELECT id, username, full_name, email, contact_number, date_of_birth, address, status, created_at FROM users';
    $sql .= ' WHERE ' . implode(' AND ', $where);
    $sql .= ' ORDER BY full_name ASC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $data = $stmt->fetchAll();

    $active   = count(array_filter($data, fn($r) => $r['status'] === 'active'));
    $inactive = count($data) - $active;

    success([
        'count'    => count($data),
        'active'   => $active,
        'inactive' => $inactive,
        'filters'  => array_filter(['status' => $_GET['status'] ?? null]),
        'data'     => $data,
    ], 'Resident report data retrieved.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
