<?php defined('APP') or die;
class App
{
	//框架版本号
	public static $version = '0.0.1';
	
	// 单列模式
	private static $instance; 
	
    // 构造函数，初始化配置
    private function __construct(){
		
		// 路径配置
		self::initConfig();
		
		// 基础设置
		self::initSetting();
		
		// 加载基类
		self::requireClass();
		
		//自动加载类
		self::autoload();
    }

	// 初始化
	public static function instance()
	{
        if(!(self::$instance instanceof self)) {
			self::$instance = new self();
		}
        return self::$instance;
    }
	
	// 运行
	public function bootstrap(){
		
		$uri = Config::get('URI');
		
		// 检测模块
		if(!file_exists(DIR_APP)) {
			new Error('Application:"'.APP.'"does not exist',500) ;
		}
		
		
		
		print_r($uri);
		
		// 检测控制器
		$controllerName = $uri['controller'];
		$controllerClass = $controllerName.'Controller';
        if(!class_exists($controllerClass)){
			new Error("Controller:$controllerName does not exist",500) ; 
		}
		
        $controller = new $controllerClass();
		
		// 检测方法
		$action = $uri['action'];
        if(!method_exists($controller, $action)){
			new Error("Action:$action does not exist",500) ;
		}
		call_user_func(array($controller,$action));
	}
	
	private static function  requireClass()
	{
		// 载入错误类
		require_once('error.class.php');
		
		// 加载工具类
		require_once('util.class.php');
		
		// 载入自定义函数
		self::extRequire(DIR_LIB.'app.function.php');
		
		// 载入控制类
		require_once('mvc.class.php');
    }
	

	private static function initConfig()
	{
		
		// 加载全局目录配置
		require_once('com.define.php');
		
		// 载入默认配置类
        require_once('config.class.php');
		$config = config::get();

		// 载入公共配置
		self::extRequire(DIR_CONFIG_INC.'config.inc.php');
		
		// 载入路由类
		require_once('uri.class.php');
		
		$uri = Uri::get();
		define('DIR_APP',DIR_USR.$uri[ALIAS_APP].DS);
		define('DIR_APP_CONTROLLER',DIR_APP.ALIAS_CONTROLLER.DS);
		define('DIR_APP_MODEL',DIR_APP.ALIAS_MODEL.DS);
		
		
		define('DIR_CONFIG_APP',DIR_APP.ALIAS_CONFIG.DS);
		define('DIR_CACHE_DATA', DIR_DATA.ALIAS_CACHE_DATA.DS.$uri[ALIAS_APP].DS);
		define('DIR_CACHE_TPL',  DIR_DATA.ALIAS_CACHE_TPL.DS.$uri[ALIAS_APP].DS);
		define('DIR_CACHE_SQL',  DIR_DATA.ALIAS_CACHE_SQL.DS.$uri[ALIAS_APP].DS);

		// 载入应用配置
		self::extRequire(DIR_CONFIG_APP.'config.inc.php');
		
		$config['URI'] = $uri;

		// 合并配置
		if(isset($config)){
			$config = array_merge(config::get(), $config);
			Config::set($config);
		}
		
    }
	private static function initSetting()
	{
		$config = Config::get();
		defined('DEBUG') or define('DEBUG', $config['DEBUG']);
		
		// 编码
		header("content-type:text/html; charset=$config[CHARSET]");
		
		// 时区
		@date_default_timezone_set($config['TIMEZONE']);
		
		// 错误屏蔽
		ini_set("display_errors", DEBUG ? 1 : 0);
		error_reporting( DEBUG ? E_ALL ^ E_NOTICE : 0 );
		
		// 启用session会话
        if($config['USE_SESSION']) session_start();
    }

	private static function autoload(){
		spl_autoload_register(array(__CLASS__,'loader'));
	}
	
	// 自动加载函数
    private static function loader($className)
	{
		
		if(substr($className, -10) == Util::ucfirst(ALIAS_CONTROLLER) ){// 加载控制器 
			$classPath = DIR_APP_CONTROLLER;
		}else if(substr($className, -5) == Util::ucfirst(ALIAS_MODEL)){ // 加载模型
			$classPath = DIR_APP_MODEL;	
		}else if(substr($className, -3) == Util::ucfirst(ALIAS_LIB)){ // 加载用户扩展
			$classPath = DIR_LIB;	
		}else{
			$classPath = DIR_SYS;//加载系统扩展
		}
		$classPath .= "$className.class.php";
		
	
		
		if(!self::extRequire($classPath)){		
			new Error("Controller File:". DIR_APP . "$className does not exist", 500) ;
		}
    }
	
	private static function extRequire($path)
	{
		if(file_exists($path)) {
			require_once($path);
			return true;
		}
		return false;
    }
}
