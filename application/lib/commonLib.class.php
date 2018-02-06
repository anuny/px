<?php defined('APP') or die;
class commonLib extends commonController {
	public $data;
	
    public function __construct($config=array()){
		parent::__construct();  //调用父类构造方法  
		$this->data = 'testLib -> test';
    }
	
	public function init(){
		parent::init();
		return $this->data;
    }
	
}