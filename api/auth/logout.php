<?php
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../../api/middleware/auth.php';

method_required('POST');

// Verify token is valid before acknowledging logout
require_auth();

// JWT is stateless — actual invalidation is done client-side by deleting the token.
// If a server-side blacklist is needed later, add token jti to a blacklist table here.
success(null, 'Logged out successfully.');
