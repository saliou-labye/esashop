<?php
/** @var string|null $error */
?>
<div class="row justify-content-center">
  <div class="col-12 col-md-7 col-lg-5">
    <h1 class="page-title mb-3">Connexion</h1>
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= e((string) $error) ?></div>
    <?php endif; ?>
    <div class="card shadow-sm">
      <div class="card-body">
        <form method="post" action="index.php?r=login">
          <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
          <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input class="form-control" type="email" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label class="form-label" for="pwd">Mot de passe</label>
            <input class="form-control" type="password" id="pwd" name="mot_de_passe" required>
          </div>
          <button class="btn btn-primary w-100" type="submit">Se connecter</button>
        </form>
      </div>
    </div>
  </div>
</div>

