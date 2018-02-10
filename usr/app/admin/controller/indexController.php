<?php
namespace usr\app\admin\controller;

class indexController extends baseController
{
    public function index()
	{
		$indexModel = $this->model('tool');
		$thisModel  = $this->model();
		$this->render('index');
    }
}