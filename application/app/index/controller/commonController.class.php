<?php defined('APP') or die;
/* 
	公共类
*/
class commonController extends Controller{
	
    protected function __construct(){
		parent::__construct();
		$url = config::get('URL');
		$navs = array(
			array('name'=>'首页','url'=>$url['APP']),
			array('name'=>'号码','url'=>$url['APP'].'/tel'),
			array('name'=>'问答','url'=>$url['APP'].'/qa'),
			array('name'=>'清除BOM','url'=>$url['APP'].'/index/clean_bom'),
			array('name'=>'清除缓存','url'=>$url['APP'].'/tool')
		);
		$this->assign('title','后台管理');
		$this->assign('logo','0851.tel');
		$this->assign('navs',$navs);
    }
	
	public function init(){
		echo 123;
    }
	
} 