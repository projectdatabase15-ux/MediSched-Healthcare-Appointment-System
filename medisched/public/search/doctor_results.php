<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/header.php';

$specialty = trim($_GET['specialty'] ?? '');
$name = trim($_GET['name'] ?? '');
$minexp = trim($_GET['minexp'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * PAGE_SIZE;

$errors = [];
if ($specialty === '') { $errors[] = "Specialty is required."; }

if ($errors) {
  echo '<div class="card"><h3>Input Error</h3><ul class="error">';
  foreach ($errors as $e) echo '<li>' . h($e) . '</li>';
  echo '</ul><a class="btn" href="doctor_search.php">Back</a></div>';
  require_once __DIR__ . '/../../includes/footer.php'; exit;
}

$where = ["d.Specialty LIKE :spec"];
$params = [":spec" => '%' . $specialty . '%'];
if ($name !== '') { $where[] = "u.Name LIKE :name"; $params[":name"] = '%' . $name . '%'; }
if ($minexp !== '' && ctype_digit($minexp)) { $where[] = "d.ExperienceYears >= :minexp"; $params[":minexp"] = (int)$minexp; }

$whereSql = implode(' AND ', $where);

$countSql = "SELECT COUNT(*) FROM doctors d JOIN users u ON d.UserID = u.UserID WHERE $whereSql";
$stmt = $pdo->prepare($countSql);
$stmt->execute($params);
$total = (int)$stmt->fetchColumn();

$sql = "SELECT d.DoctorID, u.Name, d.Specialty, d.ExperienceYears
        FROM doctors d JOIN users u ON d.UserID = u.UserID
        WHERE $whereSql
        ORDER BY u.Name ASC
        LIMIT :lim OFFSET :off";
$stmt = $pdo->prepare($sql);
foreach ($params as $k => $v) $stmt->bindValue($k, $v);
$stmt->bindValue(':lim', PAGE_SIZE, PDO::PARAM_INT);
$stmt->bindValue(':off', $offset, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();

echo '<div class="card">';
echo '<h2>Doctor Results</h2>';
echo '<p class="result-meta">Found ' . h($total) . ' result(s).</p>';
if (!$rows) {
  echo '<p>No doctors matched your search.</p>';
  echo '<a class="btn" href="doctor_search.php">Back</a>';
} else {
  echo '<div class="grid">';
  foreach ($rows as $r) {
    $detailUrl = 'doctor_detail.php?id=' . urlencode($r['DoctorID']);
    echo '<div class="card"><h3>' . h($r['Name']) . '</h3>';
    echo '<p><strong>Specialty:</strong> ' . h($r['Specialty']) . '</p>';
    echo '<p><strong>Experience:</strong> ' . h($r['ExperienceYears']) . ' years</p>';
    echo '<a class="btn btn-primary" href="' . h($detailUrl) . '">View</a>';
    echo '</div>';
  }
  echo '</div>';

  // pagination
  $pages = max(1, (int)ceil($total / PAGE_SIZE));
  if ($pages > 1) {
    echo '<div class="pagination" style="margin-top:12px">';
    for ($p=1; $p <= $pages; $p++) {
      if ($p == $page) echo '<span><strong>' . $p . '</strong></span>';
      else {
        $qs = http_build_query(['specialty'=>$specialty, 'name'=>$name, 'minexp'=>$minexp, 'page'=>$p]);
        echo '<a href="doctor_results.php?' . h($qs) . '">' . $p . '</a>';
      }
    }
    echo '</div>';
  }
}
echo '</div>';

require_once __DIR__ . '/../../includes/footer.php';
?>
