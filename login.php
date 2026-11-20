<?php
require_once 'config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $st = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $st->execute([trim($_POST['username'] ?? '')]);
    $u = $st->fetch();
    if ($u && password_verify($_POST['password'] ?? '', $u['password'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = $u['username'];
        header('Location: index.php'); exit;
    }
    $error = 'Invalid username or password';
}
?><!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login - School Management System</title><link rel="stylesheet" href="assets/style.css"></head>
<body class="login-body">
<form method="post" class="login-box">
  <h2>🏫 School Management System</h2>
  <p class="muted">Admin Login</p>
  <?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
  <label>Username</label><input name="username" required autofocus>
  <label>Password</label><input type="password" name="password" required>
  <button class="btn">Login</button>
</form>
</body></html>
