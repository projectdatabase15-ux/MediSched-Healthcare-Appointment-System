<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: doctor_search.php'); exit; }

$sql = "SELECT d.DoctorID, u.Name, d.Specialty, d.ExperienceYears, u.Email
        FROM doctors d JOIN users u ON d.UserID = u.UserID
        WHERE d.DoctorID = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$doc = $stmt->fetch();

if (!$doc) {
  echo '<div class="card"><h2>Doctor not found</h2><a class="btn" href="doctor_search.php">Back</a></div>';
  require_once __DIR__ . '/../../includes/footer.php'; exit;
}

// Next available timeslots
$ts = $pdo->prepare("SELECT SlotID, StartTime, EndTime
                     FROM timeslots
                     WHERE DoctorID = :id AND AvailabilityStatus = 'available'
                     ORDER BY StartTime ASC LIMIT 10");
$ts->execute([':id' => $id]);
$slots = $ts->fetchAll();

// Recent appointments for this doctor
$apptStmt = $pdo->prepare(
  "SELECT a.AppointmentID, a.AppointmentDate, a.AppointmentTime, a.Status,
          pu.Name AS PatientName
   FROM appointments a
   JOIN patients p ON a.PatientID = p.PatientID
   JOIN users pu   ON p.UserID    = pu.UserID
   WHERE a.DoctorID = :id
   ORDER BY a.AppointmentDate DESC, a.AppointmentTime DESC
   LIMIT 5"
);
$apptStmt->execute([':id' => $id]);
$recentAppts = $apptStmt->fetchAll();

// Recent prescriptions issued by this doctor
$rxStmt = $pdo->prepare(
  "SELECT pr.PrescriptionID, pr.Medication, pr.Dosage, pr.DateIssued,
          pu.Name AS PatientName
   FROM prescriptions pr
   JOIN patients p ON pr.PatientID = p.PatientID
   JOIN users pu   ON p.UserID     = pu.UserID
   WHERE pr.DoctorID = :id
   ORDER BY pr.DateIssued DESC
   LIMIT 5"
);
$rxStmt->execute([':id' => $id]);
$recentRx = $rxStmt->fetchAll();
?>
<div class="card">
  <h2><?php echo h($doc['Name']); ?></h2>
  <p><strong>Specialty:</strong> <?php echo h($doc['Specialty']); ?></p>
  <p><strong>Experience:</strong> <?php echo h($doc['ExperienceYears']); ?> years</p>
  <p><strong>Email:</strong> <?php echo h($doc['Email']); ?></p>

  <h3>Next Available Timeslots</h3>
  <?php if (!$slots): ?>
    <p class="help">No available timeslots listed.</p>
  <?php else: ?>
    <ul>
      <?php foreach ($slots as $s): ?>
        <li>#<?php echo h($s['SlotID']); ?>: <?php echo h($s['StartTime']); ?> → <?php echo h($s['EndTime']); ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<div class="card">
  <h3>Recent Appointments</h3>
  <?php if (!$recentAppts): ?>
    <p class="help">No appointments on record.</p>
  <?php else: ?>
    <div class="grid">
      <?php foreach ($recentAppts as $a): ?>
        <div class="card">
          <p><strong><?php echo h($a['AppointmentDate']); ?></strong> @ <?php echo h($a['AppointmentTime']); ?></p>
          <p>Patient: <?php echo h($a['PatientName']); ?></p>
          <p>Status: <?php echo status_badge($a['Status']); ?></p>
          <a class="btn btn-primary" href="appointment_detail.php?id=<?php echo (int)$a['AppointmentID']; ?>">View</a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<div class="card">
  <h3>Recent Prescriptions</h3>
  <?php if (!$recentRx): ?>
    <p class="help">No prescriptions on record.</p>
  <?php else: ?>
    <div class="grid">
      <?php foreach ($recentRx as $rx): ?>
        <div class="card">
          <p><strong><?php echo h($rx['Medication']); ?></strong> — <?php echo h($rx['Dosage']); ?></p>
          <p>Patient: <?php echo h($rx['PatientName']); ?></p>
          <p>Issued: <?php echo h($rx['DateIssued']); ?></p>
          <a class="btn btn-primary" href="prescription_detail.php?id=<?php echo (int)$rx['PrescriptionID']; ?>">View</a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <div class="actions" style="margin-top:16px">
    <a class="btn" href="doctor_search.php">Back to Search</a>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
