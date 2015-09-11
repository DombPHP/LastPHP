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

include 'WarmerModel.class.php';

/**
 * Warmer多数据库数据模型类
 */
class WarmerMultiModel extends WarmerModel {
	
	protected $host = '';
	protected $pre = 'db_host_';
	
	public function __construct($conf) {
		$_conf = $this->checkHost($conf);
		if($this->db===null) {
			$this->db = \Ext\MultiMysqli::getInstance($_conf);
		}
		parent::__construct($_conf);
	}
	
	protected function checkHost($conf) {
		$index = '';
		if(!empty($this->host)) {
			if(stristr($this->host, $this->pre)===false) {
				$index = '_'.$this->host;
				$host = $this->pre.$this->host;
			} else {
				$index = '_'.substr($this->host, 8);
				$host = $this->host;
			}
		} else {
			$host = 'db_host';
		}
		$_conf = array();
		if(isset($conf[$host])) {
			$_conf['db_host']   = $conf['db_host'.$index];
			$_conf['db_port']    = $conf['db_port'.$index];
			$_conf['db_name']   = $conf['db_name'.$index];
			$_conf['db_user']   = $conf['db_user'.$index];
			$_conf['db_pwd']    = $conf['db_pwd'.$index];
			$_conf['db_charset']    = $conf['db_charset'.$index];
			$_conf['db_prefix'] = $conf['db_prefix'.$index];
		} else {
			throw  new Exception('Server \''.$host.'\' not found');
		}
		return $_conf;
	}
}