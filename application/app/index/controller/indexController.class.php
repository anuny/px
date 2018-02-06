<?php defined('APP') or die;
/* 
	控制器/首页
*/
class indexController extends commonController {

    public function index(){
		$this->view->assign('title','test');
		$this->view->assign('test','test');
		$this->view->render('index');
    }
	
	
}