<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: appointment_search.php'); exit; }

$sql = "SELECT a.AppointmentID, a.AppointmentDate, a.AppointmentTime, a.Status,
               a.DoctorID, a.PatientID, a.TimeSlotID,
               du.Name AS DoctorName, pu.Name AS PatientName,
               du.Email AS DoctorEmail, pu.Email AS PatientEmail
        FROM appointments a
        JOIN doctors d  ON a.DoctorID  = d.DoctorID
        JOIN users du   ON d.UserID    = du.UserID
        JOIN patients p ON a.PatientID = p.PatientID
        JOIN users pu   ON p.UserID    = pu.UserID
        WHERE a.AppointmentID = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$row = $stmt->fetch();

if (!$row) {
  echo '<div class="card"><h2>Appointment not found</h2><a class="btn" href="appointment_search.php">Back</a></div>';
  require_once __DIR__ . '/../../includes/footer.php'; exit;
}

// Timeslot info
$ts = null;
if (!empty($row['TimeSlotID'])) {
  $tsStmt = $pdo->prepare("SELECT SlotID, StartTime, EndTime FROM timeslots WHERE SlotID = :sid");
  $tsStmt->execute([':sid' => $row['TimeSlotID']]);
  $ts = $tsStmt->fetch();
}

// Other prescriptions from the same doctor–patient pair (related records)
$relRx = $pdo->prepare(
  "SELECT pr.PrescriptionID, pr.Medication, pr.Dosage, pr.DateIssued
   FROM prescriptions pr
   WHERE pr.DoctorID = :did AND pr.PatientID = :pid
   ORDER BY pr.DateIssued DESC LIMIT 5"
);
$relRx->execute([':did' => $row['DoctorID'], ':pid' => $row['PatientID']]);
$relPrescriptions = $relRx->fetchAll();
?>
<div class="card">
  <h2>Appointment #<?php echo h($row['AppointmentID']); ?></h2>
  <p><strong>Date / Time:</strong> <?php echo h($row['AppointmentDate']); ?> @ <?php echo h($row['AppointmentTime']); ?></p>
  <p><strong>Status:</strong> <?php echo status_badge($row['Status']); ?></p>

  <h3>Doctor</h3>
  <p>
    <a class="btn btn-primary" href="doctor_detail.php?id=<?php echo (int)$row['DoctorID']; ?>">
      <?php echo h($row['DoctorName']); ?>
    </a>
    <span class="help" style="margin-left:8px"><?php echo h($row['DoctorEmail']); ?></span>
  </p>

  <h3>Patient</h3>
  <p><?php echo h($row['PatientName']); ?> &mdash; <?php echo h($row['PatientEmail']); ?></p>

  <?php if ($ts): ?>
    <h3>Timeslot</h3>
    <p>#<?php echo h($ts['SlotID']); ?>: <?php echo h($ts['StartTime']); ?> → <?php echo h($ts['EndTime']); ?></p>
  <?php endif; ?>
</div>

<?php if ($relPrescriptions): ?>
<div class="card">
  <h3>Prescriptions — <?php echo h($row['DoctorName']); ?> &rarr; <?php echo h($row['PatientName']); ?></h3>
  <div class="grid">
    <?php foreach ($relPrescriptions as $rx): ?>
      <div class="card">
        <p><strong><?php echo h($rx['Medication']); ?></strong> — <?php echo h($rx['Dosage']); ?></p>
        <p class="help"><?php echo h($rx['DateIssued']); ?></p>
        <a class="btn" href="prescription_detail.php?id=<?php echo (int)$rx['PrescriptionID']; ?>">View</a>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<div class="card">
  <div class="actions">
    <a class="btn" href="appointment_search.php">Back to Search</a>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
