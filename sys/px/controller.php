<?php
namespace sys\px;

// 控制层
abstract class controller
{
	private static $models = array();
	public $data   = array();
	public $db;
	public $view;
	
    public function __construct() 
	{
		$this->db = new model();
		$this->view = new view();
    }
	
	// 获取应用模型
	public function model($className='') 
	{	
		if(!isset(self::$models[$className])) {
			$class = SPACE_USR_MODEL.DS.$className.DEPR_MODEL;
			self::$models[$className] =  new $class();
		}
		return  self::$models[$className];
    }
}

