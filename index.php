<?php
require_once 'core/core.php';
core::init();

if(!file_exists('./conf/db.conf') ||
      isset($_REQUEST['force']) ||
      (isset($_SESSION['step']) &&
        isset($_REQUEST['step']) &&
        $_SESSION['step'] == $_REQUEST['step'])){

  if(isset($_REQUEST['force'])){
    @unlink('conf/db.conf');
    @unlink('conf/mail.conf');
  }

  if(!isset($_REQUEST['step']))
    $step = 1;
  else
    $step = $_REQUEST['step'];

  require_once 'core/tr.php';
  include 'install.php';
}
else{
  require_once 'core/system.php';

  users::isConnected();
  core::route();
}
