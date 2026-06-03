<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/header.php';
$pname = $_GET['pname'] ?? '';
$med = $_GET['med'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
?>
<div class="card">
  <h2>Prescription Search</h2>
  <form method="get" action="prescription_results.php">
    <label for="pname">Patient name (optional)</label>
    <input id="pname" name="pname" value="<?php echo h($pname); ?>">

    <label for="med">Medication contains (optional)</label>
    <input id="med" name="med" value="<?php echo h($med); ?>">

    <label for="from">From (YYYY-MM-DD, optional)</label>
    <input id="from" name="from" type="date" value="<?php echo h($from); ?>">

    <label for="to">To (YYYY-MM-DD, optional)</label>
    <input id="to" name="to" type="date" value="<?php echo h($to); ?>">

    <div class="actions" style="margin-top:12px">
      <button class="btn btn-primary" type="submit">Search</button>
      <a class="btn" href="prescription_search.php">Reset</a>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
