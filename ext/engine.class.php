<?php
class Engine{
	
	//模版文件夹
	public $template_dir = '';
	
	//缓存文件夹
	public $cache_dir = '';
	
	//缓存文件后缀名
	public $cache_suffix = 'tpl';
	
	function __construct($conf = ''){
		$this->cache_dir = !empty($conf['CACHE_DIR'])?$conf['CACHE_DIR']:$this->cache_dir;
		$this->cache_suffix = !empty($conf['CACHE_SUFFIX'])?$conf['CACHE_SUFFIX']:$this->cache_suffix;
	}

	public function fetch($filepath = '', $parameters = false){
		if(!is_file($filepath))
			throw new Exception('模版文件路径不正确');
		$this->_fetch($filepath,$parameters);
	}
	private function _fetch($filepath = '',$parameters = false){
		if(!is_dir($this->cache_dir))
			throw new Exception('缓存目录不正确');
		$savepath = $this->cache_dir.'/'.md5($filepath).'.'.$this->cache_suffix;
		if($this->parse($filepath,$savepath)){
			extract($parameters);
			include($savepath);
			return true;
		}
		return false;
	}
	public function compile($savepath = '',$content = ''){
		if(empty($this->cache_dir)) return false;
		if(!empty($savepath)){
			try{
				file_put_contents($savepath,$content);
			}catch(Exception $e){
				throw new Exception('创建缓存文件失败');
			}
			return true;
		}
		return false;
	}
	public function parse($filepath = '',$savepath = ''){
		if(is_file($filepath)){
			if(is_file($savepath)&&filemtime($filepath)<filemtime($savepath)){
				//return true;
			}
			$content = $this->_parse($filepath);
			return $this->compile($savepath, $content);
		}
		return false;
	}
	private function _parse($filepath){
		$content = file_get_contents($filepath);
		if($content===false) throw Exception('文件获取失败');
		$content = ''.$content;
		$content = $this->parse_echo($content);
		$content = $this->parse_include($content);
		$content = $this->parse_foreach($content);
		$content = $this->parse_for($content);
		$content = $this->parse_while($content);
		$content = $this->parse_if($content);
		$content = $this->parse_php($content);
		return $content;
	}
	private function parse_include($content = ''){
		//
		return $content;
	}
	private function parse_foreach($content = ''){
		
		return $content;
	}
	private function parse_while($content = ''){
		
		return $content;
	}
	private function parse_for($content = ''){
		
		return $content;
	}
	private function parse_if($content = ''){
		
		return $content;
	}
	private function parse_echo($content = ''){
		$content = preg_replace('/\{\$(\w+?)\}/is','<?php echo \$$1;?>',$content);
		return $content;
	}
	private function parse_php($content = ''){
		$content = preg_replace('/<php>([\w\W]*?)<\/php>/is','<?php $1 ?>',$content);
		return $content;
	}
	/**
	 * 获取页面内容
	 */
	public function get_include_contents($filename,$parameters) {
		extract($parameters);
		if (is_file($filename)) {
			ob_start();
			include $filename;
			return ob_get_clean();
		}
		return false;
	}
}