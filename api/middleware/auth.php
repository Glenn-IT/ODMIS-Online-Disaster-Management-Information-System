<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../helpers/response.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

function require_auth(): object {
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';

    if (!$header || !str_starts_with($header, 'Bearer ')) {
        error('Unauthorized: no token provided.', 401);
    }

    $token = trim(substr($header, 7));

    try {
        $decoded = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
    } catch (ExpiredException) {
        error('Unauthorized: token has expired.', 401);
    } catch (SignatureInvalidException) {
        error('Unauthorized: invalid token signature.', 401);
    } catch (Exception) {
        error('Unauthorized: malformed token.', 401);
    }

    return $decoded;
}

function require_admin(): object {
    $user = require_auth();
    if ($user->role !== 'admin') {
        error('Forbidden: admin access required.', 403);
    }
    return $user;
}
