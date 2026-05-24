<?php

/**
 * Alias for hidden form field names and auto-generated IDs.
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

function form_tag($action, $attributes=''){
    return "<form action='" . PUBLIC_PATH . _html_tag_escape($action) . "'" . _html_tag_attributes($attributes) . ">\r\n";
}

function end_form_tag(){
    return "</form>\r\n";
}

function submit_tag($caption, $attributes=''){
    return "<input type='submit' value='" . _html_tag_escape($caption) . "'" . _html_tag_attributes($attributes) . " />\r\n";
}

function button_tag($caption, $type='button', $attributes=''){
    return "<button type='" . _html_tag_escape($type) . "'" . _html_tag_attributes($attributes) . ">" . _html_tag_escape($caption) . "</button>\r\n";
}

function text_field_tag($name, $value='', $attributes=''){
    list($id, $name) = get_field_name_and_id($name);

    return "<input type='text' name='" . $name . "'" . $id . " value='" . _html_tag_escape($value) . "'" . _html_tag_attributes($attributes) . " />\r\n";
}

function password_field_tag($name, $value='', $attributes=''){
    list($id, $name) = get_field_name_and_id($name);

    return "<input type='password' name='" . $name . "'" . $id . " value='" . _html_tag_escape($value) . "'" . _html_tag_attributes($attributes) . " />\r\n";
}

function text_area_tag($name, $value='', $attributes=''){
    list($id, $name) = get_field_name_and_id($name);

    return "<textarea name='" . $name . "'" . $id . _html_tag_attributes($attributes) . ">" . _html_tag_escape($value) . "</textarea>\r\n";
}

function hidden_field_tag($name, $value='', $attributes=''){
    list($id, $name) = get_field_name_and_id($name);

    return "<input type='hidden' name='" . $name . "'" . $id . " value='" . _html_tag_escape($value) . "'" . _html_tag_attributes($attributes) . " />\r\n";
}

function check_box_tag($name, $value, $checked=false, $attributes=''){
    list($id, $name) = get_field_name_and_id($name);
    $checked = $checked === true ? ' checked' : '';

    return "<input type='checkbox'" . $id . " name='" . $name . "' value='" . _html_tag_escape($value) . "'" . $checked . _html_tag_attributes($attributes) . "/>\r\n";
}

function radio_button_tag($name, $value, $checked=false, $attributes=''){
    list($id, $name) = get_field_name_and_id($name);
    $checked = $checked === true ? ' checked' : '';

    return "<input type='radio'" . $id . " name='" . $name . "' value='" . _html_tag_escape($value) . "'" . $checked . _html_tag_attributes($attributes) . "/>\r\n";
}

function select_tag($name, $options='', $include_blank=false, $attributes=''){
    list($id, $name) = get_field_name_and_id($name);

    $code = "";
    if ($include_blank !== false) {
        $code = "<option value=''>" . _html_tag_escape($include_blank) . "</option>\r\n";
    }

    return "<select" . $id . " name='" . $name . "'" . _html_tag_attributes($attributes) . ">\r\n" . $code . $options . "</select>\r\n";
}

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
