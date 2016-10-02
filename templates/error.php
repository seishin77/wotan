<!-- begin <?php echo core::basedir(__FILE__);?> -->
<h1><?php echo tr('ERROR'); ?></h1>
<form method="post" action="index.php" class="form-horizontal col-offset-lg-2 col-lg-8">
  <input type="hidden" name="step" value="<?php echo $step - 1; ?>" />
  <div class="row">
    <div class="col-lg-12">
      <?php echo $content;?>
    </div>
  </div>
  <div class="form-group">
    <input type="submit" class="btn btn-primary btn-block" value="<?php echo tr('NEXT');?>">
  </div>
</form>
<!-- end <?php echo core::basedir(__FILE__);?> -->
