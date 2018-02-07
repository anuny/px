<?php defined('APP') or die;
class App
{
	// 版本号
	public static $version = '0.0.1';
	
	// 单列模式
	private static $instance; 
	
    // 构造函数，初始化配置
    private function __construct(){
		
		// 应用配置
		self::initConfig();
		
		// 自动加载类
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
			new Error('Application:"'.$uri['app'].'"does not exist',500) ;
		}

		// 检测控制器
		$controllerName = $uri['controller'];
		$controllerClass = $controllerName.'Controller';
        if(!class_exists($controllerClass)){
			new Error('Controller: "'.$controllerName.'"  does not exist',500) ; 
		}
		
        $controller = new $controllerClass();
		
		// 检测方法
		$action = $uri['action'];
        if(!method_exists($controller, $action)){
			new Error('Action: "'.$action.' "does not exist',500) ;
		}
		call_user_func(array($controller,$action));
	}
	

	private static function initConfig()
	{
		// 加载全局目录配置
		require_once('com.define.php');
		
		// 载入路由类
		require_once('uri.class.php');

		// 获取 应用/控制器/方法/参数
		$uri = Uri::get();
		$app_name = $uri[NAME_APP];
		
		// 定义当前APP目录常量
		define('DIR_APP',DIR_USR.NAME_APP.DS.$app_name.DS);
		define('DIR_CONFIG_APP',DIR_APP.NAME_CONFIG.DS);
		define('DIR_CTRL',DIR_APP.NAME_CTRL.DS);
		define('DIR_MODEL',DIR_APP.NAME_MODEL.DS);
		define('DIR_THEME_APP',DIR_THEME.$app_name.DS);
		define('DIR_COMPILED_APP',DIR_COMPILED.$app_name.DS);
		
		// 载入默认配置类
        require_once('config.class.php');

		// 载入公共配置
		$configIncPath = DIR_CONFIG_INC.'config.inc.php';
		if(file_exists($configIncPath)) require_once($configIncPath);
			
		// 载入应用配置
		$configAppPath = DIR_CONFIG_APP.'config.app.php';	
		if(file_exists($configAppPath)) require_once($configAppPath);
		
		// 获取默认配置
		$configInc = Config::get();
		
		// 路由配置
		$configInc['URI'] = $uri;

		// 合并配置
		if(isset($config) && is_array($config)){
			$configInc = array_merge($configInc, $config);
			Config::set($configInc);
		}
		
		// 网址解析
		require_once('url.class.php');
		$url = Url::get();
		Config::set('URL',$url);
		
		// 调试
		defined('DEBUG') or define('DEBUG', $configInc['DEBUG']);
		
		// 编码
		header("content-type:text/html; charset=$configInc[CHARSET]");
		
		// 时区
		@date_default_timezone_set($configInc['TIMEZONE']);
		
		// 错误屏蔽
		ini_set("display_errors", DEBUG ? 1 : 0);
		error_reporting( DEBUG ? E_ALL ^ E_NOTICE : 0 );
		
		// 启用session
        if($configInc['USE_SESSION']) session_start();
		
		// 载入错误类
		require_once('error.class.php');
		
		// 加载工具类
		require_once('helper.class.php');
		
		// 载入自定义函数
		$functionApp = DIR_LIB.'app.function.php';
		if(file_exists($functionApp)) {
			require_once($functionApp);
		}

		// 载入控制类
		require_once('controller.class.php');
    }
	

	private static function autoload(){
		spl_autoload_register(array(__CLASS__,'loader'));
	}
	
	// 自动加载
    private static function loader($className)
	{
		$dirs = array(DIR_CTRL,DIR_LIB,DIR_SYS,DIR_MODEL);
		foreach($dirs as $dir) {
			$file = $dir . $className . '.class.php';
			if ( is_file($file) ) {   
				require_once($file); 
				return true;
			} 
		}
		return false;
    }
}
