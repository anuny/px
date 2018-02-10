<?php
namespace sys\px;

// 路由类
abstract class path{
	private static $config = array();

	public static function config(){
		self::$config = config::get();
		self::setDir();
		self::setUrl();
	}
	private static function setDir(){
		
		$appName = self::$config['URI']['app'];
		define('SPACE_USR_CTRL',NAME_USR.DS.$appName.DS.NAME_CTRL);
		
		print_r(self::$config['URI']);
	}
	
	private static function setUrl(){
		$uri = self::$uri;
		$appName = $uri['app'];
		$root       = str_replace(basename($_SERVER["SCRIPT_NAME"]),'',$_SERVER["SCRIPT_NAME"]);
		$app        = config::get('URL_REWRITE')? $appName : 'index.php/' . $appName;// APP路径
		$url_root   = substr($root, 0, -1);// 根路径
		$url_app    = $url_root.'/'.$app;// APP路径
		$url_usr    = $url_root.'/'.NAME_USR;// USR路径
		$url_static = $url_usr.'/'.NAME_STATIC;// 静态文件夹路径
		$url_theme  = $url_usr.'/'.NAME_THEME.'/'.$appName.'/'.config::get('THEME');// 主题静态文件夹
		$url_upload = $url_usr.'/'.NAME_UPLOAD;// 上传文件路径
		$url = array('ROOT'=>$url_root,'APP'=>$url_app,'THEME'=>$url_theme,'UPLOAD'=>$url_upload,'STATIC'=>$url_static);
		config::set('URL',$url);
	}	
	
}