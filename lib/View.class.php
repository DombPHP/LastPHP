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
 * 框架视图类
 */
class View {
	/**
	 * 模板目录
	 *
	 * @var string
	 */
	private $template_dir = '';
	
	/**
	 * 模板缓存目录
	 *
	 * @var string
	 */
	private $cache_dir = '';
	
	/**
	 * 模板文件后缀
	 *
	 * @var string
	 */
	private $template_suffix = 'tpl';
	
	/**
	 * 模版引擎对象
	 *
	 * @var object
	 */
	private $template = null;
	
	/**
	 * 模板变量
	 *
	 * @var array
	 */
	private $vars = array();
	
	/**
	 * 模板文件名称
	 *
	 * @var string
	 */
	private $file = '';
	
	/**
	 * 模板引擎名称
	 *
	 * @var string
	 */
	private $engine = '';
	
	/**
	 * 初始化方法
	 *
	 * @access public
	 * @return void
	 */
	public function init() {
		if(!$this->engine) return false;
		$this->template = new $this->engine;
		if(!is_dir($this->template_dir)) {
			throw new Exception('Directory \''.$this->template_dir.'\' not found');
		}
		$this->template->setTemplateDir($this->template_dir);
		if(!is_dir($this->cache_dir)) {
			throw new Exception('Directory \''.$this->cache_dir.'\' not found');
		}
		$this->template->setCompileDir($this->cache_dir);
	}
	
	/**
	 * 提取页面内容
	 *
	 * @access public
	 * @param string $file 模板文件名称
	 * return string
	 */
	public function fetch($file = '') {
		if(is_file($file)) {
			return $this->get_include_contents($file, $this->vars);
		}
		$file = ($file?$file:$this->file).'.'.$this->template_suffix;
		if($this->template) {
			$this->template->assign($this->vars);
			return $this->template->fetch($file);
		} else {
			return $this->get_include_contents($this->template_dir.$file, $this->vars);
		}
	}
	
	/**
	 * 获取页面内容
	 *
	 * @access public
	 * @param string $file 模板文件名称
	 * @return void
	 */
	public function display($file = '') {
		echo $this->fetch($file);
	}
	
	/**
	 * 模板变量赋值
	 *
	 * @access public
	 * @param string $name 模板变量名称
	 * @param mixed $value 模板变量值
	 * @return void
	 */
	public function assign($name, $value = null) {
		if(is_array($name)) {
			// 第一个参数使用数组可一次设置多个变量
			$this->vars = array_merge($this->vars, $name);
		} else {
			// 一次设置一个变量
			$this->vars[$name] = $value;
		}
	}

	/**
	 * 获取页面内容
	 *
	 * @access private
	 * @param string $filename 模板文件完整名称
	 * @param array $parameters 模板变量
	 * @return string
	 */
	private function get_include_contents($filename, $parameters) {
		extract($parameters);
		if(is_file($filename)) {
			ob_start();
			include $filename;
			return ob_get_clean();
		}
		return '';
	}
	
	/**
	 * 设置模板目录
	 *
	 * @access public
	 * @param string $tpl 模板目录
	 */
	public function setTemplateDir($tpl = '') {
		$this->template_dir = $tpl;
	}
	
	/**
	 * 设置模板缓存魔力
	 *
	 * @access public
	 * @param string $c 模板缓存目录
	 * @return void
	 */
	public function setCacheDir($c = '') {
		$this->cache_dir = $c;
	}
	
	/**
	 * 设置模板后缀
	 *
	 * @access public
	 * @param string $s 模板后缀
	 * @return void
	 */
	public function setTemplateSuffix($s) {
		$this->template_suffix = $s;
	}
	
	/**
	 * 设置模板引擎
	 *
	 * @access public
	 * @param string $e 模板引擎名称
	 * @return void
	 */
	public function setTemplateEngine($e) {
		$this->engine = $e;
	}
	
	/**
	 * 设置模板名称
	 *
	 * @access public
	 * @param string $file 模板文件名称
	 * @return void
	 */
	public function setFile($file = '') {
		$this->file = $file;
	}
}