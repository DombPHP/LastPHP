<?php
/**core function
 */
class sys{
	/**
	*/
	static function dns($domain){
		if(!strpos('.',$domain)) return $domain;
		$host = $domain;
		$sub = explode(',',$host);
		$sub = $sub[0];
		return $sub;
	}
	static function url($domain = '',$uri = '',$mode = 0,$params = '',$seperator = '/',$sufix = '',$fragment = ''){
		$params = $params?(is_array($params)?$params:parse_str($uri)):$params;
		$query = http_build_query($params);
		$url = 'http://'.$domain.($uri&&substr($uri,0,1)!='/'?'/':'').$uri;
		switch($mode){
			case 0:
				$url .= '?'.$query; break;
			case 1:
				$seperator = $seperator?$seperator:'/';
				$query = implode($seperator,$params);
				$url .= '/'.$query;break;
		}
		$url .= ($sufix?'.'.$sufix:'');
		$url .= $fragment?'#'.$fragment:'';
		return $url;
	}
	static function redirect($url = ''){
		header('location:'.$url);
	}
	static function get($c='',$k=''){
		return defined($k)?constant($k):isset($c[$k])?$c[$k]:'';
	}
}