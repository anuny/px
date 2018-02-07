<?php defined('APP') or die; ?>
<?php $this->render('header'); ?> 
<body style="padding-top:70px">
  <div class="container">
    <div class="jumbotron">
        <h1><?php echo $test;?></h1>
        <p>This is a template showcasing the optional theme stylesheet included in Bootstrap. Use it as a starting point to create something more unique by building on or modifying it.</p>
      </div>
    <div class="starter-template">
      <h1>Bootstrap starter template</h1>
      <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
    </div>
Mysql Host:<span class="badge test"><?php echo $admin['Host'];?></span> Password: <span class="badge test"><?php echo $admin['Password'];?></span>
  </div>
  <?php $this->render('footer'); ?>
</body> 
</html> 