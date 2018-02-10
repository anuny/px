<?php
namespace sys\px;

// 路由类
abstract class uri extends app{
	private static $uri;
	private static function getUrl(){
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
		self::$uri = str_replace(array('//', '../'), '/', trim($uri, '/'));
	}
		
	// 路由解析
	public static function parse(){
		$app = 'index';
		$controller = 'index';
		$action = 'index';
		
		$uri = self::$uri;

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
		config::set('URI',array(NAME_APP=>$app,NAME_CTRL=>$controller,'action'=>$action,'req'=>$_GET));
	}	
}