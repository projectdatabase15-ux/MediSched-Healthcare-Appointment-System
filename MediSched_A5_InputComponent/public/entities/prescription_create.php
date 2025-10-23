<?php require __DIR__ . '/../config.php';
$appts = $pdo->query('SELECT a.id, u1.full_name AS patient, u2.full_name AS doctor, t.start_ts FROM appointments a JOIN patients p ON a.patient_id=p.id JOIN users u1 ON p.user_id=u1.id JOIN doctors d ON a.doctor_id=d.id JOIN users u2 ON d.user_id=u2.id JOIN timeslots t ON a.timeslot_id=t.id ORDER BY t.start_ts DESC')->fetchAll();
?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Create Prescription</title>
<link rel="stylesheet" href="/public/assets/style.css">
</head><body><?php include __DIR__ . '/../navbar.php'; ?>
<div class="container"><div class="card">
  <h1>Create Prescription</h1>
  <form method="post" action="prescription_create_action.php">
    <label>Appointment</label>
    <select name="appointment_id" required>
      <option value="">— choose appointment —</option>
      <?php foreach($appts as $a): ?>
        <option value="<?php echo $a['id']; ?>"><?php echo h('#'.$a['id'].' — '.$a['doctor'].' → '.$a['patient'].' — '.$a['start_ts']); ?></option>
      <?php endforeach; ?>
    </select>
    <label>Medication</label>
    <input type="text" name="medication" required />
    <label>Dosage</label>
    <input type="text" name="dosage" required />
    <label>Notes</label>
    <textarea name="notes" placeholder="Optional notes"></textarea>
    <div class="btnrow">
      <button type="submit">Create</button>
      <a class="btn secondary" href="/public/maintenance.php">Back</a>
    </div>
  </form>
</div></div></body></html>
