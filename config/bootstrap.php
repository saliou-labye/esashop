<?php

declare(strict_types=1);

session_start();

$app = require __DIR__ . '/app.php';
$pdo = require __DIR__ . '/db.php';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_verify(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }
    $token = $_POST['_csrf'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['_csrf'] ?? '', $token)) {
        http_response_code(400);
        die('CSRF token invalide.');
    }
}

function current_user_id(): ?int
{
    $id = $_SESSION['user_id'] ?? null;
    return is_numeric($id) ? (int) $id : null;
}

function current_admin_id(): ?int
{
    $id = $_SESSION['admin_id'] ?? null;
    return is_numeric($id) ? (int) $id : null;
}

