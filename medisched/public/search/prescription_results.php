<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/header.php';

$pname = trim($_GET['pname'] ?? '');
$med = trim($_GET['med'] ?? '');
$from = trim($_GET['from'] ?? '');
$to = trim($_GET['to'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * PAGE_SIZE;

$where = ["1=1"];
$params = [];
if ($pname !== '') { $where[] = "pu.Name LIKE :pname"; $params[":pname"] = '%' . $pname . '%'; }
if ($med !== '') { $where[] = "pr.Medication LIKE :med"; $params[":med"] = '%' . $med . '%'; }
if ($from !== '') { $where[] = "pr.DateIssued >= :from"; $params[":from"] = $from; }
if ($to !== '') { $where[] = "pr.DateIssued <= :to"; $params[":to"] = $to; }
$whereSql = implode(' AND ', $where);

$countSql = "SELECT COUNT(*)
             FROM prescriptions pr
             JOIN patients p ON pr.PatientID = p.PatientID
             JOIN users pu ON p.UserID = pu.UserID
             JOIN doctors d ON pr.DoctorID = d.DoctorID
             JOIN users du ON d.UserID = du.UserID
             WHERE $whereSql";
$stmt = $pdo->prepare($countSql);
$stmt->execute($params);
$total = (int)$stmt->fetchColumn();

$sql = "SELECT pr.PrescriptionID, pr.Medication, pr.Dosage, pr.Notes, pr.DateIssued,
               pu.Name AS PatientName, du.Name AS DoctorName
        FROM prescriptions pr
        JOIN patients p ON pr.PatientID = p.PatientID
        JOIN users pu ON p.UserID = pu.UserID
        JOIN doctors d ON pr.DoctorID = d.DoctorID
        JOIN users du ON d.UserID = du.UserID
        WHERE $whereSql
        ORDER BY pr.DateIssued DESC
        LIMIT :lim OFFSET :off";
$stmt = $pdo->prepare($sql);
foreach ($params as $k=>$v) $stmt->bindValue($k, $v);
$stmt->bindValue(':lim', PAGE_SIZE, PDO::PARAM_INT);
$stmt->bindValue(':off', $offset, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();

echo '<div class="card">';
echo '<h2>Prescription Results</h2>';
echo '<p class="result-meta">Found ' . h($total) . ' result(s).</p>';

if (!$rows) {
  echo '<p>No prescriptions found.</p>';
  echo '<a class="btn" href="prescription_search.php">Back</a>';
} else {
  echo '<div class="grid">';
  foreach ($rows as $r) {
    $detailUrl = 'prescription_detail.php?id=' . urlencode($r['PrescriptionID']);
    echo '<div class="card"><h3>' . h($r['Medication']) . '</h3>';
    echo '<p><strong>Patient:</strong> ' . h($r['PatientName']) . '</p>';
    echo '<p><strong>Doctor:</strong> ' . h($r['DoctorName']) . '</p>';
    echo '<p><strong>Date:</strong> ' . h($r['DateIssued']) . '</p>';
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
        $qs = http_build_query(['pname'=>$pname, 'med'=>$med, 'from'=>$from, 'to'=>$to, 'page'=>$p]);
        echo '<a href="prescription_results.php?' . h($qs) . '">' . $p . '</a>';
      }
    }
    echo '</div>';
  }
}
echo '</div>';

require_once __DIR__ . '/../../includes/footer.php';
?>
