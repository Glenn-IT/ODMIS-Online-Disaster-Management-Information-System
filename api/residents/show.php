<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('GET');
require_admin();

$id = (int) ($_GET['id'] ?? 0);
if ($id < 1) error('Invalid resident ID.');

try {
    $pdo  = Database::connect();
    $stmt = $pdo->prepare(
        "SELECT id, username, email, role, full_name, contact_number, date_of_birth, address, status, security_question, created_at
         FROM users WHERE id = ? AND role = 'user' LIMIT 1"
    );
    $stmt->execute([$id]);
    $row  = $stmt->fetch();
} catch (PDOException $e) {
    error('Database error.', 500);
}

if (!$row) error('Resident not found.', 404);

success($row, 'Resident retrieved.');
