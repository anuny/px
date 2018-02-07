<?php defined('APP') or die;

class Url
{
	public static function get()
	{	
		$config = Config::get();
		$appName = $config['URI']['app'];
		$root = str_replace(basename($_SERVER["SCRIPT_NAME"]),'',$_SERVER["SCRIPT_NAME"]);
		$appPath = $config['URL_REWRITE']? $appName : 'index.php/'.$appName;// APP路径
		$url_root   = substr($root, 0, -1);// 根路径
		$url_app    = $url_root.'/'.$appPath;// APP路径
		$url_usr = $url_root.'/'.NAME_USR;// 静态文件夹路径
		$url_static = $url_usr.'/'.NAME_STATIC;// 静态文件夹路径
		$url_theme  = $url_usr.'/'.NAME_THEME.'/'.$appName.'/'.$config['THEME'];// 主题静态文件夹
		$url_upload = $url_usr.'/'.NAME_UPLOAD;// 上传文件路径
		return array('ROOT'=>$url_root,'APP'=>$url_app,'USR'=>$url_usr,'THEME'=>$url_theme,'UPLOAD'=>$url_upload,'STATIC'=>$url_static);
    }
}
