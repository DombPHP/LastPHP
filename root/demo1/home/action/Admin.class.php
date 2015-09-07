<?php
class AdminAction extends Action{
	function __construct(){
		echo 'parent construct';
	}
	public function find(){
		$this->display();
	}
}
