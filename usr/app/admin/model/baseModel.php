<?php
namespace usr\app\admin\model;
use sys\px\controller;
use sys\px\config;
class baseModel extends controller
{
	public function __construct()
	{
		parent::__construct();
		$nav = array(
			array('name'=>'首页','url'=>URL_APP),
			array('name'=>'清除BOM','url'=>URL_APP.'/tool/clean_bom.html'),
			array('name'=>'清除缓存','url'=>URL_APP.'/tool.html')
		);
		$this->data['nav'] = $nav;
		//$indexModel = $this->db->table('index');
    }

    public function getNav()
	{
		return $this->data['nav'];
	}
}
