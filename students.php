<?php
$title = 'Students';
require 'includes/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $pdo->prepare('DELETE FROM students WHERE student_id = ?')->execute([$_POST['delete']]);
    header('Location: students.php?msg=Student+deleted'); exit;
}
$q = trim($_GET['q'] ?? '');
$class = $_GET['class'] ?? '';
$sql = 'SELECT * FROM students WHERE (name LIKE ? OR student_id LIKE ?)';
$args = ["%$q%", "%$q%"];
if ($class !== '') { $sql .= ' AND class = ?'; $args[] = $class; }
$st = $pdo->prepare($sql . ' ORDER BY student_id');
$st->execute($args);
$rows = $st->fetchAll();
?>
<?php if (!empty($_GET['msg'])): ?><div class="alert ok"><?= e($_GET['msg']) ?></div><?php endif; ?>
<div class="toolbar">
  <form method="get" class="inline">
    <input name="q" placeholder="Search name or ID" value="<?= e($q) ?>">
    <select name="class"><option value="">All classes</option>
      <?php for ($i = 1; $i <= 12; $i++): ?><option <?= $class == $i ? 'selected' : '' ?>><?= $i ?></option><?php endfor; ?>
    </select>
    <button class="btn">Search</button>
  </form>
  <a class="btn" href="student_form.php">+ Add Student</a>
</div>
<table class="data"><thead><tr><th>ID</th><th>Name</th><th>Class</th><th>Section</th><th>Gender</th><th>Attendance</th><th>Marks</th><th>Actions</th></tr></thead><tbody>
<?php foreach ($rows as $r): ?>
<tr><td><?= e($r['student_id']) ?></td><td><?= e($r['name']) ?></td><td><?= e($r['class']) ?></td>
<td><?= e($r['section']) ?></td><td><?= e($r['gender']) ?></td>
<td><span class="pill <?= $r['attendance'] < 80 ? 'low' : 'good' ?>"><?= e($r['attendance']) ?>%</span></td>
<td><?= e($r['marks']) ?></td>
<td class="actions"><a href="student_form.php?id=<?= urlencode($r['student_id']) ?>">Edit</a>
<form method="post" class="delete inline"><input type="hidden" name="delete" value="<?= e($r['student_id']) ?>"><button class="link danger">Delete</button></form></td></tr>
<?php endforeach; if (!$rows): ?><tr><td colspan="8">No students found.</td></tr><?php endif; ?>
</tbody></table>
<?php require 'includes/footer.php'; ?>
