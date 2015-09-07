<?php
use \Ext\Image\User as u;
class UserController extends AdminController {
	private $id = 0;
	function __construct(){
		parent::__construct();
	}
	public function index(){
		$name = 'liwang';
		$sex = 1;
		$user = new UserModel();
		$user->query('select date()');
		//$act = new \Action\User();
		// $namespace = new \Model\User();
		$namespace = new u();
		// echo $user->getLastSql();
		// $this->success('heheh');
		// $this->error('error!');
		// $this->redirect('index');
		// trigger_error('xxx',E_USER_WARNING);
		// throw new Exception('用户名错误');
		$this->assign('name', 'liwang');
		$this->assign('sex', 1);
		$this->display();
	}
	public function show(){
		$model = new User();
		$model->find();
	}
}
