<?php
// 404 — no DB connection needed
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/header.php';
http_response_code(404);
?>
<div class="card">
  <h2>404 — Not Found</h2>
  <p>The page you requested does not exist.</p>
  <div class="actions">
    <a class="btn btn-primary" href="<?php echo base_url('public/'); ?>">Go to Search</a>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
