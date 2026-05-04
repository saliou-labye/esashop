<?php
include 'db.php';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // 1. On vérifie d'abord dans la table 'admins'
    $queryAdmin = "SELECT * FROM admins WHERE email = :email";
    $stmtAdmin = $pdo->prepare($queryAdmin);
    $stmtAdmin->execute(['email' => $email]);
    $admin = $stmtAdmin->fetch(PDO::FETCH_ASSOC);

    if ($admin && $mot_de_passe === $admin['mot_de_passe']){
        // C'est un admin !
        $_SESSION['admin_id'] = $admin['id'];
        header('Location: admin.php');
        exit;
    } 
    
    // 2. Si ce n'est pas un admin, on vérifie dans la table 'clients'
    $queryClient = "SELECT * FROM clients WHERE email = :email";
    $stmtClient = $pdo->prepare($queryClient);
    $stmtClient->execute(['email' => $email]);
    $client = $stmtClient->fetch(PDO::FETCH_ASSOC);

    if ($client && password_verify($mot_de_passe, $client['mot_de_passe'])) {
        // C'est un client classique !
        $_SESSION['user_id'] = $client['id'];
        header('Location: index.php'); 
          
        exit;
    } else {
        // Aucun des deux ne correspond
        $message = "Email ou mot de passe incorrect.";
    }
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
   <h1>Connexion</h1>
<?php if (isset($message)): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>
<form action="login.php" method="post">
    <label for="email">Email :</label>
    <input type="email" name="email" id="email" required>
    <label for="mot_de_passe">Mot de passe :</label>
    <input type="password" name="mot_de_passe" id="mot_de_passe" required>
    <button type="submit">Se connecter</button>
</form>
<?php
include 'footer.php';
?>


 
</body>
</html>
