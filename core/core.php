<?php

require_once 'core/controller.php';

class core{
	public static $ppath = '';
	public static $vpath = '';
	public static $url   = '';
	public static $ruri  = '';

	public static function init(){
		session_name('wotansession');
		session_start();
		self::$ppath = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..') .
							DIRECTORY_SEPARATOR;
		self::$vpath = dirname($_SERVER['PHP_SELF']) . '/';
		self::$url = $_SERVER['HTTP_HOST'] . self::$vpath;
		if(!isset($_SESSION['flash']))
			self::resetFlash();

		self::$ruri = array('WCONTROLLER' => 'index', 'WACTION' => 'index');
		if(isset($_SERVER['REDIRECT_URL'])){
			$tab = explode('/', str_replace(self::$vpath, '', $_SERVER['REDIRECT_URL']));
			$nb = count($tab);
			if($nb > 0)
				self::$ruri['WCONTROLLER'] = $tab[0];

			if($nb > 1)
				self::$ruri['WACTION'] = $tab[1];

			$i = 2;
			while($i + 1 < $nb){
				self::$ruri[$tab[$i]] = $tab[$i + 1]; 
				$i+=2;
			}
			if($i < $nb)
				self::$ruri[$tab[$i]] = null; 
		}
	}

	public static function getRURI(){
		return self::$ruri;
	}

	public static function getPPath(){
		return self::$ppath;
	}

	public static function getVPath(){
		return self::$vpath;
	}

	public static function vpath($p=''){
		return self::getVPath() . $p;
	}

	public static function path($p=''){
		return self::getPPath() . $p;
	}

	public static function basedir($p){
		return DIRECTORY_SEPARATOR . str_replace(self::getPPath(), '', $p);
	}

	public static function getFlash(){
		return $_SESSION['flash'];
	}

	public static function resetFlash(){
		$_SESSION['flash'] = array('nbflash' => 0,
																'success' => array(),
																'info' => array(),
																'warning' => array(),
																'danger' => array(),
																);
	}

	public static function addFlash($txt, $level='info'){
		$_SESSION['flash']['nbflash']++;
		$_SESSION['flash'][$level][] = $txt;
	}

	public static function salt($l=6){
		$chars = str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789#/:;!.,?*%$');
		$n = count($chars);
		$retour = '';

		for($i=0; $i < $l; $i++)
			$retour .= $chars[mt_rand(0, $n - 1)];

		return $retour;
	}

	public static function crypt($p){
		if(function_exists('password_hash'))
			return password_hash($p, PASSWORD_BCRYPT);

		return crypt($p);
	}

	public static function verify($p, $h){
		if(function_exists('password_hash'))
			return password_verify($p, $h);

		return hash_equals($h, crypt($p, $h));
	}

	public static function getUrl($path=''){
		return '//' . self::$url . $path;
	}

	public static function redirect($path=''){
		header('Location: ' . self::getUrl($path));	
		exit();
	}

	public static function partial_render($tpl, $data=array()){
		ob_start();
		extract($data, EXTR_PREFIX_ALL, 'WOT');
		include $tpl;
		return ob_get_clean();
	}

	public static function render($tpl='', $data=array(), $mtpl='templates/main.php'){
	  ob_start();

	  if($tpl != '')
	  	echo self::partial_render($tpl, $data);

	  if(isset($_REQUEST['debug'])){
	    echo '<pre>';
	    echo '_REQUEST', PHP_EOL;
	    print_r($_REQUEST);
	    echo '_SESSION', PHP_EOL;
	    print_r($_SESSION);
	    echo '_SERVER', PHP_EOL;
	    print_r($_SERVER);
	    echo '</pre>';
	  }
	  $content = ob_get_clean();

	  echo self::partial_render($mtpl, array('content' => $content));
	}

	public static function route(){
		try{
			$ctrl   = strtolower(self::$ruri['WCONTROLLER']) . 'Controller';
			$action = strtolower(self::$ruri['WACTION']) . 'Action';
			if(!file_exists('controllers/' . $ctrl .'.php'))
				throw new Exception('CONTROLLER_NOT_FOUND');
				
			require_once 'controllers/' . $ctrl .'.php';
			if(!method_exists($ctrl, $action))
				throw new Exception('ACTION_NOT_FOUND');
			$c = new $ctrl();
			if($c->preAction()){
				$c->$action();
				$c->postAction();
			}
		}
		catch(Exception $e){
			switch($e->getMessage()){
				case 'CONTROLLER_NOT_FOUND':
					$content = tr('CONTROLLER NOT FOUND') . ' : ' . $ctrl;
					break;
				case 'ACTION_NOT_FOUND':
					$content = tr('ACTION NOT FOUND') . ' : ' . $ctrl . '::' . $action;
					break;
				default:
					$content = tr('UNKNOWN ERROR');
					break;
			}
			$c = new Controller();
			$c->error($content);
		}
	}


}
