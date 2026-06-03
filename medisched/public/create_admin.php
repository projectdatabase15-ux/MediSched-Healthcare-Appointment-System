<?php
/**
 * One-time admin seeder — run once, then delete this file.
 * Usage: php public/create_admin.php  OR  visit it in a browser once.
 */
require_once __DIR__ . '/../includes/db.php';

$name     = "Admin User";
$email    = "admin@medisched.local";
$password = "admin123"; // Change after first login

// Check if admin already exists
$check = $pdo->prepare("SELECT UserID FROM users WHERE Email = ?");
$check->execute([$email]);
if ($check->fetch()) {
  echo "<p>Admin account already exists. Delete this file.</p>";
  exit;
}

$hash = password_hash($password, PASSWORD_BCRYPT);
$stmt = $pdo->prepare("INSERT INTO users (Name, Email, Password, Role) VALUES (?, ?, ?, 'admin')");
$stmt->execute([$name, $email, $hash]);

echo "<p>Admin created successfully.</p>";
echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
echo "<p><strong>Password:</strong> " . htmlspecialchars($password) . "</p>";
echo "<p><strong>⚠ Delete this file now.</strong></p>";
