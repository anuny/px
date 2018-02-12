<?php
namespace sys;

// 控制层
abstract class controller
{
	private static $models = array();
	private static $controllers = array();
	public $data   = array();
	public $db;
	public $view;

    public function __construct()
	{
		$this->db = new model();
		$this->view = new view();
    }

	/**
	 * [获取应用模型]
	 * @param  string $className [模型名称]
	 * @return [object]          [模型对象]
	 */
	public function model($className='')
	{
		if(!isset(self::$models[$className])) {
			$class = SPACE_MODEL.DS.$className.DEPR_MODEL;
			self::$models[$className] =  new $class();
		}
		return  self::$models[$className];
    }
	
	public function controller($className='')
	{
		if(!isset(self::$controllers[$className])) {
			$class = SPACE_CTRL.DS.$className.DEPR_CTRL;
			self::$controllers[$className] =  new $class();
		}
		return  self::$controllers[$className];
    }
	
}
