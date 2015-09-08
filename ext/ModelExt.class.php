<?php
include CORE_PATH.'/pack/model/Model.class.php';
class ModelExt extends Model {
	
	public function __construct() {
		parent::__construct($GLOBALS['CONF']);
	}
}