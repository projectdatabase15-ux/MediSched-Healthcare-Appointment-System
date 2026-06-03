<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// Must be logged in AND be an admin
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
  header("Location: login.php");
  exit;
}

// Create presence_log first, THEN insert
$pdo->query("CREATE TABLE IF NOT EXISTS presence_log (
  id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  event    VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
$pdo->query("INSERT INTO presence_log (event) VALUES ('admin_accessed')");

$flash = '';

// Handle add user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
  $name  = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';
  $role  = in_array($_POST['role'] ?? '', ['admin','doctor','patient']) ? $_POST['role'] : 'patient';

  if ($name && $email && $pass) {
    $hash = password_hash($pass, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (Name, Email, Password, Role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $hash, $role]);
    $flash = "User '{$name}' added.";
  }
}

// Handle delete user (cannot delete yourself)
if (isset($_GET['delete'])) {
  $del = (int) $_GET['delete'];
  if ($del !== (int)$_SESSION['user_id']) {
    $pdo->prepare("DELETE FROM users WHERE UserID = ?")->execute([$del]);
    $flash = "User deleted.";
  }
}

// Fetch all users
$users = $pdo->query("SELECT UserID, Name, Email, Role FROM users ORDER BY Role, Name")->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
    <h2 style="margin:0">Admin Dashboard</h2>
    <a class="btn btn-danger" href="logout.php">Logout</a>
  </div>

  <?php if ($flash): ?>
    <p style="color:var(--accent);margin-top:10px"><?php echo h($flash); ?></p>
  <?php endif; ?>
</div>

<div class="card">
  <h3>Add User</h3>
  <form method="POST">
    <input type="hidden" name="add_user" value="1">

    <label for="name">Full Name</label>
    <input id="name" type="text" name="name" required>

    <label for="email">Email</label>
    <input id="email" type="email" name="email" required>

    <label for="password">Password</label>
    <input id="password" type="password" name="password" required>

    <label for="role">Role</label>
    <select id="role" name="role">
      <option value="patient">Patient</option>
      <option value="doctor">Doctor</option>
      <option value="admin">Admin</option>
    </select>

    <div class="actions" style="margin-top:12px">
      <button class="btn btn-primary" type="submit">Add User</button>
    </div>
  </form>
</div>

<div class="card">
  <h3>All Users (<?php echo count($users); ?>)</h3>
  <?php if (!$users): ?>
    <p class="help">No users found.</p>
  <?php else: ?>
    <table style="width:100%;border-collapse:collapse;">
      <thead>
        <tr style="color:var(--muted);font-size:.85rem;text-align:left">
          <th style="padding:6px 8px">ID</th>
          <th style="padding:6px 8px">Name</th>
          <th style="padding:6px 8px">Email</th>
          <th style="padding:6px 8px">Role</th>
          <th style="padding:6px 8px"></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($users as $u): ?>
        <tr style="border-top:1px solid #1f2937">
          <td style="padding:6px 8px;color:var(--muted)"><?php echo h($u['UserID']); ?></td>
          <td style="padding:6px 8px"><?php echo h($u['Name']); ?></td>
          <td style="padding:6px 8px"><?php echo h($u['Email']); ?></td>
          <td style="padding:6px 8px"><code><?php echo h($u['Role']); ?></code></td>
          <td style="padding:6px 8px">
            <?php if ((int)$u['UserID'] !== (int)$_SESSION['user_id']): ?>
              <a class="btn btn-danger" href="?delete=<?php echo (int)$u['UserID']; ?>"
                 onclick="return confirm('Delete this user?')">Delete</a>
            <?php else: ?>
              <span class="help">(you)</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
