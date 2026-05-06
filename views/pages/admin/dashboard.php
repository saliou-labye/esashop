<!--
  Tableau de bord administrateur:
  - Raccourcis vers la gestion des produits
  - Raccourcis vers la gestion des catégories
  - Raccourcis vers la gestion des commandes
-->
<div class="d-flex align-items-end justify-content-between mb-3">
  <div>
    <h1 class="page-title mb-1">Administration</h1>
    <div class="text-muted">Gestion du catalogue & des commandes</div>
  </div>
</div>

<div class="row g-3">
  <div class="col-12 col-md-4">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <div class="fw-semibold mb-2">Produits</div>
        <div class="text-muted mb-3">CRUD produits + upload image + stock.</div>
        <a class="btn btn-primary" href="index.php?r=admin_products">Gérer les produits</a>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-4">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <div class="fw-semibold mb-2">Catégories</div>
        <div class="text-muted mb-3">Ajouter, modifier et supprimer les catégories.</div>
        <a class="btn btn-primary" href="index.php?r=admin_categories">Gérer les catégories</a>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-4">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <div class="fw-semibold mb-2">Commandes</div>
        <div class="text-muted mb-3">Voir toutes les commandes et changer leur statut.</div>
        <a class="btn btn-primary" href="index.php?r=admin_orders">Gérer les commandes</a>
      </div>
    </div>
  </div>
</div>

