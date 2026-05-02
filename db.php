<?php
$host = 'localhost';
$db = 'shopesadb';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("vous n'etes pas connecter à votre base de donnée $db : " . $e->getMessage());
}
?>