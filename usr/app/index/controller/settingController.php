<?php
namespace usr\app\index\controller;
use sys\config;

class settingController extends baseController
{
    public function index()
	{
		$config = config::get();
		$this->view->assign('config',$config);
		$this->view->render('setting');
    }
	public function save()
	{
		
		print_r(1);
	
    }
}