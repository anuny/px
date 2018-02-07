<?php defined('APP') or die;
/* 
	公共类
*/
class commonModel extends Controller{
	protected function _construct(){
		$this->config = config::get();
	}
} 