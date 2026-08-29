<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/header.php';

$pendingCount   = (int) db()->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'")->fetchColumn();
$confirmedCount = (int) db()->query("SELECT COUNT(*) FROM bookings WHERE status = 'confirmed'")->fetchColumn();
$cancelledCount = (int) db()->query("SELECT COUNT(*) FROM bookings WHERE status = 'cancelled'")->fetchColumn();
$roomCount      = (int) db()->query("SELECT COUNT(*) FROM rooms WHERE is_active = 1")->fetchColumn();

$upcoming = db()->query(
    "SELECT b.*, r.name AS room_name FROM bookings b
     JOIN rooms r ON r.id = b.room_id
     WHERE b.status <> 'cancelled' AND b.check_in >= CURDATE()
     ORDER BY b.check_in ASC LIMIT 8"
)->fetchAll();
?>

<h1>Dashboard</h1>
<p class="subtitle">Overview of reservation activity.</p>

<div class="stat-cards">
  <div class="stat-card">
    <span class="stat-num"><?= $pendingCount ?></span>
    <span class="stat-label">Pending Requests</span>
  </div>
  <div class="stat-card">
    <span class="stat-num"><?= $confirmedCount ?></span>
    <span class="stat-label">Confirmed Bookings</span>
  </div>
  <div class="stat-card">
    <span class="stat-num"><?= $cancelledCount ?></span>
    <span class="stat-label">Cancelled</span>
  </div>
  <div class="stat-card">
    <span class="stat-num"><?= $roomCount ?></span>
    <span class="stat-label">Active Room Types</span>
  </div>
</div>

<h2 class="section-title">Upcoming Arrivals</h2>
<table class="data-table">
  <thead>
    <tr><th>Guest</th><th>Room</th><th>Check-in</th><th>Check-out</th><th>Status</th></tr>
  </thead>
  <tbody>
    <?php if (!$upcoming): ?>
      <tr><td colspan="5" class="empty">No upcoming bookings.</td></tr>
    <?php else: foreach ($upcoming as $b): ?>
      <tr>
        <td><?= h($b['full_name']) ?></td>
        <td><?= h($b['room_name']) ?></td>
        <td><?= h($b['check_in']) ?></td>
        <td><?= h($b['check_out']) ?></td>
        <td><span class="badge badge-<?= h($b['status']) ?>"><?= h(ucfirst($b['status'])) ?></span></td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
