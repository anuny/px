<?php defined('APP') or die;

class ToolController extends BaseController
{
    public function index()
    {
        $this->del_dir(DIR_DATA);
        $this->redirect('index.html');
    }
	
	//遍历删除目录和目录下所有文件
	public function del_dir($dir){
		if (!is_dir($dir)){
			return false;
		}
		$handle = opendir($dir);
		while (($file = readdir($handle)) !== false){
			if ($file != "." && $file != ".."){
				is_dir("$dir/$file")?	self::del_dir("$dir/$file"):@unlink("$dir/$file");
			}
		}
		if (readdir($handle) == false){
			closedir($handle);
			@rmdir($dir);
		}
	}
}