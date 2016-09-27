<!-- begin <?php echo core::basedir(__FILE__);?> -->
<form method="post" action="index.php" class="form-horizontal col-push-lg-2 col-lg-8">
  <input type="hidden" name="step" value="4" />
  <div class="row">
    <div class="form-group">
      <div class="col-lg-4">
        <label for="name" class="control-label">
          <?php echo tr('LOGIN');?> :
        </label>
      </div>
      <div class="col-lg-8">
        <input type="text" class="form-control" id="name" name="name">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group">
      <div class="col-lg-4">
        <label for="email" class="control-label">
          <?php echo tr('EMAIL');?> :
        </label>
      </div>
      <div class="col-lg-8">
        <input type="text" class="form-control" id="email" name="email">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group">
      <div class="col-lg-4">
        <label for="pass" class="control-label">
          <?php echo tr('PASSWORD');?> :
        </label>
      </div>
      <div class="col-lg-8">
        <input type="password" class="form-control" id="pass" name="pass">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group">
      <div class="col-lg-4">
        <label for="pass2" class="control-label">
          <?php echo tr('PASSWORD AGAIN');?> :
        </label>
      </div>
      <div class="col-lg-8">
        <input type="password" class="form-control" id="pass2" name="pass2">
      </div>
    </div>
  </div>

  <div class="form-group">
    <input type="submit" class="btn btn-primary btn-block" value="<?php echo tr('NEXT');?>">
  </div>
</form>
<!-- end <?php echo core::basedir(__FILE__);?> -->
