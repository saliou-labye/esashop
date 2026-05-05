<?php

declare(strict_types=1);

/**
 * PDO connection (MySQL/MariaDB) for ShopESA.
 * If you need local overrides, create `config/db.local.php` returning same array keys.
 */

$default = [
    'host' => 'localhost',
    'db' => 'shopesadb',
    'user' => 'root',
    'pass' => '',
    'charset' => 'utf8mb4',
];

$localPath = __DIR__ . '/db.local.php';
if (is_file($localPath)) {
    $local = require $localPath;
    if (is_array($local)) {
        $default = array_merge($default, $local);
    }
}

$dsn = sprintf(
    'mysql:host=%s;dbname=%s;charset=%s',
    $default['host'],
    $default['db'],
    $default['charset']
);

try {
    $pdo = new PDO($dsn, $default['user'], $default['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    die("Connexion BDD impossible : " . $e->getMessage());
}

return $pdo;

