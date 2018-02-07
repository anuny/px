<?php defined('APP') or die;
// 控制器
class Controller extends app
{
    public function __construct()
	{
		$this->data   = array();
		$this->config = config::get();
		$this->cache  = new cache();
		$this->model  = new model();
        $this->view   = new view();
		if(method_exists($this, '_construct')){
			 $this->_construct();
		}
		print_r(parent::$version);
    }
}
