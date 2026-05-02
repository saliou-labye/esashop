<?php
include 'db.php';
include 'header.php';

$query = "SELECT * FROM produits";
$stmt = $pdo->query($query);
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Conteneur principal */
        .products-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 40px;
            font-size: 2.2rem;
            position: relative;
        }

        h1::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: #3498db;
            margin: 10px auto;
            border-radius: 2px;
        }

        /* Grille de produits */
        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            padding: 20px 0;
        }

        /* Carte de produit individuelle */
        .product {
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            border: 1px solid #eee;
        }

        .product:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        }

        /* Image du produit */
        .product img {
            width: 100%;
            height: 250px;
            object-fit: cover; /* Garde les proportions de l'image */
            border-bottom: 1px solid #f0f0f0;
        }

        /* Détails du produit */
        .product h2 {
            font-size: 1.25rem;
            margin: 15px;
            color: #333;
        }

        .product p {
            color: #666;
            font-size: 0.9rem;
            margin: 0 15px 10px;
            flex-grow: 1; /* Permet d'aligner les prix en bas si les descriptions varient */
        }

        .product .price {
            font-weight: bold;
            color: #27ae60;
            font-size: 1.2rem;
            margin: 0 15px 15px;
        }

        /* Bouton "Voir le produit" */
        .product a {
            display: block;
            text-align: center;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            padding: 12px;
            margin: 0 15px 20px;
            border-radius: 6px;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        .product a:hover {
            background-color: #2980b9;
        }

        /* Responsive : ajustement pour tablettes et mobiles */
        @media (max-width: 600px) {
            .products {
                grid-template-columns: 1fr; /* Une seule colonne sur mobile */
            }
        }
    </style>
</head>
<body>
<div class="products-container">
    <h1>Bienvenue sur notre platforme de E-commerce</h1>
    <div class="products">
        <?php foreach ($produits as $produit): ?>
        
            <div class="product">
                <img src="image<?php echo htmlspecialchars($produit['image']); ?>" alt="<?php echo htmlspecialchars($produit['nom']); ?>">
                <h2><?php echo htmlspecialchars($produit['nom']); ?></h2>
                <p><?php echo htmlspecialchars($produit['description']); ?></p>
                <p>Prix : <?php echo htmlspecialchars($produit['prix']); ?> €</p>
                <a href="product.php?id=<?php echo htmlspecialchars($produit['id']); ?>">Voir le produit</a>
            </div>
        <?php endforeach; ?>
    
    </div>
 </div>
   
<?php include 'footer.php'; ?>
</body>
</html>
