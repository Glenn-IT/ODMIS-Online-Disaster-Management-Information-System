<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('GET');
$token_user = require_auth();

$id = (int) ($_GET['id'] ?? 0);
if ($id < 1) error('Invalid relief operation ID.');

$official_barangays = [
    'Abariongan Ruar', 'Abariongan Uneg', 'Balagan', 'Balanni', 'Cabayo',
    'Calapangan', 'Calassitan', 'Campo', 'Centro Norte', 'Centro Sur',
    'Dungao', 'Lattac', 'Lipatan', 'Lubo', 'Mabitbitnong',
    'Masical', 'Matalao', 'Nag-uma', 'Namuccayan', 'Niug Norte',
    'Niug Sur', 'Palusao', 'Poblacion', 'San Manuel', 'San Roque',
    'Santa Felicitas', 'Santa Maria', 'Sidiran', 'Tabang', 'Tamucco', 'Virginia'
];

try {
    $pdo  = Database::connect();
    $stmt = $pdo->prepare('SELECT * FROM relief_operations WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $row  = $stmt->fetch();
} catch (PDOException $e) {
    error('Database error.', 500);
}

if (!$row) error('Relief operation not found.', 404);

// If resident, verify the operation belongs strictly to their registered barangay
if ($token_user->role === 'user') {
    $uStmt = $pdo->prepare('SELECT address FROM users WHERE id = ? LIMIT 1');
    $uStmt->execute([$token_user->sub]);
    $uRow = $uStmt->fetch();
    $residentAddress = $uRow['address'] ?? '';

    $userBarangay = null;
    foreach ($official_barangays as $b) {
        if (stripos($residentAddress, $b) !== false) {
            $userBarangay = $b;
            break;
        }
    }

    if (!$userBarangay || strcasecmp($row['barangay'], $userBarangay) !== 0) {
        error('Access denied. This relief operation is not in your registered barangay.', 403);
    }
}

success($row, 'Relief operation retrieved.');
