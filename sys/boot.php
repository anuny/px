<?php defined('PX') or die;
	
// 耗时计算开始
define('STIME', microtime(true));

// 定义目录分隔符
define('DS', DIRECTORY_SEPARATOR);

// 伪静态后缀
define('URL_HTML_SUFFIX', '.html');

//模块，控制器，方法分隔符
define('URL_DEPR', '/');

//参数分隔符
define('URL_PARAM_DEPR', '-');

// 目录名称
$name_maps = array(
    'SYS' => 'sys', // 框架
	'USR' => 'usr', // 用户
	'APP' => 'app', // 应用
	'CONFIG' => 'config', // 配置
	'DATA' => 'data', // 数据
	'LIB' => 'lib',// 类库
	'STATIC' => 'static', // 静态文件
	'THEME' => 'theme', // 模板
	'UPLOAD' => 'upload', // 上传文件
	'CTRL' => 'controller', // 控制器
	'MODEL' => 'model', // 模型
	'CACHE' => 'cache', // 缓存
	'COMPILED'=> 'compiled', // 编译
	'LOG' => 'log' // 日志
);
// 定义文件夹别名
foreach($name_maps as $key => $name) define('NAME_'.$key, $name);

// 获取根目录
$dir_file = dirname(__FILE__);
$strrpos = strrpos($dir_file, DIRECTORY_SEPARATOR)+1;

// 定义文件夹绝对路径
define('DIR_ROOT', substr($dir_file, 0, $strrpos));
define('DIR_SYS', DIR_ROOT . NAME_SYS .DS);
define('DIR_USR', DIR_ROOT . NAME_USR .DS);
define('DIR_APP', DIR_USR . NAME_APP .DS);
define('DIR_CONFIG_INC', DIR_USR . NAME_CONFIG .DS);
define('DIR_DATA', DIR_USR . NAME_DATA .DS);
define('DIR_LIB', DIR_USR . NAME_LIB .DS);
define('DIR_STATIC', DIR_USR . NAME_STATIC .DS);
define('DIR_THEME', DIR_USR . NAME_THEME .DS);
define('DIR_UPLOAD', DIR_USR . NAME_UPLOAD .DS);
define('DIR_CACHE', DIR_DATA . NAME_CACHE .DS);
define('DIR_COMPILED', DIR_DATA . NAME_COMPILED .DS);
define('DIR_LOG', DIR_DATA . NAME_LOG .DS);

require_once 'px/app.php';
