<?php

/**
 * PX Content Management System
 * http://pxcms.yangfei.name
 * @copyright Copyright (c) 2018 Anuny
 * @license pxcms is opensource software licensed under the MIT license.
 * @license pxcms is opensource software licensed under the MIT license.
 */

namespace sys;
require_once 'defined.php';

class app {

	// 单例
	private static $instance;

	// 构造
    private function __construct()
	{
		// 自动加载类
		spl_autoload_register('self::loader');
    }
	// 初始化
	public static function init()
	{
        if(!(self::$instance instanceof self)) {
			self::$instance = new self();
		}
        return self::$instance;
    }

	// 实例化
	public static function bootStrap()
	{
		// 网址解析
		uri::parse();

		// 初始配置
		setting::init();

		// 路由分发
		router::dispatch();
	}

	// 加载器
	private static function loader($className)
	{
		$classPath = DIR_ROOT . $className . '.php';
		if ( is_file($classPath) ) {
			require_once $classPath;
		}else{
			new error('Loader Error: Class files"'.$classPath.'"does not exist!',500) ;
		}
    }
	
	//防止克隆
	public function __clone() {} 

}
