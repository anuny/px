<?php
namespace usr\app\index\controller;
use sys\controller;
use sys\helper;

class baseController extends Controller
{
    public function __construct()
	{
		parent::__construct();
		$nav  = $this->model('base')->getNav();
		$this->view->assign('nav',$nav);
		$this->view->assign('title','后台管理');
		$this->view->assign('logo','PXCMS');
		$this->view->assign('serverInfo',helper::serverInfo());
		$this->view->assign('runtime',helper::runtime());
		$this->view->assign('memory',helper::memory());
	}
}