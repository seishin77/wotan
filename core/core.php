<?php

class core{
	public static $ppath = '';
	public static $vpath = '';
	public static $flash = array('nbflash' => 0,
																'success' => array(),
																'info' => array(),
																'warning' => array(),
																'danger' => array(),
															);

	public static function init(){
		self::$ppath = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..') .
							DIRECTORY_SEPARATOR;
		self::$vpath = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
	}

	public static function getPPath(){
		return self::$ppath;
	}

	public static function getVPath(){
		return self::$vpath;
	}

	public static function vpath($p){
		return self::getVPath() . $p;
	}
	
	public static function path($p){
		return self::getPPath() . $p;
	}
	
	public static function basedir($p){
		return DIRECTORY_SEPARATOR . str_replace(self::getPPath(), '', $p);
	}

	public static function getFlash(){
		return self::$flash;
	}

	public static function resetFlash(){
		self::$flash = array('nbflash' => 0,
													'success' => array(),
													'info' => array(),
													'warning' => array(),
													'danger' => array(),
													);
	}

	public static function addFlash($txt, $level='info'){
		self::$flash['nbflash']++;
		self::$flash[$level][] = $txt;
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
}
