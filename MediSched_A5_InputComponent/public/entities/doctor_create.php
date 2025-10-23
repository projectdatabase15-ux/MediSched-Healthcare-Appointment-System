<?php require __DIR__ . '/../config.php'; $users=$pdo->query('SELECT id, full_name, email FROM users ORDER BY full_name')->fetchAll(); ?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Create Doctor</title>
<link rel="stylesheet" href="/public/assets/style.css">
</head><body><?php include __DIR__ . '/../navbar.php'; ?>
<div class="container"><div class="card">
  <h1>Create Doctor</h1>
  <form method="post" action="doctor_create_action.php">
    <label>User Account</label>
    <select name="user_id" required>
      <option value="">— choose user —</option>
      <?php foreach($users as $u): ?>
        <option value="<?php echo $u['id']; ?>"><?php echo h($u['full_name'].' — '.$u['email']); ?></option>
      <?php endforeach; ?>
    </select>
    <label>Specialization</label>
    <input type="text" name="specialization" required />
    <div class="btnrow">
      <button type="submit">Create</button>
      <a class="btn secondary" href="/public/maintenance.php">Back</a>
    </div>
  </form>
</div></div></body></html>
