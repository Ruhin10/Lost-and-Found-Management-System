<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Remove all session values */
$_SESSION = [];

/* Remove the session cookie */
if (ini_get('session.use_cookies')) {
    $cookieParameters = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $cookieParameters['path'],
        $cookieParameters['domain'],
        (bool) $cookieParameters['secure'],
        (bool) $cookieParameters['httponly']
    );
}

/* Destroy the session */
session_destroy();

/* Return to login page */
header(
    "Location: ../View/AdminLogin.php?message=logged_out"
);

exit;