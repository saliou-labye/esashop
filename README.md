# ShopESA — TP E-Commerce PHP (ESA-AGOE)

Plateforme e-commerce simple en PHP + MySQL (PDO), avec authentification, catalogue, panier, commandes et zone admin.

## Prérequis

- XAMPP/WAMP (PHP 8.x + MySQL/MariaDB)

## Installation

1. Copier le dossier dans `htdocs/` (ex: `htdocs/ESASHOP`)
2. Créer la base et les tables via `database.sql` (phpMyAdmin → Import)
3. Configurer la connexion DB dans `config/db.local.php` (optionnel)

Exemple `config/db.local.php` :

```php
<?php
return [
  'host' => 'localhost',
  'db' => 'shopesadb',
  'user' => 'root',
  'pass' => '',
];
```

4. Ouvrir `http://localhost/ESASHOP/index.php`
5. (Optionnel) Créer un admin initial : `http://localhost/ESASHOP/seed_admin.php`

## Routes principales

- `index.php` : accueil + filtre catégorie
- `index.php?r=product&id=1` : détail produit
- `index.php?r=cart` : panier
- `index.php?r=checkout` : validation commande
- `index.php?r=orders` : historique client
- `index.php?r=admin` : dashboard admin
- `index.php?r=admin_products` : CRUD produits (admin)
- `index.php?r=admin_categories` : CRUD catégories (admin)
- `index.php?r=admin_orders` : gestion des commandes (admin)

