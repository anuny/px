<?php
namespace usr\app\admin\controller;
use sys\px\controller;
use sys\px\helper;

class baseController extends Controller
{
    public function __construct()
	{
		parent::__construct();
		$nav  = $this->model('base')->getNav();
		$this->view->assign('nav',$nav);
		$this->view->assign('title','后台管理');
		$this->view->assign('logo','PXCMS');
		$this->view->assign('runtime',helper::runtime());
	}
}