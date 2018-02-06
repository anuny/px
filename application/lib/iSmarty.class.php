<?php
/**
 * iSmarty ģ������ v1.1
 * @author Ե�� <yvsm@vducn.com>
 * @date 2016-07-01
 * DEMO:
  $tpl = new iSmarty();
  $tpl->assign('Name','����');

	$data['title']='����';
	$data['url']='http://127.0.0.1';
	$tpl->assign($data);
	$tpl->display('template.htm');
 */
class iSmarty{
    /*
     * ģ��Ŀ¼
     * @var string
     */
    public $templatedir = './Template';
     
    /*
     * �������Ŀ¼
     * @var string
     */
    public $compiledir = './Temp';
     
    /*
     * ģ�����
     * @var array
     */
    protected $vars = array();
     
    /*
     * ����PHP��ǩ
     * @param string $tpl (template file)
     */
    protected function parse($tpl){
        // ����ģ���ļ� //
		$tplfile = $this->templatedir .$tpl;
        $fp   = @fopen($tplfile, 'r');
        $text = fread($fp, filesize($tplfile));
        fclose($fp);
        // �滻ģ���ǩ��PHP��ǩ //
        $text        = str_replace('{/if}', '<?php } ?>', $text);
        $text        = str_replace('{/loop}', '<?php } ?>', $text);
        $text        = str_replace('{foreachelse}', '<?php } else {?>', $text);
        $text        = str_replace('{/foreach}', '<?php } ?>', $text);
        $text        = str_replace('{else}', '<?php } else {?>', $text);
        $text        = str_replace('{loopelse}', '<?php } else {?>', $text);
        // ģ���ǩ //
        $pattern     = array(
            '/\$(\w*[a-zA-Z0-9_])/',
            '/\$this\-\>vars\[\'(\w*[a-zA-Z0-9_])\'\]+\.(\w*[a-zA-Z0-9])/',
            '/\{include file=(\"|\'|)(\w*[a-zA-Z0-9_\.][a-zA-Z]\w*)(\"|\'|)\}/',
            '/\{\$this\-\>vars(\[\'(\w*[a-zA-Z0-9_])\'\])(\[\'(\w*[a-zA-Z0-9_])\'\])?\}/',
            '/\{if (.*?)\}/',
            '/\{elseif (.*?)\}/',
            '/\{loop \$(.*) as (\w*[a-zA-Z0-9_])\}/',
            '/\{foreach \$(.*) (\w*[a-zA-Z0-9_])\=\>(\w*[a-zA-Z0-9_])\}/'
        );
        // �滻PHP��ǩ //
        $replacement = array(
            '$this->vars[\'\1\']',
            '$this->vars[\'\1\'][\'\2\']',
            '<?php $this->display(\'\2\')?>',
            '<?php echo \$this->vars\1\3?>',
            '<?php if(\1) {?>',
            '<?php } elseif(\1) {?>',
            '<?php if (count((array)\$\1)) foreach((array)\$\1 as \$this->vars[\'\2\']) {?>',
            '<?php if (count((array)\$\1)) foreach((array)\$\1 as \$this->vars[\'\2\']=>$this->vars[\'\3\']) {?>'
        );
        // �滻ģ���ǩ��PHP��ǩ //
        $text = preg_replace($pattern, $replacement, $text);
         
        // ���������ļ� //
        $compliefile = $this->compiledir . md5($tpl) . '.php';
        if ($fp = @fopen($compliefile, 'w')) {
            fputs($fp, $text);
            fclose($fp);
        }
    }
     
    /*
     * ģ�����ֵ��
     * @param array|string $k the template variable name(s)
     * @param mixed $v the value to assign
     */
    public function assign($k, $v = null)
    {
		if(is_array($k) && $v==null){
			foreach($k as $key=>$value){
				$this->vars[$key] = $value;
			}
		}else{
			$this->vars[$k] = $v;
		}
    }
     
    /*
     * ����ģ��Ŀ¼
     * @param string $str (path)
     */
    protected function templateDir($path)
    {
        $this->templatedir = $this->pathCheck($path);
    }
     
    /*
     * ����ģ�����·��
     * @param string $str (path)
     */
    protected function compileDir($path){
        $this->compiledir = $this->pathCheck($path);
    }
     
    /*
     * ���·�������һ���ַ�
     * @param string $str (path)
     * @return string
     */
    protected function pathCheck($str){
        return (preg_match('/\/$/', $str)) ? $str : $str . '/';
    }
     
    /*
     * ִ������ʾģ��Ľ��
     * @param string $tpl (template file)
     */
    public function display($tpl){
        $tplfile = $this->templatedir . $tpl;
        if (!file_exists($tplfile)) {
            exit('can not load template file : ' . $tplfile);
        }
        $compliefile = $this->compiledir . md5($tpl) . '.php';

        if (!file_exists($compliefile) || filemtime($tplfile) > filemtime($compliefile)) {
            $this->parse($tpl);
        }
        include_once($compliefile);
    }
}
 
?>