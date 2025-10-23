<?php require __DIR__ . '/../config.php'; ?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Create Time Slot</title>
<link rel="stylesheet" href="/public/assets/style.css">
</head><body><?php include __DIR__ . '/../navbar.php'; ?>
<div class="container"><div class="card">
  <h1>Create Time Slot</h1>
  <form method="post" action="timeslot_create_action.php">
    <label>Start</label>
    <input type="datetime-local" name="start_ts" required />
    <label>End</label>
    <input type="datetime-local" name="end_ts" required />
    <div class="btnrow">
      <button type="submit">Create</button>
      <a class="btn secondary" href="/public/maintenance.php">Back</a>
    </div>
  </form>
</div></div></body></html>
