<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('GET');
$token_user = require_auth();

$official_barangays = [
    'Abariongan Ruar', 'Abariongan Uneg', 'Balagan', 'Balanni', 'Cabayo',
    'Calapangan', 'Calassitan', 'Campo', 'Centro Norte', 'Centro Sur',
    'Dungao', 'Lattac', 'Lipatan', 'Lubo', 'Mabitbitnong',
    'Masical', 'Matalao', 'Nag-uma', 'Namuccayan', 'Niug Norte',
    'Niug Sur', 'Palusao', 'Poblacion', 'San Manuel', 'San Roque',
    'Santa Felicitas', 'Santa Maria', 'Sidiran', 'Tabang', 'Tamucco', 'Virginia'
];

try {
    $pdo    = Database::connect();
    $where  = [];
    $params = [];

    // Role-based scoping:
    // If the authenticated user is a resident ('user'), strictly constrain to their registered barangay.
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

        if ($userBarangay) {
            $where[]  = 'barangay = ?';
            $params[] = $userBarangay;
        } else {
            // Resident address has no registered barangay on file; do not expose other barangays
            $where[] = '1 = 0';
        }
    } else {
        // Admin user can filter by any requested barangay
        if (!empty($_GET['barangay'])) {
            $where[]  = 'barangay = ?';
            $params[] = $_GET['barangay'];
        }
    }

    if (!empty($_GET['status'])) {
        $where[]  = 'status = ?';
        $params[] = $_GET['status'];
    }
    if (!empty($_GET['start'])) {
        $where[]  = 'operation_date >= ?';
        $params[] = $_GET['start'];
    }
    if (!empty($_GET['end'])) {
        $where[]  = 'operation_date <= ?';
        $params[] = $_GET['end'];
    }

    $sql  = 'SELECT * FROM relief_operations';
    $sql .= $where ? ' WHERE ' . implode(' AND ', $where) : '';
    $sql .= ' ORDER BY operation_date DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    success($stmt->fetchAll(), 'Relief operations retrieved.');
} catch (PDOException $e) {
    error('Database error.', 500);
}
