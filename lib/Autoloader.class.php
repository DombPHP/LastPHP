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
  * 自动加载器
  */
class Autoloader {
	
	/**
	 * 注册自动加载器
	 *
	 * @static
	 * @access public
	 * @return void
	 */
	public static function register() {
		spl_autoload_register("Autoloader::autoload");
	}
	
	/**
	 * 自动加载方法
	 *
	 * @static
	 * @access public
	 * @param string $class_name 类名称
	 * @return void
	 */
	public static function autoload($class_name) {
		// 命名空间自动导入
		if(stripos($class_name, '\\')) {
			$names = explode('\\', $class_name);
			
			// 控制器导入
			if($names[0]=='Controller') {
				$names[0] = 'controller';
				$_class = implode('/', $names);
				if(is_file($file = APP_PATH.'/'.$_class.'.class.php')) {
					include $file;
					return;
				} else {
					if(is_file($file = APP_PATH.'/controller/'.$names[count($names)-1].'.class.php')) {
						include $file;
						return;
					}
				}
			}
			
			// 模型导入
			if($names[0]=='Model') {
				$names[0] = 'model';
				$_class = implode('/', $names);
				if(is_file($file = APP_PATH.'/'.$_class.'.class.php')) {
					include $file;
					return;
				} else {
					if(is_file($file = APP_PATH.'/model/'.$names[count($names)-1].'.class.php')) {
						include $file;
						return;
					}
				}
			}
			
			// 扩展导入
			if($names[0]=='Ext') {
				$names[0] = 'ext';
				$_class = implode('/', $names);
				if(is_file($file = CORE_PATH.'/'.$_class.'.class.php')) {
					include $file;
					return;
				} else {
					if(is_file($file = CORE_PATH.'/ext/'.$names[count($names)-1].'.class.php')) {
						include $file;
						return;
					}
				}
			}
			
			// 包导入
			if($names[0]) {
				$_class = implode('/', $names);
				if(is_file($file = CORE_PATH.'/pack/'.$_class.'.class.php')) {
					include $file;
					return;
				} else {
					if(is_file($file = CORE_PATH.'/pack/'.$names[count($names)-1].'.class.php')) {
						include $file;
						return;
					}
				}
			}
		} else {
			// Model后缀导入
			if(substr($class_name,-5)=='Model') {
				if(file_exists($file = APP_PATH.'/model/'.$class_name.'.class.php')) {
					include $file;
					return ;
				}
			}
			
			// Action后缀导入
			if(substr($class_name,-10)=='Controller') {
				if(file_exists($file = APP_PATH.'/controller/'.$class_name.'.class.php')) {
					include $file;
					return ;
				}
			}
			
			// Ext后缀导入
			if(substr($class_name,-3)=='Ext') {
				if(is_file($file = CORE_PATH.'/ext/'.$class_name.'.class.php')) {
					include $file;
					return ;
				}
			}
			
			// 无后缀直接导入包
			if(is_file($file = CORE_PATH.'/pack/'.$class_name.'.class.php')) {
				include $file;
				return ;
			}
		}
	}
}