<?php defined('APP') or die;

// 定义目录分隔符
define('DS', DIRECTORY_SEPARATOR);

// 耗时计算开始
define('STIME', microtime(true));
// 目录名称
$alia_maps = array(
	'ROOT'       => 'root', // 根目录
	'SYS'        => 'system', // 框架目录
	'USR'        => 'application', // 用户目录
	'APP'        => 'app', // 应用目录
	'CONTROLLER' => 'controller', // 控制器目录
	'MODEL'      => 'model', // 模型目录
	'CONFIG'     => 'config', // 配置目录
	'WWWROOT'    => 'wwwroot', // 公共配置目录
	'STATIC'     => 'static', // 静态文件目录
	'THEME'      => 'theme', // 模板目录
	'DATA'       => 'data', // 数据目录
	'COMPILED'   => 'compiled', // 编译目录
	'CACHE_DATA' => 'cache_data', // 数据缓存目录
	'CACHE_TPL'  => 'cache_tpl', // 模板缓存目录
	'CACHE_SQL'  => 'cache_sql', // SQL缓存目录
	'LIB'        => 'lib'// 类库目录
);
// 定义文件夹别名
foreach($alia_maps as $key => $value) define('ALIAS_'.$key, $value);

// 获取根目录
$dir_file = dirname(__FILE__);
$strrpos = strrpos($dir_file, DIRECTORY_SEPARATOR)+1;

// 定义文件夹绝对路径
define('DIR_ROOT',       substr($dir_file, 0, $strrpos));
define('DIR_SYS',        DIR_ROOT.ALIAS_SYS.DS);
define('DIR_USR',        DIR_ROOT.ALIAS_USR.DS);

define('DIR_CONFIG_INC', DIR_USR.ALIAS_CONFIG.DS);
define('DIR_THEME',      DIR_USR.ALIAS_THEME.DS.APP.DS);
define('DIR_LIB',        DIR_USR.ALIAS_LIB.DS);

define('DIR_DATA',       DIR_USR.ALIAS_DATA.DS);
define('DIR_COMPILED',   DIR_DATA.ALIAS_COMPILED.DS);

define('DIR_WWWROOT',    DIR_ROOT.ALIAS_WWWROOT.DS);
define('DIR_STATIC',     DIR_WWWROOT.ALIAS_STATIC.DS);


