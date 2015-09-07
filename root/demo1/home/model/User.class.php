<?php
class UserModel extends AdminModel{
	private $id = 0;
	function __construct(){
		//echo 1;
	}
	public function show(){
		echo 'this is my first model->show method';
	}
}
