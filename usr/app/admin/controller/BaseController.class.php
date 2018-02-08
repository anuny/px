<?php defined('APP') or die;
/* 
	公共类
*/
class BaseController extends Controller
{
	public function _construct()
	{
		
		//$admin = $this->Model->table('user')->where("User = 'root'")->find();
		
		
		//$this->View->assign('admin', $admin);
		
		$url = $this->config['URL'];
		$navs = array(
			array('name'=>'首页','url'=>$url['APP'].'.html'),
			array('name'=>'清除BOM','url'=>$url['APP'].'/index/clean_bom.html'),
			array('name'=>'清除缓存','url'=>$url['APP'].'/tool.html')
		);
		
		$this->assign('test',$this->test());
		//$this->assign('runtime',Helper::runtime());
		$this->assign('runtime',123);
		$this->assign('title','后台管理');
		$this->assign('logo','PXCMS');
		$this->assign('navs',$navs);
	}
	
	public function test()
	{
		$start=array('快乐的','冷静的','醉熏的','潇洒的','糊涂的','积极的','冷酷的','深情的','粗暴的','温柔的');
		$end=array('毛豆','花生','学姐','店员','电源','饼干','小鸭子','小海豚','小熊猫','小懒猪');
		$s=rand(0,9);
		$e=rand(0,9);
		return $start[$s].$end[$e];
	}
} 