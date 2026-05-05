<?php
$appName = $app['app_name'] ?? 'ShopESA';
$year = (int) date('Y');
?>
</main>
<footer class="site-footer border-top">
  <div class="container footer-inner">
    <div class="footer-col">
      <div class="footer-brand">© <?= $year ?> <?= e($appName) ?></div>
      <div class="text-muted small">TP E-Commerce PHP — ESA-AGOE</div>
    </div>
    <div class="footer-col">
      <strong>Adresse boutique</strong>
      <div class="small">Agoe Sogbossito</div>
    </div>
    <div class="footer-col">
      <strong>Contact</strong>
      <div class="small">
        <a href="mailto:salioulabye@gmail.com">salioulabye@gmail.com</a>
      </div>
      <div class="small mt-2">
        <a href="tel:+22897848660">+228 97 84 86 60</a>
        <span class="text-muted"> / </span>
        <a href="tel:+22892281553">+228 92 28 15 53</a>
      </div>
    </div>
  </div>
</footer>
</body>
</html>
