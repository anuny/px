<?php defined('APP') or die;
class toolController extends commonController {

	public function __construct(){
		parent::__construct();
    }
	

    public function index(){
		del_dir(DIR_DATA);
		$this->redirect('index');
    }

	

	
}