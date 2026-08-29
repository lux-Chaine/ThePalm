<?php
$pageTitle = 'Bookings';
require_once __DIR__ . '/includes/header.php';

$flash = '';

// ---- handle status update / delete ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf_token'] ?? null)) {
        $flash = 'Session expired, please try again.';
    } else {
        $bookingId = filter_var($_POST['booking_id'] ?? '', FILTER_VALIDATE_INT);
        $action    = $_POST['action'] ?? '';

        if ($bookingId) {
            if ($action === 'set_status') {
                $status = $_POST['status'] ?? '';
                if (in_array($status, ['pending', 'confirmed', 'cancelled'], true)) {
                    $stmt = db()->prepare('UPDATE bookings SET status = :s WHERE id = :id');
                    $stmt->execute(['s' => $status, 'id' => $bookingId]);
                    $flash = "Booking #{$bookingId} updated to " . ucfirst($status) . '.';
                }
            } elseif ($action === 'delete') {
                $stmt = db()->prepare('DELETE FROM bookings WHERE id = :id');
                $stmt->execute(['id' => $bookingId]);
                $flash = "Booking #{$bookingId} deleted.";
            }
        }
    }
}

// ---- filters ----
$statusFilter = $_GET['status'] ?? 'all';
$allowedStatus = ['all', 'pending', 'confirmed', 'cancelled'];
if (!in_array($statusFilter, $allowedStatus, true)) {
    $statusFilter = 'all';
}

$sql = "SELECT b.*, r.name AS room_name FROM bookings b JOIN rooms r ON r.id = b.room_id";
$params = [];
if ($statusFilter !== 'all') {
    $sql .= ' WHERE b.status = :status';
    $params['status'] = $statusFilter;
}
$sql .= ' ORDER BY b.created_at DESC';

$stmt = db()->prepare($sql);
$stmt->execute($params);
$bookings = $stmt->fetchAll();
?>

<h1>Bookings</h1>
<p class="subtitle">All reservation requests submitted from the website.</p>

<?php if ($flash): ?><p class="notice"><?= h($flash) ?></p><?php endif; ?>

<div class="filter-row">
  <?php foreach ($allowedStatus as $s): ?>
    <a href="?status=<?= $s ?>" class="filter-pill <?= $statusFilter === $s ? 'active' : '' ?>"><?= ucfirst($s) ?></a>
  <?php endforeach; ?>
</div>

<table class="data-table">
  <thead>
    <tr>
      <th>#</th><th>Guest</th><th>Room</th><th>Check-in</th><th>Check-out</th>
      <th>Guests</th><th>Contact</th><th>Status</th><th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!$bookings): ?>
      <tr><td colspan="9" class="empty">No bookings found.</td></tr>
    <?php else: foreach ($bookings as $b): ?>
      <tr>
        <td>#<?= (int) $b['id'] ?></td>
        <td><?= h($b['full_name']) ?><?php if ($b['notes']): ?><br><small class="muted"><?= h($b['notes']) ?></small><?php endif; ?></td>
        <td><?= h($b['room_name']) ?></td>
        <td><?= h($b['check_in']) ?></td>
        <td><?= h($b['check_out']) ?></td>
        <td><?= (int) $b['adults'] ?>A / <?= (int) $b['children'] ?>C</td>
        <td><?= h($b['email']) ?><br><small class="muted"><?= h($b['phone']) ?></small></td>
        <td><span class="badge badge-<?= h($b['status']) ?>"><?= h(ucfirst($b['status'])) ?></span></td>
        <td class="actions-cell">
          <form method="post" class="inline-form">
            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
            <input type="hidden" name="booking_id" value="<?= (int) $b['id'] ?>">
            <input type="hidden" name="action" value="set_status">
            <select name="status" onchange="this.form.submit()">
              <option value="pending" <?= $b['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
              <option value="confirmed" <?= $b['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
              <option value="cancelled" <?= $b['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
          </form>
          <form method="post" class="inline-form" onsubmit="return confirm('Delete this booking permanently?');">
            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
            <input type="hidden" name="booking_id" value="<?= (int) $b['id'] ?>">
            <input type="hidden" name="action" value="delete">
            <button type="submit" class="btn-danger-small">Delete</button>
          </form>
        </td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
