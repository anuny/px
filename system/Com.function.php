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