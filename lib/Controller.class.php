<?php
/**
 * Warmer
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Michael Lee
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
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
 * @lisense      The MIT License (MIT)
 * @version      0.2.0
 */
 
/**
 * 框架控制器类
 */
class Controller {
	
	/**
	 * 视图对象
	 *
	 * @access protected
	 * @var View
	 */
	protected $view = null;
	
	/**
	 * 构造方法
	 */
	function __construct() {
		// 实例化视图类
		$this->view = new View();
		
		// 设置模板文件名
		$this->view->setFile(METHOD);
		
		// 设置模板引擎
		$this->view->setTemplateEngine(TEMPLATE_ENGINE);
		
		// 设置模板后缀名
		$this->view->setTemplateSuffix(TEMPLATE_SUFFIX);
		
		// 设置模板目录
		$this->view->setTemplateDir(TEMPLATE_DIR);
		
		// 设置模板缓存
		$this->view->setCacheDir(CACHE_DIR);
		
		// 初始化视图类
		$this->view->init();
	}
	
	/**
	 * 输出页面内容
	 *
	 * @access protected
	 * @param string $file 模板文件
	 * @return void
	 */
	protected function display($file = '') {
		$this->view->display($file);
	}
	
	/**
	 * 页面变量赋值
	 *
	 * @access protected
	 * @param string $name 变量名称
	 * @param mixed $val 变量值
	 * @return void
	 */
	protected function assign($name, $val) {
		$this->view->assign($name, $val);
	}
	
	/**
	 * 获取页面内容
	 *
	 * @access protected
	 * @param string $file 模板文件
	 * @return string
	 */
	protected function fetch($file = '') {
		return $this->view->fetch($file);
	}
	
	/**
	 * 成功提示页面
	 *
	 * @access protected
	 * @param string $msg 提醒信息
	 * @param string $go 跳转页面
	 * @param integer $delay 跳转延迟时间，以秒为单位
	 * @return void
	 */
	protected function success($msg, $go = '', $delay = 3) {
		$title = isset($GLOBALS['CONF']['tips_success_title']) && !empty($GLOBALS['CONF']['tips_success_title']) ? $GLOBALS['CONF']['tips_success_title'] : 'Success';
		$this->_tips('tips_success', $msg, $go, $title, $delay);
	}
	
	/**
	 * 操作失败提示页面
	 *
	 * @access protected
	 * @param string $msg 提醒信息
	 * @param string $go 跳转页面
	 * @param integer $delay 跳转延迟时间，以秒为单位
	 * @return void
	 */
	protected function error($msg, $go = '', $delay = 3) {
		$title = isset($GLOBALS['CONF']['tips_error_title']) && !empty($GLOBALS['CONF']['tips_error_title']) ? $GLOBALS['CONF']['tips_error_title'] : 'Success';		
		$this->_tips('tips_error', $msg, $go, $title, $delay);
	}
	
	/**
	 * 地址重定向
	 *
	 * @access protected
	 * @param string $url 网址
	 * @return void
	 */
	protected function redirect($url) {
		header('location:'.$url);
	}
	
	/**
	 * 获取配置文件中的提示页面文件
	 *
	 * @access private
	 * @param string $name 配置项名称
	 * @return void
	 */
	private function _getFile($name) {
		if(isset($GLOBALS['CONF'][$name])) {
			$tpl = $GLOBALS['CONF'][$name];
			if(!$tpl || !is_file($tpl)) {
				$tpl = CORE_PATH.'/error/'.$name.'.php';
			}			
		} else {
			$tpl = CORE_PATH.'/error/'.$name.'.php';
		}
		return $tpl;
	}
	
	/**
	 * 输出提示页面
	 *
	 * @access private
	 * @param string $type 提示类型名称
	 * @param string $msg 提醒信息
	 * @param string $go 跳转页面
	 * @param string $title 提示页面标题
	 * @param integer $delay 跳转延迟时间，以秒为单位
	 * @return void
	 */
	private function _tips($type, $msg = '', $go = '', $title = '', $delay = 3) {
		$this->assign('msg', $msg);
		$this->assign('go', $go);
		$this->assign('title', $title);
		$this->assign('delay', $delay);
		$this->display($this->_getFile($type));
		die();
	}
}
