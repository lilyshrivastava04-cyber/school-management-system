<?php
$title = 'Dashboard';
require 'includes/header.php';
$one = fn($sql) => $pdo->query($sql)->fetchColumn();
$students = $one('SELECT COUNT(*) FROM students');
$teachers = $one('SELECT COUNT(*) FROM teachers');
$avgAtt   = round((float)$one('SELECT AVG(attendance) FROM students'), 1);
$avgMarks = round((float)$one('SELECT AVG(marks) FROM students'), 1);
$paid     = $one("SELECT COALESCE(SUM(amount),0) FROM fees WHERE status='Paid'");
$pending  = $one("SELECT COALESCE(SUM(amount),0) FROM fees WHERE status='Pending'");
$byClass  = $pdo->query('SELECT class, COUNT(*) c, ROUND(AVG(marks),1) m FROM students GROUP BY class ORDER BY class')->fetchAll();
?>
<div class="cards">
  <div class="card"><span>Total Students</span><b><?= $students ?></b></div>
  <div class="card"><span>Total Teachers</span><b><?= $teachers ?></b></div>
  <div class="card"><span>Avg Attendance</span><b><?= $avgAtt ?>%</b></div>
  <div class="card"><span>Avg Marks</span><b><?= $avgMarks ?></b></div>
  <div class="card green"><span>Fees Collected</span><b>₹<?= number_format($paid) ?></b></div>
  <div class="card red"><span>Fees Pending</span><b>₹<?= number_format($pending) ?></b></div>
</div>
<h3>Class-wise Summary</h3>
<table class="data"><thead><tr><th>Class</th><th>Students</th><th>Avg Marks</th></tr></thead><tbody>
<?php foreach ($byClass as $r): ?>
<tr><td><?= e($r['class']) ?></td><td><?= e($r['c']) ?></td><td><?= e($r['m']) ?></td></tr>
<?php endforeach; ?>
</tbody></table>
<?php require 'includes/footer.php'; ?>
