<?php
require_once __DIR__ . '/../../includes/functions.php';

app_session_start();

/** Redirect to login if not authenticated. Call at the top of every protected admin page. */
function require_admin_login(): void
{
    if (empty($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit;
    }
}

/** Simple brute-force throttle keyed by session. */
function login_is_throttled(): bool
{
    $_SESSION['login_attempts'] = $_SESSION['login_attempts'] ?? [];
    $_SESSION['login_attempts'] = array_filter(
        $_SESSION['login_attempts'],
        fn($t) => $t > time() - 900 // 15 minutes
    );
    return count($_SESSION['login_attempts']) >= 8;
}

function login_register_attempt(): void
{
    $_SESSION['login_attempts'][] = time();
}
