<?php defined('APP') or die;

//是否开启调试模式
$config['DEBUG']= 1;	

//是否开启重写,需 PATH_INFO 支持
$config['URL_REWRITE']=0;

// 会话
$config['USE_SESSION']=1;

//设置网址域名	
$config['APP_DOMAIN']='pxcms.com';

//是否开启模板缓存	
$config['CACHE_TPL']= true;

//是否开启数据缓存	
$config['CACHE_DATA']=true;


//伪静态后缀设置，例如 .html ，一般不需要修改
$config['URL_HTML_SUFFIX']='.html';

//模块，控制器，方法分隔符
$config['URL_DEPR']='/';

//参数分隔符
$config['URL_PARAM_DEPR']='-';
				

$config['THEME']='default';

//设置密匙
$config['SECRETKEY']='GHJsdfhj447fsfFG45fdggs';

//默认头像
$config['NOAVATAR']='no-avatar.jpg';

//设置时区
$config['CHARSET']='UTF-8';

//设置时区
$config['TIMEZONE']='PRC';

//数据库主机，一般不需要修改
$config['DB_HOST']='db'; 

//数据库用户名
$config['DB_USER']=''; 

//数据库密码
$config['DB_PWD']=''; 

//数据库端口，mysql默认是3306，一般不需要修改
$config['DB_PORT']=3306; 

//数据库名
$config['DB_NAME']='data'; 

//数据库前缀
$config['DB_PREFIX']=''; 

//数据库编码，一般不需要修改
$config['DB_CHARSET']='utf8'; 








