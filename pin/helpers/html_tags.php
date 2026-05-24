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
 * form_tag
 * @param string $action
 * @param string|array $attributes
 * @return string
 */
function form_tag($action, $attributes=''){
    return "<form action='" . PUBLIC_PATH . _html_tag_escape($action) . "'" . _html_tag_attributes($attributes) . ">\r\n";
}

/**
 * end_form_tag
 * @return string
 */
function end_form_tag(){
    return "</form>\r\n";
}

/**
 * submit_tag
 * @param string $caption
 * @param string|array $attributes
 * @return string
 */
function submit_tag($caption, $attributes=''){
    return "<input type='submit' value='" . _html_tag_escape($caption) . "'" . _html_tag_attributes($attributes) . " />\r\n";
}

/**
 * button_tag
 * @param string $caption
 * @param string $type
 * @param string|array $attributes
 * @return string
 */
function button_tag($caption, $type='button', $attributes=''){
    return "<button type='" . _html_tag_escape($type) . "'" . _html_tag_attributes($attributes) . ">" . _html_tag_escape($caption) . "</button>\r\n";
}

/**
 * get_field_name_and_id
 * @param string $name
 * @return array
 */
function get_field_name_and_id($name)
{
    $id = "";
    if (strpos($name, ".") !== false) {
        $items = explode(".", $name);
        $id = " id='" . _html_tag_escape($items[0]) . "_" . _html_tag_escape($items[1]) . "' ";
        $name = $items[0] . "[" . $items[1] . "]";
    }

    return [$id, _html_tag_escape($name)];
}

/**
 * text_field_tag
 * @param string $name
 * @param string $value
 * @param string|array $attributes
 * @return string
 */
function text_field_tag($name, $value='', $attributes=''){
    list($id, $name) = get_field_name_and_id($name);

    return "<input type='text' name='" . $name . "'" . $id . " value='" . _html_tag_escape($value) . "'" . _html_tag_attributes($attributes) . " />\r\n";
}

/**
 * password_field_tag
 * @param string $name
 * @param string $value
 * @param string|array $attributes
 * @return string
 */
function password_field_tag($name, $value='', $attributes=''){
    list($id, $name) = get_field_name_and_id($name);

    return "<input type='password' name='" . $name . "'" . $id . " value='" . _html_tag_escape($value) . "'" . _html_tag_attributes($attributes) . " />\r\n";
}

/**
 * text_area_tag
 * @param string $name
 * @param string $value
 * @param string|array $attributes
 * @return string
 */
function text_area_tag($name, $value='', $attributes=''){
    list($id, $name) = get_field_name_and_id($name);

    return "<textarea name='" . $name . "'" . $id . _html_tag_attributes($attributes) . ">" . _html_tag_escape($value) . "</textarea>\r\n";
}

/**
 * hidden_field_tag
 * @param string $name
 * @param string $value
 * @param string|array $attributes
 * @return string
 */
function hidden_field_tag($name, $value='', $attributes=''){
    list($id, $name) = get_field_name_and_id($name);

    return "<input type='hidden' name='" . $name . "'" . $id . " value='" . _html_tag_escape($value) . "'" . _html_tag_attributes($attributes) . " />\r\n";
}

/**
 * check_box_tag
 * @param string $name
 * @param string $value
 * @param string $text
 * @param bool $checked
 * @param string|array $attributes
 * @return string
 */
function check_box_tag($name, $value, $text, $checked=false, $attributes=''){
    list($id, $name) = get_field_name_and_id($name);
    $checked = $checked === true ? ' checked' : '';

    return "<input type='checkbox'" . $id . " name='" . $name . "' value='" . _html_tag_escape($value) . "'" . $checked . _html_tag_attributes($attributes) . "/>\r\n";
}

/**
 * radio_button_tag
 * @param string $name
 * @param string $value
 * @param bool $checked
 * @param string|array $attributes
 * @return string
 */
function radio_button_tag($name, $value, $checked=false, $attributes=''){
    list($id, $name) = get_field_name_and_id($name);
    $checked = $checked === true ? ' checked' : '';

    return "<input type='radio'" . $id . " name='" . $name . "' value='" . _html_tag_escape($value) . "'" . $checked . _html_tag_attributes($attributes) . "/>\r\n";
}

/**
 * label_tag
 * @param string $field
 * @param string $caption
 * @param string|array $attributes
 * @return string
 */
function label_tag($field, $caption, $attributes='') {
    return "<label for='" . _html_tag_escape($field) . "'" . _html_tag_attributes($attributes) . ">" . _html_tag_escape($caption) . "</label>\r\n";
}

/**
 * select_tag
 * @param string $name
 * @param string $options
 * @param bool $include_blank
 * @param string|array $attributes
 * @return string
 */
function select_tag($name, $options='', $include_blank=false, $attributes=''){
    list($id, $name) = get_field_name_and_id($name);

    $code = "";
    if ($include_blank !== false) {
        $code = "<option value=''>" . _html_tag_escape($include_blank) . "</option>\r\n";
    }

    return "<select" . $id . " name='" . $name . "'" . _html_tag_attributes($attributes) . ">\r\n" . $code . $options . "</select>\r\n";
}

/**
 * options_for_dbselect
 * @param array $data
 * @param string $show
 * @param string $value
 * @param string $selected
 * @return string
 */
function options_for_dbselect($data, $show, $value, $selected='')
{
    $code = "";
    foreach($data as $item) {
        $selected_tag = "";
        if ($selected == $item[$value]) {
            $selected_tag = " selected='selected' ";
        }
        $code .= "<option value='" . _html_tag_escape($item[$value]) . "'" . $selected_tag . ">" . _html_tag_escape($item[$show]) . "</option>\r\n";
    }
    return $code;
}

/**
 * options_for_select
 * @param array $data
 * @param string $selected
 * @return string
 */
function options_for_select($data, $selected='')
{
    $code = "";
    foreach($data as $key => $value) {
        $selected_tag = "";
        if ($selected == $value) {
            $selected_tag = " selected='selected' ";
        }
        $code .= "<option value='" . _html_tag_escape($key) . "'" . $selected_tag . ">" . _html_tag_escape($value) . "</option>\r\n";
    }
    return $code;
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

/**
 * CSRF support
 */
function csrf_token_name(): string
{
    return '_csrf_token';
}

function csrf_token(): string
{
    if (!session_has(csrf_token_name())) {
        session_set(csrf_token_name(), bin2hex(random_bytes(32)));
    }

    return session_get(csrf_token_name());
}

function csrf_field_tag(): string
{
    return hidden_field_tag(csrf_token_name(), csrf_token());
}

function csrf_meta_tag(): string
{
    return "<meta name='csrf-token' content='" . _html_tag_escape(csrf_token()) . "'>\r\n";
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
