<?php
namespace usr\app\admin\controller;

class indexController extends baseController
{
	public function __construct()
	{
		parent::__construct();
	}
	
    public function index()
	{
		$this->view->assign('test','test');
		$this->view->render('index');
    }
}