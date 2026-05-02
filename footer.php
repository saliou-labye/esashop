<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>

        footer {
            background-color: #2c3e50; /* Même bleu foncé que le header pour la cohérence */
            color: #bdc3c7; /* Gris clair pour un texte doux */
            padding: 2rem 0;
            margin-top: 50px; /* Espace avec le contenu principal */
            border-top: 3px solid #3498db; /* Rappel de la couleur d'accentuation */
            text-align: center;
        }

        footer p {
            margin: 0;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
        }

        /* Optionnel : Si vous ajoutez des liens ou icônes plus tard */
        footer .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Style pour s'assurer que le footer reste en bas si la page est courte */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1; /* Pousse le footer vers le bas */
        }

        /* Style pour les scripts ou petits textes additionnels */
        footer .credits {
            margin-top: 10px;
            font-size: 0.8rem;
            opacity: 0.7;
        }  
    </style>
</head>
<body>
    <main>
        <footer>
             <p>&copy; 2024 Site e-Commerce de Cosmétiques</p>
    </main>
        </footer>
</body>
</html>