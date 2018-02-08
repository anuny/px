<?php defined('APP') or die;
/* 
	公共类
*/
class IndexModel extends BaseModel{
	protected function _construct(){
		$this->config = config::get();
	}
} 