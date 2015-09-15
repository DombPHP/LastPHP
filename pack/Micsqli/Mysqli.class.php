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
 * 单数据库连接类
 */
class Mysqli {
	/**
	 * 数据库连接资源对象
	 *
	 * @access private
	 * @var string
	 */
	protected $link;
	
	/**
	 * Mysqli_result
	 *
	 * @access private
	 * @var Mysqli_result
	 */
	protected $result;
	
	/**
	 * 最后插入记录ID
	 *
	 * @access private
	 * @var integer
	 */
	protected $insert_id = 0;
	
	/**
	 * 静态类实例
	 *
	 * @static
	 * @access public
	 * @var MysqliDb
	 */
	private static $instance = null;
	
	/**
	 * 构造方法
	 *
	 * @param array $conf 数据库配置参数
	 * @return void
	 */
	public function __construct(&$conf) {
		$this->conf = $conf;
	}
	
	/**
	 * 获取类实例
	 *
	 * @access public
	 * @param array $conf 配置参数
	 * @return Mysqli
	 */
	public static function getInstance(&$conf) {
		if(self::$instance && self::$instance instanceof self) {
			return self::$instance;
		} else {
			self::$instance = new self($conf);
			return self::$instance;
		}
	}
	
	public function connect($conf) {
		$host       = $conf['db_host'];
		$port       = $conf['db_port'];
		$db         = $conf['db_name'];
		$user       = $conf['db_user'];
		$pwd        = $conf['db_pwd'];
		$charset    = $conf['db_charset'];
		return $this->_connect($host, $port, $user, $pwd, $db, $charset);
	}
	
	/**
	 * 数据库连接方法
	 *
	 * @access private
	 * @param string 
	 * @return void
	 */
	private function _connect($host, $port, $user, $pwd, $db, $charset) {
		$this->link = new \Mysqli($host, $user, $pwd, $db, $port);
		if($this->link->connect_error) {
			throw new \Exception($this->link->connect_error);
		}
		if(!$this->link->set_charset($charset)) {
			throw new \Exception($this->link->error);
		}
		return $this->link;
	}
	
	/**
	 * 执行查询语句并返回查询结果
	 *
	 * 执行SELECT,SHOW,DESCRIBE,EXPLAIN查询
	 *
	 * @access public
	 * @param string $sql 查询语句
	 * @return array
	 */
	public function query($sql) {
		$this->result = $this->_query($sql);
		if(is_object($this->result)) {
			return $this->_fetchAll($this->result);
		} else {
			return array();
		}
	}
	
	/**
	 * 执行查询语句并返回受影响行数
	 * 
	 * @access public
	 * @param string $sql 查询语句
	 * @return integer
	 */
	public function execute($sql) {
		$this->_query($sql);
		$this->insert_id = $this->link->insert_id;
		return $this->link->affected_rows();
	}
	
	/**
	 * 执行查询语句
	 *
	 * @access private
	 * @param string $sql 查询语句
	 * @return mixed
	 */
	protected function _query($sql) {
		$this->connect($this->conf);		
		$result = $this->link->query($sql);
		if($this->link->errno) {
			trigger_error($this->link->error.'; SQL:'.$sql);
		}
		return $result;
	}
	
	/**
	 * 开始事务
	 *
	 * @access public
	 * @return bool
	 */
	public function begin() {
		return $this->link->begin_transaction();
	}
	
	/**
	 * 提交事务
	 *
	 * @access public
	 * @return bool
	 */
	public function commit() {
		return $this->link->commit();
	}
	
	/**
	 * 回滚事务
	 *
	 * @access public
	 * @return bool
	 */
	public function rollback() {
		return $this->link->rollback();
	}
	
	/**
	 * 获取最后插入ID
	 *
	 * @access public
	 * @return integer
	 */
	public function get_insert_id() {
		return $this->insert_id;
	}
	
	/**
	 * 获取关联数组
	 *
	 * @access private
	 * @param Mysqli_result $result 查询结果集
	 * @return array
	 */
	private function _fetchAll($result) {
		$rows = array();
		while($row = $result->fetch_assoc()) {
			$rows[] = $row;
		}
		return $rows;
	}
	
	/**
	 * 关闭数据库连接
	 *
	 * @access private
	 * @return void
	 */
	private function close() {
		if($this->link) {
			$this->link->close();
			$this->link = null;
		}
	}
	
	/**
	 * 释放Mysqli_result
	 *
	 * @access private
	 * @return void
	 */
	private function free() {
		if($this->result) {
			$this->result->free();
			$this->result = null;
		}
	}
	
	/**
	 * 析构方法
	 */
	public function __destruct(){
		$this->close();
		$this->free();
	}
}