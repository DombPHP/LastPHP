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
 * 抽象数据模型类
 */
abstract class AbstractWarmerModel {
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
	 * @var object
	 */
	protected $db = null;
	
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
	function __construct($conf = null) {
		if(empty($this->real_table_name)) {
			if(!is_string($this->tbl) || !trim($this->tbl)) {
				$classname = get_class($this);
				if(preg_match('/Model$/', $classname)) {
					$this->tbl = substr($classname, 0, -5);
				} else {
					$this->tbl = $classname;
				}
			}
			if(isset($conf['db_prefix']) && !empty($conf['db_prefix'])) {
				$this->real_table_name = $conf['db_prefix'].$this->tbl;
			} else {
				$this->real_table_name = $this->tbl;
			}
		}
		if($this->db===null) {
			$this->db = \Micsqli\Mysqli::getInstance($conf);
		}
	}
	
	/**
	 * 解析插入语句
	 *
	 * @access protected
	 * @param string $data 插入数据
	 * @return string
	 */
	protected function _parse_add($data) {
		$sql = 'INSERT INTO '.$this->real_table_name;
		foreach($data as $key => $val) {
			$fields[] = $key;
			$values[] = $val;
		}
		$fields = implode(',', $fields);
		$values = implode(',', $values);
		$sql .= '('.$fields.')';
		$sql .= ' VALUES('.$values.');';
		return $sql;
	}
	
	/**
	 * 解析更新语句
	 *
	 * @access protected
	 * @param string $data 编辑数据
	 * @param string $cond 编辑条件
	 * @return string
	 */
	protected function _parse_edit($data, $cond = '') {
		$sql = 'UPDATE '.$this->real_table_name.' SET ';
		if(empty($cond)) {
			if(isset($data[$this->pk])) {
				$cond = $this->pk.'='.$data[$this->pk];
				unset($data[$this->pk]);
			}
		} else {
			if(isset($data[$this->pk])) {
				unset($data[$this->pk]);
			}
			if(is_numeric($cond)) {
				$cond = $this->pk.'=\''.$cond.'\'';
			} else if(is_array($cond)) {
				$cond = $this->pk.' in(\''.implode('\',\'', $cond).'\')';
			} else if(strpos($cond, ',')) {
				$cond = $this->pk.' in('.$cond.')';
			}
		}
		foreach($data as $key => $val) {
			$s .= $key.'='.$val.',';
		}
		$s = substr($s, 0, -1);
		return $sql.$s.($cond ? ' WHERE '.$cond : '');
	}
	
	/**
	 * 解析删除语句
	 *
	 * @access protected
	 * @param string $cond 删除条件
	 * @return string
	 */
	protected function _parse_delete($cond) {
		if(is_numeric($cond)) {
			$cond = $this->pk.'=\''.$cond.'\'';
		} else if(is_array($cond)) {
			$cond = $this->pk.' in(\''.implode('\',\'', $cond).'\')';
		} else if(strpos($cond, ',')) {
			$cond = $this->pk.' in('.$cond.')';
		}
		$sql = 'DELETE FROM '.$this->real_table_name.' WHERE '.$cond;
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
	 * @param string $group 分组条件
	 * @param string $having 分组筛选条件
	 * @return string
	 */
	protected function _parse_select($fields = '', $cond = '', $order = '', $rows = 0, $page = 0, $group = '', $having = '') {
		$fields = $fields ? $fields : '*';
		$sql = 'SELECT '.$fields.' FROM '.$this->real_table_name;
		if($cond) {
			$sql .= ' WHERE '.$cond;
		}
		if($group) {
			$sql .= ' GROUP BY '.$group;
		}
		if($order) {
			$sql .= ' ORDER BY '.$order;
		}
		if($having) {
			$sql .= ' HAVING '.$having;
		}
		if($rows > 0) {
			if($page > 0) {
				$start = ($page-1) * $rows;
				$sql .= ' LIMIT '.$start.','.$rows;
			}else{
				$sql .= ' LIMIT '.$rows;
			}
		}
		return $sql;
	}
	
	/**
	 * 解析统计记录数语句
	 *
	 * @access protected
	 * @param string $field 字段
	 * @param string $cond 删除条件
	 * @return string
	 */
	protected function _parse_count($field = '', $cond = '') {
		$field = $field ? $field : '*';
		$sql = 'SELECT COUNT('.$field.') AS TEMP_COUNT FROM '.$this->real_table_name;
		if($cond) {
			$sql .= ' WHERE '.$cond;
		}
		return $sql;
	}
	
	/**
	 * 解析求和语句
	 *
	 * @access protected
	 * @param string $field 字段
	 * @param string $cond 删除条件
	 * @return string
	 */
	protected function _parse_sum($field, $cond = '') {
		$sql = 'SELECT SUM('.$field.') AS TEMP_SUM FROM '.$this->real_table_name;
		if($cond) {
			$sql .= ' WHERE '.$cond;
		}
		return $sql;
	}
	
	/**
	 * 解析求平均值语句
	 *
	 * @access protected
	 * @param string $field 字段
	 * @param string $cond 删除条件
	 * @return string
	 */
	protected function _parse_avg($field, $cond = '') {
		$sql = 'SELECT AVG('.$field.') AS TEMP_AVG FROM '.$this->real_table_name;
		if($cond) {
			$sql .= ' WHERE '.$cond;
		}
		return $sql;
	}
	
	/**
	 * 解析求最大值语句
	 *
	 * @access protected
	 * @param string $field 字段
	 * @param string $cond 删除条件
	 * @return string
	 */
	protected function _parse_max($field, $cond = '') {
		$sql = 'SELECT MAX('.$field.') AS TEMP_MAX FROM '.$this->real_table_name;
		if($cond) {
			$sql .= ' WHERE '.$cond;
		}
		return $sql;
	}
	
	/**
	 * 解析求最小值语句
	 *
	 * @access protected
	 * @param string $field 字段
	 * @param string $cond 删除条件
	 * @return string
	 */
	protected function _parse_min($field, $cond = '') {
		$sql = 'SELECT MIN('.$field.') AS TEMP_MIN FROM '.$this->real_table_name;
		if($cond) {
			$sql .= ' WHERE '.$cond;
		}
		return $sql;
	}
	
	/**
	 * 解析自动增加语句
	 *
	 * @access protected
	 * @param string $field 字段
	 * @param string $cond 执行条件
	 * @param integer $offset 偏移量
	 * @return string
	 */
	protected function _parse_inc($field, $cond = '', $offset = 0) {
		$offset = $offset ? $offset : 1;
		$sql = 'UPDATE '.$this->real_table_name.' SET '.$field.'='.$field.'+'.$offset;
		if($cond) {
			$sql .= ' WHERE '.$cond;
		}
		return $sql;
	}
	
	/**
	 * 解析自动减少语句
	 *
	 * @access protected
	 * @param string $field 字段
	 * @param string $cond 执行条件
	 * @param integer $offset 偏移量
	 * @return string
	 */
	protected function _parse_dec($field, $cond = '', $offset = 0) {
		$offset = $offset ? $offset : 1;
		$sql = 'UPDATE '.$this->real_table_name.' SET '.$field.'='.$field.'-'.$offset;
		if($cond) {
			$sql .= ' WHERE '.$cond;
		}
		return $sql;
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
	
	/**
	 * 获取最后查询语句
	 *
	 * @access public
	 * @return string
	 */
	public function getLastSql() {
		return $this->last_query_sql;
	}
	
	/**
	 * 设置主键名称
	 *
	 * @access public
	 * @param string $pk 主键名称
	 * @return void
	 */
	public function setPk($pk) {
		$this->pk = $pk;
	}
	
	/**
	 * 获取主键名称
	 *
	 * @access public
	 * @return string
	 */
	public function getPk() {
		return $this->pk;
	}
	
	/**
	 * 开始事务
	 *
	 * @access public
	 * @return bool
	 */
	public function begin() {
		$this->db->begin();
	}
	
	/**
	 * 提交事务
	 *
	 * @access public
	 * @return bool
	 */
	public function commit() {
		$this->db->commit();
	}
	
	/**
	 * 回滚事务
	 *
	 * @access public
	 * @return bool
	 */
	public function rollback() {
		$this->db->rollback();
	}
}