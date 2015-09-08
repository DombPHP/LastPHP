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
 * 框架核心类
 */
class Main {
	/**
	 * 运行方法
	 *
	 * @static
	 * @access public
	 * @return void
	 */
	public static function run() {
		try {
			self::init();
		} catch(Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * 初始化方法
	 *
	 * @static
	 * @access public
	 * @return void
	 */
	public static function init() {
		// 加载框架配置文件
		$_core_conf = self::load_sys_conf();
		
		// 加载网站配置文件
		$_site_conf = self::load_site_conf();
		
		// 合并配置文件
		$_tmp_conf  = array_merge($_core_conf, $_site_conf);
		
		// 定义网址分割字符
		defined('SEPERATOR') or define('SEPERATOR',isset($_tmp_conf['seperator'])&&$_tmp_conf['seperator']?$_tmp_conf['seperator']:'/');
		
		// 定义网页后缀名
		defined('SUFFIX') or define('SUFFIX', isset($_tmp_conf['suffix'])&&$_tmp_conf['suffix']?$_tmp_conf['suffix']:'');
		
		// 定义项目所在目录
		$project_path = isset($_tmp_conf['project_path'])&&$_tmp_conf['project_path']?$_tmp_conf['project_path']:SCRIPT_PATH;
		define('PROJECT_PATH', realpath($project_path).'/');
		
		// 处理域名映射
		$module = '';
		if(!defined('PROJECT')) {
			if($_tmp_conf['map_domain_name']==1) {
				$project_map = self::map($_tmp_conf);
				if(!empty($project_map)) {
					$project_map = explode(':', $project_map);
					$project = $project_map[0];
					if(isset($project_map[1])) {
						$module = $project_map[1];
					}
					define('PROJECT', $project);
				}			
			}
		}
		
		// 解析网址参数
		$_info = self::_parse($_tmp_conf);
		$project = $_info['project'];
		
		// 定义模块名称和方法名称
		$module = $module ? $module : $_info['module'];
		$method = $_info['method'];
		define('MODULE', ucwords($module));
		define('METHOD', $method);
		
		// 定义项目名称
		defined('PROJECT') or define('PROJECT', $project);
		
		// 定义项目路径
		define('APP_PATH', PROJECT_PATH.PROJECT);
		
		// 加载项目配置文件并合并配置文件
		$_pro_conf = self::load_pro_conf();
		$_conf = array_merge($_tmp_conf, $_pro_conf);
		$GLOBALS['CONF'] = $_conf;
		
		// 加载函数文件
		self::load_site_func();
		self::load_pro_func();
		
		// 定义错误页面文件
		if(isset($_conf['error_page'])&&!empty($_conf['error_page'])) {
			defined('ERROR_PAGE') or define('ERROR_PAGE', str_replace('CORE_PATH', CORE_PATH, str_replace('SCRIPT_PATH', SCRIPT_PATH, str_replace('APP_PATH', APP_PATH, $_conf['error_page']))));
			
		}
		// 定义错误信息
		if(isset($_conf['error_message']) && !empty($_conf['error_message'])) {
			defined('ERROR_MESSAGE') or define('ERROR_MESSAGE', $_conf['error_message']);
		}
		
		// 设置默认时区
		$timezone = isset($_conf['date_default_timezone']) && !empty($_conf['date_default_timezone']) ? $_conf['date_default_timezone'] : 'Asia/Shanghai';
		date_default_timezone_set($timezone);
		
		// 定义主题
		$theme  = isset($_conf['theme'])?$_conf['theme']:'default';
		
		// 定义模板后缀
		$suffix = isset($_conf['template_suffix'])&&!empty($_conf['template_suffix'])?$_conf['template_suffix']:'tpl';
		define('TEMPLATE_SUFFIX', $suffix);
		
		// 定义模板引擎名称
		$engine = $_conf['engine'];
		define('TEMPLATE_ENGINE', $engine);
		
		// 定义模板目录
		define('TEMPLATE_DIR', APP_PATH.'/view/'.($theme?$theme:'').'/'.$module.'/');
		
		// 定义模板缓存目录
		define('CACHE_DIR', str_replace('CORE_PATH', CORE_PATH, str_replace('SCRIPT_PATH', SCRIPT_PATH, str_replace('APP_PATH', APP_PATH, $_conf['cache_dir']))));
		
		// 加载框架核心文件
		self::load_sys_file();
		
		// 注册自动加载器
		include CORE_PATH.'/lib/Autoloader.class.php';
		Autoloader::register();
		
		// 设置输出编码
		$charset = isset($_conf['charset']) && trim($_conf['charset']) ? $_conf['charset'] : 'utf-8';
		header('Content-type:text/html;charset='.$charset);
		
		// 调用模块控制器方法
		self::locator($module, $method);
	}
	
	/**
	 * 解析参数
	 *
	 * @static
	 * @access public
	 * @param string $conf 配置参数
	 * @return void
	 */
	public static function _parse($conf) {
		$query_string = $_SERVER['QUERY_STRING'];
		$project = 'home';
		$module  = 'Index';
		$method  = 'index';
		if($query_string) {
			parse_str($QUERY_STRING, $parameters);
			if(isset($parameters['p']) && $parameters['p']) {
				$project = $parameters['p'];
				if(!preg_match ("/^[a-z]/i", $project)) {
					throw  new Exception('Project \''.$project.'\' not found');
				}
			}
			$module  = isset($parameters['m']) && $parameters['m'] ? $parameters['m'] : $module;
			$method  = isset($parameters['a']) && $parameters['m'] ? $parameters['a'] : $method;
		}
		if(!empty($_SERVER['PATH_INFO'])) {
			$path_info = trim($_SERVER['PATH_INFO']);
			if(substr($path_info, 0, 1)=='/') {
				$path_info = substr($path_info, 1);
			}
			$path_info = preg_replace('/\.'.SUFFIX.'$/is','',$path_info);
			$path_info = explode(SEPERATOR,$path_info);
			$_GET['__vars__'] = $path_info;
			if(defined('PROJECT')) {
				$project = PROJECT;
				if(defined('MODULE')) {
					$method = isset($path_info[0]) && $path_info[0] ? $path_info[0] : $method;
				} else {
					$module = isset($path_info[0]) && $path_info[0] ? $path_info[0] : $module;
					$method = isset($path_info[1]) && $path_info[1] ? $path_info[1] : $method;
				}
			} else {
				$project = isset($path_info[0]) && $path_info[0] ? $path_info[0] : $project;
				$module  = isset($path_info[1]) && $path_info[1] ? $path_info[1] : $module;
				$method  = isset($path_info[2]) && $path_info[2] ? $path_info[2] : $method;
			}
			if(!preg_match ("/^[a-z]/i", $project)) {
				throw  new Exception('Project \''.$project.'\' not found');
			}
		}
		return array('project' => strtolower($project), 'module' => $module, 'method' => $method);
	}
	
	/**
	 * 加载框架配置参数
	 * 
	 * @static
	 * @access private
	 * @return void
	 */
	private static function load_sys_conf() {
		$_core_configure = parse_ini_file(CORE_PATH.'/conf/conf.php', true);
		return array_change_key_case($_core_configure, CASE_LOWER);
	}
	
	/**
	 * 加载网站配置参数
	 *
	 * @static
	 * @access private
	 * @return array
	 */
	private static function load_site_conf() {
		$conf_path = SCRIPT_PATH.'/conf/conf.php';
		if(is_file($conf_path)) {
			return array_change_key_case(parse_ini_file($conf_path, true), CASE_LOWER);
		}
		return array();
	}
	
	/**
	 * 加载项目配置参数
	 *
	 * @static
	 * @access private
	 * @return array
	 */
	private static function load_pro_conf() {
		$conf_path = APP_PATH.'/conf/conf.php';
		if(is_file($conf_path)) {
			return array_change_key_case(parse_ini_file($conf_path, true), CASE_LOWER);
		}
		return array();
	}
	
	/**
	 * 加载站点函数文件
	 *
	 * @static
	 * @access private
	 * @return void
	 */
	private static function load_site_func() {
		$func_path = APP_PATH.'/func/func.php';
		if(is_file($func_path)) {
			include $func_path;
		}
	}
	
	/**
	 * 加载项目函数文件
	 *
	 * @static
	 * @access private
	 * @return void
	 */
	private static function load_pro_func() {
		$func_path = APP_PATH.'/func/func.php';
		if(is_file($func_path)) {
			include $func_path;
		}
	}
	
	/**
	 * 加载框架文件
	 * 
	 * @static
	 * @access private
	 * @return void
	 */
	private static function load_sys_file() {
		$autoLoadDir = array(
			CORE_PATH.'/func/func.php',
			CORE_PATH.'/lib/Controller.class.php',
			CORE_PATH.'/lib/View.class.php',
			CORE_PATH.'/lib/iTemplate.class.php',
		);
		self::require_all($autoLoadDir);
	}
	
	/**
	 * 映射主机名称到指定的项目
	 *
	 * @static
	 * @access private
	 * @param string $project 项目名称
	 * @param array $conf 配置参数
	 * @return void
	 */
	private static function map($conf) {
		$host = $_SERVER['HTTP_HOST'];
		return isset($conf[$host])?trim($conf[$host]):'';
	}
	
	/**
	 * 定位模块操作
	 *
	 * @static
	 * @access public
	 * @param string $module 模块名称
	 * @param string $method 操作名称
	 * @param array $conf 配置参数
	 * @return void
	 */
	public static function locator($module = '', $method = '') {
		$module_path = APP_PATH.'/controller/'.$module.'Controller.class.php';
		if(is_file($module_path)) {
			include $module_path;
		} else {
			throw new Exception('File \''.$module_path.'\' not found');
		}
		$class = $module.'Controller';
		if(!class_exists($class)) {
			$empty_path = APP_PATH.'/controller/EmptyController.class.php';
			if(file_exists($empty_path)) {
				include $empty_path;
				if(class_exists('EmptyController')) {
					$class = 'EmptyController';
				} else {
					throw new Exception('Class \''.$module.'\' not found');
				}
			} else {
				throw new Exception('Class \''.$module.'\' not found');
			}
		}
		$class = new $class();
		if(method_exists($class, $method)) {
			call_user_func(array($class, $method));
		} else {
			if(method_exists($class, '_empty')) {
				call_user_func(array($class, '_empty'), $method);
			} else {
				throw new Exception('Method \''.$method.'\' not found');
			}
		}
	}
	
	/**
	 * 包含文件
	 * 
	 * @static
	 * @access private
	 * @param string $file 文件路径
	 * @return void
	 */
	private static function _require($file = '') {
	    if(empty($file)) throw new  Exception('File \''.$file.'\' not found');
	    if(is_file($file)) {
	        require $file;
	    return true;
	 }
	 return false;
	}
	
	/**
	 * 包含多个文件
	 * 
	 * @static
	 * @access private
	 * @param array $files 文件路径数组
	 * @return void
	 */
	private static function require_all($files = array()) {
	    if(is_array($files)) {
	       foreach($files as $file) {
	           self::_require($file);
	       }
	    }
	}
}