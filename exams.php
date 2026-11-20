<?php
$title = 'Examinations';
require 'includes/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $pdo->prepare('DELETE FROM exams WHERE id = ?')->execute([(int)$_POST['delete']]);
    } else {
        $pdo->prepare('INSERT INTO exams (student_id,exam_name,subject,marks_obtained,max_marks) VALUES (?,?,?,?,?)')
            ->execute([$_POST['student_id'], trim($_POST['exam_name']), trim($_POST['subject']), (int)$_POST['marks'], (int)$_POST['max']]);
    }
    header('Location: exams.php'); exit;
}
$students = $pdo->query('SELECT student_id, name FROM students ORDER BY student_id')->fetchAll();
$rows = $pdo->query('SELECT e.*, s.name FROM exams e JOIN students s USING (student_id) ORDER BY e.id DESC')->fetchAll();
?>
<form method="post" class="toolbar inline">
  <select name="student_id" required><?php foreach ($students as $s): ?><option value="<?= e($s['student_id']) ?>"><?= e($s['student_id'] . ' - ' . $s['name']) ?></option><?php endforeach; ?></select>
  <input name="exam_name" placeholder="Exam (e.g. Mid Term)" required>
  <input name="subject" placeholder="Subject" required>
  <input type="number" name="marks" placeholder="Marks" min="0" required>
  <input type="number" name="max" value="100" min="1" required>
  <button class="btn">+ Add Result</button>
</form>
<table class="data"><thead><tr><th>Student</th><th>Exam</th><th>Subject</th><th>Marks</th><th>Percent</th><th></th></tr></thead><tbody>
<?php foreach ($rows as $r): ?>
<tr><td><?= e($r['student_id'] . ' - ' . $r['name']) ?></td><td><?= e($r['exam_name']) ?></td><td><?= e($r['subject']) ?></td>
<td><?= e($r['marks_obtained']) ?>/<?= e($r['max_marks']) ?></td><td><?= round($r['marks_obtained'] / $r['max_marks'] * 100, 1) ?>%</td>
<td><form method="post" class="delete inline"><input type="hidden" name="delete" value="<?= e($r['id']) ?>"><button class="link danger">Delete</button></form></td></tr>
<?php endforeach; ?>
</tbody></table>
<?php require 'includes/footer.php'; ?>
