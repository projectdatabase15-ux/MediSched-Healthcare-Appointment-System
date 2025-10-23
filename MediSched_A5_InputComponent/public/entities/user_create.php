<?php require __DIR__ . '/../config.php'; ?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Create User</title>
<link rel="stylesheet" href="/public/assets/style.css">
</head><body><?php include __DIR__ . '/../navbar.php'; ?>
<div class="container">
  <div class="card">
    <h1>Create User</h1>
    <form method="post" action="user_create_action.php">
      <label>Full Name</label>
      <input type="text" name="full_name" required />
      <label>Email</label>
      <input type="email" name="email" required />
      <label>Password</label>
      <input type="password" name="password" required />
      <div class="btnrow">
        <button type="submit">Create</button>
        <a class="btn secondary" href="/public/maintenance.php">Back</a>
      </div>
    </form>
  </div>
</div></body></html>
