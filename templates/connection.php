<!-- begin <?php echo core::basedir(__FILE__);?> -->
<?php
if(class_exists('users')){
  if(!users::isConnected()){
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
?>
        <ul class="nav navbar-nav navbar-right">
          <li class="w-label"><?php echo tr('WELCOME'); ?></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo users::getName(); ?><span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#"><?php echo tr('MY PROFIL'); ?></a></li>
              <li><a href="#"><?php echo tr('MY CLASSES'); ?></a></li>
<?php
if(users::isModerator()){
?>
              <li role="separator" class="divider"></li>
              <li><a href="#"><?php echo tr('MODERATION'); ?></a></li>
<?php
  if(users::isAdmin()){
?>
              <li role="separator" class="divider"></li>
              <li><a href="#"><?php echo tr('ADMINISTRATION'); ?></a></li>
<?php  
  }
}
?>
<!--
              <li><a href="#">Another action</a></li>
              <li><a href="#">Something else here</a></li>
-->
              <li role="separator" class="divider"></li>
              <li><a href="<?php echo core::getUrl('logout.php');?>"><?php echo tr('LOGOUT'); ?></a></li>
              <form></form>
            </ul>
          </li>
        </ul>
<?php
  }
}
?>
<!-- end <?php echo core::basedir(__FILE__);?> -->