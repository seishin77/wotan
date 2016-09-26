<!-- begin <?php echo core::basedir(__FILE__);?> -->
<h1><?php echo tr('STEP'), ' ', $step, ' : ', tr('DATABASE CONFIGURATION');?></h1>
<form method="post" action="index.php" class="form-horizontal col-lg-6">
  <input type="hidden" name="step" value="2" />
  <div class="row">
    <div class="form-group">
      <div class="col-lg-2">
        <label for="host" class="control-label">
          <?php echo tr('HOSTNAME');?> :
        </label>
      </div>
      <div class="col-lg-10">
        <input type="text" class="form-control" id="host" name="host">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group">
      <div class="col-lg-2">
        <label for="database" class="control-label">
          <?php echo tr('DATABASE');?> :
        </label>
      </div>
      <div class="col-lg-10">
        <input type="text" class="form-control" id="database" name="database">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group">
      <div class="col-lg-2">
        <label for="user" class="control-label">
          <?php echo tr('USERNAME');?> :
        </label>
      </div>
      <div class="col-lg-10">
        <input type="text" class="form-control" id="user" name="user">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group">
      <div class="col-lg-2">
        <label for="password" class="control-label">
          <?php echo tr('PASSWORD');?> :
        </label>
      </div>
      <div class="col-lg-10">
        <input type="password" class="form-control" id="password" name="password">
      </div>
    </div>
  </div>
  <div class="form-group">
    <input type="submit" class="btn btn-primary btn-block" value="<?php echo tr('NEXT');?>">
  </div>
</form>
<!-- end <?php echo core::basedir(__FILE__);?> -->
