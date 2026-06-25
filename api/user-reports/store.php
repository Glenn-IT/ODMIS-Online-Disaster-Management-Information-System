<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('POST');
$token_user = require_auth();

// Supports both JSON (no photo) and multipart/form-data (with photo)
$is_multipart = str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'multipart/form-data');
$body = $is_multipart ? $_POST : get_json_body();

$required = ['incident_type', 'description', 'location', 'report_date'];
$missing  = array_filter($required, fn($f) => empty(trim($body[$f] ?? '')));
if ($missing) error('Missing required fields.', 400, array_values($missing));

$allowed_types = ['Flood', 'Typhoon', 'Earthquake', 'Fire', 'Landslide', 'Other'];
if (!in_array($body['incident_type'], $allowed_types, true)) error('Invalid incident type.');

// ── Handle optional photo upload ─────────────────────────────
$photo_path = null;
if ($is_multipart && !empty($_FILES['photo']['name'])) {
    $file = $_FILES['photo'];

    if ($file['size'] > UPLOAD_MAX_SIZE) {
        error('Photo exceeds maximum size of 5MB.');
    }

    $finfo     = new finfo(FILEINFO_MIME_TYPE);
    $mime      = $finfo->file($file['tmp_name']);
    if (!in_array($mime, UPLOAD_ALLOWED_TYPES, true)) {
        error('Invalid file type. Only JPEG, PNG, and WebP are allowed.');
    }

    $ext        = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename   = uniqid('report_', true) . '.' . $ext;
    $dest       = UPLOAD_DIR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        error('Failed to save uploaded file.', 500);
    }

    $photo_path = 'uploads/reports/' . $filename;
}

try {
    $pdo  = Database::connect();
    $stmt = $pdo->prepare(
        'INSERT INTO user_reports (user_id, incident_type, description, location, report_date, photo_path, status)
         VALUES (?, ?, ?, ?, ?, ?, \'Pending\')'
    );
    $stmt->execute([
        $token_user->sub,
        $body['incident_type'],
        sanitize($body['description']),
        sanitize($body['location']),
        $body['report_date'],
        $photo_path,
    ]);

    $new = $pdo->prepare('SELECT * FROM user_reports WHERE id = ? LIMIT 1');
    $new->execute([(int) $pdo->lastInsertId()]);

    success($new->fetch(), 'Report submitted.', 201);
} catch (PDOException $e) {
    error('Database error.', 500);
}
