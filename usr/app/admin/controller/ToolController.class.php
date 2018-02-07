<?php defined('APP') or die;

class ToolController extends CommonController
{
    public function index()
    {
        Helper::del_dir(DIR_DATA);
        Helper::redirect('index.html');
    }
}