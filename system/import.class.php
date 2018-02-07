<?php defined('APP') or die;

class import{
	public static $ctrls = array();
	public static $models = array();
	// 控制器之间相互调用
	static function  controller($ctrl){
		if(isset(self::$ctrls[$ctrl])){
			return self::$ctrls[$ctrl];
		}	
		$classname=$ctrl.'Controller';
		$modulePath = DIR_CONTROLLER . $classname .'.class.php';
		if(file_exists($modulePath)){
			require_once($modulePath);
			if(class_exists($classname)){
				return self::$ctrls[$ctrl]=new $classname();
			}
		}else{
			return false;
		}
	}
	//模型之间相互调用
	static function  model($model){
		if(isset(self::$models[$model])){
			return self::$models[$model];
		}
		$classname=$model.'Model';
		$path = DIR_MODEL . $classname . '.class.php';
		if(file_exists($path)){
			require_once($path);
			if(class_exists($classname)){
				return self::$models[$model]=new $classname();
			}
		}
		return false;
	}
	
}