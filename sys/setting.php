<?php
namespace sys;

// 初始配置类
abstract class setting
{
	public static function init()
	{
		self::setDir(); // 目录配置
		self::config(); // 系统设置
		self::setUrl(); // 网址设置
	}
	
	private static function setDir()
	{	
		// 定义当前APP命名空间
		$appSpace = NAME_USR.DS.NAME_APP.DS.URI_APP.DS;
		
		// 控制器命名空间
		define('SPACE_CTRL',$appSpace.NAME_CTRL);
		
		// 模型命名空间
		define('SPACE_MODEL',$appSpace.NAME_MODEL);
		
		// 类库命名空间
		define('SPACE_LIB',NAME_USR.DS.NAME_LIB);
		
		// 定义当前APP目录常量
		define('DIR_CONFIG_APP',DIR_APP.URI_APP.DS.NAME_CONFIG.DS);
		define('DIR_COMPILED_APP',DIR_COMPILED.URI_APP.DS);
	}
	
	private static function config()
	{
		// 载入公共配置
		$configIncPath = DIR_CONFIG_INC.DEPER_CONFIG.'.php';	
		if(file_exists($configIncPath)) require_once $configIncPath;
		
		// 载入应用配置
		$configAppPath = DIR_CONFIG_APP.DEPER_CONFIG_APP.'.php';	
		if(file_exists($configAppPath)) require_once $configAppPath;
		

		// 获取默认配置
		$configInc = config::get();

		// 合并配置
		if(isset($config) && is_array($config)){
			$configInc = array_merge($configInc, $config);
			config::set($configInc);
		}
		
		// 应用主题目录
		define('DIR_THEME_APP',DIR_THEME.URI_APP.DS.$configInc['THEME'].DS);

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
		$functionApp = DIR_LIB.DEPER_FUNCTION_APP.'.php';
		if(file_exists($functionApp)) require_once $functionApp;
	}
	
	
	private static function setUrl()
	{
		$root = str_replace(basename($_SERVER["SCRIPT_NAME"]),'',$_SERVER["SCRIPT_NAME"]);
		$app = config::get('URL_REWRITE')? URI_APP : 'index.php/' . URI_APP;
		$app = DEFAULT_MAP && ($app ==DEFAULT_APP) ? '' : '/'.$app;
		define('URL_ROOT',substr($root, 0, -1));   // 根路径
		define('URL_USR',URL_ROOT.'/'.NAME_USR);// USR路径
		define('URL_APP',URL_ROOT.$app);// APP路径
		define('URL_THEME',URL_USR.'/'.NAME_THEME.'/'.URI_APP.'/'.config::get('THEME'));// 主题静态文件夹
		define('URL_UPLOAD',URL_USR.'/'.NAME_UPLOAD);// 上传文件路径
		define('URL_STATIC',URL_USR.'/'.NAME_STATIC);// 静态文件夹路径
	}	
	
}