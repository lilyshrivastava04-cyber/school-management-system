<?php
$title = 'Attendance';
require 'includes/header.php';
$date  = $_GET['date'] ?? date('Y-m-d');
$class = $_GET['class'] ?? '10';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ins = $pdo->prepare('INSERT INTO attendance_log (student_id, att_date, status) VALUES (?,?,?)
                          ON DUPLICATE KEY UPDATE status = VALUES(status)');
    foreach ($_POST['status'] ?? [] as $sid => $status) {
        if (in_array($status, ['Present', 'Absent'], true)) $ins->execute([$sid, $_POST['date'], $status]);
    }
    $msg = 'Attendance saved for ' . e($_POST['date']);
    $date = $_POST['date']; $class = $_POST['class'];
}
$st = $pdo->prepare('SELECT s.*, a.status FROM students s
    LEFT JOIN attendance_log a ON a.student_id = s.student_id AND a.att_date = ?
    WHERE s.class = ? ORDER BY s.student_id');
$st->execute([$date, $class]);
$rows = $st->fetchAll();
?>
<?php if ($msg): ?><div class="alert ok"><?= $msg ?></div><?php endif; ?>
<form method="get" class="toolbar inline">
  <input type="date" name="date" value="<?= e($date) ?>">
  <select name="class"><?php for ($i = 1; $i <= 12; $i++): ?><option <?= $class == $i ? 'selected' : '' ?>><?= $i ?></option><?php endfor; ?></select>
  <button class="btn">Load</button>
</form>
<form method="post">
<input type="hidden" name="date" value="<?= e($date) ?>"><input type="hidden" name="class" value="<?= e($class) ?>">
<table class="data"><thead><tr><th>ID</th><th>Name</th><th>Section</th><th>Present</th><th>Absent</th></tr></thead><tbody>
<?php foreach ($rows as $r): $cur = $r['status'] ?? 'Present'; ?>
<tr><td><?= e($r['student_id']) ?></td><td><?= e($r['name']) ?></td><td><?= e($r['section']) ?></td>
<td><input type="radio" name="status[<?= e($r['student_id']) ?>]" value="Present" <?= $cur==='Present'?'checked':'' ?>></td>
<td><input type="radio" name="status[<?= e($r['student_id']) ?>]" value="Absent" <?= $cur==='Absent'?'checked':'' ?>></td></tr>
<?php endforeach; if (!$rows): ?><tr><td colspan="5">No students in this class.</td></tr><?php endif; ?>
</tbody></table>
<?php if ($rows): ?><button class="btn">Save Attendance</button><?php endif; ?>
</form>
<?php require 'includes/footer.php'; ?>
