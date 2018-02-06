<?php defined('APP') or die;

// 视图
class View
{
	private $config = array();
    private $data   = array();
    private $tplDir = '';
	private $compiledDir = '';
	private $cacheDir = '';
	

    public function __construct()
	{
		$this->cache  = new cache(); 
		$this->config = config::get();
        $this->tplDir = DIR_THEME . $this->config['THEME'] . DS;
		$this->compiledDir = DIR_COMPILED;
		$this->cacheDir = DIR_CACHE_TPL;
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
		if(!$tplName){
			$tplName = $this->config['controller']. '.' . $this->config['action'];
		};
		
		// 模板文件名
		$tplFile  = $this->tplDir . $tplName. '.html';
		
		// 判断模板文件是否存在
		if(!file_exists($tplFile)){
			new Error('Error: 模板 '.$cacheFile.' 不存在！', 500) ;	
		}
		
		// 编译文件名
		$compileFile  = $this->compiledDir . $tplName. '.php';
		
		// 缓存文件名
		$cacheFile  = $this->cacheDir . $tplName. '.html';

		// 读取缓存
		$isCache = $this->config['CACHE_TPL'];
		if($isCache){
			if(file_exists($cacheFile) && file_exists($compileFile)){
                //是否修改过编译文件或者模板文件
                if(filemtime($cacheFile)>=filemtime($compileFile) && filemtime($compileFile)>filemtime($tplFile)){
                    include $cacheFile;
                    return;
                }
            }
		}
		

		// 编译模板
        if(!file_exists($compileFile) || (filemtime($compileFile) < filemtime($tplFile))){
            $this->compile($tplName);
        }
		
		// 导入数据
		extract($this->data);

		
		//生成缓存文件
		if($isCache){
			
			if(!is_dir($this->cacheDir) && !mk_dir($this->cacheDir)){
				new Error('ERROR:"' . $tplName . '"缓存目录创建失败！', 500) ;
			}
			
			ob_start();
			include $compileFile;
			// 获取缓冲区内容
			$content = ob_get_contents();
			
			// 清除缓冲区
			ob_end_clean();

            file_put_contents($cacheFile,$content);
			
			//载入缓存文件
            include $cacheFile;
		}else{
			//载入编译文件
			include $compileFile;
		}
		unset($tplName);
	}
	
	protected function compile($tplName)
	{
		if(!is_dir($this->compiledDir) && !mk_dir($this->compiledDir)){
			new Error('ERROR:"' . $tplName . '"编译目录创建失败！', 500) ;
		}
		$tplFile  = $this->tplDir . $tplName. '.html';
		$compileFile  = $this->compiledDir . $tplName. '.php';
		$content = file_get_contents($tplFile);
		$content = $this->parse($content);
		if(!file_put_contents($compileFile, $content)){
			new Error('ERROR:"' . $tplName . '"编译失败！', 500) ;
		}
	}
	
	/**
     * 解析模板
     */
	protected function parse($tpl)
	{
		$tpl = preg_replace("/([\n\r]+)\t+/s","\\1",$tpl);
		$tpl = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}",$tpl);
		$tpl = preg_replace("/\{url\s+(\S+)\}/","<?php echo getUrl('\\1');?>",$tpl);
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
		$tpl = preg_replace("/\{\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\(([^{}]*)\))\}/","<?php echo \\1;?>",$tpl);
		$tpl = preg_replace("/\{(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/","<?php echo \\1;?>",$tpl);
		$tpl = preg_replace("/\{(\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\}/es", "addquote('<?php echo \\1;?>')",$tpl);
		$tpl = preg_replace("/\{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)\}/s", "<?php echo \\1;?>",$tpl);
		return $tpl;
	}
}
