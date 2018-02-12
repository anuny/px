<?php
namespace usr\app\index\controller;
use sys\helper AS helper;

class commentController extends baseController
{

    public function index()
	{
		$data = $this->model('comment')->get(1);
		$this->view->assign('comments',$data);
		$this->view->render('comment');
    }
	

}