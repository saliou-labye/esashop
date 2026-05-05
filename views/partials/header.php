<?php
/** @var array $app */
$appName = $app['app_name'] ?? 'ShopESA';
$userId = current_user_id();
$adminId = current_admin_id();
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($appName) ?></title>
  <link href="public/css/app.css" rel="stylesheet">
</head>
<body>
<nav class="navbar" style="background: var(--brand);">
  <div class="container">
    <a class="navbar-brand" href="index.php">ShopESA</a>
    <div class="navbar-collapse" id="navMain">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?r=cart">Panier</a></li>
        <?php if ($userId): ?>
          <li class="nav-item"><a class="nav-link" href="index.php?r=orders">Mes commandes</a></li>
        <?php endif; ?>
        <?php if ($adminId): ?>
          <li class="nav-item"><a class="nav-link" href="index.php?r=admin">Admin</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if ($userId || $adminId): ?>
          <li class="nav-item"><a class="nav-link" href="index.php?r=logout">Déconnexion</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="index.php?r=login">Connexion</a></li>
          <li class="nav-item"><a class="nav-link" href="index.php?r=register">Inscription</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<main class="container py-4">

