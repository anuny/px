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
<?php echo $admin['uname'];?> Email: <?php echo $admin['email'];?>
  </div>
  <footer class="footer">
    <div class="container">
      <p class="text-muted">Done in :<span class="user"><?php echo runtime();?> second(s)</span> &nbsp;&nbsp; <?php echo date("Y-m-d G:i T",time());?></p>
    </div>
  </footer>
</body> 
</html>