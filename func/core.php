<?php
/**core function
 */
function U($domain = '',$uri = '',$mode = 0,$params = '',$seperator = '/',$sufix = '',$fragment = ''){
	$domain = $_SERVER['HTTP_HOST'];
	//$uri = $_SERVER['SCRIPT_NAME'];
	return sys::url($domain,$uri,$mode,$params,$seperator,$sufix,$fragment);
}
function g($key=''){
	global $_configure;
	return sys::get($_configure,$key);
}