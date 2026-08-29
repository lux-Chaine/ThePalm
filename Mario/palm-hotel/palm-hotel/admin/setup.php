<?php
/**
 * ONE-TIME SETUP WIZARD
 * Visit /admin/setup.php once after importing sql/schema.sql to
 * create your first admin account. This page refuses to run again
 * once an admin_users row already exists, so it is safe to leave
 * the file on the server (but you may also just delete it afterwards).
 */
require_once __DIR__ . '/../includes/functions.php';

$existingCount = (int) db()->query('SELECT COUNT(*) FROM admin_users')->fetchColumn();
$error = '';
$done  = false;

if ($existingCount > 0) {
    // Setup already completed — do nothing sensitive.
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf_token'] ?? null)) {
        $error = 'Your session expired. Please reload the page and try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        $confirm  = (string) ($_POST['confirm'] ?? '');

        if (mb_strlen($username) < 3) {
            $error = 'Username must be at least 3 characters.';
        } elseif (mb_strlen($password) < 8) {
            $error = 'Password must be at least 8 characters.';
        } elseif ($password !== $confirm) {
            $error = 'Passwords do not match.';
        } else {
            $stmt = db()->prepare(
                'INSERT INTO admin_users (username, password_hash, full_name) VALUES (:u, :p, :f)'
            );
            $stmt->execute([
                'u' => $username,
                'p' => password_hash($password, PASSWORD_DEFAULT),
                'f' => 'Hotel Admin',
            ]);
            $done = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Setup — The Palm Hotel</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="auth-page">
  <div class="auth-card">
    <h1>Admin Setup</h1>

    <?php if ($existingCount > 0 && !$done): ?>
      <p class="notice">Setup has already been completed. Go to <a href="login.php">the login page</a>.</p>

    <?php elseif ($done): ?>
      <p class="success">Admin account created successfully. You can now <a href="login.php">log in</a>.</p>
      <p class="hint">For security, please delete <code>/admin/setup.php</code> from the server now.</p>

    <?php else: ?>
      <p class="hint">Create your first admin username and password. This form only works once.</p>
      <?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
      <form method="post" novalidate>
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <label>Username
          <input type="text" name="username" required minlength="3" autofocus>
        </label>
        <label>Password
          <input type="password" name="password" required minlength="8">
        </label>
        <label>Confirm Password
          <input type="password" name="confirm" required minlength="8">
        </label>
        <button type="submit">Create Admin Account</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
