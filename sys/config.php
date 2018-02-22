<?php
namespace sys;

// 配置类
class config 
{
    static public $config=array(	
		'DEBUG' => true,// 开启调试模式
		'URL_REWRITE' => true,// 开启伪静态重写
		'USE_SESSION' => true,// 开启SESSION会话
		'DOMAIN' => '',// 设置域名	
		'THEME' => 'default',// 默认主题
		'CACHE_TPL' => true,//是否开启模板缓存	
		'MINIFY' => true,//是否开启页面压缩
		'CACHE_DATA'=>true,//是否开启数据缓存	
		'CHARSET' => 'UTF-8',// 设置编码
		'TIMEZONE' => 'PRC',// 设置时区
		'DB_HOST' => '127.0.0.1', // 数据库主机
		'DB_USER' => 'root', // 数据库用户名
		'DB_PWD' => 'root', // 数据库密码
		'DB_PORT' => 3306, // 数据库端口
		'DB_NAME' => '', // 数据库名
		'DB_PREFIX' => '', // 数据库前缀
		'DB_CHARSET' => 'utf8' // 数据库编码
	);
		
	//获取
	static public function get( $key = '' ) 
	{
		return (isset($key) && $key !='') ? self::$config[$key] : self::$config;
	}
	
	//设置
	static public function set( $key , $value='') 
	{
		return (isset($value) && !empty($value)) ? self::$config[$key] = $value : self::$config = $key;
	}
	
	//保存
	static public function save( $path , $array=array()) 
	{
		if (empty($array) || !is_array($array)) {
            return false;
        }
		$config = @file_get_contents($path); //读取配置
		foreach ($array as $name => $value) {
            $name = str_replace(array("'", '"', '[','*'), array("\\'", '\"', '\[','\*'), $name); //转义特殊字符，再传给正则替换
            if (is_string($value) && !in_array($value, array('true', 'false', '3306'))) {
                if(!is_numeric($value)){
                    $value = "'" . $value . "'"; //如果是字符串，加上单引号
                }
            }
            $config = preg_replace("/(\\$" . $name . ")\s*=\s*(.*?);/i", "$1={$value};", $config); //查找替换
        }
		// 写入配置
        if (@file_put_contents($path, $config)){
			return true;
        }else{
			return false;
        }
	}
}
