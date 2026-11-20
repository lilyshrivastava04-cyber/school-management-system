<?php
require 'includes/header.php';
$id = $_GET['id'] ?? '';
$s = ['student_id'=>'','name'=>'','class'=>'','section'=>'','gender'=>'Male','attendance'=>0,'marks'=>0];
if ($id) {
    $st = $pdo->prepare('SELECT * FROM students WHERE student_id = ?'); $st->execute([$id]);
    $s = $st->fetch() ?: $s;
}
$title = $id ? 'Edit Student' : 'Add Student';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d = [trim($_POST['name']), (int)$_POST['class'], strtoupper(trim($_POST['section'])), $_POST['gender'],
          (float)$_POST['attendance'], (int)$_POST['marks']];
    if ($d[4] < 0 || $d[4] > 100 || $d[5] < 0 || $d[5] > 100) {
        $error = 'Attendance and marks must be between 0 and 100.';
    } else {
        try {
            if ($id) {
                $pdo->prepare('UPDATE students SET name=?, class=?, section=?, gender=?, attendance=?, marks=? WHERE student_id=?')
                    ->execute([...$d, $id]);
            } else {
                $pdo->prepare('INSERT INTO students (name,class,section,gender,attendance,marks,student_id) VALUES (?,?,?,?,?,?,?)')
                    ->execute([...$d, trim($_POST['student_id'])]);
            }
            header('Location: students.php?msg=Student+saved'); exit;
        } catch (PDOException $ex) { $error = 'Could not save (duplicate Student ID?).'; }
    }
    $s = array_merge($s, $_POST);
}
?>
<?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="form">
  <label>Student ID</label><input name="student_id" value="<?= e($s['student_id']) ?>" <?= $id ? 'readonly' : 'required' ?>>
  <label>Name</label><input name="name" value="<?= e($s['name']) ?>" required>
  <label>Class</label><input type="number" name="class" min="1" max="12" value="<?= e($s['class']) ?>" required>
  <label>Section</label><input name="section" maxlength="1" value="<?= e($s['section']) ?>" required>
  <label>Gender</label><select name="gender"><?php foreach (['Male','Female','Other'] as $g): ?><option <?= $s['gender']===$g?'selected':'' ?>><?= $g ?></option><?php endforeach; ?></select>
  <label>Attendance (%)</label><input type="number" step="0.01" name="attendance" value="<?= e($s['attendance']) ?>">
  <label>Marks</label><input type="number" name="marks" value="<?= e($s['marks']) ?>">
  <div><button class="btn">Save</button> <a href="students.php">Cancel</a></div>
</form>
<?php require 'includes/footer.php'; ?>
