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

namespace Micsqli;

/**
 * 数据库代理类
 */
class Proxy extends MultiMysqli {
	
	/**
	 * 主数据库配置项名称
	 *
	 * @access private
	 * @var string
	 */
	private $master = '';
	
	/**
	 * 从数据库配置项名称
	 *
	 * @access private
	 * @var string
	 */
	private $slave = '';
	
	/**
	 * 主数据库配置参数
	 *
	 * @access private
	 * @var string
	 */
	private $master_conf = array();
	
	/**
	 * 从数据库配置参数
	 *
	 * @access private
	 * @var string
	 */
	private $slave_conf = array();
	
	/**
	 * 静态类实例
	 *
	 * @access private
	 * @var MultiMysqli
	 */
	private static $instance;
	
	public function __construct($conf = null) {
		parent::__construct($conf);
		$this->checkddb();
	}
	
	/**
	 * 获取类实例
	 *
	 * @access public
	 * @param array $conf 配置参数
	 * @return Proxy
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
	 * 检查数据库配置信息
	 *
	 * @access protected
	 * @param array $conf 配置参数
	 * @return void
	 */
	protected function checkddb() {
		$ddb = isset($this->global_conf['ddb']) ? $this->global_conf['ddb'] : 0;
		$proxy = isset($this->global_conf['proxy']) ? $this->global_conf['proxy'] : 0;
		if(empty($this->conf) && $ddb==1) {
			if($proxy==1) {
				$this->master = $this->master ? $this->master : isset($this->global_conf['master']) ? $this->global_conf['master'] : 'db_host_master';
				$this->slave  = $this->slave ? $this->slave : $this->getServer();
				$this->master_conf = $this->checkHost($this->master);
				$this->slave_conf  = $this->checkHost($this->slave);
			} else {
				$this->slave  = $this->getServer('servers');
				$this->master = $this->slave;
				$this->master_conf = $this->checkHost($this->master);
				$this->slave_conf  = $this->master_conf;
			}
		}
	}
	
	/**
	 * 随机获取服务器信息
	 *
	 * @access private
	 * @param array $conf 配置参数
	 * @param string $flag 标识
	 * @return void
	 */
	protected function getServer($flag = 'slaves') {
		$servers = isset($this->global_conf[$flag]) && $this->global_conf[$flag] ? $this->global_conf[$flag] : null;
		if($servers) {
			$servers = explode(',', $servers);
			$count = count($servers);
			$rnd = rand(0, $count-1);
			return $servers[$rnd];
		}
		throw new Exception('Database server not found');
	}
	
	/**
	 * 重写query方法
	 *
	 * @access pubbic
	 * @param string $sql 查询语句
	 * @return array
	 */
	public function query($sql) {
		if($this->slave_conf) {
			$this->conf = $this->slave_conf;
		}
		return parent::query($sql);
	}
	
	/**
	 * 重写execute方法
	 *
	 * @access public
	 * @param string $sql 查询语句
	 * @return integer
	 */
	public function execute($sql) {
		if($this->master_conf) {
			$this->conf = $this->master_conf;			
		}
		return parent::execute($sql);
	}
}