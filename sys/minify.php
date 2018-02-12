<?php
namespace sys;

// 模板压缩类
class minify {
	protected $css;
    protected $js;
    protected $comment;
    protected $comments;
    protected $html = '';
    public function __construct($array = array()) {
        $this->css = $array['css']||true;
		$this->js = $array['js']||false;
		$this->comment = $array['comment']||false;
		$this->comments = $array['comments']||true;
		$this->html = $this->minifyHTML($array['html']);
		if ($this->comment) {
			$this->html.= "\n" . $this->bottomComment($array['html'], $this->html);
		}
    }
    public function __toString() {
        return $this->html;
    }
    protected function bottomComment($raw, $compressed) {
        $raw = strlen($raw);
        $compressed = strlen($compressed);
        $savings = ($raw - $compressed) / $raw * 100;
        $savings = round($savings, 2);
        return '<!--Bytes before:' . $raw . ', after:' . $compressed . '; saved:' . $savings . '%-->';
    }
    protected function minifyHTML($html) {
        $pattern  = '/<(?<script>script).*?<\/script\s*>|';
		$pattern .= '<(?<style>style).*?<\/style\s*>|';
		$pattern .= '<!(?<comment>--).*?-->|';
		$pattern .= '<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|';
		$pattern .= '(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
        if (preg_match_all($pattern, $html, $matches, PREG_SET_ORDER) === false) {
            return $html;
        }
        $overriding = false;
        $raw_tag = false;
        $html = '';
        foreach ($matches as $token) {
            $tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;
            $content = $token[0];
            $relate = false;
            $strip = false;
            if (is_null($tag)) {
                if (!empty($token['script'])) {
                    $strip = $this->js;
                    $relate = $this->js;
                } else if (!empty($token['style'])) {
                    $strip = $this->css;
                } else if ($content === '<!--wp-html-compression no compression-->') {
                    $overriding = !$overriding;
                    continue;
                } else if ($this->comments) {
                    if (!$overriding && $raw_tag !== 'textarea') {
                        $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
                        $relate = true;
                        $strip = true;
                    }
                }
            } else{
				if ($tag === 'pre' || $tag === 'textarea') {
                    $raw_tag = $tag;
                } else if ($tag === '/pre' || $tag === '/textarea') {
                    $raw_tag = false;
                } else if (!$raw_tag && !$overriding) {
                    if ($tag !== '') {
                        if (strpos($tag, '/') === false)  $content = preg_replace('/(\s+)(\w++(?<!action|alt|content|src)=(""|\'\'))/i', '$1', $content);
                        $content = preg_replace('/\s+(\/?\>)/', '$1', $content);
                        if ($tag !== 'link') {
                            $relate = true;
                        } else if (preg_match('/rel=(?:\'|\")\s*canonical\s*(?:\'|\")/i', $content) === 0) $relate = true;
                    } else{
                        if (strrpos($html, ' ') === strlen($html) - 1) $content = preg_replace('/^[\s\r\n]+/', '', $content);
                    }
                    $strip = true;
                }
            }
            if ($strip) $content = $this->removeWhiteSpace($content, $html);
            $html.= $content;
        }
        return $html;
    }
    protected function removeWhiteSpace($html, $full_html) {
        $html = str_replace("\t", ' ', $html);
        $html = str_replace("\r", ' ', $html);
        $html = str_replace("\n", ' ', $html);
        while (strpos($html, '  ') !== false) $html = str_replace('  ', ' ', $html);
        return $html;
    }
}