<?php defined('APP') or die; ?>
<!DOCTYPE html>
<html>
<head>
<title>Error</title>
<meta charset="utf-8" />
<style>
body{margin: 0;padding: 0;font: 12px/1.5 微软雅黑,tahoma,arial;}
body{background:#efefef;}
h1, h2, h3, h4, h5, h6 {}
ul, ol {list-style: none outside none;}
a {text-decoration: none;color:#447BC4}
a:hover {text-decoration: underline;}
.ip-attack{width:600px; margin:200px auto 0;}
.ip-attack dl{ background:#fff; padding:30px; border-radius:10px;border: 1px solid #CDCDCD;-webkit-box-shadow: 0 0 8px #CDCDCD;-moz-box-shadow: 0 0 8px #cdcdcd;box-shadow: 0 0 8px #CDCDCD;}
.ip-attack dt{text-align:left;}
.ip-attack dd{font-size:16px; color:#f00; text-align:left;}
.tips{text-align:center; font-size:14px; line-height:50px; color:#f00;}
</style>
</head>
<body>
<div class="ip-attack">
  <dl>
    <h1><?php echo $error['code'];?> Error</h1>
    <dt style="font-size:18px"><?php echo $error['message'];?></dt>
<dt style="font-size:12px;padding:10px 0"><?php echo $error['trace'];?></dt>
    <dt><a href="javascript:history.back()" >返回</a></dt>
  </dl>
</div>
</body>
</html>