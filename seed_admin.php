<?php

declare(strict_types=1);

require __DIR__ . '/config/bootstrap.php';

// Run once in browser or CLI:
// http://localhost/ESASHOP/seed_admin.php
// It creates an admin account if it doesn't exist.

$email = 'admin@shopesa.local';
$plainPassword = 'admin123';
$nom = 'Admin';

$stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email');
$stmt->execute(['email' => $email]);
$exists = $stmt->fetch();

if ($exists) {
    echo "Admin already exists: " . e($email);
    exit;
}

$hash = password_hash($plainPassword, PASSWORD_BCRYPT);
$stmt = $pdo->prepare("INSERT INTO users (nom, email, password, role) VALUES (:nom, :email, :pwd, 'admin')");
$stmt->execute([
    'nom' => $nom,
    'email' => $email,
    'pwd' => $hash,
]);

echo "Admin created:\n";
echo "email: " . e($email) . "\n";
echo "password: " . e($plainPassword) . "\n";

