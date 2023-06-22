<?php

function javascript_include_tag($src){
	return "<script type='text/javascript' src='".PUBLIC_PATH."js/$src.js'></script>\r\n";
}

function stylesheet_link($src=''){
	return "<link rel='stylesheet' type='text/css' href='".PUBLIC_PATH."css/$src.css'/>\r\n";
}

function link_to($action, $text, $attributes=''){
	return "<a href='".PUBLIC_PATH."$action' $attributes>$text</a>";
}

function img_tag($img, $attributes=''){
	return "<img src='".PUBLIC_PATH."img/$img' $attributes />\r\n";
}

function form_tag($action, $attributes=''){
	return "<form action='".PUBLIC_PATH."$action' $attributes>\r\n";
}

function end_form_tag(){
	return "</form>\r\n";
}

function submit_tag($caption, $attributes=''){
	return "<input type='submit' value='$caption' $attributes />\r\n";
}

function button_tag($caption, $type='button', $attributes=''){
	return "<button type='$type' $attributes>$caption</button>\r\n";
}

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

function text_field_tag($name, $value='', $attributes=''){
	list($id, $name) = get_field_name_and_id($name);
	
	return "<input type='text' name='$name' $id value='$value' $attributes />\r\n";
}

function password_field_tag($name, $value='', $attributes=''){
	list($id, $name) = get_field_name_and_id($name);
	
	return "<input type='password' name='$name' $id value='$value' $attributes />\r\n";
}

function text_area_tag($name, $value='', $attributes=''){
	list($id, $name) = get_field_name_and_id($name);
	
	return "<textarea name='$name' $id $attributes>$value</textarea>\r\n";
}

function hidden_field_tag($name, $value='', $attributes=''){
	list($id, $name) = get_field_name_and_id($name);
	
	return "<input type='hidden' name='$name' $id value='$value' $attributes />\r\n";
}

function check_box_tag($name, $value, $text, $checked=false, $attributes=''){
	list($id, $name) = get_field_name_and_id($name);
	$checked = $checked == true ? ' checked ' : '';
	return "<input type='checkbox' $id name='$name' $checked $attributes value='$value'/>\r\n";
}

function radio_button_tag($name, $value, $checked=false, $attributes=''){
	list($id, $name) = get_field_name_and_id($name);
	$checked = $checked == true ? ' checked ' : '';
	return "<input type='radio' $id name='$name' $checked $attributes value='$value'/>\r\n";
}

function label_tag($field, $caption, $attributes='') {
    return "<label for='$field' $attributes>$caption</label>\r\n";
}

function select_tag($name='', $options, $include_blank=false, $attributes=''){
	list($id, $name) = get_field_name_and_id($name);
	
	$code = "";
	if ($include_blank != false) {
		$code="<option value=''>$include_blank</option>\r\n";
	}		
	
	return "<select $id name='$name' $attributes>\r\n$code$options</select>\r\n";

}

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

function js_redirect_to($action, $seconds = 0.01){
	$seconds*=1000;
	return "<script type=\"text/javascript\">setTimeout('window.location=\"?/$action\"', $seconds)</script>";
}

function button_to_action($caption, $action, $attributes=''){
	return "<button type='button' $attributes  onclick='window.location=\"".PUBLIC_PATH."$action\"'>$caption</button>";
}
