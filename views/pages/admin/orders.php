<?php
/** @var array $orders */
?>
<!--
  Administration des commandes:
  - Affiche toutes les commandes
  - Permet de changer le statut (En attente, Expédiée, Livrée)
-->
<div class="d-flex align-items-end justify-content-between mb-3">
  <div>
    <h1 class="page-title mb-1">Admin — Commandes</h1>
    <div class="text-muted">Liste des commandes + changement de statut</div>
  </div>
  <a class="btn btn-outline-secondary" href="index.php?r=admin">Dashboard</a>
</div>

<div class="card shadow-sm">
  <!-- Tableau des commandes -->
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Date</th>
          <th>Client</th>
          <th class="text-end">Total</th>
          <th>Statut</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $o): ?>
          <tr>
            <td class="fw-semibold">#<?= (int) $o['id'] ?></td>
            <td><?= e((string) ($o['date_commande'] ?? '')) ?></td>
            <td><?= e((string) ($o['nom'] ?? '')) ?></td>
            <td class="text-end fw-semibold"><?= number_format((float) ($o['total'] ?? 0), 0, ',', ' ') ?> FCFA</td>
            <td>
              <!-- Formulaire de mise à jour du statut -->
              <form class="d-flex gap-2" method="post" action="index.php?r=admin_orders">
                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="id" value="<?= (int) $o['id'] ?>">
                <select class="form-select form-select-sm" name="statut" style="min-width: 170px;">
                  <?php
                  $statuses = ['En attente', 'Expédiée', 'Livrée'];
                  foreach ($statuses as $st):
                  ?>
                    <option value="<?= e($st) ?>" <?= ($o['statut'] ?? '') === $st ? 'selected' : '' ?>><?= e($st) ?></option>
                  <?php endforeach; ?>
                </select>
                <button class="btn btn-sm btn-outline-primary" type="submit">OK</button>
              </form>
            </td>
            <td class="text-end"></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

