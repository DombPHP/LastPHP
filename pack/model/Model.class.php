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

include 'AbstractModel.class.php';

/**
 * 框架数据模型类
 */
class Model extends AbstractModel {
	
	/**
	 * 查询一条记录
	 *
	 * @access public
	 * @param string $fields 数据字段
	 * @param string $cond 查询条件
	 * @param string $order 排序
	 * @return array
	 */
	public function find($fields='', $cond='', $order='') {
		$sql = $this->_parse_find($fields, $cond,$order);
		return $this->query($sql);
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
	 * 
	 * @return array
	 */
	public function select($sql) {
		return $this->query($sql);
	}
}