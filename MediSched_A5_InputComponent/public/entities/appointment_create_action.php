<?php require __DIR__ . '/../config.php';
$patient_id = (int)($_POST['patient_id'] ?? 0);
$doctor_id = (int)($_POST['doctor_id'] ?? 0);
$slot_id = (int)($_POST['timeslot_id'] ?? 0);
$reason = $_POST['reason'] ?? null;
try{
  // Prevent double-booking same doctor in same slot
  $chk = $pdo->prepare('SELECT COUNT(*) c FROM appointments WHERE doctor_id=? AND timeslot_id=?');
  $chk->execute([$doctor_id,$slot_id]);
  $row = $chk->fetch();
  if(($row['c'] ?? 0) > 0){
    throw new Exception('This time slot is already booked for the selected doctor.');
  }
  $stmt = $pdo->prepare('INSERT INTO appointments(patient_id,doctor_id,timeslot_id,reason) VALUES(?,?,?,?)');
  $stmt->execute([$patient_id,$doctor_id,$slot_id,$reason]);
  $id = $pdo->lastInsertId();
  $msg = "Appointment created with id $id";
  $ok = true;
}catch(Exception $e){ $msg = $e->getMessage(); $ok=false; }
?>
<!doctype html><html><head><meta charset="utf-8"><title>Appointment Feedback</title><link rel="stylesheet" href="/public/assets/style.css"></head>
<body><?php include __DIR__ . '/../navbar.php'; ?><div class="container"><div class="card">
<h1>Appointment — Feedback</h1>
<div class="feedback <?php echo $ok?'success':'error'; ?>"><?php echo h($msg); ?></div>
<div class="btnrow"><a class="btn" href="/public/maintenance.php">Back to Maintenance</a></div>
</div></div></body></html>
