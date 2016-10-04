<!-- begin <?php echo core::basedir(__FILE__);?> -->
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only"><?php echo tr('Toggle navigation')?></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?php echo core::vpath();?>">Wotan</a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <li class="active"><a href="<?php echo core::vpath();?>"><?php echo tr('Home')?></a></li>
          <li><a href="#about"><?php echo tr('About')?></a></li>
          <li><a href="#contact"><?php echo tr('Contact')?></a></li>
        </ul>
        <?php include 'connection.php'; ?>
      </div>
    </div>
  </nav>
<!-- end <?php echo core::basedir(__FILE__);?> -->