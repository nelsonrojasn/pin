<?php

/**
 * javascript_include_tag
 * @param string $src
 * @return string
 */
function javascript_include_tag($src){
	return "<script type='text/javascript' src='".PUBLIC_PATH."js/$src.js'></script>\r\n";
}

/**
 * stylesheet_link
 * @param string $src
 * @return string
 */
function stylesheet_link($src=''){
	return "<link rel='stylesheet' type='text/css' href='".PUBLIC_PATH."css/$src.css'/>\r\n";
}

/**
 * link_to
 * @param string $action
 * @param string $text
 * @param string $attributes
 * @return string
 */
function link_to($action, $text, $attributes=''){
	return "<a href='".PUBLIC_PATH."$action' $attributes>$text</a>";
}

/**
 * img_tag
 * @param string $img
 * @param string $attributes
 * @return string
 */
function img_tag($img, $attributes=''){
	return "<img src='".PUBLIC_PATH."img/$img' $attributes />\r\n";
}

/**
 * form_tag
 * @param string $action
 * @param string $attributes
 * @return string
 */
function form_tag($action, $attributes=''){
	return "<form action='".PUBLIC_PATH."$action' $attributes>\r\n";
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
 * @param string $attributes
 * @return string
 */
function submit_tag($caption, $attributes=''){
	return "<input type='submit' value='$caption' $attributes />\r\n";
}

/**
 * button_tag
 * @param string $caption
 * @param string $type
 * @param string $attributes
 * @return string
 */
function button_tag($caption, $type='button', $attributes=''){
	return "<button type='$type' $attributes>$caption</button>\r\n";
}

/**
 * get_field_name_and_id
 * @param string $name
 * @return array
 */
function get_field_name_and_id($name)
{
	$id="";
	if (strpos($name, ".") != false) {
		$items = explode(".", $name);
		$id=" id='{$items[0]}_{$items[1]}' ";
		$name = $items[0]."[".$items[1]."]";
	}
	return [$id, $name];
}

/**
 * text_field_tag
 * @param string $name
 * @param string $value
 * @param string $attributes
 * @return string
 */
function text_field_tag($name, $value='', $attributes=''){
	list($id, $name) = get_field_name_and_id($name);
	
	return "<input type='text' name='$name' $id value='$value' $attributes />\r\n";
}

/**
 * password_field_tag
 * @param string $name
 * @param string $value
 * @param string $attributes
 * @return string
 */
function password_field_tag($name, $value='', $attributes=''){
	list($id, $name) = get_field_name_and_id($name);
	
	return "<input type='password' name='$name' $id value='$value' $attributes />\r\n";
}

/**
 * text_area_tag
 * @param string $name
 * @param string $value
 * @param string $attributes
 * @return string
 */
function text_area_tag($name, $value='', $attributes=''){
	list($id, $name) = get_field_name_and_id($name);
	
	return "<textarea name='$name' $id $attributes>$value</textarea>\r\n";
}

/**
 * hidden_field_tag
 * @param string $name
 * @param string $value
 * @param string $attributes
 * @return string
 */
function hidden_field_tag($name, $value='', $attributes=''){
	list($id, $name) = get_field_name_and_id($name);
	
	return "<input type='hidden' name='$name' $id value='$value' $attributes />\r\n";
}

/**
 * check_box_tag
 * @param string $name
 * @param string $value
 * @param string $text
 * @param bool $checked
 * @param string $attributes
 * @return string
 */
function check_box_tag($name, $value, $text, $checked=false, $attributes=''){
	list($id, $name) = get_field_name_and_id($name);
	$checked = $checked == true ? ' checked ' : '';
	return "<input type='checkbox' $id name='$name' $checked $attributes value='$value'/>\r\n";
}

/**
 * radio_button_tag
 * @param string $name
 * @param string $value
 * @param bool $checked
 * @param string $attributes
 * @return string
 */
function radio_button_tag($name, $value, $checked=false, $attributes=''){
	list($id, $name) = get_field_name_and_id($name);
	$checked = $checked == true ? ' checked ' : '';
	return "<input type='radio' $id name='$name' $checked $attributes value='$value'/>\r\n";
}

/**
 * label_tag
 * @param string $field
 * @param string $caption
 * @param string $attributes
 * @return string
 */
function label_tag($field, $caption, $attributes='') {
    return "<label for='$field' $attributes>$caption</label>\r\n";
}

/**
 * select_tag
 * @param string $name
 * @param string $options
 * @param bool $include_blank
 * @param string $attributes
 * @return string
 */
function select_tag($name, $options='', $include_blank=false, $attributes=''){
	list($id, $name) = get_field_name_and_id($name);
	
	$code = "";
	if ($include_blank != false) {
		$code="<option value=''>$include_blank</option>\r\n";
	}		
	
	return "<select $id name='$name' $attributes>\r\n$code$options</select>\r\n";

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
	$code="";
	foreach($data as $item) {
		$selected_tag="";
		if ($selected == $item[$value]){
			$selected_tag = " selected='selected' ";
		}
		$code.="<option value='{$item[$value]}' $selected_tag>{$item[$show]}</option>\r\n";
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
	$code="";
	foreach($data as $key => $value) {
		$selected_tag="";
		if ($selected == $value){
			$selected_tag = " selected='selected' ";
		}
		$code.="<option value='$key' $selected_tag>$value</option>\r\n";
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
	$seconds*=1000;
	return "<script type=\"text/javascript\">setTimeout('window.location=\"?/$action\"', $seconds)</script>";
}

/**
 * button_to_action
 * @param string $caption
 * @param string $action
 * @param string $attributes
 * @return string
 */
function button_to_action($caption, $action, $attributes=''){
	return "<button type='button' $attributes  onclick='window.location=\"".PUBLIC_PATH."$action\"'>$caption</button>";
}
