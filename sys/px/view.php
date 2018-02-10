<?php
namespace sys\px;

// 视图层
class view {
	private $config = array();
    private $data   = array();
    private $tplDir = '';
	private $compiledDir = '';
	

    public function __construct()
	{
		$app = config::get('URI')['app'];
		$theme = config::get('THEME');
        $this->tplDir = DIR_THEME.$app.DS.$theme.DS;
		$this->compiledDir = DIR_COMPILED.$app.DS;
    }
	
    /**
     * 设置模板变量
     */
    public function assign($k, $v) 
	{
        $this->data[$k] = $v;
    }
	
    /**
     * 渲染模板并输出
     */
    public function render($tplName) 
	{
		$tplName = trim($tplName);
		

		// 没有模板名
		if(!isset($tplName) || $tplName==''){
			$tplName = config::get('URI')['controller']. '.' . config::get('URI')['action'];
		};
	
		// 模板文件名
		$tplFile  = $this->tplDir . $tplName. '.php';
	
		// 判断模板文件是否存在
		if(!file_exists($tplFile)){
			new error('Error: 模板 '.$tplFile.' 不存在！', 500) ;	
		}
		
		// 编译文件名
		$compileFile  = $this->compiledDir . $tplName. '.php';
		
		// 编译模板
        if(!file_exists($compileFile) || (filemtime($compileFile) < filemtime($tplFile))){
			$path = dirname($compileFile);
			if(!is_dir($path) && !Helper::mk_dir($path)){
				new Error('ERROR:"' . $tplName . '"编译目录创建失败！', 500) ;
			}
		
			$content = file_get_contents($tplFile);
			$content = $this->parse($content);
            if(!file_put_contents($compileFile, $content)){
				new error('ERROR:"' . $tplName . '"编译失败！', 500) ;
			}
        }
		
		// 导入数据
		extract($this->data);

		//载入编译文件
		include $compileFile;
		
		unset($tplName);
	}
	
	protected function getUrl($name){
		$url = config::get('URL');
		$name = strtoupper($name);
		return $url[$name];
	}


	/**
     * 解析模板
     */
	protected function parse($tpl)
	{
		
		//替换载入模板
		
		$tpl = preg_replace("/([\n\r]+)\t+/s","\\1",$tpl);
		$tpl = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}",$tpl);
		
		$tpl = preg_replace("/\{url\s+(\S+)\}/", "<?php echo \$this->getUrl('$1'); ?>",$tpl);
		
		
		
		
		
		$tpl = preg_replace('/<!--#include\s*file=[\"|\'](.*)[\"|\']-->/iU',"<?php \$this->render('$1'); ?>", $tpl);
		$tpl = preg_replace("/\{include\s+(.+)\}/","<?php \$this->render('\\1'); ?>",$tpl);		
		
		
		$tpl = preg_replace("/\{php\s+(.+)\}/","\n<?php \\1?>",$tpl);
		$tpl = preg_replace("/\{if\s+(.+?)\}/","<?php if(\\1) { ?>",$tpl);
		$tpl = preg_replace("/\{else\}/","<?php } else { ?>",$tpl);
		$tpl = preg_replace("/\{elseif\s+(.+?)\}/","<?php } elseif (\\1) { ?>",$tpl);
		$tpl = preg_replace("/\{\/if\}/","<?php } ?>",$tpl);
		$tpl = preg_replace("/\{foreach\s+(\S+)\s+(\S+)\}/","<?php if(is_array(\\1)) foreach(\\1 AS \\2){?>",$tpl);
		$tpl = preg_replace("/\{foreach\s+(\S+)\s+(\S+)\s+(\S+)\}/","<?php if(is_array(\\1)) foreach(\\1 AS \\2 => \\3){?>",$tpl);
		$tpl = preg_replace("/\{\/foreach\}/","<?php }?>",$tpl);
		$tpl = preg_replace("/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\(([^{}]*)\))\}/","<?php echo \\1;?>",$tpl);
		$tpl = preg_replace("/\{\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\(([^{}]*)\))\}/","<?php echo \\1;?>",$tpl); // 函数
		$tpl = preg_replace("/\{(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/","<?php echo \\1;?>",$tpl); // 变量
		
	    $tpl = preg_replace("/\{(\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\}/", "<?php echo \\1;?>",$tpl);// {$array['a']}
		
		$tpl = preg_replace("/\{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)\}/s", "<?php echo \\1;?>",$tpl); // 数组对象
		
		$tpl = preg_replace ( "/\{(\\$[a-z0-9_]+)\.([a-z0-9_]+)\}/i", "<?php echo $1['$2']; ?>", $tpl);
		$tpl = preg_replace ( "/\{(\\$[a-z0-9_]+)\.([a-z0-9_]+)\.([a-z0-9_]+)\}/i", "<?php echo $1[\'$2\'][\'$3\']; ?>", $tpl);
		
		$tpl = preg_replace ( "/\{(\\$[a-z0-9_]+)\::([a-z0-9_]+)\}/i", "<?php $1::$2; ?>", $tpl);
		$tpl = preg_replace ( "/\{(\\$[a-z0-9_]+)\::([a-z0-9_]+)\.([a-z0-9_]+)\}/i", "<?php echo $1::\'$2\'][\'$3\']; ?>", $tpl);
		$tpl = '<?php use sys\px\helper;?>'.$tpl;
	
		return $tpl;
	}
	
	
}