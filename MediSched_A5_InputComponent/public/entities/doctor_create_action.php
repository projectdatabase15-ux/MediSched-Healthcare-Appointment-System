<?php require __DIR__ . '/../config.php';
$user_id = (int)($_POST['user_id'] ?? 0);
$spec = $_POST['specialization'] ?? '';
try{
  $stmt = $pdo->prepare('INSERT INTO doctors(user_id, specialization) VALUES(?,?)');
  $stmt->execute([$user_id,$spec]);
  $id = $pdo->lastInsertId();
  $msg = "Doctor created with id $id";
  $ok = true;
}catch(Exception $e){ $msg = $e->getMessage(); $ok=false; }
?>
<!doctype html><html><head><meta charset="utf-8"><title>Doctor Feedback</title><link rel="stylesheet" href="/public/assets/style.css"></head>
<body><?php include __DIR__ . '/../navbar.php'; ?><div class="container"><div class="card">
<h1>Doctor — Feedback</h1>
<div class="feedback <?php echo $ok?'success':'error'; ?>"><?php echo h($msg); ?></div>
<div class="btnrow"><a class="btn" href="/public/maintenance.php">Back to Maintenance</a></div>
</div></div></body></html>
