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

include 'WarmerMultiModel.class.php';

/**
 * Warmer多数据库数据模型类
 */
class WarmerDistributedModel extends WarmerMultiModel {
	
	protected $master = '';
	protected $slave = '';
	protected $conf = null;
	
	public function __construct($conf) {
		$this->checkddb($conf);
		$this->conf = &$conf;
		parent::__construct($conf);
	}
	
	protected function checkddb($conf) {
		$ddb = isset($conf['ddb']) ? $conf['ddb'] : 0;
		$proxy = isset($conf['proxy']) ? $conf['proxy'] : 0;
		if($ddb) {
			if($proxy) {
				$this->master = $this->master ? $this->master : 'db_host_master';
				$this->slave = $this->slave ? $this->slave : self::_getServer($conf);
			} else {
				$this->slave = self::_getServer($conf, 'servers');
				$this->master = $this->slave;
			}
		}
	}
	
	private function _getServer($conf, $flag = 'slaves') {
		$servers = isset($conf[$flag]) && $conf[$flag] ? $conf[$flag] : null;
		if($servers) {
			$servers = explode(',', $servers);
			$count = count($servers);
			$rnd = rand(0, $count-1);
			return $servers[$rnd];
		}
		throw new Exception('Database server not found');
	}
	
	public function query($sql) {
		$this->host = $this->slave;
		$_conf = $this->checkHost($this->conf);
		$this->db = \Ext\MultiMysqli::getInstance($_conf);
		return parent::query($sql);
	}
	
	public function execute($sql) {
		$this->host = $this->master;
		$_conf = $this->checkHost($this->conf);
		$this->db = \Ext\MultiMysqli::getInstance($_conf);
		return parent::execute($sql);
	}
}