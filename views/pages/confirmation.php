<?php
/** @var int $id */
?>
<div class="text-center py-5">
  <h1 class="page-title mb-2">Commande confirmée</h1>
  <p class="text-muted mb-4">Merci. Votre commande a été enregistrée<?= $id ? ' (ID: ' . (int) $id . ')' : '' ?>.</p>
  <div class="d-flex justify-content-center gap-2">
    <a class="btn btn-primary" href="index.php">Retour à l’accueil</a>
    <a class="btn btn-outline-secondary" href="index.php?r=orders">Voir mes commandes</a>
  </div>
</div>

