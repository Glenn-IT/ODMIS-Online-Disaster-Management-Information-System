<?php

function send_json(int $status, array $payload): never {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function success(mixed $data = null, string $message = 'OK', int $status = 200): never {
    $body = ['success' => true, 'message' => $message];
    if ($data !== null) {
        $body['data'] = $data;
    }
    send_json($status, $body);
}

function error(string $message, int $status = 400, mixed $errors = null): never {
    $body = ['success' => false, 'message' => $message];
    if ($errors !== null) {
        $body['errors'] = $errors;
    }
    send_json($status, $body);
}

function method_required(string ...$methods): void {
    if (!in_array($_SERVER['REQUEST_METHOD'], $methods, true)) {
        error('Method not allowed. Expected: ' . implode(', ', $methods), 405);
    }
}

function get_json_body(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function sanitize(string $value): string {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}
