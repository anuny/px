<?php
namespace usr\app\admin\controller;
use sys\px\controller;

class baseController extends Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->assign('logo','0851.tel');
	}
}