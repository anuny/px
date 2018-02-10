<?php
namespace usr\app\admin\controller;

class tool extends base
{
    public function index()
	{
		$this->render('index');
		print_r($this);
    }
}