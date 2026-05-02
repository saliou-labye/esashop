
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/styles.css">
    <title>Site e-Commerce de Cosmétiques</title>
    <style>
       header {
    background-color: #2c3e50; /* Bleu foncé professionnel */
    color: #ffffff;
    padding: 1rem 5%;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

nav ul {
    list-style: none;
    display: flex;
    margin: 0;
    padding: 0;
    gap: 20px; /* Espacement entre les liens */
}

nav ul li {
    display: inline;
}

nav ul li a {
    color: #ecf0f1;
    text-decoration: none;
    font-weight: 500;
    font-size: 1.1rem;
    transition: color 0.3s ease;
    padding: 8px 12px;
    border-radius: 4px;
}

/* Effet au survol des liens */
nav ul li a:hover {
    color: #3498db; /* Bleu clair au survol */
    background-color: rgba(255, 255, 255, 0.1);
}

/* Style spécifique pour le bouton de déconnexion si besoin */
nav ul li a[href*="logout"] {
    color: #e74c3c; /* Rouge pour la déconnexion */
}

/* Ajustements pour mobiles */
@media (max-width: 768px) {
    nav ul {
        flex-direction: column;
        gap: 10px;
        text-align: center;
        width: 100%;
    }
    
    nav {
        flex-direction: column;
    }
} 
    </style>
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="cart.php">Panier</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="logout.php">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="login.php">Connexion</a></li>
                <li><a href="register.php">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<main>