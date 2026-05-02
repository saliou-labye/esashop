<?php
include 'db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_product'])) {
        $nom = $_POST['nom'];
        $description = $_POST['description'];
        $prix = $_POST['prix'];
        $stock = $_POST['stock'];
        $categorie_id = $_POST['categorie_id'];
        $image_data = $_FILES['image'];
        $ajouter = isset($_POST["ajouter"]) ? htmlspecialchars($_POST["ajouter"]) : '0';
         $filename= $image_data["name"];

         $dir= "upload/";

        $destination= $dir.time().$filename;
        if(move_uploaded_file($image_data["tmp_name"], $destination)){
            $insert =$pdo->prepare("INSERT INTO produits (nom, description, prix, stock, image, categorie_id) VALUES (?, ?, ?, ?, ?, ?)");
            if($insert->execute([$nom, $description, $prix, $stock, $destination, $categorie_id])){
                echo "Enregistrer avec succès! ";
            }
        
        }else{
            echo "Echec de l'enregistrement! ";
        }

       

        if (isset($_POST['delete_product'])) {
            $id = $_POST['id'];
            $query = "DELETE FROM produits WHERE id = :id";
            $stmt = $pdo->prepare($query);
            if ($stmt->execute(['id' => $id])) {
                header("Location: manage_products.php");
                exit();
            } else {
                echo "Erreur lors de la suppression.";
                }
        }
    }
}
        $query = "SELECT * FROM produits";
        $stmt = $pdo->query($query);
        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 1rem;
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        th, td {
            text-align: left;
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            background: #f1f5f9;
            font-weight: 700;
        }

        tr:hover {
            background: #f8fafc;
        }

        /* Statuts des commandes */
        .statut-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .statut-en-attente { background: #fef3c7; color: #92400e; }
        .statut-livree { background: #dcfce7; color: #166534; }
    </style>
</head>
<body>
    <h1>Gérer les produits</h1>
<h2>Ajouter un produit</h2>
<form action="manage_products.php" method="post" enctype="multipart/form-data">
    <label for="nom">Nom :</label>
    <input type="text" name="nom" id="nom" required>
    <label for="description">Description :</label>
    <textarea name="description" id="description" required></textarea>
    <label for="prix">Prix :</label>
    <input type="number" name="prix" id="prix" step="0.01" required>
    <label for="stock">Stock :</label>
    <input type="number" name="stock" id="stock" required>
    <label for="categorie_id">Catégorie :</label>
    <select name="categorie_id" id="categorie_id">
        <?php
        $query = "SELECT * FROM categories";
        $stmt = $pdo->query($query);
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($categories as $categorie): ?>
            <option value="<?= htmlspecialchars($categorie['id']) ?>"><?= htmlspecialchars($categorie['nom']) ?></option>
        <?php endforeach; ?>
    </select>
    <label for="image">Image :</label>
    <input type="file" name="image" id="image" required>
    <button type="submit" name="add_product">Ajouter</button>
</form>

<h2>Liste des produits</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Prix</th>
            <th>Stock</th>
            <th>Catégorie</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produits as $produit): ?>
            <tr>
                <td><?= htmlspecialchars($produit['id']) ?></td>
                <td><?= htmlspecialchars($produit['nom']) ?></td>
                <td><?= htmlspecialchars($produit['description']) ?></td>
                <td><?= htmlspecialchars($produit['prix']) ?> FCFA</td>
                <td><?= htmlspecialchars($produit['stock']) ?></td>
                <td><?= htmlspecialchars($produit['categorie_id']) ?></td>
                <td><img src="<?= htmlspecialchars($produit['image']) ?>" width="50"></td>
                <td>
                <form  method="post">
                    <input type="hidden" name="id" value="<?= $produit['id'] ?>">
                    <button type="submit" name="delete_product">Supprimer</button>
                </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
include 'footer.php';
?>


</body>
</html>
