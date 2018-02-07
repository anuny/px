<?php defined('APP') or die;

class Import{
	public static $ctrls = array();
	public static $models = array();
	// 控制器之间相互调用
	static function  controller($name){
		if(isset(self::$ctrls[$name])){
			return self::$ctrls[$name];
		}	
		$classname=$name.'Controller';
		$path = DIR_CONTROLLER . $classname .'.class.php';
		if(file_exists($path)){
			require_once($path);
			if(class_exists($classname)){
				return self::$ctrls[$name]=new $classname();
			}
		}else{
			return false;
		}
	}
	//模型之间相互调用
	static function  model($name){
		if(isset(self::$models[$name])){
			return self::$models[$name];
		}
		$classname=$name.'Model';
		$path = DIR_MODEL . $classname . '.class.php';
		if(file_exists($path)){
			require_once($path);
			if(class_exists($classname)){
				return self::$models[$name]=new $classname();
			}
		}
		return false;
	}
	
}