<?php defined('APP') or die;
/* 
	控制器/首页
*/
class indexController extends commonController {

    public function index(){
		$this->View->assign('test','test');
		$this->View->render('index');
    }
	
	
}