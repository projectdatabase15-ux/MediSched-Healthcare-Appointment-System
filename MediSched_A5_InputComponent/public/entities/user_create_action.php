<?php require __DIR__ . '/../config.php';
$full = $_POST['full_name'] ?? '';
$email = $_POST['email'] ?? '';
$pass = $_POST['password'] ?? '';
try{
  $stmt = $pdo->prepare('INSERT INTO users(full_name,email,passhash) VALUES(?,?,SHA2(?,256))');
  $stmt->execute([$full,$email,$pass]);
  $id = $pdo->lastInsertId();
  $msg = "User created with id $id";
  $ok = true;
}catch(Exception $e){
  $msg = $e->getMessage();
  $ok = false;
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>User Feedback</title><link rel="stylesheet" href="/public/assets/style.css"></head>
<body><?php include __DIR__ . '/../navbar.php'; ?><div class="container"><div class="card">
<h1>User — Feedback</h1>
<div class="feedback <?php echo $ok?'success':'error'; ?>"><?php echo h($msg); ?></div>
<div class="btnrow"><a class="btn" href="/public/maintenance.php">Back to Maintenance</a></div>
</div></div></body></html>
