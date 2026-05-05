<?php
/** @var array $orders */
?>
<div class="d-flex align-items-end justify-content-between mb-3">
  <div>
    <h1 class="page-title mb-1">Mes commandes</h1>
    <div class="text-muted">Historique des commandes</div>
  </div>
</div>

<?php if (!$orders): ?>
  <div class="alert alert-info">Aucune commande pour le moment.</div>
<?php else: ?>
  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Statut</th>
            <th class="text-end">Total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $o): ?>
            <tr>
              <td class="fw-semibold">#<?= (int) $o['id'] ?></td>
              <td><?= e((string) ($o['created_at'] ?? '')) ?></td>
              <td>
                <span class="badge text-bg-secondary badge-status"><?= e((string) $o['statut']) ?></span>
              </td>
              <td class="text-end fw-semibold"><?= number_format((float) ($o['total'] ?? 0), 0, ',', ' ') ?> FCFA</td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>

