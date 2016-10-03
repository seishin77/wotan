<?php

class users{
	public static function login(){
		$email = filter_input(INPUT_POST, 'login', FILTER_VALIDATE_EMAIL);
		$login = filter_input(INPUT_POST, 'login',  FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/[a-zA-Z][a-zA-Z0-9]{3,}/')));
		$pass  = filter_input(INPUT_POST, 'pass',  FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/.{6,}/')));

  	$db = dbm::getConnexion();

  	if($email !== false)
  		$db->q('SELECT `id`, `name`, `status`, `token`, `__passwd` AS p FROM `user` WHERE `email`=?;', 's', array($email));
  	else
  		$db->q('SELECT `id`, `name`, `status`, `token`, `__passwd` AS p FROM `user` WHERE `name`=?;', 's', array($login));
		$r = $db->fetch_array();

  	if($r && $r['p'] == crypt($pass, $r['p'])){
    	$_SESSION['user'] =$r;
  		$db->q('SELECT `id`, `name` FROM `class` ORDER BY `id`;');
  		$class = array();
  		while($r = $db->fetch_array())
  			$class['id'] = $r['name'];

  		$db->q('SELECT `idclass`, `role` FROM `belong_to` WHERE `iduser`=?', 'd', array($_SESSION['user']['id']));
  		$_SESSION['user']['class'] = array('STUDENT' => array(), 'TEACHER' => array());
  		while($r = $db->fetch_array())
  			$_SESSION['user']['class'][$r['role']][] = array($r['idclass'], $class[$r['idclass']]);

  		return true;
  	}
  	else{
  		core::addFlash(tr('INVALID CREDENTIALS'), 'danger');
	  	return false;
  	}
	}

	public static function logout(){
		unset($_SESSION['user']);
	}

	public static function connected(){
		return isset($_SESSION['user']);
	}

	public static function getName(){
		return $_SESSION['user']['name'];
	}
}