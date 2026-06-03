<?php
// includes/helpers.php
// Utility functions that don't require a DB connection.
// Loaded by db.php automatically; can also be loaded alone for pages like 404.

if (!function_exists('h')) {
  function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}

if (!function_exists('base_url')) {
  function base_url($path) {
    if (!defined('APP_BASE')) require_once __DIR__ . '/config.php';
    $path = '/' . ltrim($path, '/');
    return APP_BASE . $path;
  }
}

if (!function_exists('status_badge')) {
  function status_badge($status) {
    $map = [
      'Scheduled'   => 'status-scheduled',
      'Completed'   => 'status-completed',
      'Cancelled'   => 'status-cancelled',
      'Rescheduled' => 'status-rescheduled',
    ];
    $cls = $map[$status] ?? '';
    return '<span class="status ' . h($cls) . '">' . h($status) . '</span>';
  }
}
