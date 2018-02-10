<?php
namespace sys\px;
use sys\ext\mysql;

// 模型层
class model {
	public $db;
    public function __construct()
	{
		$this->db = 'model';
    }

}