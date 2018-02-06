<?php defined('APP') or die;
/* 
	公共类
*/
class commonController extends Controller
{
	public function _construct()
	{
		$admin = $this->Model->table('admin')->where("uname = 'admin'")->find();
		$this->View->assign('admin', $admin);
		$url = $this->config['URL'];
		$navs = array(
			array('name'=>'首页','url'=>$url['APP']),
			array('name'=>'号码','url'=>$url['APP'].'/tel'),
			array('name'=>'问答','url'=>$url['APP'].'/qa'),
			array('name'=>'清除BOM','url'=>$url['APP'].'/index/clean_bom.html'),
			array('name'=>'清除缓存','url'=>$url['APP'].'/tool.html')
		);
		$this->View->assign('title','后台管理');
		$this->View->assign('logo','0851.tel');
		$this->View->assign('navs',$navs);
	}

	
	public function init()
	{
		echo 123;
    }
	
} 