<?php
require_once __DIR__ . '/includes/auth.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Two independent throttles: session-based (fast, resets if the
    // attacker clears cookies) and IP-based via the database (survives
    // cookie clearing / private browsing, so it's the real backstop).
    if (login_is_throttled() || rate_limit_hit('login', 8, 900)) {
        $error = 'Too many attempts. Please wait 15 minutes and try again.';
    } elseif (!csrf_check($_POST['csrf_token'] ?? null)) {
        $error = 'Your session expired. Please reload the page and try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = (string) ($_POST['password'] ?? '');

        $stmt = db()->prepare('SELECT * FROM admin_users WHERE username = :u LIMIT 1');
        $stmt->execute(['u' => $username]);
        $admin = $stmt->fetch();

        login_register_attempt();
        rate_limit_record('login');

        if ($admin && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']       = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            unset($_SESSION['login_attempts']);
            header('Location: dashboard.php');
            exit;
        }
        $error = 'Invalid username or password.';
    }
}

$existingCount = (int) db()->query('SELECT COUNT(*) FROM admin_users')->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login — The Palm Hotel</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="auth-page">
  <div class="auth-card">
    <h1>The Palm Hotel</h1>
    <p class="hint">Admin sign in</p>

    <?php if ($existingCount === 0): ?>
      <p class="notice">No admin account exists yet. Please run <a href="setup.php">setup.php</a> first.</p>
    <?php else: ?>
      <?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
      <form method="post" novalidate>
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <label>Username
          <input type="text" name="username" required autofocus>
        </label>
        <label>Password
          <input type="password" name="password" required>
        </label>
        <button type="submit">Sign In</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
