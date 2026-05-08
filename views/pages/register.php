<?php
/** @var string|null $error */
?>
<!--
  Page d'inscription client:
  - Saisie des informations utilisateur
  - Envoi vers la route register (POST)
-->
<div class="row justify-content-center">
  <div class="col-12 col-md-8 col-lg-6">
    <h1 class="page-title mb-3">Inscription</h1>
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= e((string) $error) ?></div>
    <?php endif; ?>
    <div class="card shadow-sm">
      <div class="card-body">
        <!-- Formulaire de création de compte -->
        <form method="post" action="index.php?r=register" class="cardo";>
          <!-- Token CSRF pour éviter les soumissions malveillantes -->
          <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
          <div class="mb-3">
            <label class="form-label" for="nom">Nom</label>
            <input class="form-control" type="text" id="nom" name="nom" required>
          </div>
          <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input class="form-control" type="email" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label class="form-label" for="pwd">Mot de passe</label>
            <input class="form-control" type="password" id="pwd" name="mot_de_passe" required>
          </div>
          <div class="mb-3">
            <label class="form-label" for="adresse">Adresse</label>
            <textarea class="form-control" id="adresse" name="adresse" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label" for="tel">Téléphone</label>
            <input class="form-control" type="tel" id="tel" name="telephone">
          </div>
          <button class="btn btn-primary w-100" type="submit">Créer mon compte</button>
        </form>
      </div>
    </div>
  </div>
</div>

