<?php
namespace sys;

// 模板引擎
class template 
{
    protected $css;
    protected $js;
    protected $comment;
    protected $comments;
    protected $html = '';
	
	$this->css = $array['css']||true;
		$this->js = $array['js']||false;
		$this->comment = $array['comment']||false;
		$this->comments = $array['comments']||true;
		$this->html = $this->minifyHTML($array['html']);
		if ($this->comment) {
			$this->html.= "\n" . $this->bottomComment($array['html'], $this->html);
		}

	/**
     * 解析模板
     */
	public static function parse($tpl,$minify=array())
	{
		
		//替换载入模板
		
		$tpl = preg_replace("/([\n\r]+)\t+/s","\\1",$tpl);
		$tpl = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}",$tpl);

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
	
		//return new minify(array('html'=>$tpl));
	}
}