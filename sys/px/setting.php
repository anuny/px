<?php
namespace sys\px;

// 路由类
abstract class setting
{
	private static $config = array();

	public static function init()
	{
		self::setDir();
		self::config();
		self::setUrl();
	}
	
	private static function config()
	{
		//require_once DIR_SYS.NAME_SYS_PX.DS.'helper.php';
		//require_once DIR_SYS.NAME_SYS_PX.DS.'config.php';
		
		// 载入公共配置
		$configIncPath = DIR_CONFIG_INC.'config.inc.php';
		
		if(file_exists($configIncPath)) require_once $configIncPath;
		
		// 载入应用配置
		$configAppPath = DIR_CONFIG_APP.'config.app.php';	
		if(file_exists($configAppPath)) require_once $configAppPath;
		

		// 获取默认配置
		$configInc = config::get();

		// 合并配置
		if(isset($config) && is_array($config)){
			$configInc = array_merge($configInc, $config);
			config::set($configInc);
		}

		// 调试
		defined('DEBUG') or define('DEBUG', $configInc['DEBUG']);
		
		// 编码
		header("content-type:text/html; charset=$configInc[CHARSET]");
		
		// 时区
		@date_default_timezone_set($configInc['TIMEZONE']);
		
		// 错误屏蔽
		ini_set("display_errors", DEBUG ? 'On' : 'Off');
		error_reporting( DEBUG ? E_ALL ^ E_NOTICE : 0 );
		
		// 启用session
        if($configInc['USE_SESSION']) session_start();

		// 载入自定义函数
		$functionApp = DIR_LIB.'function.app.php';
		if(file_exists($functionApp)) require_once $functionApp;
	}
	private static function setDir()
	{
		
		// 定义当前APP命名空间
		$appSpace = NAME_USR.DS.NAME_APP.DS.URI_APP.DS;
		define('SPACE_USR_CTRL',$appSpace.NAME_CTRL);
		define('SPACE_USR_MODEL',$appSpace.NAME_MODEL);
		define('SPACE_USR_LIB',NAME_USR.DS.NAME_LIB);
	
		// 定义当前APP目录常量
		define('DIR_CONFIG_APP',DIR_APP.URI_APP.DS.NAME_CONFIG.DS);
		define('DIR_THEME_APP',DIR_THEME.URI_APP.DS.config::get('THEME').DS);
		define('DIR_COMPILED_APP',DIR_COMPILED.URI_APP.DS);
	}
	
	private static function setUrl()
	{
		$root       = str_replace(basename($_SERVER["SCRIPT_NAME"]),'',$_SERVER["SCRIPT_NAME"]);
		$app        = config::get('URL_REWRITE')? URI_APP : 'index.php/' . URI_APP;// APP路径
		$url_root   = substr($root, 0, -1);// 根路径
		$url_app    = $url_root.'/'.$app;// APP路径
		$url_usr    = $url_root.'/'.NAME_USR;// USR路径
		$url_static = $url_usr.'/'.NAME_STATIC;// 静态文件夹路径
		$url_theme  = $url_usr.'/'.NAME_THEME.'/'.URI_APP.'/'.config::get('THEME');// 主题静态文件夹
		$url_upload = $url_usr.'/'.NAME_UPLOAD;// 上传文件路径
		$url = array('ROOT'=>$url_root,'APP'=>$url_app,'THEME'=>$url_theme,'UPLOAD'=>$url_upload,'STATIC'=>$url_static);
		define('URL_ROOT',$url_root);
		define('URL_USR',$url_usr);
		define('URL_APP',$url_app);
		define('URL_THEME',$url_theme);
		define('URL_UPLOAD',$url_upload);
		define('URL_STATIC',$url_static);
	}	
	
}