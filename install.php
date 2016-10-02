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
    //echo '<pre>', print_r($GLOBALS, true), '</pre>';
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
      switch($status){
        // -1 .htaccess error
        case -1:
          core::addFlash(tr('HTACCESS CREATION ERROR'), 'danger');
          break;
        // -2 db.conf error
        case -2:
          core::addFlash(tr('DB CONFIGURATION ERROR'), 'danger');
          break;
        // -3 sql error
        case -3:
          core::addFlash(tr('SQL ERROR'), 'danger');
          break;
      }
      echo '<h1>', tr('STEP'), ' ', $step - 1, ' : ', tr('DATABASE CONFIGURATION'), '</h1>';
      include 'templates/flash.php';
      include 'templates/install/dbconf.php';
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
      switch($status){
        case -1:
          core::addFlash(tr('INVALID TYPE CONFIGURATION'), 'danger');
          break;
        case -2:
          core::addFlash(tr('INVALID PARAMETER'), 'danger');
          break;
        case -3:
          core::addFlash(tr('FILE CREATION ERROR'), 'danger');
          break;
      }
      echo '<h1>', tr('STEP'), ' ', $step - 1, ' : ', tr('MAIL CONFIGURATION'), '</h1>';
      include 'templates/flash.php';
      include 'templates/install/mailconf.php';
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
      core::addFlash(tr('ADMINISTRATOR CREATION ERROR'));
      echo '<h1>', tr('STEP'), ' ', $step - 1, ' : ', tr('ADMINISTRATOR ACCOUNT CREATION'), '</h1>';
      include 'templates/flash.php';
      include 'templates/admin/create_user.php';
    }
    break;
  case 5:
    break;
  default:
    $content = tr('UNKNOWN INSTALLATION STEP');
    include 'templates/error.php';
    break;
}

echo '</div>';
include 'templates/foot.php';
include 'templates/utils/select_first_form.php';

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

  $params = array(array($name, $email, $epass, 'ADMINISTRATOR'));
  array_unshift($params, $queries['insert'][0]['user'][0], $queries['insert'][0]['user'][1]);
  call_user_func_array(array($db, 'q'), $params);

  include 'core/mail.php';
  $msg =  sprintf('Hi %s,<br/><br/>Now, you are an administrator of %s', $name, '');
  mail::mail($email, tr('ADMINISTRATOR OF WOTAN SITE'), $msg);
  return 0;
}

function wotan_mailconf(){
  $type  = filter_input(INPUT_POST, 'type', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/(?:PHP)|(?:SMTP)/')));
  $smtp  = filter_input(INPUT_POST, 'host', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/[a-zA-Z0-9][a-zA-Z0-9_-]+/')));
  $port  = filter_input(INPUT_POST, 'port', FILTER_VALIDATE_INT);
  $email = filter_input(INPUT_POST, 'user', FILTER_VALIDATE_EMAIL);
  $pass  = filter_input(INPUT_POST, 'pass',  FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/.{6,}/')));
  $pass2 = filter_input(INPUT_POST, 'pass2', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/.{6,}/')));

  if($type === false){
    return -3;
  }
  if($type == 'SMTP'){
    if($smtp === false || $port === false || $email === false || $pass === false || $pass != $pass2)
      return -2;
  }

  if(!file_exists('conf/mail.conf')){
    $fd = fopen('conf/mail.conf', 'w');
    if($fd === false){
      return -1;
    }
    else{
      if($type == 'SMTP')
        fputs($fd, sprintf('<?php
define("SMTPTYPE","%s");
define("SMTP","%s");
define("SMTPPORT", "%s");
define("EMAILNAME","WOTAN");
define("EMAIL","%s");
define("EMAILPASS","%s");
', $type, $smtp, $port, $email, $pass));
      else
        fputs($fd, sprintf('<?php
define("SMTPTYPE","%s");
', $type));
      fclose($fd);
    }
  }
  require_once 'core/mail.php';
  mailer::init();

  if(mailer::mail('ykohler@gmail.com', 'Test é WOTAN', 'Test ' . date('Y-m-d H:i:s')))
    core::addFlash(tr('MAIL SENT'));
  else
    core::addFlash(tr('MAIL UNSENT'));

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
    try{
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
        foreach($vv as $k3 => $v3){
          foreach($v3 as $k => $v){
            if($k == 'file'){
              if(!is_array($v))
                $files = array($v);
              else
                $files = $v;
              foreach($files as $val){
                $fd = fopen($val, 'r');

                if($fd === false)
                  throw new Exception();

                while(($data = fgetcsv($fd)) !== false){
                  $params = array($data);
                  array_unshift($params, $queries['insert'][$kk][$k3][0], $queries['insert'][$kk][$k3][1]);
                  call_user_func_array(array($db, 'q'), $params);
                }

                fclose($fd);
              }
            }
            else {
              $params = array($v);
              array_unshift($params, $queries['insert'][$kk][$k3][0], $queries['insert'][$kk][$k3][1]);
              call_user_func_array(array($db, 'q'), $params);
            }
          }
        }
      }
    }
    catch(Exception $e){
      return -3;
    }
  }
  return 0;
}
?>
<!-- end <?php echo core::basedir(__FILE__);?> -->
