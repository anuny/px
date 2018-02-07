<?php defined('APP') or die;

class uri
{
	public static $uri = array();
	public static $url = array();
	
    public function __construct(){
		$this->setUri();
		$this->setUrl();
    }
	private function getUri()
	{
		if ( ! isset($_SERVER['REQUEST_URI']) OR ! isset($_SERVER['SCRIPT_NAME'])) {
			return '';
		}

		$uri = $_SERVER['REQUEST_URI'];
		$scr = $_SERVER['SCRIPT_NAME'];
		
		
		//去除url包含的当前文件的路径信息
		if ( $uri && @strpos($uri,$scr,0) !== false ){
			$uri = substr($uri, strlen($scr));
		} else {
			$scr = str_replace(basename($_SERVER["SCRIPT_NAME"]), '', $_SERVER["SCRIPT_NAME"]);
			if ( $uri && @strpos($uri, $scr, 0) !== false ){
				$uri = substr($uri, strlen($scr));
			}
		}
		
		// 如果 strpos($uri, $_SERVER['SCRIPT_NAME']) === 0和elseif都无法匹配的时候，
		// 返回这个url的path部分。
		$uri = parse_url($uri, PHP_URL_PATH);

		// 将路径中的 '//' 或 '../' 等进行清理
		return str_replace(array('//', '../'), '/', trim($uri, '/'));
       
    }
	


	private function setUri()
	{	
		$config =Config::get();
		$url = $this->getUri();
		
		$controller = 'index';
		$action = 'index';
		
		
		//去除问号后面的查询字符串
		if ( $url && false !== ($pos = @strrpos($url, '?')) ) {
			$url = substr($url,0,$pos);
		}
		
		//去除后缀
		if ($url&&($pos = strrpos($url,$config['URL_HTML_SUFFIX'])) > 0) {
			$url = substr($url,0,$pos);
		}
		
		$flag=0;
		//获取控制器名称
		if ( $url && ($pos = @strpos($url, $config['URL_DEPR'], 1) )>0 ) {
			$controller = substr($url,0,$pos);//控制器名
			$url = substr($url,$pos+1);//除去控制器名称，剩下的url字符串
			$flag = 1;//标志可以正常查找到控制器
		} else {	//如果找不到控制器分隔符，以当前网址为控制器名
			$controller = $url;
		}
		
		
		
		$flag2=0;//用来表示是否需要解析参数
		//获取操作方法名称
		if($url&&($pos=@strpos($url,$config['URL_DEPR'],1))>0) {
			$action = substr($url, 0, $pos);//方法名
			$url = substr($url, $pos+1);
			$flag2 = 1;//表示需要解析参数
		} else {
			//不能找不到方法，把剩下的网址当作参数处理
			if($flag){
				$action=$url;
			}
		}

		
		//解析参数
		if($flag2) {
			$param = explode($config['URL_PARAM_DEPR'], $url);
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

		$controller = $controller !='' ? $controller : 'index';
		$action = $action !='' ? $action : 'index';
		self::$uri = array('controller'=>$controller,'action'=>$action,'get'=>$_GET);	
    }
	
	private function setUrl()
	{
		$root       = str_replace(basename($_SERVER["SCRIPT_NAME"]),'',$_SERVER["SCRIPT_NAME"]);
		$app        = config::get('URL_REWRITE')?APP:APP.'.php';// APP路径
		$url_root   = substr($root, 0, -1);// 根路径
		$url_app    = $url_root.'/'.$app;// APP路径
		$url_static = $url_root.'/'.ALIAS_STATIC;// 静态文件夹路径
		$url_theme  = $url_static.'/'.ALIAS_THEME.'/'.APP.'/'.config::get('THEME');// 主题静态文件夹
		$url_upload = $url_static.'/'.ALIAS_UPLOAD;// 上传文件路径
		self::$url = array('ROOT'=>$url_root,'APP'=>$url_app,'THEME'=>$url_theme,'UPLOAD'=>$url_upload,'STATIC'=>$url_static);
	}
}
