<?php defined('PX') or die; ?>
{include header} 
<body style="padding-top:70px">
  <div class="container">
  <div class="alert alert-success" role="alert">所有文件BOM清除完成！</div>
  <?php
  function getInfo($array){
	$ret = "";
	foreach($array as $v){
		$stat = $v['stat'];
		$file = $v['file'];
		
		if(is_array($stat)){
			$ret.= '<li class="floder text-primary"><span>'.$file.'</span><i class="badge">'.count($stat).'个文件</i></li>';
			$ret.=getInfo($stat);
		}else{
			$ret.= '<li class="file text-info"><span class="arrow">--></span><span class="title">'.$file.'</span><i class="badge">'.$stat.'</i></li>';
		}
	}
	return $ret;
  }
  echo '<div class="bom-list">'.getInfo($info).'</div>';
  ?>
  </div>
  {include footer} 
</body>
</html>

