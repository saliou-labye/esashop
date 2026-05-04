
<?php
include 'db.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_id = $_SESSION['user_id'];
    $query = "INSERT INTO commandes (client_id, statut) VALUES (:client_id, 'En attente')";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['client_id' => $client_id]);
    $commande_id = $pdo->lastInsertId();

    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $query = "SELECT prix FROM produits WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $product_id]);
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);

        $query = "INSERT INTO details_commandes (commande_id, produit_id, quantite, prix_unitaire) VALUES (:commande_id, :produit_id, :quantite, :prix_unitaire)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'commande_id' => $commande_id,
            'produit_id' => $product_id,
            'quantite' => $quantity,
            'prix_unitaire' => $produit['prix']
        ]);

        $query = "UPDATE produits SET stock = stock - :quantite WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['quantite' => $quantity, 'id' => $product_id]);
    }
    unset($_SESSION['cart']);
    header('Location: confirmation.php');
    exit;
}
?>

<h1>Validation de la commande</h1>
<p>Etes-vous sûr de vouloir valider votre commande</p>
<form action="checkout.php" method="post">
    <button type="submit">Valider</button>
</form>

<?php
include 'footer.php';
?>

