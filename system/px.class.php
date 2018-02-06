<?php defined('APP') or die;

// 加载全局目录配置
require_once('common.path.php');

class app
{
	// 单列模式
	private static $instance; 
    
    // 构造函数，初始化配置
    private function __construct(){
		
		//框架版本号
		define('APP_VER', '0.0.1');
		
		//载入默认配置类
        require_once('config.class.php');

		//载入公共配置
		require_once(DIR_CONFIG_INC.'config.inc.php');
		
		// 载入应用配置
		$app_config_file = DIR_CONFIG_APP.'config.php';
		if(file_exists($app_config_file)) require_once($app_config_file);

		//参数配置
		$config = array_merge(config::get(), $config);
		
		defined('DEBUG') or define('DEBUG', $config['DEBUG']);
		
		// 错误屏蔽
		ini_set("display_errors", DEBUG ? 1 : 0);
		error_reporting( DEBUG ? E_ALL ^ E_NOTICE : 0 );
		
		// 编码
		header("content-type:text/html; charset=$config[CHARSET]");

		// 时区
		@date_default_timezone_set($config['TIMEZONE']);
		
		// 启用session会话
        if($config['USE_SESSION']) session_start();
		
		//参数配置
		config::set($config);

		// 载入错误类
		require_once('error.class.php');
		
		// 加载常用函数库
		require_once('common.function.php');
		
		// 路由
		$this->uri = parseUrl();

		config::set('URI',$this->uri);
		
		$app = config::get('URL_REWRITE') ? APP : APP.'.php';

		// 获取地址
		$url = getURLS();

		config::set('URL',$url);
		
		// 载入控制类
		require_once('mvc.class.php');
		
		//注册类的自动加载
		spl_autoload_register( array($this, 'autoload') );
    }

	// 初始化
	public static function init()
	{
        if(!(self::$instance instanceof self))  self::$instance = new self();
        return self::$instance;
    }

    // 实例化
    public function run()
	{
		// 检测模块
		if(!file_exists(DIR_APP)) new Error('Application:"'.APP.'"does not exist',404) ;		

		// 检测控制器
		$controller = $this->uri['controller'];
		$controllerClass = $controller.'Controller';
        if(!class_exists($controllerClass)) new Error("Controller:$controller does not exist",404) ; 
        $controller = new $controllerClass();
		
		// 检测方法
		$action = $this->uri['action'];
        if(!method_exists($controller, $action)) new Error("Action:$action Controller",404) ;
        call_user_func(array($controller,$action));
    }
	
	// 自动加载函数
    private function autoload($class)
	{
		if(substr($class, -10) == ufirst(ALIAS_CONTROLLER) ){// 加载控制器 
			$classPath = DIR_CONTROLLER;
		}else if(substr($class, -5) == ufirst(ALIAS_MODEL)){ // 加载模型
			$classPath = DIR_MODEL;	
		}else if(substr($class, -3) == ufirst(ALIAS_LIB)){ // 加载用户扩展
			$classPath = DIR_LIB;	
		}else{
			$classPath = DIR_SYS;//加载系统扩展
		}
		$classPath .= $class. '.class.php';
		file_exists($classPath) ? require_once($classPath) : new Error('Controller File:"'.APP . DS . $class . '"does not exist', 404) ;
    }
}
