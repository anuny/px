<?php
namespace sys;

// 工具类
abstract class helper
{

	/**
	 * 创建多级文件
	 * @param  string   $path 要创建的路径
	 * @param  integer  $mode 权限
	 * @return boolean        状态
	 */
	public static function mk_dir($path='', $mode = 0777)
	{
		return !is_dir($path) && mkdir($path,0777,true) || @chmod($true,0777);
	}

	/**
	 * 遍历删除目录和目录下所有文件
	 * @param  string $path 要删除路径
	 * @return boolean  状态
	 */
	static function del_dir($path='')
	{
		if (!is_dir($path)){
			return false;
		}
		$handle = opendir($path);
		while (($file = readdir($handle)) !== false){
			if ($file != "." && $file != ".."){
				is_dir("$path/$file")?	self::del_dir("$path/$file"):@unlink("$path/$file");
			}
		}
		if (readdir($handle) == false){
			closedir($handle);
			@rmdir($path);
		}
	}


	/**
	 * 程序执行时间
	 * @return time
	 */
	public static function runtime()
	{
		define('ETIME', microtime(true));
		$runTime = number_format(ETIME - STIME, 4);
		return $runTime;
	}
	
	/**
	 * 程序内存消耗
	 * @return kb
	 */
	public static function memory()
	{
		$size = memory_get_usage(true);
		$unit=array('b','kb','mb','gb','tb','pb');
		return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i]; 
	}
	
	/**
	 * 程序内存消耗
	 * @return kb
	 */
	public static function serverInfo()
	{
		return  array(
            'os'=>PHP_OS,
            'software'=>$_SERVER["SERVER_SOFTWARE"],
            'name'=>$_SERVER['SERVER_NAME'],
            'prot'=>$_SERVER['SERVER_PORT'],
            'root'=>$_SERVER["DOCUMENT_ROOT"],
            'agent'=>substr($_SERVER['HTTP_USER_AGENT'], 0, 40),
            'protocol'=>$_SERVER['SERVER_PROTOCOL'],
            'filesize'=>ini_get('upload_max_filesize'),
            'time'=>ini_get('max_execution_time').'秒',
            '服务器时间'=>date("Y年n月j日 H:i:s"),
            '北京时间'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),
            '服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
            '用户的IP地址'=>$_SERVER['REMOTE_ADDR'],
            '剩余空间'=>round((disk_free_space(".")/(1024*1024)),2).'M',
        );
		
	}

		

	/**
	 * 重定向至指定url
	 * @param string $url 要跳转的url
	 * @param void
	 */
	static function redirect($url)
	{
		header("Location: $url",false, 301);
		exit;
	}


}
