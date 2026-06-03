<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/header.php';
$specialty = $_GET['specialty'] ?? '';
$name = $_GET['name'] ?? '';
$minexp = $_GET['minexp'] ?? '';
?>
<div class="card">
  <h2>Doctor Search</h2>
  <form method="get" action="doctor_results.php">
    <label for="specialty">Specialty <span class="help">(e.g., Cardiology)</span></label>
    <input id="specialty" name="specialty" required value="<?php echo h($specialty); ?>">

    <label for="name">Name (optional)</label>
    <input id="name" name="name" value="<?php echo h($name); ?>">

    <label for="minexp">Min Experience (years, optional)</label>
    <input id="minexp" name="minexp" type="number" min="0" step="1" value="<?php echo h($minexp); ?>">

    <div class="actions" style="margin-top:12px">
      <button class="btn btn-primary" type="submit">Search</button>
      <a class="btn" href="doctor_search.php">Reset</a>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
