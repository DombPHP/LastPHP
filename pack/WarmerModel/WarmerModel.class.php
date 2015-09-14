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

include 'AbstractWarmerModel.class.php';

/**
 * 数据模型基类
 */
class WarmerModel extends AbstractWarmerModel {
	
	/**
	 * 查询一条记录
	 *
	 * @access public
	 * @param string $fields 数据字段
	 * @param string $cond 查询条件
	 * @param string $order 排序
	 * @param string $group 分组
	 * @param string $having 筛选条件
	 * @return array
	 */
	public function find($fields = '', $cond = '', $order = '', $group = '', $having = '') {
		$result = $this->select($fields, $cond, $order, 1, 1, $group, $having);
		if(!empty($result)) {
			return $result[0];
		}
		return array();
	}
	
	/**
	 * 添加数据
	 *
	 * @access public
	 * @param array $data 添加的数据
	 * @return integer
	 */
	public function add($data) {
		$sql = $this->_parse_add($data);
		$this->execute($sql);
		return $this->last_insert_id();
	}
	
	/**
	 * 修改数据
	 *
	 * @access public
	 * @param array $data 新数据
	 * @param string $cond 执行条件
	 * @return integer
	 */
	public function edit($data, $cond) {
		$sql = $this->_parse_edit($data, $cond);
		return $this->execute($sql);
	}
	
	/**
	 * 修改数据
	 *
	 * @access public
	 * @param array $data 新数据
	 * @param string $cond 执行条件
	 * @return integer
	 */
	public function update($data, $cond) {
		return $this->edit($data, $cond);
	}
	
	/**
	 * 删除数据记录
	 *
	 * @access public
	 * @param string $cond 执行条件
	 * @return integer
	 */
	public function delete($cond) {
		$sql = $this->_parse_delete($cond);
		return $this->execute($sql);
	}
	
	/**
	 * 查询数据
	 *
	 * @access public
	 * @param string $fields 查询字段
	 * @param string $cond 查询条件
	 * @param string $order 排序
	 * @param integer $rows 查询数量
	 * @param integer $page 查询页数
	 * @param string $group 分组条件
	 * @param string $having 分组筛选条件
	 * @return array
	 */
	public function select($fields = '', $cond = '', $order = '', $rows = 0, $page = 0, $group = '', $having = '') {
		$sql = $this->_parse_select($fields, $cond, $order, $rows, $page, $group, $having);
		return $this->query($sql);
	}
	
	/**
	 * 设置字段值
	 *
	 * @access public
	 * @param string $field 数据字段
	 * @param string $value 字段值
	 * @param string $cond 执行条件
	 * @return integer
	 */
	public function setField($field, $value, $cond = '') {
		return $this->edit(array($field => $value), $cond);
	}
	
	/**
	 * 查询字段值
	 *
	 * @access public
	 * @param string $field 数据字段
	 * @param string $cond 查询条件
	 * @param string $order 排序
	 * @param string $group 分组
	 * @param string $having 筛选条件
	 * @return array
	 */
	public function getField($field, $cond = '', $order = '', $group = '', $having = '') {
		if(is_numeric($cond)) {
			$cond = $this->pk.'=\''.$cond.'\'';
		}
		$data = $this->find($field, $cond , $order, $group, $having);
		return $data[$field];
	}
	
	/**
	 * 查询记录数量
	 *
	 * @access public
	 * @param string $field 字段名称
	 * @param string $cond 查询条件
	 * @return integer
	 */
	public function count($field, $cond = '') {
		$sql = $this->_parse_count($field, $cond = '');
		$result = $this->query($sql);
		if(!empty($result)) {
			return $result[0]['TEMP_COUNT'];
		}
		return 0;
	}
	
	/**
	 * 求和
	 *
	 * @access public
	 * @param string $field 字段名称
	 * @param string $cond 查询条件
	 * @return integer
	 */
	public function sum($field, $cond = '') {
		$sql = $this->_parse_sum($field, $cond = '');
		$result = $this->query($sql);
		if(!empty($result)) {
			return $result[0]['TEMP_SUM'];
		}
		return 0;
	}
	
	/**
	 * 求平均值
	 *
	 * @access public
	 * @param string $field 字段名称
	 * @param string $cond 查询条件
	 * @return integer
	 */
	public function avg($field, $cond = '') {
		$sql = $this->_parse_avg($field, $cond = '');
		$result = $this->query($sql);
		if(!empty($result)) {
			return $result[0]['TEMP_AVG'];
		}
		return 0;
	}
	
	/**
	 * 求最大值
	 *
	 * @access public
	 * @param string $field 字段名称
	 * @param string $cond 查询条件
	 * @return integer
	 */
	public function max($field, $cond = '') {
		$sql = $this->_parse_max($field, $cond = '');
		$result = $this->query($sql);
		if(!empty($result)) {
			return $result[0]['TEMP_MAX'];
		}
		return 0;
	}
	
	/**
	 * 求最小值
	 *
	 * @access public
	 * @param string $field 字段名称
	 * @param string $cond 查询条件
	 * @return integer
	 */
	public function min($field, $cond = '') {
		$sql = $this->_parse_min($field, $cond = '');
		$result = $this->query($sql);
		if(!empty($result)) {
			return $result[0]['TEMP_MIN'];
		}
		return 0;
	}
	
	/**
	 * 自动增加
	 *
	 * @access protected
	 * @param string $field 字段
	 * @param string $cond 执行条件
	 * @param integer $offset 偏移量
	 * @return integer
	 */
	public function inc($field, $cond = '', $offset = 0) {
		$sql = $this->_parse_inc($field, $cond, $offset);
		return $this->execute($sql);
	}
	
	/**
	 * 自动减少
	 *
	 * @access protected
	 * @param string $field 字段
	 * @param string $cond 执行条件
	 * @param integer $offset 偏移量
	 * @return integer
	 */
	public function dec($field, $cond = '', $offset = 0) {
		$sql = $this->_parse_dec($field, $cond, $offset); 
		return $this->execute($sql);
	}
	
	/**
	 * 设置表别名
	 *
	 * @access public
	 * @param string $alias 别名
	 * @return void
	 */
	public function setAlias($alias) {
		$this->real_table_name .= ' AS '.$alias;
	}
}