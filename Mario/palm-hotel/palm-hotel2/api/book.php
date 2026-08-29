<?php
/**
 * POST /api/book.php
 * Handles a new reservation request submitted from the website's
 * booking form. Validates input, checks room availability, stores
 * the booking as "pending", and emails both the hotel and the guest.
 */
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['ok' => false, 'error' => 'Invalid request method.'], 405);
}

app_session_start();

// ---- rate limit: max 5 submissions per 10 minutes, checked both by
// session (fast) and by IP via the database (can't be bypassed just
// by clearing cookies) ----
$_SESSION['booking_attempts'] = $_SESSION['booking_attempts'] ?? [];
$_SESSION['booking_attempts'] = array_filter(
    $_SESSION['booking_attempts'],
    fn($t) => $t > time() - 600
);
if (count($_SESSION['booking_attempts']) >= 5 || rate_limit_hit('booking', 5, 600)) {
    json_response(['ok' => false, 'error' => 'Too many requests. Please try again later.'], 429);
}

// ---- honeypot spam trap (hidden field must stay empty) ----
if (!empty($_POST['website'])) {
    json_response(['ok' => false, 'error' => 'Invalid submission.'], 400);
}

// ---- CSRF check ----
if (!csrf_check($_POST['csrf_token'] ?? null)) {
    json_response(['ok' => false, 'error' => 'Your session expired. Please refresh the page and try again.'], 419);
}

// ---- collect + validate input ----
$roomId   = filter_var($_POST['room_id'] ?? '', FILTER_VALIDATE_INT);
$fullName = trim($_POST['full_name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$checkIn  = trim($_POST['check_in'] ?? '');
$checkOut = trim($_POST['check_out'] ?? '');
$adults   = filter_var($_POST['adults'] ?? 1, FILTER_VALIDATE_INT);
$children = filter_var($_POST['children'] ?? 0, FILTER_VALIDATE_INT);
$notes    = trim($_POST['notes'] ?? '');

$errors = [];

if (!$roomId || !get_active_room($roomId)) {
    $errors[] = 'Please select a valid room type.';
}
if ($fullName === '' || mb_strlen($fullName) > 150) {
    $errors[] = 'Please enter your full name.';
}
if ($email === '' || !is_valid_email($email)) {
    $errors[] = 'Please enter a valid email address.';
}
if ($phone === '' || !is_valid_phone($phone)) {
    $errors[] = 'Please enter a valid phone number.';
}
$today = date('Y-m-d');
if (!$checkIn || !$checkOut || !DateTime::createFromFormat('Y-m-d', $checkIn) || !DateTime::createFromFormat('Y-m-d', $checkOut)) {
    $errors[] = 'Please choose valid check-in and check-out dates.';
} elseif ($checkIn < $today) {
    $errors[] = 'Check-in date cannot be in the past.';
} elseif ($checkOut <= $checkIn) {
    $errors[] = 'Check-out date must be after the check-in date.';
}
if ($adults === false || $adults < 1 || $adults > 10) {
    $errors[] = 'Please enter a valid number of adults.';
}
if ($children === false || $children < 0 || $children > 10) {
    $errors[] = 'Please enter a valid number of children.';
}
if (mb_strlen($notes) > 1000) {
    $errors[] = 'Notes are too long.';
}

if (!empty($errors)) {
    json_response(['ok' => false, 'error' => implode(' ', $errors)], 422);
}

$room = get_active_room($roomId);

// ---- check availability against total_units for that room type ----
$overlapping = count_overlapping_bookings($roomId, $checkIn, $checkOut);
if ($overlapping >= (int) $room['total_units']) {
    json_response([
        'ok'    => false,
        'error' => 'Sorry, the ' . $room['name'] . ' is fully booked for those dates. Please try different dates or another room type.',
    ], 409);
}

// ---- store booking ----
try {
    $stmt = db()->prepare(
        'INSERT INTO bookings (room_id, full_name, email, phone, check_in, check_out, adults, children, notes, status)
         VALUES (:room_id, :full_name, :email, :phone, :check_in, :check_out, :adults, :children, :notes, "pending")'
    );
    $stmt->execute([
        'room_id'    => $roomId,
        'full_name'  => $fullName,
        'email'      => $email,
        'phone'      => $phone,
        'check_in'   => $checkIn,
        'check_out'  => $checkOut,
        'adults'     => $adults,
        'children'   => $children,
        'notes'      => $notes,
    ]);
    $bookingId = (int) db()->lastInsertId();
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => 'Something went wrong while saving your request. Please try again.'], 500);
}

$_SESSION['booking_attempts'][] = time();
rate_limit_record('booking');

// ---- notify the hotel ----
$hotelBody = "New reservation request received on the website.\n\n"
    . "Booking ID: #{$bookingId}\n"
    . "Room type: {$room['name']}\n"
    . "Guest: {$fullName}\n"
    . "Email: {$email}\n"
    . "Phone: {$phone}\n"
    . "Check-in: {$checkIn}\n"
    . "Check-out: {$checkOut}\n"
    . "Adults: {$adults}  Children: {$children}\n"
    . "Notes: " . ($notes !== '' ? $notes : '-') . "\n\n"
    . "Manage this booking in the admin panel: " . SITE_URL . "/admin/bookings.php\n";
send_mail(RESERVATION_EMAIL, 'New Booking Request #' . $bookingId . ' — ' . SITE_NAME, $hotelBody);

// ---- confirmation email to the guest ----
$guestBody = "Dear {$fullName},\n\n"
    . "Thank you for your reservation request with " . SITE_NAME . ".\n\n"
    . "Room type: {$room['name']}\n"
    . "Check-in: {$checkIn}\n"
    . "Check-out: {$checkOut}\n"
    . "Guests: {$adults} adult(s), {$children} child(ren)\n\n"
    . "Your request is now pending confirmation from our reservations team. "
    . "We will contact you shortly at {$phone} or by replying to this email.\n\n"
    . "Warm regards,\n" . SITE_NAME . "\n" . RESERVATION_EMAIL;
send_mail($email, 'We received your reservation request — ' . SITE_NAME, $guestBody);

json_response([
    'ok'         => true,
    'booking_id' => $bookingId,
    'message'    => 'Thank you! Your reservation request has been received. Our team will confirm availability with you shortly by email or phone.',
]);
