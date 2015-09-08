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
 * 框架入口类
 */
class Warmer {
	
	/**
	 * 初始化方法
	 *
	 * @static
	 * @return void
	 */
	static function init() {
		// 定义调试模式
		defined('DEBUG') or define('DEBUG', false);
		
		// 定义框架目录
		define('CORE_PATH', dirname(__FILE__));
		
		// 当前脚本所在目录
		define('SCRIPT_PATH', dirname($_SERVER['SCRIPT_FILENAME']).'/');
		
		// 错误处理类
		include CORE_PATH.'/lib/Error.class.php';
		Error::register();
		
		 // 包含主类
		include CORE_PATH.'/lib/Main.class.php';
	}
	
	/**
	 * 启动方法
	 *
	 * @static
	 * @return void
	 */
	static function start() {
		self::init();
		Main::run();
	}
}

/**
 * 开始运行程序
 */
Warmer::start();