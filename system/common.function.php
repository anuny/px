<?php defined('APP') or die;

/**
 *
 * 打印变量
 * 
 */
function dump($val){
	echo "<pre>";
	if(is_string($val) || is_numeric($val))
		echo $val;
	elseif(is_array($val)) {
		print_r($val);
	}elseif(is_object($val)){
		print_r($val);
	}
	echo "</pre>";
}

// 首字母大写
function ufirst($str){
	return ucfirst(strtolower($str));
}


// 创建多级文件
function mk_dir($path, $mode = 0777){
	return !is_dir($path) && mkdir($path,0777,true) || @chmod($true,0777);
}


	

//遍历删除目录和目录下所有文件
function del_dir($dir){
	if (!is_dir($dir)){
		return false;
	}
	$handle = opendir($dir);
	while (($file = readdir($handle)) !== false){
		if ($file != "." && $file != ".."){
			is_dir("$dir/$file")?	del_dir("$dir/$file"):@unlink("$dir/$file");
		}
	}
	if (readdir($handle) == false){
		closedir($handle);
		@rmdir($dir);
	}
}

//复制目录
function copy_dir($sourceDir,$aimDir){
	$succeed = true;
	if(!file_exists($aimDir)){
		if(!mkdir($aimDir,0777)){
			return false;
		}
	}
	$objDir = opendir($sourceDir);
	while(false !== ($fileName = readdir($objDir))){
		if(($fileName != ".") && ($fileName != "..")){
			if(!is_dir("$sourceDir/$fileName")){
				if(!copy("$sourceDir/$fileName","$aimDir/$fileName")){
					$succeed = false;
					break;
				}
			}else{
				copy_dir("$sourceDir/$fileName","$aimDir/$fileName");
			}
		}
	}
	closedir($objDir);
	return $succeed;
}


function addquote($var){
	return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
}
	
/**
 *
 * 写文件
 *
 */
function writeFile($file,$data,$mode = "wb") {
	if(!is_writable($file)) {
		$ch  = @chmod($file,"0755");
	}
	if(@$fp = fopen($file,$mode)) {
		flock($fp,LOCK_EX);
		fwrite($fp,$data);
		fclose($fp);
		return true;
	} else {
		return false;
	}
}


// 控制器之间相互调用
function  controller($controller){
	static $module_obj=array();
	if(isset($module_obj[$controller])){
		return $module_obj[$controller];
	}	
	$modulePath = DIR_APP . $controller .'Controller.class.php';
	if(file_exists($modulePath)){
			require_once($modulePath);
			$classname=$controller.'Controller';
			if(class_exists($classname)){
				return  $module_obj[$controller]=new $classname();
			}
	}else{
		return false;
	}
}


//模型调用函数
if(!function_exists('model')){
	function  model($model){
		static $model_obj=array();
		if(isset($model_obj[$model])){
			return $model_obj[$model];
		}
		$modelPath = DIR_MODEL . $model . 'Model.class.php';
		if(file_exists($modelPath)){
				require_once($modelPath);
				$classname=$model.'Model';
				if(class_exists($classname)){
					return  $model_obj[$model]=new $classname();
				}
		}
		return false;
	}
}


// 获取查询
function getURI() {
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


//网址解析
function parseUrl(){
	$config =config::get();
	$url = getURI();
	
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
	return array('controller'=>$controller,'action'=>$action,'get'=>$_GET);	
}

function getURLS(){	
	$root        = str_replace(basename($_SERVER["SCRIPT_NAME"]),'',$_SERVER["SCRIPT_NAME"]);
	$app        = config::get('URL_REWRITE')?APP:APP.'.php';// APP路径
	$url_root   = substr($root, 0, -1);// 根路径
	$url_app    = $url_root.'/'.$app;// APP路径
	$url_static = $url_root.'/'.ALIAS_STATIC;// 静态文件夹路径
	$url_theme  = $url_static.'/'.ALIAS_THEME.'/'.APP.'/'.config::get('THEME');// 主题静态文件夹
	$url_upload = $url_static.'/'.ALIAS_UPLOAD;// 上传文件路径
	$url = array('ROOT'=>$url_root,'APP'=>$url_app,'THEME'=>$url_theme,'UPLOAD'=>$url_upload,'STATIC'=>$url_static);
	return $url;
}

function getUrl($name){
	$name = strtoupper($name);
	$url = config::get('URL');
	return $url[$name];
}

/**
 * 是否是AJAx提交的
 * @return bool
 */
function isAjax(){
    if(isset($_SERVER['HTTP_REQUEST_TYPE']) && strtolower($_SERVER['HTTP_REQUEST_TYPE']) == 'xmlhttprequest'){
        return true;
    }else{
        return false;
    }
}

/**
 * 是否是GET提交的
 */
function isGet(){
    return $_SERVER['REQUEST_METHOD'] == 'GET' ? true : false;
}


/**
 * 是否是POST提交
 * @return int
 */
function isPost() {
    return $_SERVER['REQUEST_METHOD'] == 'POST'  ? 1 : 0;
}

/**
 * 程序执行时间
 * @return time
 */
function runtime(){
	define('ETIME', microtime(true));
	$runTime = number_format(ETIME - STIME, 4);
	return $runTime;
}


function getClient($name){
	if ('ip' == $name) {
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")){
			$ip = getenv("HTTP_CLIENT_IP");
		}else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")){
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		}else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")){
			$ip = getenv("REMOTE_ADDR");
		}else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")){
			$ip = $_SERVER['REMOTE_ADDR'];
		}else{
			$ip = "unknown";
		}
		if (preg_match('#^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$#', $ip)) {
			$ip_array = explode('.', $ip);	
			if($ip_array[0]<=255 && $ip_array[1]<=255 && $ip_array[2]<=255 && $ip_array[3]<=255){
				return $ip;
			}			
		}
		return "unknown"; 
		break;
	} else if ('agent' == $name && !empty($_SERVER['HTTP_USER_AGENT'])) {
		return $_SERVER['HTTP_USER_AGENT'];
	} else if ('method' == $name && !empty($_SERVER['REQUEST_METHOD'])) {
		return strtoupper($_SERVER['REQUEST_METHOD']);
	} else if ('referer' == $name && !empty($_SERVER['HTTP_REFERER'])) {
		return $_SERVER['HTTP_REFERER'];
	} else if ('langs' == $name && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
		if (preg_match_all("/[a-z-]+/i", $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches)) {
			return array_map('strtolower', $matches[0]);
		} else {
			return array('en');
		}
	}
	return false;
}

/**
 * 将数据用json格式输出至浏览器，并停止执行代码
 * @param array $data 要输出的数据
 */
function ajaxReturn($data){
	return json_encode($data);
}

/**
 * 重定向至指定url
 * @param string $url 要跳转的url
 * @param void
 */
function redirect($url){
	header("Location: $url",false, 301);
	exit;
}