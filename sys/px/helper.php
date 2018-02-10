<?php
namespace sys\px;

// 配置类
abstract class helper 
{

	// 创建多级文件
	public static function mk_dir($path, $mode = 0777)
	{
		return !is_dir($path) && mkdir($path,0777,true) || @chmod($true,0777);
	}
	
	//遍历删除目录和目录下所有文件
	static function del_dir($dir)
	{
		if (!is_dir($dir)){
			return false;
		}
		$handle = opendir($dir);
		while (($file = readdir($handle)) !== false){
			if ($file != "." && $file != ".."){
				is_dir("$dir/$file")?	self::del_dir("$dir/$file"):@unlink("$dir/$file");
			}
		}
		if (readdir($handle) == false){
			closedir($handle);
			@rmdir($dir);
		}
	}

	
	// 获取网址
	public static function getUrl($name)
	{
		$name = strtoupper($name);
		$url = config::get('URL');
		return $url[$name];
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