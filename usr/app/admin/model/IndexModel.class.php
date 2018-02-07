<?php defined('APP') or die;
/* 
	公共类
*/
class IndexModel extends CommonModel{
	protected function _construct(){
		$this->config = config::get();
	}
} 