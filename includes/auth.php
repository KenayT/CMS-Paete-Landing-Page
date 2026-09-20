<?php

require_once __DIR__ . '/functions.php';

/**
 * Use this in src/admin/*.php (HTML pages).
 * No session -> browser gets redirected to the login page.
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /CMS-Paete-Landing-Page-main/src/admin/login.php');
        exit;
    }
}

function isLoggedIn(): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return !empty($_SESSION['admin_id']);
}

/**
 * Use this in api/admin/*.php (fetch() endpoints).
 * No session -> 401 JSON. Never a redirect — a redirected fetch()
 * response can't be parsed as JSON on the front end.
 */
function requireApiLogin(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['admin_id'])) {
        jsonResponse(['error' => 'Unauthorized. Please log in.'], 401);
        // jsonResponse() already exits, so execution stops here.
    }
}