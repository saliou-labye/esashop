<?php
include 'db.php';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_BCRYPT);
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];

    $query = "INSERT INTO clients (nom, email, mot_de_passe, adresse, telephone) VALUES (:nom, :email, :mot_de_passe, :adresse, :telephone)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['nom' => $nom, 'email' => $email, 'mot_de_passe' => $mot_de_passe, 'adresse' => $adresse, 'telephone' => $telephone]);
    header('Location: login.php');
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
        form {
            background: white;
            padding: 2.5rem;
            max-width: 500px;
            margin: 2rem auto;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        input, textarea, select {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1.5rem;
            border: 1px solid #cbd5e1;
            border-radius: var(--border-radius);
            box-sizing: border-box; /* Important pour le padding */
        }

        input:focus {
            outline: 2px solid var(--primary-color);
            border-color: transparent;
        }
    </style>
</head>
<body>
    <h1>Inscription</h1>
<form action="register.php" method="post">
    <label for="nom">Nom :</label>
    <input type="text" name="nom" id="nom" required>
    <label for="email">Email :</label>
    <input type="email" name="email" id="email" required>
    <label for="mot_de_passe">Mot de passe :</label>
    <input type="password" name="mot_de_passe" id="mot_de_passe" required>
    <label for="adresse">Adresse :</label>
    <textarea name="adresse" id="adresse" required></textarea>
    <label for="telephone">Téléphone :</label>
    <input type="tel" name="telephone" id="telephone">
    <button type="submit">S'inscrire</button>
</form>

<?php
include 'footer.php';
?>
</body>
</html>


