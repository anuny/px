<?php
namespace usr\app\index\model;

class indexModel extends baseModel
{
    public function get()
	{
		return $this->getNav();
    }
}