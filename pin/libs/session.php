<?php

/**
 * Session management functions
 */

/**
 * Get session value by key
 */
function session_get($key)
{
    return $_SESSION[$key] ?? null;
}

/**
 * Set session value
 */
function session_set($key, $value)
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION[$key] = $value;
}

/**
 * Delete session value
 */
function session_delete($key)
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    unset($_SESSION[$key]);
}

/**
 * Check if session key exists
 */
function session_has($key): bool
{
    return isset($_SESSION[$key]);
}
