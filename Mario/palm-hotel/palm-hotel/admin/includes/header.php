<?php
require_once __DIR__ . '/auth.php';
require_admin_login();
$currentPage = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= isset($pageTitle) ? h($pageTitle) . ' — ' : '' ?>Admin — The Palm Hotel</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
  <aside class="admin-sidebar">
    <div class="admin-brand">THE PALM<span>Admin Panel</span></div>
    <nav>
      <a href="dashboard.php" class="<?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">Dashboard</a>
      <a href="bookings.php" class="<?= $currentPage === 'bookings.php' ? 'active' : '' ?>">Bookings</a>
      <a href="rooms.php" class="<?= in_array($currentPage, ['rooms.php', 'room-form.php']) ? 'active' : '' ?>">Rooms</a>
    </nav>
    <div class="admin-user">
      <span>Signed in as <strong><?= h($_SESSION['admin_username'] ?? '') ?></strong></span>
      <a href="logout.php" class="logout-link">Log out</a>
    </div>
  </aside>
  <main class="admin-content">
