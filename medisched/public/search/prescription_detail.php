<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: prescription_search.php'); exit; }

$sql = "SELECT pr.PrescriptionID, pr.Medication, pr.Dosage, pr.Notes, pr.DateIssued,
               pr.DoctorID,
               pu.Name AS PatientName, du.Name AS DoctorName
        FROM prescriptions pr
        JOIN patients p ON pr.PatientID = p.PatientID
        JOIN users pu   ON p.UserID     = pu.UserID
        JOIN doctors d  ON pr.DoctorID  = d.DoctorID
        JOIN users du   ON d.UserID     = du.UserID
        WHERE pr.PrescriptionID = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$row = $stmt->fetch();

if (!$row) {
  echo '<div class="card"><h2>Prescription not found</h2><a class="btn" href="prescription_search.php">Back</a></div>';
  require_once __DIR__ . '/../../includes/footer.php'; exit;
}
?>
<div class="card">
  <h2>Prescription #<?php echo h($row['PrescriptionID']); ?></h2>

  <p><strong>Patient:</strong> <?php echo h($row['PatientName']); ?></p>

  <p>
    <strong>Doctor:</strong>
    <a class="btn btn-primary" style="margin-left:6px"
       href="doctor_detail.php?id=<?php echo (int)$row['DoctorID']; ?>">
      <?php echo h($row['DoctorName']); ?>
    </a>
  </p>

  <p><strong>Date Issued:</strong> <?php echo h($row['DateIssued']); ?></p>
  <p><strong>Medication:</strong> <?php echo h($row['Medication']); ?></p>
  <p><strong>Dosage:</strong> <?php echo h($row['Dosage']); ?></p>

  <?php if (!empty($row['Notes'])): ?>
    <p><strong>Notes:</strong> <?php echo nl2br(h($row['Notes'])); ?></p>
  <?php endif; ?>

  <div class="actions" style="margin-top:16px">
    <a class="btn" href="prescription_search.php">Back to Search</a>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
