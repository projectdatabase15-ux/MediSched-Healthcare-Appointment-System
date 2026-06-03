<?php // includes/header.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MediSched – Search</title>
  <link rel="stylesheet" href="<?php echo base_url('assets/styles.css'); ?>">
</head>
<body>
<header class="site-header">
  <div class="container">
    <h1><a href="<?php echo base_url('public/'); ?>">MediSched</a></h1>
    <nav>
      <a href="<?php echo base_url('public/search/doctor_search.php'); ?>">Doctors</a>
      <a href="<?php echo base_url('public/search/appointment_search.php'); ?>">Appointments</a>
      <a href="<?php echo base_url('public/search/prescription_search.php'); ?>">Prescriptions</a>
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="<?php echo base_url('public/admin.php'); ?>">Admin</a>
        <a href="<?php echo base_url('public/logout.php'); ?>">Logout</a>
      <?php else: ?>
        <a href="<?php echo base_url('public/login.php'); ?>">Login</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">
