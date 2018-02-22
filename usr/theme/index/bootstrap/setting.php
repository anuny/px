<?php defined('PX') or die; ?>
{include header} 
<body style="padding-top:70px">

<div class="container">
  <div class="row">
    <div class="col-xs-12 col-sm-2 " id="sidebar" role="navigation">
      <div class="list-group">    
        <a href="#" class="list-group-item active"><span class="glyphicon glyphicon-{$vo.slug}"></span>公共设置</a> 
		<a href="#" class="list-group-item"><span class="glyphicon glyphicon-{$vo.slug}"></span>网站设置</a> 
		<a href="#" class="list-group-item"><span class="glyphicon glyphicon-{$vo.slug}"></span>后台设置</a> 
      </div>
    </div>
    <div class="col-xs-12 col-sm-10">
      <div class="row">
        <div class="form-group col-md-10">
          <div class="panel panel-default">
            <div class="panel-heading"><strong>系统设置</strong></div>
            <div class="panel-body">
              <form action="{URL_APP}/setting/save" id="form" name="form" method="post">
                <div class="tab-content"> 
                  <div role="tabpanel" class="tab-pane active" id="siteconfig">

				  
				  <div class="form-group">
  <label for="sitename">网站名称</label>
  <input name="sitename" id="sitename" type="text" class="form-control" placeholder="网站名称" value="{$config.sitename}">
</div>
<div class="form-group">
  <label for="seoname">网站副标题</label>
  <input name="seoname" id="seoname" type="text" class="form-control" placeholder="网站副标题" value="{$config.seoname}">
</div>
<div class="form-group">
  <label for="siteurl">网站地址</label>
  <input name="siteurl" id="siteurl" type="text" class="form-control" placeholder="网站地址" value="{$config.siteurl}">
</div>
<div class="form-group">
  <label for="keywords">站点关键词</label>
  <input name="keywords" id="keywords" type="text" class="form-control" placeholder="站点关键词" value="{$config.keywords}">
</div>
<div class="form-group">
  <label for="description">站点描述</label>
  <textarea name="description" id="description" class="form-control" rows="3" placeholder="站点描述">{$config.description}</textarea>
  <span class="help-block"><span class="glyphicon glyphicon-question-sign"></span> 站点描述60字以内</span>
</div>
<br>
<div class="form-group">
  <label for="masteraddress">联系地址</label>
  <input name="masteraddress" id="masteraddress" type="text" class="form-control" placeholder="联系地址" value="{$config.masteraddress}">
</div>
<div class="form-group">
  <label for="masterphone">联系电话</label>
  <input name="masterphone" id="masterphone" type="text" class="form-control" placeholder="联系电话" value="{$config.masterphone}">
</div>
<div class="form-group">
  <label for="masterfax">传真号码</label>
  <input name="masterfax" id="masterfax" type="text" class="form-control" placeholder="传真号码" value="{$config.masterfax}">
</div>
<div class="form-group">
  <label for="masteremail">电子邮箱</label>
  <input name="masteremail" id="masteremail" type="text" class="form-control" placeholder="电子邮箱" value="{$config.masteremail}">
</div>
<div class="form-group">
  <label for="copyright">版权信息</label>
  <input name="copyright" id="copyright" type="text" class="form-control" placeholder="版权信息" value="{$config.copyright}">
</div>
<div class="form-group">
  <label for="AUTHO_KEY">授权密匙</label>
  <input name="AUTHO_KEY" id="AUTHO_KEY" type="text" class="form-control" placeholder="授权密匙" value="{$config.AUTHO_KEY}">
</div>
<br>
<div class="form-group">
  <label for="SITE_STATUS">网站状态</label>
  <br>
  <?php
    $type = array(1=>'开启',0=>'关闭');
    $name= 'SITE_STATUS';
    foreach ($type as $key => $value){
    	$onclick = 'onclick="siteStatus('.$key.')"';
    	$checked = $config[$name] == $key? 'checked="checked"':'';
        echo '<label><input type="radio" name="'.$name.'" '.$onclick.' value="'.$key.'" '.$checked.'>'.$value.'</label>  ';
    }
    ?>
</div>
<div class="form-group" style="display:none" id="sitecloseinfo">
  <label for="SITE_CLOSE_INFO">网站关闭说明</label>
  <input name="SITE_CLOSE_INFO" id="SITE_CLOSE_INFO" type="text" class="form-control" placeholder="关闭说明" value="{$config.SITE_CLOSE_INFO}">
</div>





				  </div>
                  <button type="submit" class="btn btn-success">保存设置</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="form-group col-md-2">
          <ul class="nav nav-pills nav-stacked" role="tablist">
            
            <li role="presentation" class="active"><a href="#siteconfig" role="tab" data-toggle="tab">站点设置</a></li>
            
            <li role="presentation"><a href="#perconfig" role="tab" data-toggle="tab">性能设置</a></li>
            
            <li role="presentation"><a href="#tplconfig" role="tab" data-toggle="tab">模板设置</a></li>
            
            <li role="presentation"><a href="#upconfig" role="tab" data-toggle="tab">上传设置</a></li>
            
            <li role="presentation"><a href="#fwconfig" role="tab" data-toggle="tab">防火墙设置</a></li>
         
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

  {include footer}
</body> 
</html> 
