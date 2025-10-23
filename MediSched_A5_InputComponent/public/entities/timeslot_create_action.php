<?php require __DIR__ . '/../config.php';
$start = $_POST['start_ts'] ?? '';
$end   = $_POST['end_ts'] ?? '';
try{
  if (strtotime($end) <= strtotime($start)) throw new Exception('End must be after Start');
  $stmt = $pdo->prepare('INSERT INTO timeslots(start_ts, end_ts) VALUES(?,?)');
  $stmt->execute([$start,$end]);
  $id = $pdo->lastInsertId();
  $msg = "Time slot created with id $id";
  $ok = true;
}catch(Exception $e){ $msg = $e->getMessage(); $ok=false; }
?>
<!doctype html><html><head><meta charset="utf-8"><title>Time Slot Feedback</title><link rel="stylesheet" href="/public/assets/style.css"></head>
<body><?php include __DIR__ . '/../navbar.php'; ?><div class="container"><div class="card">
<h1>Time Slot — Feedback</h1>
<div class="feedback <?php echo $ok?'success':'error'; ?>"><?php echo h($msg); ?></div>
<div class="btnrow"><a class="btn" href="/public/maintenance.php">Back to Maintenance</a></div>
</div></div></body></html>
