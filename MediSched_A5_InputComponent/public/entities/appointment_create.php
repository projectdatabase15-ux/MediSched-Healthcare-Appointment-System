<?php require __DIR__ . '/../config.php';
$patients=$pdo->query('SELECT p.id, u.full_name, u.email FROM patients p JOIN users u ON p.user_id=u.id ORDER BY u.full_name')->fetchAll();
$doctors=$pdo->query('SELECT d.id, u.full_name, u.email, d.specialization FROM doctors d JOIN users u ON d.user_id=u.id ORDER BY u.full_name')->fetchAll();
$slots=$pdo->query('SELECT id, start_ts, end_ts FROM timeslots ORDER BY start_ts')->fetchAll();
?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Create Appointment</title>
<link rel="stylesheet" href="/public/assets/style.css">
</head><body><?php include __DIR__ . '/../navbar.php'; ?>
<div class="container"><div class="card">
  <h1>Create Appointment</h1>
  <form method="post" action="appointment_create_action.php">
    <label>Patient</label>
    <select name="patient_id" required>
      <option value="">— choose patient —</option>
      <?php foreach($patients as $p): ?>
        <option value="<?php echo $p['id']; ?>"><?php echo h($p['full_name'].' — '.$p['email']); ?></option>
      <?php endforeach; ?>
    </select>
    <label>Doctor</label>
    <select name="doctor_id" required>
      <option value="">— choose doctor —</option>
      <?php foreach($doctors as $d): ?>
        <option value="<?php echo $d['id']; ?>"><?php echo h($d['full_name'].' ('.$d['specialization'].') — '.$d['email']); ?></option>
      <?php endforeach; ?>
    </select>
    <label>Time Slot</label>
    <select name="timeslot_id" required>
      <option value="">— choose slot —</option>
      <?php foreach($slots as $s): ?>
        <option value="<?php echo $s['id']; ?>"><?php echo h($s['start_ts'].' → '.$s['end_ts']); ?></option>
      <?php endforeach; ?>
    </select>
    <label>Reason</label>
    <input type="text" name="reason" placeholder="e.g., General check-up" />
    <div class="btnrow">
      <button type="submit">Create</button>
      <a class="btn secondary" href="/public/maintenance.php">Back</a>
    </div>
  </form>
</div></div></body></html>
