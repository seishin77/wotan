<!-- begin <?php echo core::basedir(__FILE__);?> -->
<?php

if(!isset($_REQUEST['step']))
  $step = 1;
else
  $step = $_REQUEST['step'];

$_SESSION['step'] = $step + 1;

include 'templates/head.php';
echo '<div class="container">', PHP_EOL;


/*
echo core::getVPath(), '<br/>', core::getPPath(),'<br/>', PHP_EOL;

  1. Configure db
  2. Configure others systems (mail, ...)
  3. Create 1 administrator and 1 teacher
  Create or not a student class
  Create thematic categories and questions
  Invite students

*/

switch($step){
  case 1:
    echo '<h1>', tr('STEP'), ' ', $step, ' : ', tr('DATABASE CONFIGURATION'), '</h1>';
    include 'templates/flash.php';
    include 'templates/install/dbconf.php';
    break;
  case 2:
    $status = wotan_dbconf();
    if($status === 0){
      core::addFlash(tr('DATABASE CREATED'));
      echo '<h1>', tr('STEP'), ' ', $step, ' : ', tr('MAIL CONFIGURATION'), '</h1>';
      include 'templates/flash.php';
      include 'templates/install/mailconf.php';
    }
    else{
      // TODO : ERROR
    }
    break;
  case 3:
    $status = wotan_mailconf();
    if($status === 0){
      core::addFlash(tr('MAIL CONFIGURED'));
      echo '<h1>', tr('STEP'), ' ', $step, ' : ', tr('ADMINISTRATOR ACCOUNT CREATION'), '</h1>';
      include 'templates/flash.php';
      include 'templates/admin/create_user.php';
    }
    else{
      // TODO : ERROR
      // -1 .htaccess error
      // -2 db.conf error
    }
    break;
  case 4:
    $status = wotan_create();
    if($status === 0){
      core::addFlash(tr('ADMINISTRATOR CREATED'));
      echo '<h1>', tr('STEP'), ' ', $step, ' : ', tr(''), '</h1>';
      include 'templates/flash.php';
      include 'templates/install/finish.php';
    }
    else{
      // TODO : ERROR
    }
    break;
  case 5:
    break;
  default:
    echo 'ERROR';    
    break;
}

echo '</div>';
include 'templates/foot.php';
include 'templates/utils/select_first_form.php';

// TODO
function wotan_create(){
  include 'sql/create.php';
  unset($_SESSION['step']);
  return 0;
}

// TODO
function wotan_mailconf(){
  return 0;
}

function wotan_dbconf(){
  if(!file_exists('conf'))
    mkdir('conf', 0700);

  if(!file_exists('conf/.htaccess')){
    $fd = fopen('conf/.htaccess', 'w');
    if($fd === false){
      return -1;
    }
    else{
      fputs($fd, 'order deny, allow
deny from all
');
      fclose($fd);
    }
  }

  return wotan_dbconfig();
}

function wotan_dbconfig(){
  $fd = fopen('conf/db.conf', 'w');
  if($fd === false){
    return -2;
  }
  else{
    fputs($fd, sprintf('<?php
define("HOST","%s");
define("DB", "%s");
define("USER","%s");
define("PASS","%s");
', $_REQUEST['host'], $_REQUEST['database'], $_REQUEST['user'], $_REQUEST['password']));
    fclose($fd);
    require_once 'core/db.php';
    $db = new db($_REQUEST['host'], '', $_REQUEST['user'], $_REQUEST['password']);
    $db->q(sprintf('CREATE DATABASE IF NOT EXISTS `%s`;', $_REQUEST['database']));
    
    $db = dbm::getConnexion();
    include 'sql/create.php';

    foreach($queries['create'] as $kk => $vv){
      foreach($vv as $k => $v)
        $db->q($v);
    }
  }
  return 0;
}
?>
<!-- end <?php echo core::basedir(__FILE__);?> -->