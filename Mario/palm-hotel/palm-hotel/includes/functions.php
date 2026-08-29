<?php
require_once __DIR__ . '/../config/db.php';

/** Escape a string for safe HTML output. */
function h(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/** Start (or resume) the PHP session with sane cookie settings. */
function app_session_start(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }
}

/** Generate (or reuse) a CSRF token for the current session. */
function csrf_token(): string
{
    app_session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Validate a submitted CSRF token. */
function csrf_check(?string $token): bool
{
    app_session_start();
    return !empty($_SESSION['csrf_token']) && !empty($token)
        && hash_equals($_SESSION['csrf_token'], $token);
}

/** Very small helper to return a JSON response and stop execution. */
function json_response(array $data, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/** Basic email format check. */
function is_valid_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/** Basic phone check — digits, spaces, +, -, at least 7 digits. */
function is_valid_phone(string $phone): bool
{
    $digits = preg_replace('/\D/', '', $phone);
    return strlen($digits) >= 7 && preg_match('/^[0-9+\s\-()]+$/', $phone);
}

/**
 * Count how many bookings for a room overlap a given date range
 * (excludes cancelled bookings). Used to enforce room availability.
 */
function count_overlapping_bookings(int $roomId, string $checkIn, string $checkOut, ?int $excludeBookingId = null): int
{
    $sql = "SELECT COUNT(*) FROM bookings
            WHERE room_id = :room_id
              AND status <> 'cancelled'
              AND check_in < :check_out
              AND check_out > :check_in";
    $params = [
        'room_id'    => $roomId,
        'check_out'  => $checkOut,
        'check_in'   => $checkIn,
    ];
    if ($excludeBookingId !== null) {
        $sql .= ' AND id <> :exclude_id';
        $params['exclude_id'] = $excludeBookingId;
    }
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return (int) $stmt->fetchColumn();
}

/** Fetch a room by id, or null if it doesn't exist / is inactive. */
function get_active_room(int $roomId): ?array
{
    $stmt = db()->prepare('SELECT * FROM rooms WHERE id = :id AND is_active = 1');
    $stmt->execute(['id' => $roomId]);
    $room = $stmt->fetch();
    return $room ?: null;
}

/**
 * Send a plain-text email using PHP's mail(). On most Bluehost/cPanel
 * shared hosting this works out of the box because mail() is routed
 * through the server's local mail transfer agent. If deliverability
 * is unreliable, switch this function to use PHPMailer + SMTP instead
 * (see README.md for instructions).
 */
function send_mail(string $to, string $subject, string $body): bool
{
    $fromHeader = MAIL_FROM_NAME . ' <' . MAIL_FROM_EMAIL . '>';
    $headers  = "From: {$fromHeader}\r\n";
    $headers .= "Reply-To: {$fromHeader}\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $subjectEncoded = '=?UTF-8?B?' . base64_encode($subject) . '?=';

    return @mail($to, $subjectEncoded, $body, $headers);
}
