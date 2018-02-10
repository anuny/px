<?php
namespace usr\app\admin\controller;
use sys\px\helper AS helper;

class toolController extends baseController
{

    public function index()
	{
		helper::del_dir(DIR_DATA);
        helper::redirect('index.html');
    }
	
	public function clean_bom()
	{
		$root = str_replace("\\",'/',substr(DIR_ROOT, 0, strrpos(DIR_ROOT, DIRECTORY_SEPARATOR)));
        $ret = clean_bom($root);
		$this->view->assign('info',$ret);
		$this->view->render('clean-bom');
    }
}