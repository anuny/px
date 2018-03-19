<?php

// 扫描文件目录
function clean_bom($basedir)
{
	$ret=array();
	if ($dh = opendir ( $basedir )) { 		
		while ( ($file = readdir ( $dh )) !== false )
		{    
			if ($file != '.' && $file != '..' && $file != '.svn' && $file != '.htaccess') {    
				if (! is_dir ( $basedir . "/" . $file )) {// 如果是文件
					$info= array(
						'file'=>"$basedir/$file",
						'stat'=>clean_bom_file ( "$basedir/$file" )
					);
					array_push($ret,$info);
				} else {    
					$dirname = $basedir . "/" .$file; // 如果是目录    
					clean_bom ( $dirname ); // 递归   
					$info= array(
						'file'=>"$dirname",
						'stat'=>clean_bom ( $dirname )
					);
					array_push($ret,$info);
				}    
			}    
		}    
		closedir ( $dh );
	}
	return $ret;
}    


// 检查bom并清除
function clean_bom_file($filename) 
{       
	$contents = file_get_contents ( $filename );    
	$charset [1] = substr ( $contents, 0, 1 );    
	$charset [2] = substr ( $contents, 1, 1 );    
	$charset [3] = substr ( $contents, 2, 1 );    
	if (ord ( $charset [1] ) == 239 && ord ( $charset [2] ) == 187 && ord ( $charset [3] ) == 191) { // BOM 的前三个字符的ASCII 码分别为 239 187 191   
		$rest = substr ( $contents, 3 ); 
		clean_bom_rewrite ( $filename, $rest );    
		return 'cleaned'; 
	} else  {
		return 'nobom';  
	} 
		  
}

// 写入文件
function clean_bom_rewrite ($filename, $data) 
{
	$filenum = fopen($filename, "w");
	flock($filenum, LOCK_EX);
	fwrite($filenum, $data);
	fclose($filenum);
}