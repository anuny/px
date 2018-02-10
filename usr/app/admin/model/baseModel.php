<?php
namespace usr\app\admin\model;
use sys\px\controller;

class baseModel extends Controller
{
    public function __construct()
	{
		$this->assign('logo','0851.tel');
	}
}