<?php
/** @var array $categories */
/** @var string|null $flash */
/** @var string|null $error */
/** @var array|null $editCategory */
?>
<!--
  Administration des catégories:
  - Formulaire d'ajout
  - Formulaire de modification
  - Liste + suppression
-->
<div class="d-flex align-items-end justify-content-between mb-3">
  <div>
    <h1 class="page-title mb-1">Admin — Catégories</h1>
    <div class="text-muted">Gestion des catégories du catalogue</div>
  </div>
  <a class="btn btn-outline-secondary" href="index.php?r=admin">Dashboard</a>
</div>

<?php if (!empty($flash)): ?>
  <div class="alert alert-success"><?= e((string) $flash) ?></div>
<?php endif; ?>
<?php if (!empty($error)): ?>
  <div class="alert alert-danger"><?= e((string) $error) ?></div>
<?php endif; ?>

<div class="row g-3">
  <div class="col-12 col-lg-5">
    <!-- Formulaire: ajouter une catégorie -->
    <div class="card shadow-sm mb-3">
      <div class="card-header bg-white fw-semibold">Ajouter une catégorie</div>
      <div class="card-body">
        <form method="post" action="index.php?r=admin_categories">
          <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="action" value="add">
          <div class="mb-2">
            <label class="form-label" for="c_nom">Nom</label>
            <input class="form-control" type="text" id="c_nom" name="nom" required>
          </div>
          <div class="mb-3">
            <label class="form-label" for="c_desc">Description</label>
            <textarea class="form-control" id="c_desc" name="description" rows="3"></textarea>
          </div>
          <button class="btn btn-primary w-100" type="submit">Ajouter</button>
        </form>
      </div>
    </div>

    <div class="card shadow-sm">
      <!-- Formulaire: modifier une catégorie -->
      <div class="card-header bg-white fw-semibold">Modifier une catégorie</div>
      <div class="card-body">
        <form method="post" action="index.php?r=admin_categories" class="row g-2">
          <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="action" value="update">
          <div class="col-12">
            <label class="form-label" for="u_cat_id">Catégorie</label>
            <select class="form-select" id="u_cat_id" name="id" required>
              <?php foreach ($categories as $c): ?>
                <option value="<?= (int) $c['id'] ?>" <?= ((int) ($editCategory['id'] ?? 0) === (int) $c['id']) ? 'selected' : '' ?>>#<?= (int) $c['id'] ?> — <?= e((string) $c['nom']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label" for="u_cat_nom">Nom</label>
            <input class="form-control" type="text" id="u_cat_nom" name="nom" value="<?= e((string) ($editCategory['nom'] ?? '')) ?>" required>
          </div>
          <div class="col-12">
            <label class="form-label" for="u_cat_desc">Description</label>
            <textarea class="form-control" id="u_cat_desc" name="description" rows="3"><?= e((string) ($editCategory['description'] ?? '')) ?></textarea>
          </div>
          <div class="col-12">
            <button class="btn btn-outline-primary" type="submit">Enregistrer</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-7">
    <div class="card shadow-sm">
      <!-- Tableau des catégories existantes -->
      <div class="card-header bg-white fw-semibold">Liste des catégories</div>
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Nom</th>
              <th>Description</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($categories as $c): ?>
              <tr>
                <td class="fw-semibold">#<?= (int) $c['id'] ?></td>
                <td><?= e((string) $c['nom']) ?></td>
                <td class="text-muted"><?= e((string) ($c['description'] ?? '')) ?></td>
                <td class="text-end">
                  <div class="d-flex gap-2">
                  <a class="btn btn-sm btn-outline-primary" href="index.php?r=admin_categories&edit=<?= (int) $c['id'] ?>">Modifier</a>
                  <form method="post" action="index.php?r=admin_categories">
                    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
                    <button class="btn btn-sm btn-outline-danger" type="submit">Supprimer</button>
                  </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
