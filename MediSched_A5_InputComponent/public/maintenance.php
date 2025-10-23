<?php require __DIR__ . '/config.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MediSched — Maintenance</title>
  <link rel="stylesheet" href="/public/assets/style.css">
</head>
<body>
  <?php include __DIR__ . '/navbar.php'; ?>
  <div class="container">
    <div class="card">
      <h1>Maintenance</h1>
      <p>Links to input pages for entities and relationship-sets (Assignment 5).</p>
      <div class="card">
        <h3>Entities</h3>
        <ul>
          <li><a href="/public/entities/user_create.php">Create User</a></li>
          <li><a href="/public/entities/doctor_create.php">Create Doctor</a></li>
          <li><a href="/public/entities/patient_create.php">Create Patient</a></li>
          <li><a href="/public/entities/timeslot_create.php">Create Time Slot</a></li>
          <li><a href="/public/entities/appointment_create.php">Create Appointment</a></li>
          <li><a href="/public/entities/prescription_create.php">Create Prescription</a></li>
        </ul>
      </div>
      <div class="card">
        <h3>Relationship Input (via selects)</h3>
        <ul class="small">
          <li><strong>Appointment</strong> relates <em>Patient</em>, <em>Doctor</em>, and <em>TimeSlot</em>.</li>
          <li><strong>Prescription</strong> relates to an <em>Appointment</em> (and thus indirectly Doctor &amp; Patient).</li>
          <li><strong>Doctor</strong> and <strong>Patient</strong> are linked to <em>User</em> accounts.</li>
        </ul>
        <p class="small">Each form reads the referenced tables and exposes <code>&lt;select&gt;</code> controls with meaningful labels.</p>
      </div>
    </div>
  </div>
</body>
</html>
