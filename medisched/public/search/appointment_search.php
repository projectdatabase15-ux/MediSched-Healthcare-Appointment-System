<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/header.php';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$dname = $_GET['dname'] ?? '';
$status = $_GET['status'] ?? '';
?>
<div class="card">
  <h2>Appointment Search</h2>
  <form method="get" action="appointment_results.php">
    <label for="from">From (YYYY-MM-DD)</label>
    <input id="from" name="from" type="date" required value="<?php echo h($from); ?>">

    <label for="to">To (YYYY-MM-DD)</label>
    <input id="to" name="to" type="date" required value="<?php echo h($to); ?>">

    <label for="dname">Doctor name (optional)</label>
    <input id="dname" name="dname" value="<?php echo h($dname); ?>">

    <label for="status">Status (optional)</label>
    <select id="status" name="status">
      <option value="">Any</option>
      <?php foreach (['Scheduled','Rescheduled','Cancelled','Completed'] as $s): ?>
        <option value="<?php echo h($s); ?>" <?php echo $status===$s?'selected':''; ?>><?php echo h($s); ?></option>
      <?php endforeach; ?>
    </select>

    <div class="actions" style="margin-top:12px">
      <button class="btn btn-primary" type="submit">Search</button>
      <a class="btn" href="appointment_search.php">Reset</a>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
