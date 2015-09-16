<?php
/**
 * Warmer
 *
 * An open source web application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Michael Lee
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @copyright    2015 Michael Lee
 * @author       Micheal Lee <michaellee15@sina.com>
 * @license      The MIT License (MIT)
 * @version      0.2.0
 */

/**
 * 包含MultiMysqli类
 */
include CORE_PATH.'/pack/Micsqli/MultiMysqli.class.php';

/**
 * 扩展MultiMysqli类
 */
class MultiMysqliExt extends \Micsqli\MultiMysqli {

	/**
	 * 静态类实例
	 *
	 * @static
	 * @access private
	 * @var MultiMysqliExt
	 */
	private static $instance = null;
	
	/**
	 * 构造方法
	 *
	 * @access public
	 * @param mixed $conf 配置参数
	 * @return void
	 */
	public function __construct($conf = null) {
		$this->global_conf = $GLOBALS['CONF'];
		parent::__construct($conf);
	}
	
	/**
	 * 获取类实例
	 *
	 * @access public
	 * @param array $conf 配置参数
	 * @return MultiMysqli
	 */
	public static function getInstance($conf = null) {
		if(self::$instance && self::$instance instanceof self) {
			return self::$instance;
		} else {
			self::$instance = new self($conf);
			return self::$instance;
		}
	}
	
	/**
	 * 加载网站配置参数
	 *
	 * @static
	 * @access private
	 * @return array
	 */
	private function load_site_conf() {
		$conf_path = SCRIPT_PATH.'/conf/hosts.php';
		if(is_file($conf_path)) {
			return array_change_key_case(parse_ini_file($conf_path, true), CASE_LOWER);
		}
		return array();
	}
}