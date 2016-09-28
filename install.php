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
      echo '<h1>', tr('STEP'), ' ', $step, ' : ', tr('ADMINISTRATOR ACCOUNT CREATION'), '</h1>';
      include 'templates/flash.php';
      include 'templates/admin/create_user.php';
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

  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  $name  = filter_input(INPUT_POST, 'name',  FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/[a-zA-Z][a-zA-Z0-9]{3,}/')));
  $pass  = filter_input(INPUT_POST, 'pass',  FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/.{6,}/')));
  $pass2 = filter_input(INPUT_POST, 'pass2', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/.{6,}/')));
  
  if($pass != $pass2){
    $_SESSION['step'] = 4;
    core::addFlash(tr('PASSWORDS MUST BE IDENTICAL'), 'danger');
  }
  if($name == false){
    $_SESSION['step'] = 4;
    core::addFlash(tr('ONLY CHARACTERS AND NUMBERS ARE AUTHORIZED IN LOGIN'), 'danger');
    core::addFlash(tr('THE LENGTH OF LOGIN MUST BE AT LEAST 4'), 'danger');
  }
  if($email == false){
    $_SESSION['step'] = 4;
    core::addFlash(tr('EMAIL IS INCORRECT'), 'danger');
  }
  if($pass == false ){
    $_SESSION['step'] = 4;
    core::addFlash(tr('THE LENGTH OF PASSWORD MUST BE AT LEAST 6'), 'danger');
  }
  require_once 'core/db.php';
  $db = dbm::getConnexion();
  $db->q('SELECT name, email FROM `user` WHERE name=? OR email=?;', 'ss', array($name, $email));
  if($db->num_rows() > 0){
    $_SESSION['step'] = 4;
    $r = $db->fetch_array();
    if($r['name'] == $name){
      core::addFlash(tr('THIS LOGIN IS ALREADY USED'), 'danger');
    }
    if($r['email'] == $email){
      core::addFlash(tr('THIS EMAIL IS ALREADY USED'), 'danger');
    }
  }
  if($_SESSION['step'] == 4)
    return -1;
  unset($_SESSION['step']);
  
  $epass = core::crypt($pass);
  include 'sql/init.php';

  $params = array(array($name, $email, $epass));
  array_unshift($params, $queries['insert'][0]['user'][0], $queries['insert'][0]['user'][1]);
  call_user_func_array(array($db, 'q'), $params);

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
    include 'sql/init.php';

    foreach($queries['drop'] as $kk => $vv){
      foreach($vv as $k => $v)
        $db->q($v);
    }
    foreach($queries['create'] as $kk => $vv){
      foreach($vv as $k => $v)
        $db->q($v);
    }

    foreach($queries['data'] as $kk => $vv){
      var_dump('vv', $vv);
      foreach($vv as $k3 => $v3){
        foreach($v3 as $k => $v){
          $params = array($v);
          var_dump(array($kk, $k3, $queries['insert'][$kk][$k3]));
          array_unshift($params, $queries['insert'][$kk][$k3][0], $queries['insert'][$kk][$k3][1]);
          call_user_func_array(array($db, 'q'), $params);
        }
      }
    }
  }
  return 0;
}
?>
<!-- end <?php echo core::basedir(__FILE__);?> -->