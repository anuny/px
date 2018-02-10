<?php defined('PX') or die;

//是否开启调试模式
$config['DEBUG']= true;	

//是否开启重写,需 PATH_INFO 支持
$config['URL_REWRITE']= true;

// 会话
$config['USE_SESSION']=true;

//是否开启模板缓存	
$config['CACHE_TPL']= true;

//是否开启数据缓存	
$config['CACHE_DATA']=true;
			
			
$config['THEME']='bootstrap';

//设置网址域名	
$config['DOMAIN']='3.pxcms.com';