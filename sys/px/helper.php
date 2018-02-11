<?php
namespace sys\px;

// 配置类
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
