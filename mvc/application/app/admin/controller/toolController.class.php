<?php defined('APP') or die;

class toolController extends commonController
{
    public function index()
    {
        del_dir(DIR_DATA);
        redirect('index.html');
    }
}