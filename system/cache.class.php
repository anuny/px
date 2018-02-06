<?php defined('APP') or die;

class Cache {
	
	public function __construct( ) {
		$this->uri = config::get('URI');
    }
	
	// 读取缓存
	public function readData($filename){
		$result = array();
		if (!empty($result[$filename])) {
			return $result[$filename];
		}
		$filepath = DIR_CACHE_DATA . $this->uri['module']. DS . $filename . '.php';
		
		if (file_exists($filepath)) {
			include_once($filepath);
			$result[$filename] = $data;
			return $result[$filename];
		} else {
			return false;
		}
	}
	
	// 写入缓存
	public function write($type,$filePath, $filename, $value){
		
		if(!is_dir($filePath) && !mk_dir($filePath)){
			new Error($filePath."创建失败", 404) ;
		}
		
		$file = $filePath.$filename;
		
		switch ($type){
			case 'data':
				$content  = "<?php\r\n";
				$content .= "\$data = " . var_export($value, true) . ";\r\n";
				$content .= "?>";
			break;  
			case 'tpl':
				$content  = $value;
			break;
			case 'sql':
			  
			break;
			default:
		}
		$strlen = file_put_contents($file, $content);
		@chmod($file, 0777);
		return $strlen;
	}
	
	
	/**
	 *  清除指定后缀的模板缓存或编译文件
	 *
	 * @access  public
	 * @param  string     $type  要清除的类型
	 * @param  string     $ext   需要删除的文件名，不包含后缀
	 *
	 * @return int        返回清除的文件个数
	 */
	 
	public function clean($type = 'data', $ext = ''){
		$dirs = array();
		if ($type=='data') {
			$dirs = array(DIR_CACHE_DATA);
		}  elseif ($type=='sql') {
			$dirs = array(DIR_CACHE_SQL);
		} elseif ($type=='tpl') {
			$dirs = array(DIR_CACHE_TPL);
		}
		$str_len = strlen($ext);
		$count   = 0;

		foreach ($dirs AS $dir) {
			$folder = @opendir($dir);

			if ($folder === false) {
				continue;
			}
			while ($file = readdir($folder)) {
				if ($file == '.' || $file == '..' || $file == 'index.htm' || $file == 'index.html') {
					continue;
				}
				if (is_file($dir . $file)) {
					/* 如果有文件名则判断是否匹配 */
					$pos = strrpos($file, '.');

					if ($str_len > 0 && $pos !== false) {
						$ext_str = substr($file, 0, $pos);

						if ($ext_str == $ext) {
							if (@unlink($dir . $file)) {
								$count++;
							}
						}
					} else {
						if (@unlink($dir . $file)) {
							$count++;
						}
					}
				}
			}
			closedir($folder);
		}
		return $count;
	}	
}






