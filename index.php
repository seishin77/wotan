<?php
require_once 'core/core.php';

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