<!-- begin <?php echo core::basedir(__FILE__);?> -->
<?php
if(class_exists('users')){
  if(!users::connected()){
?>
        <form class="navbar-form pull-right" method="post" action="connect.php">
            <div class="form-group">
              <label class="sr-only" for="login"><?php echo tr('LOGIN'); ?></label>
              <input type="text"     class="form-control input-sm" id="login" name="login" placeholder="<?php echo tr('LOGIN'); ?>">
              <label class="sr-only" for="pass"><?php echo tr('PASSWORD'); ?></label>
              <input type="password" class="form-control input-sm" id="pass" name="pass" placeholder="<?php echo tr('PASSWORD'); ?>">
            </div>
            <div class="form-group">
              <label class="white">
                <input type="checkbox" name="remember"> <?php echo tr('REMEMBER ME ?');?>
              </label>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo tr('LOGIN'); ?></button>
        </form>
<?php
  }
  else{
    echo '<form class="navbar-form pull-right" method="post" action="logout.php"><label class="white">', tr('WELCOME'), ' ',
      users::getName(), ' <button type="submit" class="btn btn-primary">', tr('LOGOUT'), '</button></label></form>';
  }
}
?>
<!-- end <?php echo core::basedir(__FILE__);?> -->