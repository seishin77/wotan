<?php
session_start();

if(!file_exists('./conf/db.conf') ||
      isset($_REQUEST['force']) ||
      (isset($_SESSION['step']) &&
        isset($_REQUEST['step']) &&
        $_SESSION['step'] == $_REQUEST['step'])){

  if(isset($_REQUEST['force'])){
    @unlink('conf/db.conf');
    @unlink('conf/mail.conf');
  }
  require_once 'core/core.php';
  require_once 'core/tr.php';
  core::init();
  include 'install.php';
}
else{
  require_once 'core/system.php';

  core::init();

  $db = dbm::getConnexion();
  $db->query('SELECT * FROM user;');

  ob_start();
  $db->print_table();

  if(isset($_REQUEST['debug'])){
    echo '<pre>';
    echo '_REQUEST', PHP_EOL;
    print_r($_REQUEST);
    echo '_SERVER', PHP_EOL;
    print_r($_SERVER);
    echo '</pre>';
  }
  $content = ob_get_clean();

  include 'templates/main.php';
}
