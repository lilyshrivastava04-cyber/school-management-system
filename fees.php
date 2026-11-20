<?php
$title = 'Fees';
require 'includes/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['toggle'])) {
        $pdo->prepare("UPDATE fees SET status = IF(status='Paid','Pending','Paid') WHERE id = ?")->execute([(int)$_POST['toggle']]);
    } else {
        $pdo->prepare('INSERT INTO fees (student_id, amount, due_date, status) VALUES (?,?,?,?)')
            ->execute([$_POST['student_id'], (float)$_POST['amount'], $_POST['due_date'], $_POST['status']]);
    }
    header('Location: fees.php'); exit;
}
$students = $pdo->query('SELECT student_id, name FROM students ORDER BY student_id')->fetchAll();
$rows = $pdo->query('SELECT f.*, s.name FROM fees f JOIN students s USING (student_id) ORDER BY f.due_date DESC, f.id DESC')->fetchAll();
?>
<form method="post" class="toolbar inline">
  <select name="student_id" required><?php foreach ($students as $s): ?><option value="<?= e($s['student_id']) ?>"><?= e($s['student_id'] . ' - ' . $s['name']) ?></option><?php endforeach; ?></select>
  <input type="number" step="0.01" name="amount" placeholder="Amount" required>
  <input type="date" name="due_date" required>
  <select name="status"><option>Pending</option><option>Paid</option></select>
  <button class="btn">+ Add Fee</button>
</form>
<table class="data"><thead><tr><th>Student</th><th>Amount</th><th>Due Date</th><th>Status</th><th></th></tr></thead><tbody>
<?php foreach ($rows as $r): ?>
<tr><td><?= e($r['student_id'] . ' - ' . $r['name']) ?></td><td>₹<?= number_format($r['amount']) ?></td><td><?= e($r['due_date']) ?></td>
<td><span class="pill <?= $r['status']==='Paid' ? 'good' : 'low' ?>"><?= e($r['status']) ?></span></td>
<td><form method="post" class="inline"><input type="hidden" name="toggle" value="<?= e($r['id']) ?>"><button class="link">Mark <?= $r['status']==='Paid' ? 'Pending' : 'Paid' ?></button></form></td></tr>
<?php endforeach; ?>
</tbody></table>
<?php require 'includes/footer.php'; ?>
