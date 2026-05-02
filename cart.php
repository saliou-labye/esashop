
<?php
include 'db.php';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['id'];
    $Quantity = $_POST['quantity'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $Quantity;
    } else {
        $_SESSION['cart'][$product_id] = $Quantity;
    }
    header('Location: cart.php');
    exit;
}
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo '<p>Votre panier est vide.</p>';
    include 'footer.php';
    exit;
}

$cart = $_SESSION['cart'];
$product_ids = array_keys($cart);

$query = "SELECT * FROM produits WHERE id IN (" . implode(',', array_fill(0, count($product_ids), '?')) . ")";
$stmt = $pdo->prepare($query);
$stmt->execute($product_ids);
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .cart {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .cart-item {
            display: flex;
            align-items: center;
            background: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            gap: 2rem;
        }

        .cart-item img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 4px;
        }

        .cart-item h2 {
            margin: 0;
            font-size: 1.25rem;
        }

        .cart-item p {
            margin: 0.25rem 0;
            color: #64748b;
        }
    </style>
</head>
<body>
    <h1>Votre panier</h1>
<div class="cart">
    <?php foreach ($produits as $produit): ?>
        <div class="cart-item">
            <img src="/images/<?= htmlspecialchars($produit['image']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>">
            <h2><?= htmlspecialchars($produit['nom']) ?></h2>
            <p>Prix unitaire : <?= htmlspecialchars($produit['prix']) ?> €</p>
            <p>Quantité : <?= htmlspecialchars($cart[$produit['id']]) ?></p>
            <p>Total : <?= htmlspecialchars($produit['prix']) * $cart[$produit['id']] ?> €</p>
        </div>
    <?php endforeach; ?>
</div>

<a href="checkout.php">Passer à la caisse</a>

<?php
include 'footer.php';
?>


</body>
</html>
