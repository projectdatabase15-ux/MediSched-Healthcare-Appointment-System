<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/header.php';

$from = trim($_GET['from'] ?? '');
$to = trim($_GET['to'] ?? '');
$dname = trim($_GET['dname'] ?? '');
$status = trim($_GET['status'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * PAGE_SIZE;

$errors = [];
if ($from === '' || $to === '') { $errors[] = "From/To dates are required."; }
if ($from !== '' && $to !== '' && $from > $to) { $errors[] = "From date must be before To date."; }

if ($errors) {
  echo '<div class="card"><h3>Input Error</h3><ul class="error">';
  foreach ($errors as $e) echo '<li>' . h($e) . '</li>';
  echo '</ul><a class="btn" href="appointment_search.php">Back</a></div>';
  require_once __DIR__ . '/../../includes/footer.php'; exit;
}

$where = ["a.AppointmentDate BETWEEN :from AND :to"];
$params = [":from"=>$from, ":to"=>$to];

if ($dname !== '') { $where[] = "du.Name LIKE :dname"; $params[":dname"] = '%' . $dname . '%'; }
if ($status !== '') { $where[] = "a.Status = :status"; $params[":status"] = $status; }

$whereSql = implode(' AND ', $where);

$countSql = "SELECT COUNT(*)
             FROM appointments a
             JOIN doctors d ON a.DoctorID = d.DoctorID
             JOIN users du ON d.UserID = du.UserID
             JOIN patients p ON a.PatientID = p.PatientID
             JOIN users pu ON p.UserID = pu.UserID
             WHERE $whereSql";
$stmt = $pdo->prepare($countSql);
$stmt->execute($params);
$total = (int)$stmt->fetchColumn();

$sql = "SELECT a.AppointmentID, a.AppointmentDate, a.AppointmentTime, a.Status,
               du.Name AS DoctorName, pu.Name AS PatientName
        FROM appointments a
        JOIN doctors d ON a.DoctorID = d.DoctorID
        JOIN users du ON d.UserID = du.UserID
        JOIN patients p ON a.PatientID = p.PatientID
        JOIN users pu ON p.UserID = pu.UserID
        WHERE $whereSql
        ORDER BY a.AppointmentDate ASC, a.AppointmentTime ASC
        LIMIT :lim OFFSET :off";
$stmt = $pdo->prepare($sql);
foreach ($params as $k=>$v) $stmt->bindValue($k, $v);
$stmt->bindValue(':lim', PAGE_SIZE, PDO::PARAM_INT);
$stmt->bindValue(':off', $offset, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();

echo '<div class="card">';
echo '<h2>Appointment Results</h2>';
echo '<p class="result-meta">Found ' . h($total) . ' result(s).</p>';

if (!$rows) {
  echo '<p>No appointments found.</p>';
  echo '<a class="btn" href="appointment_search.php">Back</a>';
} else {
  echo '<div class="grid">';
  foreach ($rows as $r) {
    $detailUrl = 'appointment_detail.php?id=' . urlencode($r['AppointmentID']);
    echo '<div class="card"><h3>' . h($r['AppointmentDate']) . ' @ ' . h($r['AppointmentTime']) . '</h3>';
    echo '<p><strong>Doctor:</strong> ' . h($r['DoctorName']) . '</p>';
    echo '<p><strong>Patient:</strong> ' . h($r['PatientName']) . '</p>';
    echo '<p><strong>Status:</strong> ' . status_badge($r['Status']) . '</p>';
    echo '<a class="btn btn-primary" href="' . h($detailUrl) . '">View</a>';
    echo '</div>';
  }
  echo '</div>';

  $pages = max(1, (int)ceil($total / PAGE_SIZE));
  if ($pages > 1) {
    echo '<div class="pagination" style="margin-top:12px">';
    for ($p=1; $p <= $pages; $p++) {
      if ($p == $page) echo '<span><strong>' . $p . '</strong></span>';
      else {
        $qs = http_build_query(['from'=>$from, 'to'=>$to, 'dname'=>$dname, 'status'=>$status, 'page'=>$p]);
        echo '<a href="appointment_results.php?' . h($qs) . '">' . $p . '</a>';
      }
    }
    echo '</div>';
  }
}
echo '</div>';

require_once __DIR__ . '/../../includes/footer.php';
?>
