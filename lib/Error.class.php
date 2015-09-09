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
 * 错误处理类
 */
class Error {

	/**
	 * 错误页面
	 *
	 * @static
	 * @access private
	 * @var string
	 */
	private static $error_page = 'error/error.php';
	
	/**
	 * 错误信息
	 *
	 * @static
	 * @access private
	 * @var string
	 */
	private static $error_message = '系统错误';
	
	/**
	 * 注册错误处理器
	 *
	 * @static
	 * @access public
	 * @return void
	 */
	public static function register() {
		set_error_handler("Error::errorHandler");
		register_shutdown_function("Error::fatalError");
		set_exception_handler("Error::exceptionHandler");
	}
	/**
	 * 错误处理方法
	 * 
	 * @static
	 * @access public
	 * @param integer $errno 错误号码
	 * @param string $errstr 错误信息
	 * @param string $errfile 错误文件
	 * @param integer $errline 错误行号
	 * @return void
	 */
	public static function errorHandler($errno, $errstr, $errfile, $errline) {
		if(!(error_reporting() & $errno)) {
			return;
		}
		switch($errno) {
			case E_WARNING:
			case E_NOTICE:
			case E_USER_WARNING:
			case E_USER_NOTICE:
			case E_STRICT:
			case E_DEPRECATED:
			case E_USER_DEPRECATED:
				if(!DEBUG) {
					return;
				}
		}
		throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
	}
	
	/**
	 * 致命错误捕获方法
	 * 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function fatalError() {
		if($e = error_get_last()) {
			switch($e['type']) {
				case E_PARSE:
				case E_CORE_ERROR:
				case E_CORE_WARNING:
				case E_COMPILE_ERROR:
				case E_COMPILE_WARNING:
				case E_ERROR:
				case E_USER_ERROR:
					ob_end_clean();
					self::_errorPage($e);
			}
		}
	}
	
	/**
	 * 异常处理方法
	 *
	 * @static
	 * @access public
	 * @return void
	 */
	public static function exceptionHandler($e) {die('xx');
		self::_errorPage($e);
	}
	
	/**
	 * 输出错误页面
	 *
	 * @static
	 * @access private
	 * @param array $e 错误信息
	 * @return void
	 */
	private static function _errorPage($e) {
		if(is_object($e)){
			$error['code'] = $e->getCode();
			$error['file'] = $e->getFile();
			$error['line'] = $e->getLine();
			$error['message'] = $e->getMessage();
			$error['trace'] = self::parseTraces($e->getTrace());
		}else{
			$e['trace'] = array();
			$error = $e;
			$error['code'] = $e['type'];
		}
		$error['type'] = self::getErrorType($error['code']);
		self::log(self::parseMessage($error));
		if(DEBUG===false) {
			$error['message'] = defined('ERROR_MESSAGE') ? ERROR_MESSAGE : self::$error_message;				
			unset($error['trace']);
		}
		$error_page = defined('ERROR_PAGE') ? ERROR_PAGE : CORE_PATH.'/'.self::$error_page;
		if(is_file($error_page)) {
			include($error_page);
			die();
		} else {
			die('<b>'.$error['type'].':</b> '.$error['message'].' in '.$error['file'].' on line '.$error['line'].'\n');
		}
	}
	
	/**
	 * 记录错误日志
	 *
	 * @static
	 * @access public
	 * @param string $message 错误消息
	 * @param string $dir 错误日志目录
	 * @return void
	 */
	public static function log($message, $dir = '') {
		if(trim($dir) && is_dir($dir)) {
			if(substr(str_replace('\\', '/', $dir), -1)!='/') {
				$dir .= '/';
			}
			$log_dir = $dir;
		} else {
			$log_dir = APP_PATH.'/logs/';
			if(!is_dir($log_dir)) {
				mkdir($log_dir, 0777);
			}
		}
		$file_max_size = 2*1024*1024;
		$file_ext = 'log';
		$date = date('Y-m-d');
		$log_file = $log_dir.'/'.$date.'.'.$file_ext;
		if(is_file($log_file)) {
			$size = filesize($log_file);
			if($size>$file_max_size) {
				$log_old_file = $log_dir.$date.'-'.time().'.'.$file_ext;
				rename($log_file, $log_old_file);
				$log_file = $log_old_file;
			}
			error_log($message, 3, $log_file);
		} else {
			file_put_contents($log_file, $message);
		}
	}
	
	/**
	 * 生成日志格式信息
	 *
	 * @static
	 * @access private
	 * @param array $e 错误信息
	 * @return string
	 */
	private static function parseMessage($e) {
		$data = '['.date('Y-m-d H:i:s').'] ';
		$data .= self::getErrorType($e['code']);
		$data .= ': '.$e['message'].' in '.$e['file'];
		$data .= ' on line '.$e['line'];
		$data .= PHP_EOL;
		return $data;
	}
	
	/**
	 * 调整跟踪信息
	 *
	 * @static
	 * @access private
	 * @param array $trace 跟踪信息
	 * @return void
	 */
	private static function parseTraces($trace) {
		if($trace) {
			foreach($trace as $key=>&$val) {
				$str = '';
				if(isset($val['file'])) {
					$str .= $val['file'].'('.$val['line'].')';
				}
				$strArgs = '';
				if(isset($val['args'])) {
					array_walk($val['args'], 'self::walk');
					$strArgs = implode(',', $val['args']);
				}
				if(isset($val['class'])) {
					$str .= ' '.$val['class'].$val['type'].$val['function'].'('.$strArgs.')';
				} else {
					if(isset($val['function'])) {
						$str .= ' '.$val['function'].'('.$strArgs.')';
					}
				}
				$val = '#'.$key.' '.$str;
			}
		}
		return $trace;
	}
	
	/**
	 * 数组转字符串
	 *
	 * @static
	 * @access private
	 * @param array $item 数组值
	 * @param string $key 键名
	 */
	private static function walk(&$item,$key){
		$item = var_export($item, true);
	}
	
	/**
	 * 获取错误类型字符串
	 *
	 * @static
	 * @access private
	 * @param integer
	 * @return string
	 */
	private static function getErrorType($errno) {
		$error = '';
		switch($errno) {
			case E_PARSE:
				$error = 'Parse error';
				break;
			case E_CORE_ERROR:
				$error = 'Core error';
				break;
			case E_CORE_WARNING:
				$error = 'Core warning';
				break;
			case E_COMPILE_ERROR:
				$error = 'Compile error';
				break;
			case E_COMPILE_WARNING:
				$error = 'Compile warning';
				break;
			case E_ERROR:
				$error = 'Error';
				break;
			case E_WARNING:
				$error = 'Warning';
				break;
			case E_NOTICE:
				$error = 'Notice';
				break;
			case E_USER_ERROR:
				$error = 'User Error';
				break;
			case E_USER_WARNING:
				$error = 'User warning';
				break;
			case E_USER_NOTICE:
				$error = 'User notice';
				break;
			case E_STRICT:
				$error = 'Strict';
				break;
			case E_RECOVERABLE_ERROR:
				$error = 'Recoverable error';
				break;
			case E_DEPRECATED:
				$error = 'Deprecated';
				break;
			case E_USER_DEPRECATED:
				$error = 'User deprecated';
				break;
			case 0:
				$error = 'User exception';
				break;
			default:
				$error = 'Unknown error';
		}
		return $error;
	}
}