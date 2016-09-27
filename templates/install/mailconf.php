<!-- begin <?php echo core::basedir(__FILE__);?> -->
<form method="post" action="index.php" class="form-horizontal col-push-lg-2 col-lg-8">
  <input type="hidden" name="step" value="3" />
  <div class="row">
    <div class="form-group">
      <div class="col-lg-4">
        <label for="type" class="control-label">
          <?php echo tr('MAIL TYPE');?> :
        </label>
      </div>
      <div class="col-lg-8">
        <select class="form-control" id="type" name="type">
          <option VALUE="PHP"><?php echo tr('PHP DEFAULT');?></option>
          <option VALUE="SMTP"><?php echo tr('SMTP');?></option>
        </select>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group">
      <div class="col-lg-4">
        <label for="host" class="control-label">
          <?php echo tr('SMTP SERVER');?> :
        </label>
      </div>
      <div class="col-lg-8">
        <input type="text" class="form-control" id="host" name="host">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group">
      <div class="col-lg-4">
        <label for="port" class="control-label">
          <?php echo tr('SMTP PORT');?> :
        </label>
      </div>
      <div class="col-lg-8">
        <input type="text" class="form-control" id="port" name="port">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group">
      <div class="col-lg-4">
        <label for="user" class="control-label">
          <?php echo tr('USERNAME');?> :
        </label>
      </div>
      <div class="col-lg-8">
        <input type="text" class="form-control" id="user" name="user">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group">
      <div class="col-lg-4">
        <label for="password" class="control-label">
          <?php echo tr('PASSWORD');?> :
        </label>
      </div>
      <div class="col-lg-8">
        <input type="password" class="form-control" id="password" name="password">
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
