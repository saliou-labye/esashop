<?php
include 'db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $statut = $_POST['statut'];

    $query = "UPDATE commandes SET statut = :statut WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['statut' => $statut, 'id' => $id]);

    header('Location: manage_orders.php');
    exit;
}
?>


