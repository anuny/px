<?php defined('APP') or die;
/* 
	控制器/首页
*/
class testController {

    public function __construct(){
		$this->data = 123;
    }
	
	public function run(){
		echo $this->data;
    }
	
	
}