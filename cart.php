
<?php
session_start();
include 'db.php';

$client_id = $_SESSION['client_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['quantite']) && $idprod) {
    $idprod = (int) $_POST['id'];
    $quantite = max(1, (int) $_POST['quantite']);

    $sql = "INSERT INTO panier (idcli, idprod, quantite) 
            VALUES (:cli, :prod, :qty) 
            ON DUPLICATE KEY UPDATE quantite = quantite + :qty";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'cli' => $idcli,
        'prod' => $idprod,
        'qty' => $quantite
    ]);

    header('Location: cart.php');
    exit();
}

$produits = [];
$cart = [];

if ($client_id) {
    $sql = "SELECT p.*, pa.quantite FROM produits p
            JOIN panier pa ON p.id = pa.idprod
            WHERE pa.idcli = :cli";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['cli' => $idcli]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($items as $item) {
        $produits[] = $item;
        $cart[$item['id']] = (int) $item['quantite'];
    }
}

include 'header.php';
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
            <img src="<?= htmlspecialchars($produit['image']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>">
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
