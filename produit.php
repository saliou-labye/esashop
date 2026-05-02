<?php
include 'db.php';
include 'header.php';

// Vérification de la présence de l'ID dans l'URL
if (!isset($_GET['id'])) {
    header('Location: login.php');
    exit;
}

$product_id = $_GET['id'];
$query = "SELECT * FROM produits WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $product_id]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

// Redirection si le produit n'existe pas
if (!$produit) {
    header('Location: index.php');
    echo "aucun peoduit pour le moment";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
            .product-detail-container {
                max-width: 1100px;
                margin: 40px auto;
                padding: 20px;
                background: #fff;
                border-radius: 15px;
                box-shadow: 0 5px 25px rgba(0,0,0,0.05);
            }

            .product-detail {
                display: flex;
                flex-wrap: wrap;
                gap: 50px;
                align-items: flex-start;
            }

            /* Section Image */
            .product-detail img {
                flex: 1;
                min-width: 300px;
                max-width: 500px;
                border-radius: 12px;
                object-fit: cover;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }

            /* Section Informations */
            .product-info {
                flex: 1;
                min-width: 300px;
            }

            .product-info h2 {
                font-size: 2.2rem;
                color: #2c3e50;
                margin-bottom: 15px;
            }

            .product-info .price {
                font-size: 1.8rem;
                color: #27ae60;
                font-weight: bold;
                margin-bottom: 25px;
            }

            .product-info .description {
                line-height: 1.7;
                color: #555;
                margin-bottom: 30px;
            }

            /* Formulaire d'ajout au panier */
            .product-info form {
                background: #f9f9f9;
                padding: 20px;
                border-radius: 10px;
                display: inline-block;
                width: 100%;
            }

            .product-info label {
                display: block;
                margin-bottom: 8px;
                font-weight: bold;
            }

            .product-info input[type="number"] {
                width: 80px;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                margin-bottom: 20px;
            }

            .product-info button {
                width: 100%;
                padding: 15px;
                background-color: #3498db;
                color: white;
                border: none;
                border-radius: 6px;
                font-size: 1.1rem;
                font-weight: bold;
                cursor: pointer;
                transition: background 0.3s ease;
            }

            .product-info button:hover {
                background-color: #2980b9;
            }

            /* Section Avis */
            .reviews-section {
                margin-top: 60px;
                border-top: 1px solid #eee;
                padding-top: 30px;
            }

            .reviews-section h3 {
                font-size: 1.5rem;
                color: #2c3e50;
                margin-bottom: 20px;
            }

            .review-item {
                background: #fff;
                padding: 15px;
                border-left: 4px solid #3498db;
                margin-bottom: 15px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.03);
            }

            /* Responsive */
            @media (max-width: 768px) {
                .product-detail {
                    flex-direction: column;
                    align-items: center;
                }
                
                .product-detail img {
                    max-width: 100%;
                }
            }
    </style>
</head>
<body>
<div class="produit-detail-container">
    <div class="product-detail">
        <img src="upload/<?php echo htmlspecialchars($produit['image']); ?>" alt="<?php echo htmlspecialchars($produit['nom']); ?>">
        <div class="product-info">
            <h2><?php echo htmlspecialchars($produit['nom']); ?></h2>
            <p><?php echo htmlspecialchars($produit['description']); ?></p>
            <p>Prix : <?php echo htmlspecialchars($produit['prix']); ?> €</p>
            
            <form action="cart.php" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($produit['id']); ?>">
                <label for="quantity">Quantité :</label>
                <input type="number" name="quantity" id="quantity" min="1" value="1">
                <button type="submit">Ajouter au panier</button>
            </form>
        </div>
    </div>
    <div class="reviews-section">
        <h3>Avis du clients</h3>
        <div class="reviews">

    <?php 
        // Logique pour l'affichage des avis à venir...
         $query = 'SELECT a.note, a.commentaire, c.nom, a.date_avis FROM avis a JOIN clients c ON a.client_id = c.id WHERE produit_id = :product_id';
        $stmt = $pdo->prepare($query);
        $stmt->execute(['product_id' => $product_id]);
        $Savis = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($Savis as $avi): ?>
            <div class="review">
                <h4><?= htmlspecialchars($avi['nom']) ?></h4>
                <p>Note : <?= htmlspecialchars($avi['note']) ?></p>
                <p><?= htmlspecialchars($avi['commentaire']) ?></p>
                <p>Publié le <?= htmlspecialchars($avi['date_avis']) ?></p>
            </div>
    <?php endforeach; ?>
        </div>

<?php if (isset($_SESSION['user_id'])): ?>
        <h3>Ajouter un avis</h3>
        <form action="add_review.php" method="post">
            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
            <label for="note">Note : </label>
            <input type="number" name="note" id="note" min="1" max="5">
            <label for="commentaire">Commentaire :</label>
            <textarea name="commentaire" id="commentaire" rows="4"></textarea>
            <button type="submit">Soumettre</button>
        </form>
    </div>
    <?php else: ?>
        <p><a href="login.php">Connectez-vous</a> pour ajouter un avis.</p>
    <?php endif; ?>

    <?php
    include 'footer.php';
    ?>

</div>
</body>
</html>
