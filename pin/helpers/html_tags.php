<?php

/**
 * Escape a value for HTML attribute output.
 */
function _html_tag_escape($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

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
            $output .= ' ' . $name . '="' . _html_tag_escape($value) . '"';
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
    return "<script type='text/javascript' src='" . PUBLIC_PATH . _html_tag_escape($src) . ".js'></script>\r\n";
}

/**
 * stylesheet_link
 * @param string $src
 * @return string
 */
function stylesheet_link($src=''){
    return "<link rel='stylesheet' type='text/css' href='" . PUBLIC_PATH . _html_tag_escape($src) . ".css'/>\r\n";
}

/**
 * resolve_url
 * @param string $url
 * @return string
 */
function resolve_url(string $url)
{
    return PUBLIC_PATH . _html_tag_escape($url);
}

/**
 * link_to
 * @param string $action
 * @param string $text
 * @param string|array $attributes
 * @return string
 */
function link_to($action, $text, $attributes=''){
    return "<a href='" . PUBLIC_PATH . _html_tag_escape($action) . "'" . _html_tag_attributes($attributes) . ">" . _html_tag_escape($text) . "</a>";
}

/**
 * img_tag
 * @param string $img
 * @param string|array $attributes
 * @return string
 */
function img_tag($img, $attributes=''){
    return "<img src='" . PUBLIC_PATH . _html_tag_escape($img) . "'" . _html_tag_attributes($attributes) . " />\r\n";
}

/**
 * js_redirect_to
 * @param string $action
 * @param float $seconds
 * @return string
 */
function js_redirect_to($action, $seconds = 0.01){
    $seconds *= 1000;
    return "<script type=\"text/javascript\">setTimeout('window.location=\"?/" . _html_tag_escape($action) . "\"', $seconds)</script>";
}
