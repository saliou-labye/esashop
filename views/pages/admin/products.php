<?php
/** @var array $products */
/** @var array $categories */
/** @var string|null $flash */
/** @var array|null $editProduct */
?>
<div class="d-flex align-items-end justify-content-between mb-3">
  <div>
    <h1 class="page-title mb-1">Admin — Produits</h1>
    <div class="text-muted">CRUD (ajout/suppression) + upload image</div>
  </div>
  <a class="btn btn-outline-secondary" href="index.php?r=admin">Dashboard</a>
</div>

<?php if (!empty($flash)): ?>
  <div class="alert alert-success"><?= e((string) $flash) ?></div>
<?php endif; ?>

<div class="row g-3">
  <div class="col-12 col-lg-5">
    <div class="card shadow-sm">
      <div class="card-header bg-white fw-semibold">Ajouter un produit</div>
      <div class="card-body">
        <form method="post" action="index.php?r=admin_products" enctype="multipart/form-data">
          <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="action" value="add">
          <div class="mb-2">
            <label class="form-label" for="nom">Nom</label>
            <input class="form-control" type="text" id="nom" name="nom" required>
          </div>
          <div class="mb-2">
            <label class="form-label" for="desc">Description</label>
            <textarea class="form-control" id="desc" name="description" rows="3" required></textarea>
          </div>
          <div class="row g-2">
            <div class="col-6 mb-2">
              <label class="form-label" for="prix">Prix (FCFA)</label>
              <input class="form-control" type="number" id="prix" name="prix" min="0" step="1" required>
            </div>
            <div class="col-6 mb-2">
              <label class="form-label" for="stock">Stock</label>
              <input class="form-control" type="number" id="stock" name="stock" min="0" step="1" required>
            </div>
          </div>
          <div class="mb-2">
            <label class="form-label" for="cat">Catégorie</label>
            <select class="form-select" id="cat" name="categorie_id" required>
              <?php foreach ($categories as $c): ?>
                <option value="<?= (int) $c['id'] ?>"><?= e((string) $c['nom']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label" for="img">Image</label>
            <input class="form-control" type="file" id="img" name="image" accept="image/*">
          </div>
          <button class="btn btn-primary w-100" type="submit">Ajouter</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-7">
    <div class="card shadow-sm mb-3">
      <div class="card-header bg-white fw-semibold">Modifier un produit</div>
      <div class="card-body">
        <form method="post" action="index.php?r=admin_products" enctype="multipart/form-data" class="row g-2">
          <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="action" value="update">
          <div class="col-12 col-md-4">
            <label class="form-label" for="u_id">Produit</label>
            <select class="form-select" id="u_id" name="id" required>
              <?php foreach ($products as $p): ?>
                <option value="<?= (int) $p['id'] ?>" <?= ((int) ($editProduct['id'] ?? 0) === (int) $p['id']) ? 'selected' : '' ?>>#<?= (int) $p['id'] ?> — <?= e((string) $p['nom']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-12 col-md-8">
            <label class="form-label" for="u_nom">Nom</label>
            <input class="form-control" type="text" id="u_nom" name="nom" value="<?= e((string) ($editProduct['nom'] ?? '')) ?>" required>
          </div>
          <div class="col-12">
            <label class="form-label" for="u_desc">Description</label>
            <textarea class="form-control" id="u_desc" name="description" rows="3" required><?= e((string) ($editProduct['description'] ?? '')) ?></textarea>
          </div>
          <div class="col-6 col-md-3">
            <label class="form-label" for="u_prix">Prix</label>
            <input class="form-control" type="number" id="u_prix" name="prix" min="0" step="1" value="<?= e((string) ($editProduct['prix'] ?? '0')) ?>" required>
          </div>
          <div class="col-6 col-md-3">
            <label class="form-label" for="u_stock">Stock</label>
            <input class="form-control" type="number" id="u_stock" name="stock" min="0" step="1" value="<?= (int) ($editProduct['stock'] ?? 0) ?>" required>
          </div>
          <div class="col-12 col-md-3">
            <label class="form-label" for="u_cat">Catégorie</label>
            <select class="form-select" id="u_cat" name="categorie_id" required>
              <?php foreach ($categories as $c): ?>
                <option value="<?= (int) $c['id'] ?>" <?= ((int) ($editProduct['cat_id'] ?? 0) === (int) $c['id']) ? 'selected' : '' ?>><?= e((string) $c['nom']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-12 col-md-3">
            <label class="form-label" for="u_img">Nouvelle image (optionnel)</label>
            <input class="form-control" type="file" id="u_img" name="image" accept="image/*">
          </div>
          <div class="col-12">
            <input type="hidden" name="existing_image" id="u_existing_image" value="<?= e((string) ($editProduct['image'] ?? '')) ?>">
            <button class="btn btn-outline-primary" type="submit">Enregistrer les modifications</button>
            <div class="text-muted small mt-2">Choisis le produit à modifier, ajuste les champs puis enregistre.</div>
          </div>
        </form>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-header bg-white fw-semibold">Liste des produits</div>
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Nom</th>
              <th class="text-end">Prix</th>
              <th class="text-end">Stock</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $p): ?>
              <tr>
                <td class="fw-semibold">#<?= (int) $p['id'] ?></td>
                <td><?= e((string) $p['nom']) ?></td>
                <td class="text-end"><?= number_format((float) $p['prix'], 0, ',', ' ') ?></td>
                <td class="text-end"><?= (int) $p['stock'] ?></td>
                <td class="text-end">
                  <div class="d-flex gap-2">
                  <a class="btn btn-sm btn-outline-primary" href="index.php?r=admin_products&edit=<?= (int) $p['id'] ?>">Modifier</a>
                  <form method="post" action="index.php?r=admin_products">
                    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
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

