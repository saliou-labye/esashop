<?php
/** @var array $product */
?>
<div class="row g-4">
  <div class="col-12 col-lg-6">
    <div class="card shadow-sm">
      <?php if (!empty($product['image'])): ?>
        <img class="card-img-top" src="<?= e((string) $product['image']) ?>" alt="<?= e((string) $product['nom']) ?>" style="max-height:520px;object-fit:cover;">
      <?php else: ?>
        <div class="bg-light d-flex align-items-center justify-content-center" style="height:420px;">
          <span class="text-muted">Aucune image</span>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="col-12 col-lg-6">
    <h1 class="page-title mb-2"><?= e((string) $product['nom']) ?></h1>
    <div class="text-success fw-bold fs-4 mb-3"><?= number_format((float) $product['prix'], 0, ',', ' ') ?> FCFA</div>
    <div class="mb-3 <?= ((int) ($product['stock'] ?? 0) > 0) ? 'text-success' : 'text-danger' ?>">
      <?= ((int) ($product['stock'] ?? 0) > 0) ? ('Stock disponible: ' . (int) $product['stock']) : 'Rupture de stock' ?>
    </div>
    <p class="text-muted"><?= e((string) $product['description']) ?></p>

    <div class="card shadow-sm mt-4">
      <div class="card-body">
        <div class="fw-semibold mb-2">Ajouter au panier</div>
        <form class="row g-2 align-items-end" method="post" action="index.php?r=cart">
          <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
          <input type="hidden" name="action" value="add">
          <div class="col-6">
            <label class="form-label" for="qty">Quantité</label>
            <input class="form-control" type="number" id="qty" name="quantite" min="1" max="<?= max(1, (int) ($product['stock'] ?? 1)) ?>" value="1" required <?= ((int) ($product['stock'] ?? 0) <= 0) ? 'disabled' : '' ?>>
          </div>
          <div class="col-6">
            <button class="btn btn-primary w-100" type="submit" <?= ((int) ($product['stock'] ?? 0) <= 0) ? 'disabled' : '' ?>>Ajouter</button>
          </div>
          <div class="col-12">
            <?php if (!current_user_id()): ?>
              <div class="text-muted small">Tu dois être connecté pour gérer le panier.</div>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

