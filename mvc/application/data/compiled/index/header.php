<?php defined('APP') or die; ?>
<?php $this->render('base'); ?> 
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
<div class="navbar-header">
  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
<span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
  </button>
  <a class="navbar-brand" href="#"><?php echo $logo;?></a>
</div>
<div id="navbar" class="collapse navbar-collapse">
  <ul class="nav navbar-nav">
    <?php if(is_array($navs)) foreach($navs AS $key => $nav){?>
<li><a href="<?php echo $nav['url'];?>"><?php echo $nav['name'];?></a></li>
<?php }?>
  </ul>
</div> 
  </div>
</nav>
