<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
  header("Location: admin.php");
  exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email    = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  $stmt = $pdo->prepare("SELECT UserID, Name, Password, Role FROM users WHERE Email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  if ($user && password_verify($password, $user['Password'])) {
    session_regenerate_id(true);
    $_SESSION['user_id']   = $user['UserID'];
    $_SESSION['user_name'] = $user['Name'];
    $_SESSION['user_role'] = $user['Role'];
    header("Location: admin.php");
    exit;
  } else {
    $error = "Invalid email or password.";
  }
}
?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="card" style="max-width:400px;margin:40px auto;">
  <h2>Admin Login</h2>
  <form method="POST">
    <label for="email">Email</label>
    <input id="email" type="email" name="email" required autocomplete="email"
           value="<?php echo h($_POST['email'] ?? ''); ?>">

    <label for="password">Password</label>
    <input id="password" type="password" name="password" required autocomplete="current-password">

    <div class="actions" style="margin-top:14px">
      <button class="btn btn-primary" type="submit">Log in</button>
    </div>
  </form>

  <?php if ($error): ?>
    <p class="error" style="margin-top:12px"><?php echo h($error); ?></p>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
