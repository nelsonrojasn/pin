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
            return (string)$value;
    }
}

/**
 * Escape HTML output (use in templates)
 */
function html($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/**
 * Validate and decrypt an encrypted URL hash
 * @param string $encrypted_url - The encrypted URL hash from query param 'r'
 * @return bool - True if valid and decryptable, false otherwise
 */
function is_valid_url_hash(string $encrypted_url): bool
{
    // Permitir URLs sin hash para páginas públicas
    if (empty($encrypted_url) || $encrypted_url === '/') {
        return true; 
    }

    $decrypted = desencriptar($encrypted_url, CRIPTO_KEY);
    
    // Si retorna vacío, significa que falló la validación de integridad (HMAC)
    if (empty($decrypted)) {
        return false;
    }

    // Validar que el formato desencriptado sea válido: ?page=xxx[&action=yyy[&param=val]]
    // Debe contener al menos ?page=
    return strpos($decrypted, '?page=') === 0;
}

/**
 * Parse and decrypt an encrypted URL hash
 * Extracts page, action, and parameters from the encrypted URL
 * @param string $encrypted_url - The encrypted URL hash
 * @return array - [page, action, parameters] where parameters is an associative array
 * @throws Exception - If decryption fails or format is invalid
 */
function parse_url_hash(string $encrypted_url): array
{
    if (empty($encrypted_url) || $encrypted_url === '/') {
        return ['default', 'index', []]; // Página por defecto para URLs vacías
    }

    $decrypted = desencriptar($encrypted_url, CRIPTO_KEY);
    
    if (empty($decrypted)) {
        throw new Exception("URL inválida o manipulada.", 404);
    }

    // Remover el '?' inicial
    if (strpos($decrypted, '?') === 0) {
        $decrypted = substr($decrypted, 1);
    }

    // Parsear como query string
    $parsed = [];
    parse_str($decrypted, $parsed);

    // Extraer componentes requeridos
    $page = $parsed['page'] ?? null;
    $action = $parsed['action'] ?? 'index';
    
    // Remover page y action del array para dejar solo los parámetros
    unset($parsed['page'], $parsed['action']);
    $parameters = $parsed;

    if (!$page) {
        throw new Exception("URL debe contener el parámetro 'page'.", 404);
    }

    return [$page, $action, $parameters];
}

/**
 * Generate an encrypted URL hash for use in links
 * @param string $page - The page name
 * @param string|null $action - Optional action name (defaults to 'index')
 * @param array $parameters - Optional additional parameters
 * @return string - The encrypted URL hash
 */
function encrypt_url(string $page, ?string $action = null, array $parameters = []): string
{
    // Construir el query string
    $query_parts = ['page' => $page];
    
    if ($action && $action !== 'index') {
        $query_parts['action'] = $action;
    }

    // Añadir parámetros adicionales
    $query_parts = array_merge($query_parts, $parameters);

    // Generar query string
    $query_string = '?' . http_build_query($query_parts);

    // Encriptar y retornar
    return encriptar($query_string, CRIPTO_KEY);
}
