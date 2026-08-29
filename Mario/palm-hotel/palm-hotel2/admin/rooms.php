<?php
$pageTitle = 'Rooms';
require_once __DIR__ . '/includes/header.php';

$flash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    if (!csrf_check($_POST['csrf_token'] ?? null)) {
        $flash = 'Session expired, please try again.';
    } else {
        $roomId = filter_var($_POST['room_id'] ?? '', FILTER_VALIDATE_INT);
        if ($roomId) {
            $stmt = db()->prepare('DELETE FROM rooms WHERE id = :id');
            $stmt->execute(['id' => $roomId]);
            $flash = "Room #{$roomId} deleted.";
        }
    }
}

$rooms = db()->query('SELECT * FROM rooms ORDER BY sort_order ASC, id ASC')->fetchAll();
?>

<h1>Rooms</h1>
<p class="subtitle">Manage the room types shown on the website.</p>

<?php if ($flash): ?><p class="notice"><?= h($flash) ?></p><?php endif; ?>

<a href="room-form.php" class="btn-primary">+ Add New Room</a>

<table class="data-table">
  <thead>
    <tr><th>Name</th><th>Size</th><th>Occupancy</th><th>Price / Night</th><th>Units</th><th>Active</th><th>Actions</th></tr>
  </thead>
  <tbody>
    <?php if (!$rooms): ?>
      <tr><td colspan="7" class="empty">No rooms yet.</td></tr>
    <?php else: foreach ($rooms as $r): ?>
      <tr>
        <td><?= h($r['name']) ?></td>
        <td><?= (int) $r['size_sqm'] ?> m²</td>
        <td><?= h($r['occupancy']) ?></td>
        <td>EGP <?= number_format((float) $r['price_per_night']) ?></td>
        <td><?= (int) $r['total_units'] ?></td>
        <td><?= $r['is_active'] ? 'Yes' : 'No' ?></td>
        <td class="actions-cell">
          <a href="room-form.php?id=<?= (int) $r['id'] ?>" class="btn-secondary-small">Edit</a>
          <form method="post" class="inline-form" onsubmit="return confirm('Delete this room type permanently? Existing bookings for it will also be removed.');">
            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
            <input type="hidden" name="room_id" value="<?= (int) $r['id'] ?>">
            <input type="hidden" name="action" value="delete">
            <button type="submit" class="btn-danger-small">Delete</button>
          </form>
        </td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
