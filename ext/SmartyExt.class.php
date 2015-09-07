<?php
include CORE_PATH.'/pack/smarty-3.1.27/libs/Smarty.class.php';
class SmartyExt extends Smarty implements iTemplate {
	
	public function __construct() {
		parent::__construct();
		// $this->caching              = true;
		// $this->template_dir = TEMPLATE_DIR;
		// $this->compile_dir = CACHE_DIR;
		// $this->cache_dir = CACHE_DIR;
	}
}