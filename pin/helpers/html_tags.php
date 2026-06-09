<?php

/**
 * Build HTML attributes from string or array.
 */
function _html_tag_attributes($attributes)
{
    if (empty($attributes)) {
        return '';
    }

    if (is_array($attributes)) {
        $output = '';
        foreach ($attributes as $name => $value) {
            if ($value === null || $value === false) {
                continue;
            }
            $output .= ' ' . $name . '="' . html($value) . '"';
        }

        return $output;
    }

    return ' ' . trim($attributes);
}

/**
 * javascript_include_tag
 * @param string $src
 * @return string
 */
function javascript_include_tag($src){
    return "<script type='text/javascript' src='" . PUBLIC_PATH . html($src) . ".js'></script>\r\n";
}

/**
 * stylesheet_link
 * @param string $src
 * @return string
 */
function stylesheet_link($src=''){
    return "<link rel='stylesheet' type='text/css' href='" . PUBLIC_PATH . html($src) . ".css'/>\r\n";
}

/**
 * asset
 * @param string $path
 * @return string
 */
function asset(string $path)
{
    // Tu lógica actual: directo al grano y sanitizado para HTML
    return PUBLIC_PATH . html($path);
}

function url(string $page, string $action = 'index', null|array $parameters = null)
{
    // Usa encrypt_url() de request.php para encriptar la URL
    $encrypted = encrypt_url($page, $action, $parameters ?? []);
    return PUBLIC_PATH . '?r=' . $encrypted;
}

/**
 * img_tag
 * @param string $img
 * @param string|array $attributes
 * @return string
 */
function img_tag($img, $attributes=''){
    return "<img src='" . PUBLIC_PATH . html($img) . "'" . _html_tag_attributes($attributes) . " />\r\n";
}
