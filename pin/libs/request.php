<?php

/**
 * Safe request handling for $_POST and $_GET
 * All values are sanitized by default
 */

/**
 * Get POST value with sanitization
 * @param string $key - Variable name
 * @param string $type - Filter type: 'string', 'int', 'email', 'bool', 'url'
 * @return mixed|null
 */
function request_post(string $key, string $type = 'string')
{
    if (!filter_has_var(INPUT_POST, $key)) {
        return null;
    }

    return _sanitize($_POST[$key], $type);
}

/**
 * Check if POST key exists
 */
function request_has_post(string $key): bool
{
    return filter_has_var(INPUT_POST, $key);
}

/**
 * Get GET value with sanitization
 * @param string $key - Variable name
 * @param string $type - Filter type: 'string', 'int', 'email', 'bool', 'url'
 * @return mixed|null
 */
function request_get(string $key, string $type = 'string')
{
    if (!filter_has_var(INPUT_GET, $key)) {
        return null;
    }

    return _sanitize($_GET[$key], $type);
}

/**
 * Check if GET key exists
 */
function request_has_get(string $key): bool
{
    return filter_has_var(INPUT_GET, $key);
}

/**
 * Get REQUEST value (POST > GET) with sanitization
 */
function request_input(string $key, string $type = 'string')
{
    return request_post($key, $type) ?? request_get($key, $type);
}

/**
 * Sanitize value based on type
 */
function _sanitize($value, string $type = 'string')
{
    if (is_array($value)) {
        return array_map(fn($v) => _sanitize($v, $type), $value);
    }

    switch ($type) {
        case 'int':
        case 'integer':
            return (int)$value;

        case 'float':
        case 'decimal':
            return (float)$value;

        case 'bool':
        case 'boolean':
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);

        case 'email':
            return filter_var($value, FILTER_VALIDATE_EMAIL) ? $value : null;

        case 'url':
            return filter_var($value, FILTER_VALIDATE_URL) ? $value : null;

        case 'string':
        default:
            return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Escape HTML output (use in templates)
 */
function html($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
