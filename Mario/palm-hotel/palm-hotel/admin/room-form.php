<?php
$pageTitle = 'Room Form';
require_once __DIR__ . '/includes/header.php';

$roomId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
$room = [
    'id' => null, 'name' => '', 'slug' => '', 'size_sqm' => '', 'config_text' => '',
    'description' => '', 'occupancy' => '', 'view_type' => '', 'floor_range' => '',
    'amenities' => '', 'price_per_night' => '', 'total_units' => 1,
    'image_1' => '', 'image_2' => '', 'sort_order' => 0, 'is_active' => 1,
];
$error = '';

if ($roomId) {
    $stmt = db()->prepare('SELECT * FROM rooms WHERE id = :id');
    $stmt->execute(['id' => $roomId]);
    $found = $stmt->fetch();
    if ($found) {
        $room = $found;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf_token'] ?? null)) {
        $error = 'Session expired, please try again.';
    } else {
        $room['name']            = trim($_POST['name'] ?? '');
        $room['size_sqm']        = filter_var($_POST['size_sqm'] ?? 0, FILTER_VALIDATE_INT);
        $room['config_text']     = trim($_POST['config_text'] ?? '');
        $room['description']     = trim($_POST['description'] ?? '');
        $room['occupancy']       = trim($_POST['occupancy'] ?? '');
        $room['view_type']       = trim($_POST['view_type'] ?? '');
        $room['floor_range']     = trim($_POST['floor_range'] ?? '');
        $room['amenities']       = trim($_POST['amenities'] ?? '');
        $room['price_per_night'] = filter_var($_POST['price_per_night'] ?? 0, FILTER_VALIDATE_FLOAT);
        $room['total_units']     = filter_var($_POST['total_units'] ?? 1, FILTER_VALIDATE_INT);
        $room['image_1']         = trim($_POST['image_1'] ?? '');
        $room['image_2']         = trim($_POST['image_2'] ?? '');
        $room['sort_order']      = filter_var($_POST['sort_order'] ?? 0, FILTER_VALIDATE_INT);
        $room['is_active']       = isset($_POST['is_active']) ? 1 : 0;

        if ($room['name'] === '') {
            $error = 'Room name is required.';
        } elseif (!$room['price_per_night'] || $room['price_per_night'] <= 0) {
            $error = 'Please enter a valid price per night.';
        } elseif (!$room['total_units'] || $room['total_units'] < 1) {
            $error = 'Total units must be at least 1.';
        } else {
            // auto-generate a URL-safe slug from the name
            $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $room['name']), '-'));
            $room['slug'] = $slug !== '' ? $slug : ('room-' . time());

            if ($room['id']) {
                $stmt = db()->prepare(
                    'UPDATE rooms SET name=:name, slug=:slug, size_sqm=:size_sqm, config_text=:config_text,
                     description=:description, occupancy=:occupancy, view_type=:view_type, floor_range=:floor_range,
                     amenities=:amenities, price_per_night=:price_per_night, total_units=:total_units,
                     image_1=:image_1, image_2=:image_2, sort_order=:sort_order, is_active=:is_active
                     WHERE id=:id'
                );
                $room['id_param'] = $room['id'];
            } else {
                $stmt = db()->prepare(
                    'INSERT INTO rooms (name, slug, size_sqm, config_text, description, occupancy, view_type,
                     floor_range, amenities, price_per_night, total_units, image_1, image_2, sort_order, is_active)
                     VALUES (:name, :slug, :size_sqm, :config_text, :description, :occupancy, :view_type,
                     :floor_range, :amenities, :price_per_night, :total_units, :image_1, :image_2, :sort_order, :is_active)'
                );
            }

            $bindings = [
                'name' => $room['name'], 'slug' => $room['slug'], 'size_sqm' => $room['size_sqm'],
                'config_text' => $room['config_text'], 'description' => $room['description'],
                'occupancy' => $room['occupancy'], 'view_type' => $room['view_type'],
                'floor_range' => $room['floor_range'], 'amenities' => $room['amenities'],
                'price_per_night' => $room['price_per_night'], 'total_units' => $room['total_units'],
                'image_1' => $room['image_1'], 'image_2' => $room['image_2'],
                'sort_order' => $room['sort_order'], 'is_active' => $room['is_active'],
            ];
            if ($room['id']) {
                $bindings['id'] = $room['id'];
            }
            $stmt->execute($bindings);

            header('Location: rooms.php');
            exit;
        }
    }
}
?>

<h1><?= $room['id'] ? 'Edit Room' : 'Add New Room' ?></h1>

<?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>

<form method="post" class="admin-form">
  <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

  <label>Room Name
    <input type="text" name="name" value="<?= h($room['name']) ?>" required>
  </label>

  <div class="form-grid">
    <label>Size (m²)
      <input type="number" name="size_sqm" value="<?= h((string) $room['size_sqm']) ?>" min="0">
    </label>
    <label>Price per night (EGP)
      <input type="number" step="0.01" name="price_per_night" value="<?= h((string) $room['price_per_night']) ?>" required min="0">
    </label>
    <label>Total units available
      <input type="number" name="total_units" value="<?= h((string) $room['total_units']) ?>" min="1" required>
    </label>
    <label>Sort order
      <input type="number" name="sort_order" value="<?= h((string) $room['sort_order']) ?>">
    </label>
  </div>

  <label>Bed configuration text
    <input type="text" name="config_text" value="<?= h($room['config_text']) ?>" placeholder="e.g. King Bed (4 Rooms) · Twin Bed (28 Rooms)">
  </label>

  <label>Description
    <textarea name="description" rows="4"><?= h($room['description']) ?></textarea>
  </label>

  <div class="form-grid">
    <label>Occupancy
      <input type="text" name="occupancy" value="<?= h($room['occupancy']) ?>" placeholder="e.g. 2 Adults">
    </label>
    <label>View
      <input type="text" name="view_type" value="<?= h($room['view_type']) ?>" placeholder="e.g. City / Courtyard">
    </label>
    <label>Floor range
      <input type="text" name="floor_range" value="<?= h($room['floor_range']) ?>" placeholder="e.g. 1st – 4th">
    </label>
  </div>

  <label>Amenities (comma separated)
    <input type="text" name="amenities" value="<?= h($room['amenities']) ?>" placeholder="A/C, Shower, Safe Box, WiFi">
  </label>

  <div class="form-grid">
    <label>Image URL 1
      <input type="text" name="image_1" value="<?= h($room['image_1']) ?>">
    </label>
    <label>Image URL 2
      <input type="text" name="image_2" value="<?= h($room['image_2']) ?>">
    </label>
  </div>

  <label class="checkbox-label">
    <input type="checkbox" name="is_active" <?= $room['is_active'] ? 'checked' : '' ?>>
    Show this room on the website
  </label>

  <div class="form-actions">
    <button type="submit" class="btn-primary"><?= $room['id'] ? 'Save Changes' : 'Create Room' ?></button>
    <a href="rooms.php" class="btn-secondary-small">Cancel</a>
  </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
