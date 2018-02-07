<?php defined('APP') or die;

//错误类
class Error extends Exception 
{
    private $errorMessage = '';
    private $errorFile = '';
    private $errorLine = 0;
    private $errorCode = '';
 	private $trace = '';
	
	
    public function __construct($errorMessage, $errorCode = 0, $url='') {
		parent::__construct($errorMessage, $errorCode);
        $this->errorMessage = $errorMessage;
		$this->errorCode = $errorCode == 0?$this->getCode() : $errorCode;
        $this->errorFile = $this->getFile();
        $this->errorLine = $this->getLine();
 	    $this->trace = $this->trace();
        $this->showError($url);
    }
	
	//获取trace信息
	protected function trace() {
        $trace = $this->getTrace();
        $traceInfo='';
        foreach($trace as $t) {
            $traceInfo .= $t['file'] . ' (' . $t['line'] . ') ';
            $traceInfo .= $t['class'] . $t['type'] . $t['function'] . '(';
            $traceInfo .= ")<br />\r\n";
        }
		return $traceInfo ;
    }

	//输出错误信息
     protected function showError($url){
		 
		$view = new View();
		 
		//错误页面重定向
		if($error_url != ''){
			exit('<script language="javascript">if(self!=top){parent.location.href="'.$error_url.'";} else {window.location.href="'.$error_url.'";}</script>');
		}
		@header("HTTP/1.1 $this->errorCode Not Found");

		$errorTpl = DIR_THEME_APP.Config::get('THEME').DS.'error.php';
		
		if (file_exists($errorTpl)) {
			$view->assign('error',array('code' => $this->errorCode,'message' => $this->errorMessage,'trace' => $this->trace));
			$view->render('error');
		} else {
			$info = $this->errorCode && DEBUG ? '<p>'.$this->errorMessage.'</p><p style="font-size:12px;">'.$this->trace.'</p> ' : '<p> 很抱歉，您访问的页面不存在或已删除。 </p> ';
			echo '
			<!doctype html>
			<html>
			<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
			<title>Error</title>
			</head>
			<body>
			<h1>'.$this->errorCode.' Error</h1>';
			echo $info;
			echo '<p><a href="javascript:history.back()" >返回</a></p> 
			</body>
			</html>';
		}
		exit;	
    }
}