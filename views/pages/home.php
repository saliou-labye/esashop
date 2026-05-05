<?php
/** @var array $products */
/** @var array $categories */
/** @var int $catId */
?>
<div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3 mb-4">
  <div>
    <h1 class="page-title mb-1">Bienvenue sur ShopESA</h1>
    <div class="text-muted">Catalogue de produits — filtre par catégorie</div>
  </div>
  <form class="d-flex gap-2" method="get" action="index.php">
    <input type="hidden" name="r" value="home">
    <select class="form-select" name="cat" style="min-width: 240px;">
      <option value="0">Toutes les catégories</option>
      <?php foreach ($categories as $c): ?>
        <option value="<?= (int) $c['id'] ?>" <?= ((int) $c['id'] === (int) $catId) ? 'selected' : '' ?>>
          <?= e((string) $c['nom']) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <button class="btn btn-primary" type="submit">Filtrer</button>
  </form>
</div>

<div class="row g-3">
  <?php if (!$products): ?>
    <div class="col-12">
      <div class="alert alert-info mb-0">Aucun produit disponible pour cette catégorie.</div>
    </div>
  <?php endif; ?>
  <?php foreach ($products as $p): ?>
    <div class="col-12 col-sm-6 col-lg-4">
      <div class="card h-100 card-product shadow-sm">
        <?php if (!empty($p['image'])): ?>
          <img class="card-img-top" src="<?= e((string) $p['image']) ?>" alt="<?= e((string) $p['nom']) ?>">
        <?php else: ?>
          <div class="bg-light d-flex align-items-center justify-content-center" style="height:220px;">
            <span class="text-muted">Aucune image</span>
          </div>
        <?php endif; ?>
        <div class="card-body d-flex flex-column">
          <h5 class="card-title mb-1"><?= e((string) $p['nom']) ?></h5>
          <div class="small mb-2 <?= ((int) ($p['stock'] ?? 0) > 0) ? 'text-success' : 'text-danger' ?>">
            <?= ((int) ($p['stock'] ?? 0) > 0) ? ('Stock: ' . (int) $p['stock']) : 'Rupture de stock' ?>
          </div>
          <div class="text-muted small mb-2"><?= e(mb_strimwidth((string) $p['description'], 0, 100, '…')) ?></div>
          <div class="mt-auto d-flex align-items-center justify-content-between">
            <div class="fw-bold text-success"><?= number_format((float) $p['prix'], 0, ',', ' ') ?> FCFA</div>
            <a class="btn btn-outline-primary btn-sm" href="index.php?r=product&id=<?= (int) $p['id'] ?>">Voir</a>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

