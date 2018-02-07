<?php defined('APP') or die;
// 控制器
class Controller
{
    public function __construct()
	{
		
		$this->data   = array();
		$this->config = config::get();
		
		$this->Cache  = new Cache();
		
		
		
		$this->Model  = new Model();
		
		
        $this->View   = new View();
		if(method_exists($this, '_construct')){
			 $this->_construct();
		}
		
    }
}
