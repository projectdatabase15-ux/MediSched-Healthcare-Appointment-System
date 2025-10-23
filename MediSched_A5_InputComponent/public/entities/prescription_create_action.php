<?php require __DIR__ . '/../config.php';
$appointment_id = (int)($_POST['appointment_id'] ?? 0);
$med = $_POST['medication'] ?? '';
$dos = $_POST['dosage'] ?? '';
$notes = $_POST['notes'] ?? null;
try{
  $stmt = $pdo->prepare('INSERT INTO prescriptions(appointment_id, medication, dosage, notes) VALUES(?,?,?,?)');
  $stmt->execute([$appointment_id,$med,$dos,$notes]);
  $id = $pdo->lastInsertId();
  $msg = "Prescription created with id $id";
  $ok = true;
}catch(Exception $e){ $msg = $e->getMessage(); $ok=false; }
?>
<!doctype html><html><head><meta charset="utf-8"><title>Prescription Feedback</title><link rel="stylesheet" href="/public/assets/style.css"></head>
<body><?php include __DIR__ . '/../navbar.php'; ?><div class="container"><div class="card">
<h1>Prescription — Feedback</h1>
<div class="feedback <?php echo $ok?'success':'error'; ?>"><?php echo h($msg); ?></div>
<div class="btnrow"><a class="btn" href="/public/maintenance.php">Back to Maintenance</a></div>
</div></div></body></html>
