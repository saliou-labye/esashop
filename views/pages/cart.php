<?php
/** @var array $items */
/** @var float $subtotal */
/** @var float $vat */
/** @var float $total */
/** @var float $vatRate */
?>
<div class="d-flex align-items-end justify-content-between mb-3">
  <div>
    <h1 class="page-title mb-1">Votre panier</h1>
    <div class="text-muted">Total HT + TVA (<?= (int) round($vatRate * 100) ?>%)</div>
  </div>
  <a class="btn btn-outline-secondary" href="index.php">Continuer mes achats</a>
</div>

<?php if (!$items): ?>
  <div class="alert alert-info">Votre panier est vide.</div>
<?php else: ?>
  <div class="card shadow-sm mb-3">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Produit</th>
            <th class="text-end">Prix</th>
            <th style="width: 180px;">Quantité</th>
            <th class="text-end">Total</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $it): ?>
            <tr>
              <td>
                <div class="d-flex align-items-center gap-3">
                  <?php if (!empty($it['image'])): ?>
                    <img src="<?= e((string) $it['image']) ?>" alt="" style="width:64px;height:64px;object-fit:cover;border-radius:8px;">
                  <?php else: ?>
                    <div class="bg-light" style="width:64px;height:64px;border-radius:8px;"></div>
                  <?php endif; ?>
                  <div>
                    <div class="fw-semibold"><?= e((string) $it['nom']) ?></div>
                    <a class="small text-decoration-none" href="index.php?r=product&id=<?= (int) $it['id'] ?>">Voir</a>
                  </div>
                </div>
              </td>
              <td class="text-end"><?= number_format((float) $it['prix'], 0, ',', ' ') ?> FCFA</td>
              <td>
                <form class="d-flex gap-2" method="post" action="index.php?r=cart">
                  <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                  <input type="hidden" name="id" value="<?= (int) $it['id'] ?>">
                  <input type="hidden" name="action" value="set">
                  <input class="form-control form-control-sm" type="number" name="quantite" min="1" value="<?= (int) $it['quantite'] ?>">
                  <button class="btn btn-sm btn-outline-primary" type="submit">OK</button>
                </form>
              </td>
              <td class="text-end fw-semibold">
                <?= number_format(((float) $it['prix']) * ((int) $it['quantite']), 0, ',', ' ') ?> FCFA
              </td>
              <td class="text-end">
                <form method="post" action="index.php?r=cart">
                  <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                  <input type="hidden" name="id" value="<?= (int) $it['id'] ?>">
                  <input type="hidden" name="action" value="remove">
                  <button class="btn btn-sm btn-outline-danger" type="submit">Supprimer</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="row justify-content-end">
    <div class="col-12 col-lg-5">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between mb-2">
            <div class="text-muted">Sous-total (HT)</div>
            <div class="fw-semibold"><?= number_format($subtotal, 0, ',', ' ') ?> FCFA</div>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <div class="text-muted">TVA</div>
            <div class="fw-semibold"><?= number_format($vat, 0, ',', ' ') ?> FCFA</div>
          </div>
          <hr>
          <div class="d-flex justify-content-between mb-3">
            <div class="fw-bold">Total TTC</div>
            <div class="fw-bold text-success"><?= number_format($total, 0, ',', ' ') ?> FCFA</div>
          </div>
          <a class="btn btn-primary w-100" href="index.php?r=checkout">Valider la commande</a>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

