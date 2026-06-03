<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="card">
  <h2>Search Center</h2>
  <p class="help">Pick a search to begin:</p>
  <div class="actions">
    <a class="btn btn-primary" href="<?php echo base_url('public/search/doctor_search.php'); ?>">Find Doctors</a>
    <a class="btn" href="<?php echo base_url('public/search/appointment_search.php'); ?>">Find Appointments</a>
    <a class="btn" href="<?php echo base_url('public/search/prescription_search.php'); ?>">Find Prescriptions</a>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
