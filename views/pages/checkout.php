<?php
/** @var array $items */
/** @var float $subtotal */
/** @var float $vat */
/** @var float $total */
/** @var float $vatRate */
/** @var string|null $error */
?>
<div class="row g-4">
  <div class="col-12 col-lg-7">
    <h1 class="page-title mb-3">Validation de la commande</h1>
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= e((string) $error) ?></div>
    <?php endif; ?>
    <div class="card shadow-sm">
      <div class="card-body">
        <form method="post" action="index.php?r=checkout">
          <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
          <div class="mb-3">
            <label class="form-label" for="adresse">Adresse de livraison</label>
            <textarea class="form-control" id="adresse" name="adresse" rows="4" required></textarea>
          </div>
          <button class="btn btn-primary w-100" type="submit">Confirmer et payer (simulation)</button>
          <div class="text-muted small mt-2">Paiement non intégré (TP : simulation d'achat).</div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-5">
    <div class="card shadow-sm">
      <div class="card-header bg-white fw-semibold">Récapitulatif</div>
      <div class="card-body">
        <?php foreach ($items as $it): ?>
          <div class="d-flex justify-content-between small mb-2">
            <div class="text-truncate" style="max-width: 280px;">
              <?= e((string) $it['nom']) ?> × <?= (int) $it['quantite'] ?>
            </div>
            <div class="fw-semibold">
              <?= number_format(((float) $it['prix']) * ((int) $it['quantite']), 0, ',', ' ') ?> FCFA
            </div>
          </div>
        <?php endforeach; ?>
        <hr>
        <div class="d-flex justify-content-between mb-2">
          <div class="text-muted">Sous-total (HT)</div>
          <div class="fw-semibold"><?= number_format($subtotal, 0, ',', ' ') ?> FCFA</div>
        </div>
        <div class="d-flex justify-content-between mb-2">
          <div class="text-muted">TVA (<?= (int) round($vatRate * 100) ?>%)</div>
          <div class="fw-semibold"><?= number_format($vat, 0, ',', ' ') ?> FCFA</div>
        </div>
        <div class="d-flex justify-content-between">
          <div class="fw-bold">Total TTC</div>
          <div class="fw-bold text-success"><?= number_format($total, 0, ',', ' ') ?> FCFA</div>
        </div>
      </div>
    </div>
  </div>
</div>

