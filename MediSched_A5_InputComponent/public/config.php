<?php
// Update these credentials for your MySQL
$DB_HOST = getenv('MEDISCHED_DB_HOST') ?: 'localhost';
$DB_NAME = getenv('MEDISCHED_DB_NAME') ?: 'medisched';
$DB_USER = getenv('MEDISCHED_DB_USER') ?: 'root';
$DB_PASS = getenv('MEDISCHED_DB_PASS') ?: '';

try {
  $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
} catch (Exception $e) {
  http_response_code(500);
  echo '<div class="container"><div class="card"><h2>Database connection failed</h2><p>' . htmlspecialchars($e->getMessage()) . '</p></div></div>';
  exit;
}

function h($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
?>
