<?php
$title = 'Teachers';
require 'includes/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $pdo->prepare('DELETE FROM teachers WHERE teacher_id = ?')->execute([(int)$_POST['delete']]);
    } else {
        $pdo->prepare('INSERT INTO teachers (name,subject,phone,email) VALUES (?,?,?,?)')
            ->execute([trim($_POST['name']), trim($_POST['subject']), trim($_POST['phone']), trim($_POST['email'])]);
    }
    header('Location: teachers.php'); exit;
}
$rows = $pdo->query('SELECT * FROM teachers ORDER BY name')->fetchAll();
?>
<form method="post" class="toolbar inline">
  <input name="name" placeholder="Teacher name" required>
  <input name="subject" placeholder="Subject" required>
  <input name="phone" placeholder="Phone">
  <input type="email" name="email" placeholder="Email">
  <button class="btn">+ Add Teacher</button>
</form>
<table class="data"><thead><tr><th>#</th><th>Name</th><th>Subject</th><th>Phone</th><th>Email</th><th></th></tr></thead><tbody>
<?php foreach ($rows as $r): ?>
<tr><td><?= e($r['teacher_id']) ?></td><td><?= e($r['name']) ?></td><td><?= e($r['subject']) ?></td><td><?= e($r['phone']) ?></td><td><?= e($r['email']) ?></td>
<td><form method="post" class="delete inline"><input type="hidden" name="delete" value="<?= e($r['teacher_id']) ?>"><button class="link danger">Delete</button></form></td></tr>
<?php endforeach; ?>
</tbody></table>
<?php require 'includes/footer.php'; ?>
