<?php
namespace usr\app\admin\model;

class indexModel extends baseModel
{
    public function get()
	{
		return $this->getNav();
    }
}