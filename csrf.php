<?php
// csrf.php

// Ensure session is active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Generate and store CSRF token
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Get the current CSRF token
 */
function get_csrf_token() {
    return $_SESSION['csrf_token'] ?? '';
}

/**
 * Validate the submitted CSRF token
 */
function validate_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Invalidate the token (optional, for one-time use)
 */
function clear_csrf_token() {
    unset($_SESSION['csrf_token']);
}
