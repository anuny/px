<?php
namespace sys\px;

// 路由类
abstract class router extends app{
	private static $uri = array();
	
	// 路由分发
    public static function dispatch()
	{
		// 保存路由配置
		$uri = config::get('URI');

		// 检测模块
		$appPath = DIR_APP.$uri['app'];
		if(!file_exists($appPath)) {
			new error('Application:"'.$uri['app'].'"does not exist',500) ;
		}
		
		// 检测控制器类
		$controllerName = "$uri[controller]Controller";
		$class = '\\'.NAME_USR.'\\'.NAME_APP.'\\'.$uri['app'].'\\'.NAME_CTRL.'\\'.$controllerName;
		if(!class_exists($class)){
			new error('Controller: "'.$controllerName.'"  does not exist',500) ; 
		}
		
		// 实例化类
		$controller = new $class();
		
		// 检测方法
		$action = $uri['action'];
        if(!method_exists($controller, $action)){
			new error('Action: "'.$action.' "does not exist',500) ;
		}
		
		// 函数调用
		call_user_func(array($controller,$action));
    }
}