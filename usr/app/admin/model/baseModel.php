<?php
namespace usr\app\admin\model;
use sys\px\controller;
use sys\px\config;
class baseModel extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$url = config::get('URL');
		$nav = array(
			array('name'=>'首页','url'=>$url['APP']),
			array('name'=>'清除BOM','url'=>$url['APP'].'/index/clean_bom.html'),
			array('name'=>'清除缓存','url'=>$url['APP'].'/tool.html')
		);
		$this->data['nav'] = $nav;
		//$indexModel = $this->db->table('index');
    }

    public function getNav()
	{
		return $this->data['nav'];
	}
}
