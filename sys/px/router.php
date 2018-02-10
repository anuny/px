<?php
namespace sys\px;

// 路由类
abstract class router
{
	// 路由分发
    public static function dispatch()
	{
		// 检测模块
		$appPath = DIR_APP.URI_APP;
		if(!file_exists($appPath)) {
			new error('Application Error:Project "'.URI_APP.'"does not exist!',500) ;
		}
		
		// 检测控制器类
		$controllerName = URI_CTRL . DEPR_CTRL;
		$class = SPACE_USR_CTRL.DS.$controllerName;
		if(!class_exists($class)){
			new error('Controller Error: '.URI_APP.'"->'.$controllerName.'"  does not exist',500) ; 
		}
		
		// 实例化类
		$controller = new $class();
		
		// 检测方法
        if(!method_exists($controller, URI_ACTION)){
			new error('Action Error: '.URI_APP.'"->'.$controller.'->'.URI_ACTION.' "does not exist',500) ;
		}
		
		// 函数调用
		call_user_func(array($controller,URI_ACTION));
    }
}