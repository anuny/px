<?php defined('APP') or die;
/* 
	公共类
*/
class indexModel extends commonModel{
	protected function _construct(){
		$this->config = config::get();
	}
} 