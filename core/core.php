<?php

require_once 'db.php';
require_once 'tr.php';

class core{
	public static $ppath='';
	public static $vpath='';

	public static function init(){
		self::$ppath = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..');
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
}