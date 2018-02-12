<?php
namespace usr\app\index\model;
use sys\controller;
use sys\config;
class baseModel extends controller
{
	public function __construct()
	{
		parent::__construct();
		$nav = array(
			array('name'=>'首页','url'=>URL_APP.'/'),
			array('name'=>'清除BOM','url'=>URL_APP.'/tool/clean_bom.html'),
			array('name'=>'清除缓存','url'=>URL_APP.'/tool.html'),
			array('name'=>'评论','url'=>URL_APP.'/comment.html')
		);
		$this->data['nav'] = $nav;
    }

    public function getNav()
	{
		return $this->data['nav'];
	}
}
