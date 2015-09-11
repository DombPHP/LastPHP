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

namespace Ext;

/**
 * 数据库类
 */
class MultiMysqli extends Mysqli {
	/**
	 * 数据库连接资源对象
	 *
	 * @access private
	 * @var string
	 */
	private $links = array();
	private static $instance = null;
	
	public function __construct($conf) {
		parent::__construct($conf);
	}
	
	/**
	 * 获取类实例
	 *
	 * @access public
	 * @param array $conf 配置参数
	 * @return MultiMysqli
	 */
	public static function getInstance(&$conf) {
		$no = md5(serialize($conf));
		if(self::$instance && self::$instance instanceof self) {
			if(self::$instance->links[$no]) {print_r(self::$instance->links);
				self::$instance->link = &self::$instance->links[$no];
			} else {
				self::$instance->connect($conf);
				self::$instance->links[$no] = self::$instance->link;
			}
			return self::$instance;
		} else {
			self::$instance = new self($conf);
			self::$instance->links[$no] = self::$instance->link;
			return self::$instance;
		}
	}
}