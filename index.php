<?php

/**
 * PX Content Management System
 * http://www.yangfei.name
 * @copyright  Copyright (c) 2018 Anuny 
 * @license pxcms is opensource software licensed under the MIT license.
 */
 
class App
{
	// 版本号
	public static $version = '0.0.1';
	
	// 单列模式
	private static $instance; 
	
	public $data = array();
	
    // 构造函数，初始化配置
    private function __construct(){
		
		// 全局目录常量
		self::defines();

		// 路由解析
		self::parseUri();
		
		// 应用配置
		self::usrConfig();
		
		// 网址解析
		self::parseUrl();
		
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
		
		$uri = self::$config['URI'];
		
		// 检测模块
		if(!file_exists(DIR_APP)) {
			self::Error('Application:"'.$uri['app'].'"does not exist',500) ;
		}

		// 检测控制器
		$controllerName = $uri['controller'];
		$controllerClass = $controllerName.'Controller';
        if(!class_exists($controllerClass)){
			
			self::Error('Controller: "'.$controllerName.'"  does not exist',500) ; 
		}
		
        $controller = new $controllerClass();
		
		// 检测方法
		$action = $uri['action'];
        if(!method_exists($controller, $action)){
			self::Error('Action: "'.$action.' "does not exist',500) ;
		}
		call_user_func(array($controller,$action));
	}
	
	public static $config=array(	
		'DEBUG' => true,// 开启调试模式
		'URL_REWRITE' => true,// 开启伪静态重写
		'USE_SESSION' => true,// 开启SESSION会话
		'DOMAIN' => '',// 设置域名	
		'THEME' => 'default',// 默认主题
		'CACHE_TPL' => true,//是否开启模板缓存	
		'CACHE_DATA'=>true,//是否开启数据缓存	
		'CHARSET' => 'UTF-8',// 设置编码
		'TIMEZONE' => 'PRC',// 设置时区
		'DB_HOST' => '127.0.0.1', // 数据库主机
		'DB_USER' => 'root', // 数据库用户名
		'DB_PWD' => 'root', // 数据库密码
		'DB_PORT' => 3306, // 数据库端口
		'DB_NAME' => '', // 数据库名
		'DB_PREFIX' => '', // 数据库前缀
		'DB_CHARSET' => 'utf8', // 数据库编码
		'CTRL_SUFFIX' => 'Controller.class.php',//控制器后缀
		'CTRL_DEFAULT' => 'index',//默认模块
		'ACTION_DEFAULT' => 'index',//默认操作
		'MODEL_SUFFIX' => 'Model.class.php'//模型后缀，一般不需要修改
	);
	
	private static function defines(){
		
		define('APP', true);
		
		// 耗时计算开始
		define('STIME', microtime(true));

		// 定义目录分隔符
		define('DS', DIRECTORY_SEPARATOR);

		// 伪静态后缀
		define('URL_HTML_SUFFIX', '.html');

		//模块，控制器，方法分隔符
		define('URL_DEPR', '/');

		//参数分隔符
		define('URL_PARAM_DEPR', '-');

		// 目录名称
		$name_maps = array(
			'USR' => 'usr', // 用户
			'APP' => 'app', // 应用
			'CONFIG' => 'config', // 配置
			'DATA' => 'data', // 数据
			'LIB' => 'lib',// 类库
			'STATIC' => 'static', // 静态文件
			'THEME' => 'theme', // 模板
			'UPLOAD' => 'upload', // 上传文件
			'CTRL' => 'controller', // 控制器
			'MODEL' => 'model', // 模型
			'CACHE' => 'cache', // 缓存
			'COMPILED'=> 'compiled', // 编译
			'LOG' => 'log' // 日志
		);
		// 定义文件夹别名
		foreach($name_maps as $key => $name) define('NAME_'.$key, $name);

		// 获取根目录
		// 定义文件夹绝对路径
		define('DIR_ROOT', dirname(__FILE__) .DS);
		define('DIR_USR', DIR_ROOT . NAME_USR .DS);
		define('DIR_CONFIG_INC', DIR_USR . NAME_CONFIG .DS);
		define('DIR_DATA', DIR_USR . NAME_DATA .DS);
		define('DIR_LIB', DIR_USR . NAME_LIB .DS);
		define('DIR_STATIC', DIR_USR . NAME_STATIC .DS);
		define('DIR_THEME', DIR_USR . NAME_THEME .DS);
		define('DIR_UPLOAD', DIR_USR . NAME_UPLOAD .DS);
		define('DIR_CACHE', DIR_DATA . NAME_CACHE .DS);
		define('DIR_COMPILED', DIR_DATA . NAME_COMPILED .DS);
		define('DIR_LOG', DIR_DATA . NAME_LOG .DS);
	}
	
	
	private static function parseUri(){
		$app = 'index';
		$controller = 'index';
		$action = 'index';
		
		// 支持 PATH_INFO 模式
		if(isset($_SERVER['PATH_INFO'])){
			$uri = $_SERVER['PATH_INFO'];
		}else{
			// 解析网址参数
			$uri = $_SERVER['REQUEST_URI'];
			$scr = $_SERVER['SCRIPT_NAME'];
			// 去除index.php文件信息
			if ( $uri && @strpos($uri,$scr,0) !== false ){
				$uri = substr($uri, strlen($scr));
			} else {
				$scr = str_replace(basename($scr), '', $scr);
				if ( $uri && @strpos($uri, $scr, 0) !== false ) $uri = substr($uri, strlen($scr));
			}
			$uri = parse_url($uri, PHP_URL_PATH);
			//去除问号后面的查询字符串
			if ( $uri && false !== ($pos = @strrpos($uri, '?')) ) $uri = substr($uri,0,$pos);
			//去除后缀
			if ($uri&&($pos = strrpos($uri,'.html')) > 0) $uri = substr($uri,0,$pos);
		}
		// 将路径中的 '//' 或 '../' 等进行清理
		$uri = str_replace(array('//', '../'), '/', trim($uri, '/'));	
		
		if ($uri) {
			// 使用“/”分割字符串，并保存在数组中
			$urlArray = explode('/', $uri);
			// 删除空的数组元素
			$urlArray = array_filter($urlArray);
			
			// 获取应用名
			if($urlArray && $urlArray[0]) $app = $urlArray[0];

			// 获取控制器名
			array_shift($urlArray);
			if($urlArray) $controller = $urlArray[0];
			
			// 获取方法名
			array_shift($urlArray);
			if($urlArray) $action = $urlArray[0];
			
			// 获取URL参数
			array_shift($urlArray);
			$param = $urlArray ? $urlArray[0] : '';
			$param = explode('-', $param);
			$param_count = count($param);
			for($i=0; $i<$param_count; $i=$i+2) {			
				$_GET[$i] = $param[$i];
				if(isset($param[$i+1])) {
					if( !is_numeric($param[$i]) ){
						$_GET[$param[$i]] = $param[$i+1];
					}
					$_GET[$i+1] = $param[$i+1];
				}
			}
		}
		self::$config['URI'] = array(NAME_APP=>$app,NAME_CTRL=>$controller,'action'=>$action,'get'=>$_GET);	
	}
	
	private static function parseUrl(){
		$config = self::$config;
		$appName = $config['URI']['app'];
		$root = str_replace(basename($_SERVER["SCRIPT_NAME"]),'',$_SERVER["SCRIPT_NAME"]);
		$appPath = $config['URL_REWRITE']? $appName : 'index.php/'.$appName;// APP路径
		$url_root   = substr($root, 0, -1);// 根路径
		$url_app    = $url_root.'/'.$appPath;// APP路径
		$url_usr = $url_root.'/'.NAME_USR;// 静态文件夹路径
		$url_static = $url_usr.'/'.NAME_STATIC;// 静态文件夹路径
		$url_theme  = $url_usr.'/'.NAME_THEME.'/'.$appName.'/'.$config['THEME'];// 主题静态文件夹
		$url_upload = $url_usr.'/'.NAME_UPLOAD;// 上传文件路径
		self::$config['URL'] = array('ROOT'=>$url_root,'APP'=>$url_app,'USR'=>$url_usr,'THEME'=>$url_theme,'UPLOAD'=>$url_upload,'STATIC'=>$url_static);
	}
	
	
	//获取参数
	public static function getConfig( $key = '' ) 
	{
		return (isset($key) && $key !='') ? self::$config[$key] : self::$config;
	}
	
	//设置参数
	public static function setConfig( $key , $value='') 
	{
		return (isset($value) && !empty($value)) ? self::$config[$key] = $value : self::$config = $key;
	}
	
	// 错误提示
	public static function Error( $errorMessage, $errorCode = 0, $url='') 
	{
		
		$Exception = new Exception($errorMessage, $errorCode);

		$errorCode == 0 ? $Exception->getCode() : $errorCode;
        $errorFile = $Exception->getFile();
        $errorLine = $Exception->getLine();

		$trace = $Exception->getTrace();
        $errorTrace='';
        foreach($trace as $t) {
            $errorTrace .= $t['file'] . ' (' . $t['line'] . ') ';
            $errorTrace .= $t['class'] . $t['type'] . $t['function'] . '(';
            $errorTrace .= ")<br />\r\n";
        }

		$view = self::view();
		
		//错误页面重定向
		if($url != ''){
			
			exit('<script language="javascript">if(self!=top){parent.location.href="'.$url.'";} else {window.location.href="'.$url.'";}</script>');
		}
		@header("HTTP/1.1 $errorCode Not Found");

		$errorTpl = DIR_THEME_APP. self::getConfig('THEME') . DS . 'error.php';
		
		
		 
		
		if (file_exists($errorTpl)) {
			$view->assign('error',array('code' => $errorCode,'message' => $errorMessage,'trace' => $errorTrace));
			$view->render('error');
		} else {
			$info = $errorCode && DEBUG ? '<p>'.$errorMessage.'</p><p style="font-size:12px;">'.$errorTrace.'</p> ' : '<p> 很抱歉，您访问的页面不存在或已删除。 </p> ';
			echo '
			<!doctype html>
			<html>
			<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
			<title>Error</title>
			</head>
			<body>
			<h1>'.$errorCode.' Error</h1>';
			echo $info;
			echo '<p><a href="javascript:history.back()" >返回</a></p> 
			</body>
			</html>';
		}
		exit;	
	}
	
	
	// 应用配置
	private static function usrConfig()
	{

		// 当前应用名称
		$appName = self::$config['URI'][NAME_APP];

		// 定义当前APP目录常量
		define('DIR_APP',DIR_USR.NAME_APP.DS.$appName.DS); //当前应用
		define('DIR_CONFIG_APP',DIR_APP.NAME_CONFIG.DS); // 配置
		define('DIR_CTRL',DIR_APP.NAME_CTRL.DS); // 控制器
		define('DIR_MODEL',DIR_APP.NAME_MODEL.DS);// 模型
		define('DIR_THEME_APP',DIR_THEME.$appName.DS);// 模板
		define('DIR_COMPILED_APP',DIR_COMPILED.$appName.DS); // 模板编译
		
		// 载入公共配置
		$configIncPath = DIR_CONFIG_INC.'Config.inc.php';
		if(file_exists($configIncPath)) require_once($configIncPath);
			
		// 载入应用配置
		$configAppPath = DIR_CONFIG_APP.'config.app.php';	
		if(file_exists($configAppPath)) require_once($configAppPath);
		
		// 获取默认配置
		//$configInc = Config::get();
		$configInc = self::$config;

		// 合并配置
		if(isset($config) && is_array($config)) $configInc = array_merge($configInc, $config);
		
		// 载入自定义函数
		$functionApp = DIR_LIB.'function.app.php';
		if(file_exists($functionApp)) require_once($functionApp);
		
		// 保存配置
		self::setConfig($configInc);

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
    }
	
	// 自动加载
	private static function autoload(){
		spl_autoload_register(array(__CLASS__,'loader'));
	}

    private static function loader($className)
	{
		$className = ucfirst($className);
		$dirs = array(DIR_LIB,DIR_CTRL,DIR_MODEL);
		foreach($dirs as $dir) {
			$file = $dir . $className . '.class.php';
			if ( is_file($file) ) {   
				require_once($file); 
				return true;
			} 
		}
		return false;
    }
	
	// 缓存
	public static function Cache()
	{
		
    }
	
	
	// 注入模板数据
	public function assign($k, $v)
	{
		$this->data[$k] = $v;
		
    }
	
	// 渲染模板
	public function render($tplName)
	{
		$tplName = trim($tplName);

		// 没有模板名
		if(!$tplName){
			$tplName = self::$config['URI']['controller']. '.' . self::$config['URI']['action'];
		};
		
		// 模板文件名
		$tplFile  = DIR_THEME_APP .  self::$config['THEME'] . DS . $tplName. '.php';
		
		// 判断模板文件是否存在
		if(!file_exists($tplFile)){
			self::Error('Error: 模板 '.$tplFile.' 不存在！', 500) ;	
		}
		
		// 编译文件名
		$compileFile  = DIR_COMPILED_APP . $tplName. '.php';

		// 编译模板
        if(!file_exists($compileFile) || (filemtime($compileFile) < filemtime($tplFile))){
			$path = dirname($compileFile);
			if(!is_dir($path) && !self::mkDir($path)){
				self::Error('Error:"' . $tplName . '"编译目录创建失败！', 500) ;
			}
		
			$content = file_get_contents($tplFile);
			$content = self::parse($content);
            if(!file_put_contents($compileFile, $content)){
				self::error('Error:"' . $tplName . '"编译失败！', 500) ;
			}
        }
		
		// 导入数据
		extract($this->data);

		//载入编译文件
		include $compileFile;
		
		unset($tplName);
		
    }
	
	// 创建文件夹
	private static function mkDir($path, $mode = 0777){
		return !is_dir($path) && mkdir($path,0777,true) || @chmod($true,0777);
	}
	
	
	// 获取网址
	private static function getUrl($name){
		$name = strtoupper($name);
		$url = self::getConfig('URL');
		return $url[$name];
	}
	
	private static function addquote($var){
		return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
	}
	
	// 模板解析器
	public static function parse($tpl){
		//替换载入模板
		
		$tpl = preg_replace("/([\n\r]+)\t+/s","\\1",$tpl);
		$tpl = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}",$tpl);
		$tpl = preg_replace("/\{url\s+(\S+)\}/","<?php echo self::getUrl('\\1');?>",$tpl);
		
		$tpl = preg_replace('/<!--#include\s*file=[\"|\'](.*)[\"|\']-->/iU',"<?php \$this->render('$1'); ?>", $tpl);
		$tpl = preg_replace("/\{include\s+(.+)\}/","<?php \$this->render('\\1'); ?>",$tpl);		
		
		
		$tpl = preg_replace("/\{php\s+(.+)\}/","\n<?php \\1?>",$tpl);
		$tpl = preg_replace("/\{if\s+(.+?)\}/","<?php if(\\1) { ?>",$tpl);
		$tpl = preg_replace("/\{else\}/","<?php } else { ?>",$tpl);
		$tpl = preg_replace("/\{elseif\s+(.+?)\}/","<?php } elseif (\\1) { ?>",$tpl);
		$tpl = preg_replace("/\{\/if\}/","<?php } ?>",$tpl);
		$tpl = preg_replace("/\{foreach\s+(\S+)\s+(\S+)\}/","<?php if(is_array(\\1)) foreach(\\1 AS \\2){?>",$tpl);
		$tpl = preg_replace("/\{foreach\s+(\S+)\s+(\S+)\s+(\S+)\}/","<?php if(is_array(\\1)) foreach(\\1 AS \\2 => \\3){?>",$tpl);
		$tpl = preg_replace("/\{\/foreach\}/","<?php }?>",$tpl);
		$tpl = preg_replace("/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\(([^{}]*)\))\}/","<?php echo \\1;?>",$tpl);
		$tpl = preg_replace("/\{\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\(([^{}]*)\))\}/","<?php echo \\1;?>",$tpl); // 函数
		$tpl = preg_replace("/\{(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/","<?php echo \\1;?>",$tpl); // 变量
		$tpl = preg_replace("/\{(\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\}/es", "self::addquote('<?php echo \\1;?>')",$tpl);
		$tpl = preg_replace("/\{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)\}/s", "<?php echo \\1;?>",$tpl); // 数组对象
		
		$tpl = preg_replace ( "/\{(\\$[a-z0-9_]+)\.([a-z0-9_]+)\}/i", "<?php echo $1['$2']; ?>", $tpl);
		$tpl = preg_replace ( "/\{(\\$[a-z0-9_]+)\.([a-z0-9_]+)\.([a-z0-9_]+)\}/i", "<?php echo $1[\'$2\'][\'$3\']; ?>", $tpl);
		
		$tpl = preg_replace ( "/\{(\\$[a-z0-9_]+)\::([a-z0-9_]+)\}/i", "<?php $1::$2; ?>", $tpl);
		$tpl = preg_replace ( "/\{(\\$[a-z0-9_]+)\::([a-z0-9_]+)\.([a-z0-9_]+)\}/i", "<?php echo $1::\'$2\'][\'$3\']; ?>", $tpl);
	
	
		return $tpl;
	}
	
	// 视图层
	public static function view(){
		
		
	}
	
	// 模型层
	public static function Model(){
		
	}
	
	
	// 工具
	public static function Helper(){
		
	}
}

// 控制器
class Controller extends App
{
    public function __construct()
	{
		parent::instance();
		if(method_exists($this, '_construct')) $this->_construct();
    }
}

// 运行系统
App::Instance()->BootStrap(); 
