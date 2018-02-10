<?php
namespace sys\px;

// 控制层
abstract class controller{
	private $data   = array();
	private $model;
	
    public function __construct() {
		$this->model = new model();
    }
    public function render($data = null){
        echo 'controller->render';
    }
	public function assign($k, $v) 
	{
        $this->data[$k] = $v;
    }
	
	public function model($name='') 
	{
        if(isset($name) && $name!=''){
			$uri = config::get('URI');
			$modelName = $name.'Model';
			$class = '\\'.NAME_USR.'\\'.NAME_APP.'\\'.$uri['app'].'\\'.NAME_MODEL.'\\'.$modelName;
			return new $class();
		}else{
			return $this->model;
		}
    }
}