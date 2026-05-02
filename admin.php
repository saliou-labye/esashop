<?php
include 'db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

include 'header.php';
?>

<h1>Interface d'administration</h1>
<ul>
    <li><a href="manage_products.php">Gérer les produits</a></li>
    <li><a href="manage_orders.php">Gérer les commandes</a></li>
</ul>
<?php
include 'footer.php';
?>

