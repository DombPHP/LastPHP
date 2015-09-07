<?php
class UserAction extends Action{
	private $id = 1;
	public function index(){
		echo 'index action test success!';
	}
	public function show(){
		$name = 'liwang';
		$this->assign('name',$name);
		$this->assign('sex','男');
		//$this->view('html-index');
		echo $this->fetch();
	}
	public function _empty($a){
		echo 'this is emtpy method!';echo $a;
		//$content = file_get_contents('http://hotel.qunar.com/render/hotelDetailAllImage.jsp?hotelseq=haikou_2186&_=1386422248411');
		//echo $content;
	}
}
