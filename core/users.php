<?php

class users{
  private static function connect($row){
    $db = dbm::getConnexion();

    unset($row['p']);
    unset($row['token']);
    unset($row['tokendate']);

    $_SESSION['user'] = $row;
    $db->q('SELECT `id`, `name` FROM `class` ORDER BY `id`;');
    $class = array();
    while($r = $db->fetch_array())
      $class['id'] = $r['name'];

    $db->q('SELECT `idclass`, `role` FROM `belong_to` WHERE `iduser`=?', 'd', array($_SESSION['user']['id']));
    $_SESSION['user']['class'] = array('STUDENT' => array(), 'TEACHER' => array());
    while($r = $db->fetch_array())
      $_SESSION['user']['class'][$r['role']][$r['idclass']] = $class[$r['idclass']];
  }

  private static function setIdCookie(){
    $db =dbm::getConnexion();
    $expire = time() + 86400; // 1 day
    $wtok = core::salt(128);
    $db->q('UPDATE `user` SET tokendate=NOW()+INTERVAL 1 DAY, `token`=? WHERE `id`=?',
      'sd', array($wtok, $_SESSION['user']['id']));

    setcookie('wotan-tok', $wtok, $expire);
    setcookie('wotan-id',  md5($_SESSION['user']['id']), $expire);
  }

	public static function login(){
		$email = filter_input(INPUT_POST, 'login', FILTER_VALIDATE_EMAIL);
		$login = filter_input(INPUT_POST, 'login',  FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/[a-zA-Z][a-zA-Z0-9]{3,}/')));
		$pass  = filter_input(INPUT_POST, 'pass',  FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/.{6,}/')));
    $remember = isset($_POST['remember']);

  	$db = dbm::getConnexion();

  	if($email !== false)
  		$db->q('SELECT `id`, `name`, `status`, `token`, `__passwd` AS p FROM `user` WHERE `email`=?;', 's', array($email));
  	else
  		$db->q('SELECT `id`, `name`, `status`, `token`, `__passwd` AS p FROM `user` WHERE `name`=?;', 's', array($login));
		$r = $db->fetch_array();

  	if($r && $r['p'] == crypt($pass, $r['p'])){
      self::connect($r);

      if($remember)
        self::setIdCookie();

  		return true;
  	}
  	else{
  		core::addFlash(tr('INVALID CREDENTIALS'), 'danger');
	  	return false;
  	}
	}

	public static function logout(){
    $_SESSION = array();
    setcookie(session_name(), '', 0);
    setcookie('wotan-tok', '', 0);
    setcookie('wotan-id',  '', 0);
	}

  public static function isConnected(){
    if(isset($_SESSION['user']) && isset($_SESSION['user']['name']))
      return true;
    
    if(!isset($_COOKIE['wotan-id']) || !isset($_COOKIE['wotan-tok']))
      return false;

    $db = dbm::getConnexion();
    $db->q('SELECT `id`, `name`, `status`, `token`, `__passwd` AS p FROM `user` WHERE md5(0+`id`)=? AND `token`=? AND `tokendate` >= NOW();',
       'ss', array($_COOKIE['wotan-id'], $_COOKIE['wotan-tok']));
    $r = $db->fetch_array();

    if(!is_null($r)){
      self::connect($r);
      self::setIdCookie();
      core::addFlash(tr('I remember you'), 'info');
      return true;
    }
    else{
      $db->q('UPDATE `user` SET `token`=\'\', tokendate=NOW()-INTERVAL 1 MONTH WHERE md5(`id`)=?', 's', array($_COOKIE['wotan-id']));
      setcookie('wotan-tok', '', 0);
      setcookie('wotan-id',  '', 0);
      core::addFlash(tr('INVALID TOKEN'), 'danger');
      return false;
    }
	}

	public static function getName(){
		return $_SESSION['user']['name'];
	}

  public static function isAdmin(){
    return self::isConnected() && $_SESSION['user']['status'] == 'ADMINISTRATOR';
  }

  public static function isModerator(){
    return self::isConnected() && $_SESSION['user']['status'] == 'MODERATOR' || $_SESSION['user']['status'] == 'ADMINISTRATOR';
  }

  public static function isUser(){
    return self::isConnected() && $_SESSION['user']['status'] == 'USER';
  }

  public static function isTeacher($class=null){
    if(is_null($class))
      return self::isConnected() && !empty($_SESSION['user']['class']['TEACHER']);

    return isset($_SESSION['user']['class']['TEACHER'][$class]);
  }

  public static function isStudent($class=null){
    if(is_null($class))
      return self::isConnected() && !empty($_SESSION['user']['class']['STUDENT']);

    return isset($_SESSION['user']['class']['STUDENT'][$class]);
  }
}
