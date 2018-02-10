<?php
namespace sys\px;

// 路由类
abstract class router extends app{
	private static $uri = array();
	
	// 路由分发
    public static function dispatch()
	{
		// 保存路由配置
		$uri = self::parseUri();
		config::set('URI',$uri);
		
		
		$url = self::parseUrl();
		config::set('URL',$url);

		// 检测模块
		$appPath = DIR_APP.$uri['app'];
		if(!file_exists($appPath)) {
			new error('Application:"'.$uri['app'].'"does not exist',500) ;
		}
		
		// 检测控制器类
		$controllerName = "$uri[controller]Controller";
		$class = '\\'.NAME_USR.'\\'.NAME_APP.'\\'.$uri['app'].'\\'.NAME_CTRL.'\\'.$controllerName;
		if(!class_exists($class)){
			new error('Controller: "'.$controllerName.'"  does not exist',500) ; 
		}
		
		// 实例化类
		$controller = new $class();
		
		// 检测方法
		$action = $uri['action'];
        if(!method_exists($controller, $action)){
			new error('Action: "'.$action.' "does not exist',500) ;
		}
		
		// 函数调用
		call_user_func(array($controller,$action));
    }
	
	private static function parseUrl(){
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
		return $url;
	}
	
	// 路由解析
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
		}
		$uri = parse_url($uri, PHP_URL_PATH);
		//去除问号后面的查询字符串
		if ( $uri && false !== ($pos = @strrpos($uri, '?')) ) $uri = substr($uri,0,$pos);
		//去除后缀
		if ($uri&&($pos = strrpos($uri,'.html')) > 0) $uri = substr($uri,0,$pos);
			
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
		$ret = array(NAME_APP=>$app,NAME_CTRL=>$controller,'action'=>$action,'req'=>$_GET);	
		self::$uri = $ret;
		return $ret;
	}
	
	
}