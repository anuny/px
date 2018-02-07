<?php defined('APP') or die;
class app
{
	// 单列模式
	private static $instance; 
	
	//框架版本号
	public static $version = '0.0.1';
	
    // 构造函数，初始化配置
    private function __construct(){
		
		// 配置模块
		$this->initConfig();
		
		$this->initSetting();

		$this->initClass();
		
		//注册类的自动加载
		spl_autoload_register(array($this, 'autoload'));
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
    public function run()
	{
		// 检测模块
		if(!file_exists(DIR_APP)) {
			new error('Application:"'.APP.'"does not exist',404) ;
		}
		
		$uri = config::get('URI');

		// 检测控制器
		$controllerName = $uri['controller'];
		$controllerClass = $controllerName.'Controller';
        if(!class_exists($controllerClass)){
			new error("Controller:$controllerName does not exist",404) ; 
		}
        $controller = new $controllerClass();
		
		// 检测方法
		$action = $uri['action'];
		
        if(!method_exists($controller, $action)){
			new error("Action:$action Controller",404) ;
		}
        call_user_func(array($controller,$action));
    }
	
	private function  initConfig()
	{
		// 加载全局目录配置
		require_once('com.define.php');

		//载入默认配置类
        require_once('config.class.php');

		//载入公共配置
		require_once(DIR_CONFIG_INC.'config.inc.php');
		
		// 载入应用配置
		$this->extRequire(DIR_CONFIG_APP.'config.class.php');
		
		//参数配置
		$config = array_merge(config::get(), $config);
		
		config::set($config);
    }
	private function  initSetting()
	{
		$config = config::get();
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
		
    }
	private function  initClass()
	{
		// 载入错误类
		require_once('error.class.php');
		
		// 加载工具类
		require_once('util.class.php');
		
		// 载入自定义函数
		$this->extRequire(DIR_LIB.'app.function.php');
		
		// 载入路由类
		require_once('uri.class.php');
		$uri = new uri();
		
		config::set('URI',$uri->uri);
		
		// 获取地址
		config::set('URL',$uri->url);

		// 载入控制类
		require_once('mvc.class.php');
    }
	
	// 自动加载函数
    private function autoload($class)
	{
		if(substr($class, -10) == util::ucfirst(ALIAS_CONTROLLER) ){// 加载控制器 
			$classPath = DIR_CONTROLLER;
		}else if(substr($class, -5) == util::ucfirst(ALIAS_MODEL)){ // 加载模型
			$classPath = DIR_MODEL;	
		}else if(substr($class, -3) == util::ucfirst(ALIAS_LIB)){ // 加载用户扩展
			$classPath = DIR_LIB;	
		}else{
			$classPath = DIR_SYS;//加载系统扩展
		}
		$classPath .= $class. '.class.php';
		
		if(!$this->extRequire($classPath)){		
			new error('Controller File:"'.APP . DS . $class . '"does not exist', 404) ;
		}
    }
	private function extRequire($path)
	{
		if(file_exists($path)) {
			require_once($path);
			return true;
		}
		return false;
    }
}
