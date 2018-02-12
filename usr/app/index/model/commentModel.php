<?php
namespace usr\app\index\model;

class commentModel extends baseModel
{
    /** 
	 * @param $arc_id  文章id 
	 * @return array 
	 */  
	public function get($cid){  
	
		if(empty($cid)){  
			return array();  
		}
		$res = $this->db->table('comment')->where('cid='.$cid)->order('created ASC')->select(); 		
		$dataList = $stack = array();  
		if($res){  
			foreach($res AS $k=>$v){   //先将评论的数据进行入库（即comment_id=0）  
				if($v['pid'] == 0){  
					$v['_level'] = 0;   //设置层级数  
					$v['_root'] = $v['id'];   //标识评论id  
					array_push($stack,$v);   //入栈  
					unset($res[$k]);  
				}  
			}  
	  
			while(!empty($stack)){  
				$node = array_pop($stack);   //出栈  
				$dataList[] = $node;  
				foreach($res as $_k=>$_v){  
					if($_v['pid'] == $node['id']){  
						$_v['_level'] = $node['_level']+1;   //设置层级数  
						$_v['_root'] = $node['_root'];   //标识评论id  
						$_v['_author'] = $node['author'];   //标识评论id  
						array_push($stack,$_v);   //入栈  
						unset($res[$_k]);  
					}  
				}  
			}  
		}  
	  
		return $dataList;  
	}  
}