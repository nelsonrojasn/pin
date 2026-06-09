<?php

/**
 * Obtiene un valor de la petición o del entorno global usando notación de puntos.
 * Ejemplo: get_value("usuario.nombre")
 */
function get_value(string $field, mixed $default = '')
{
    $parts = explode('.', $field);
    $key = $parts[0];
    
    // 1. Buscamos en el input de la petición (POST/GET)
    // 2. Si no existe, buscamos en las variables globales (extraídas por load_view)
    $data = request_input($key) ?? ($GLOBALS[$key] ?? null);

    if ($data === null) return $default;
    if (count($parts) === 1) return $data;

    // Navegamos por el array usando las partes restantes
    foreach (array_slice($parts, 1) as $part) {
        if (is_array($data) && array_key_exists($part, $data)) {
            $data = $data[$part];
        } else {
            return $default;
        }
    }

    return $data;
}

function select_tag($name, $options='', $include_blank=false, $attributes=''){
    $code = "";
    if ($include_blank !== false) {
        $code = "<option value=''>" . html($include_blank) . "</option>\r\n";
    }

    return "<select name='" . html($name) . "'" . _html_tag_attributes($attributes) . ">\r\n" . $code . $options . "</select>\r\n";
}

function options_for_dbselect($data, $show, $value, $selected='')
{
    $code = "";
    foreach($data as $item) {
        $selected_tag = "";
        if ($selected == $item[$value]) {
            $selected_tag = " selected='selected' ";
        }
        $code .= "<option value='" . html($item[$value]) . "'" . $selected_tag . ">" . html($item[$show]) . "</option>\r\n";
    }
    return $code;
}

function options_for_select($data, $selected='')
{
    $code = "";
    foreach($data as $key => $value) {
        $selected_tag = "";
        if ($selected == $value) {
            $selected_tag = " selected='selected' ";
        }
        $code .= "<option value='" . html($key) . "'" . $selected_tag . ">" . html($value) . "</option>\r\n";
    }
    return $code;
}

function csrf_token_name(): string
{
    return '_csrf_token';
}

function csrf_token(): string
{
    if (!session_has(csrf_token_name())) {
        session_set(csrf_token_name(), bin2hex(random_bytes(32)));
    }

    $token = session_get(csrf_token_name());
    if (!is_string($token) || $token === '') {
        $token = bin2hex(random_bytes(32));
        session_set(csrf_token_name(), $token);
    }

    return $token;
}

function csrf_field_tag(): string
{
    return "<input type='hidden' name='" . html(csrf_token_name()) . "' value='" . html(csrf_token()) . "' />\r\n";
}

function csrf_meta_tag(): string
{
    return "<meta name='csrf-token' content='" . html(csrf_token()) . "'>\r\n";
}

function csrf_check(?string $token = null): bool
{
    if ($token === null) {
        $token = request_post(csrf_token_name(), 'string');
    }

    $stored = session_get(csrf_token_name());
    return is_string($stored) && is_string($token) && hash_equals($stored, $token);
}

function csrf_protect()
{
    if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && !csrf_check()) {
        throw new Exception('Invalid CSRF token', 403);
    }
}
