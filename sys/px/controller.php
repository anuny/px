<?php
namespace sys\px;

// 控制层
abstract class controller{
	private static $models = array();
	public $data   = array();
	public $db;
	public $view;
	
    public function __construct() {
		$this->db = new model();
		$this->view = new view();
    }

	public function model($className='') 
	{	
		if(!isset(self::$models[$className])) {
			$uri = config::get('URI');
			$modelName = $className.DEPR_MODEL;
			$class = '\\'.NAME_USR.'\\'.NAME_APP.'\\'.$uri['app'].'\\'.NAME_MODEL.'\\'.$modelName;
			self::$models[$className] =  new $class();
		}
		return  self::$models[$className];
    }
}