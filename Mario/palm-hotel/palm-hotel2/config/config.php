<?php
/**
 * =========================================================
 * The Palm Hotel — Main Configuration
 * =========================================================
 * Edit the values below with the details from your Bluehost
 * cPanel account (MySQL Databases page). See README.md.
 */

// ---- Database credentials (from cPanel > MySQL Databases) ----
// On Bluehost these are usually prefixed with your cPanel username,
// e.g. "yourcpaneluser_palmhotel"
define('DB_HOST', 'localhost');
define('DB_NAME', 'yourcpaneluser_palmhotel');
define('DB_USER', 'yourcpaneluser_palmuser');
define('DB_PASS', 'CHANGE_ME');

// ---- Site settings ----
define('SITE_NAME', 'The Palm Hotel');
define('SITE_URL', 'https://www.yourdomain.com');       // no trailing slash
define('RESERVATION_EMAIL', 'reservation@chain-luxe.com'); // where booking notifications are sent
define('MAIL_FROM_EMAIL', 'no-reply@yourdomain.com');    // must be an address @ your own domain for best deliverability
define('MAIL_FROM_NAME', 'The Palm Hotel Website');

// ---- Security ----
// Reserved random secret for any future signing/encryption needs
// (e.g. signed tokens). Not currently used elsewhere in the app —
// CSRF protection uses per-session random tokens instead — but you
// should still replace this with your own long random string before
// going live, since third-party code or future features may rely on it.
define('APP_SECRET', 'change-this-to-a-long-random-string-1234567890');

// ---- Timezone ----
date_default_timezone_set('Africa/Cairo');

// ---- Error display (turn OFF once live) ----
define('APP_DEBUG', false);
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}
