<?php
if (!isset($pageJs)) { $pageJs = []; } // example: ["auth-login.js"]
?>
  </div><!-- /.page -->

  <footer class="footer">
    <div class="footer-inner">
      <small>Web Tech Project - Turf Slot Request & Confirmation System</small>
    </div>
  </footer>

  <!-- Common JS -->
  <script src="/assets/js/common.js" defer></script>

  <!-- Page JS -->
  <?php foreach ($pageJs as $jsFile): ?>
    <script src="/assets/js/<?php echo htmlspecialchars($jsFile); ?>" defer></script>
  <?php endforeach; ?>
</body>
</html>
