<?php defined('APP') or die;
// 控制器
class Controller 
{
    public function __construct()
	{
		$this->data   = array();
<<<<<<< .mine
		$this->config = Config::get();
		$this->cache  = new cache();
		$this->model  = new model();
        $this->view   = new view();
||||||| .r6
		$this->config = config::get();
		$this->cache  = new cache();
		$this->model  = new model();
        $this->view   = new view();
=======
		$this->config = Config::get();
		$this->Cache  = new Cache();
		$this->Model  = new Model();
        $this->View   = new View();
>>>>>>> .r8
		if(method_exists($this, '_construct')){
			 $this->_construct();
		}
    }
}
