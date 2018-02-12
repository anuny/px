<?php defined('PX') or die;

// 耗时计算开始
define('STIME', microtime(true));

// 目录名称
$path_maps = array(
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
foreach($path_maps as $name => $path) define('NAME_'.$name, $path);

define('DS', DIRECTORY_SEPARATOR); // 定义目录分隔符, 兼容win与linux
define('URL_HTML_SUFFIX', '.html'); // 伪静态后缀

/**
* 模块，控制器，方法分隔符
* /应用/控制器/方法/参数
*/
define('URL_DEPR', '/'); 

/**
* 请求参数分隔符
* a-1-b-2-c-3 => ?a=1&b=2&c=3
*/
define('URL_PARAM_DEPR', '-');

/**
* 控制器后缀
* testController
*/
define('DEPR_CTRL', 'Controller');

/**
* 模型后缀
* testModel
*/
define('DEPR_MODEL', 'Model');

/**
* 公共配置文件
* /usr/config/config.inc.php
*/
define('DEPER_CONFIG', 'config.inc');

/**
* 应用配置文件
* /usr/app/应用/config/config.app.php
*/
define('DEPER_CONFIG_APP', 'config.app');

/**
* 应用函数文件
* /usr/lib/function.app.php
*/
define('DEPER_FUNCTION_APP', 'function.app'); 


define('DEFAULT_APP', 'index'); // 默认应用
define('DEFAULT_CTRL', 'index'); // 默认控制器
define('DEFAULT_ACTION', 'index'); // 默认方法

/**
* 模板文件后缀
* 为了防止直接访问模板,最好用php,并且开始判断是否有入口定义的常量 defined('PX') or die;
*/
define('TPL_SUFFIX', '.php'); 

/**
* 是否将默认应用的路由映射到根路径
* 例如把http://app.com/index/demo/test 映射成 http://app.com/demo/test 
* 前提是没有应用名与控制器名重名，优先加载应用
*/
define('DEFAULT_MAP', true); 


// 定义文件夹绝对路径
define('DIR_ROOT', dirname(dirname(__FILE__)).DS); //根目录
define('DIR_SYS', DIR_ROOT . NAME_SYS .DS); //框架目录
define('DIR_USR', DIR_ROOT . NAME_USR .DS); // 用户目录
define('DIR_APP', DIR_USR . NAME_APP .DS); // 应用目录
define('DIR_CONFIG_INC', DIR_USR . NAME_CONFIG .DS); // 公共配置目录
define('DIR_DATA', DIR_USR . NAME_DATA .DS);// 用户数据目录
define('DIR_LIB', DIR_USR . NAME_LIB .DS);// 类库目录
define('DIR_STATIC', DIR_USR . NAME_STATIC .DS);// 静态文件目录
define('DIR_THEME', DIR_USR . NAME_THEME .DS);// 主题模板目录
define('DIR_UPLOAD', DIR_USR . NAME_UPLOAD .DS);// 上传文件目录
define('DIR_CACHE', DIR_DATA . NAME_CACHE .DS);// 缓存文件目录
define('DIR_COMPILED', DIR_DATA . NAME_COMPILED .DS);// 模板编译目录
define('DIR_LOG', DIR_DATA . NAME_LOG .DS);// 日志文件目录
