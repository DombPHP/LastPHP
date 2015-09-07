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
 * 抽象数据模型类
 */
abstract class AbstractModel {
	/**
	 * 数据表名
	 *
	 * @ access protected
	 * @var string
	 */
	protected $tbl;
	
	/**
	 * 真实数据表名
	 *
	 * @access protected
	 * @var string
	 */
	protected $real_table_name;
	
	/**
	 * 数据库类
	 *
	 * @access private
	 * @var MysqliDb
	 */
	private $db;
	
	/**
	 * 主键
	 *
	 * @access protected
	 * @var string
	 */
	protected $pk = 'id';
	
	/**
	 * 最后一个查询语句
	 *
	 * @access protected
	 * @var string
	 */
	 protected $last_query_sql = '';
	
	/**
	 * 构造方法
	 *
	 * @return void
	 */
	function __construct() {
		$conf = $GLOBALS ['CONF'];
		$this->db = new MysqliDb($conf);
	}
	
	/**
	 * 解析查询语句
	 *
	 * @access protected
	 * @param string $fields 查询字段
	 * @param string $cond 查询条件
	 * @param string $order 排序
	 * @return string
	 */
	protected function _parse_find($fields='', $cond='', $order='') {
		return $this->_parse_select($fields, $cond, $order, 1);
	}
	
	/**
	 * 解析插入语句
	 *
	 * @access protected
	 * @param string $data 插入数据
	 * @return string
	 */
	protected function _parse_add($data) {
		if(!isset($data[$this->pk])) {
			$data[$this->pk] = null;
		}
		$sql = 'INSERT INTO '.$this->real_table_name;
		foreach($data as $key=>$val) {
			$fields[] = $key;
			$values[] = $val;
		}
		$fields = implode(',', $fields);
		$values = implode(',', $values);
		$sql .= '('.$fields.')';
		$sql .= ' values('.$values.');';
		return $sql;
	}
	
	/**
	 * 解析编辑语句
	 *
	 * @access protected
	 * @param string $data 编辑数据
	 * @param string $cond 编辑条件
	 * @return string
	 */
	protected function _parse_edit($data, $cond) {
		$sql = 'UPDATE '.$this->real_table_name.' set ';
		foreach($data as $key=>$val) {
			$s .= $key.'='.$val.',';
		}
		$s = substr($s, 0, -1);
		$sql .= $s.' where '.$cond;
		return $sql;
	}
	
	/**
	 * 解析删除语句
	 *
	 * @access protected
	 * @param string $cond 删除条件
	 * @return string
	 */
	protected function _parse_delete($cond) {
		$sql = 'DELETE FROM '.$this->real_table_nam.' where '.$cond;
		return $sql;
	}
	
	/**
	 * 解析查询语句
	 *
	 * @access protected
	 * @param string $fields 查询字段
	 * @param string $cond 查询条件
	 * @param string $order 排序
	 * @param integer $rows 查询数量
	 * @param integer $page 查询页数
	 * @return string
	 */
	protected function _parse_select($fields='', $cond='', $order='', $rows=0, $page=0) {
		$fields = $fields?$fields:'*';
		$sql = 'SELECT '.$fields.' FROM '.$this->real_table_name;
		if($cond) {
			$sql .= ' WHERE '.$cond;
		}
		if($order) {
			$sql .= ' ORDER BY '.$order;
		}
		if($page>0) {
			$start = ($page-1)*$rows;
			$sql .= ' LIMIT '.$start.','.$rows;
		}
		if($rows>0) {
			if($page>0) {
				$start = ($page-1)*$rows;
				$sql .= ' LIMIT '.$start.','.$rows;
			}else{
				$sql .= ' LIMIT '.$rows;
			}
		}
	}
	
	/**
	 * 查询数据
	 *
	 * @access public
	 * @param string $sql 查询语句
	 * @return mixed
	 */
	public function query($sql) {
		$this->last_query_sql = $sql;
		return $this->db->query($sql);
	}
	
	/**
	 * 执行SQL语句
	 *
	 * @access public
	 * @param string $sql SQL语句
	 * @return integer
	 */
	public function execute($sql) {
		$this->last_query_sql = $sql;		
		return $this->db->execute($sql);
	}
	
	/**
	 * 获取最后插入ID
	 *
	 * @access public
	 * @return integer
	 */
	public function last_insert_id() {
		return $this->db->get_insert_id();
	}
	
	public function getLastSql() {
		return $this->last_query_sql;
	}
}