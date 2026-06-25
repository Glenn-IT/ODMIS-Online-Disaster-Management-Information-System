<?php
require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../../api/helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('GET');
require_auth();

$filename = basename($_GET['file'] ?? '');

if ($filename === '' || $filename === '.') {
    error('File parameter is required.');
}

// Reject path traversal attempts
if ($filename !== basename($filename) || str_contains($filename, '/') || str_contains($filename, '\\')) {
    error('Invalid filename.', 400);
}

$filepath = UPLOAD_DIR . $filename;

if (!file_exists($filepath) || !is_file($filepath)) {
    error('File not found.', 404);
}

// Validate MIME type before serving
$finfo    = new finfo(FILEINFO_MIME_TYPE);
$mime     = $finfo->file($filepath);
$allowed  = ['image/jpeg', 'image/png', 'image/webp'];

if (!in_array($mime, $allowed, true)) {
    error('File type not allowed.', 403);
}

// Serve the file
header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($filepath));
header('Content-Disposition: inline; filename="' . $filename . '"');
header('Cache-Control: private, max-age=3600');
header('X-Content-Type-Options: nosniff');

readfile($filepath);
exit;
