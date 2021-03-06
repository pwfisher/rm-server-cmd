<?php 

define('NL', "\n");
define('T', "\t");

define('VERSION', '0.0.6');

$_utilName = basename($_SERVER['SCRIPT_FILENAME']);
define('UTIL',$_utilName);

include(dirname(__FILE__).'/inc.classes.php');

exec('whoami', $res); $user = $res[0];
if(empty($user)){
	throw new error('Не удалось определить пользователя.');
}

define('USER', $user);

function out($str, $color='std', $util=false){
	$util = empty($util) ? '' : $util.': ';
	system ('echo '.CColor::$c['black'].$util.CColor::$c[$color].$str.CColor::$c['std'].NL);
}

function checkValidPath($path, $isFile=false){
	$rpath = realpath($path);
	if(empty($path)){
		throw new Exception("Файл или директория не указана.");
	}elseif(!file_exists($rpath)){
		throw new Exception("Файл или директория [{$path}] не найдена.");
	}
	return $rpath;
}

function declOfNum($number, $titles) {
    $cases = array (2, 0, 1, 1, 1, 2);
    return $number." ".$titles[ ($number%100>4 && $number%100<20)? 2 : $cases[min($number%10, 5)] ];
}
