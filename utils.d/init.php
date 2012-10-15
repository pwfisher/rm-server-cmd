<?

define('NL', "\n");
define('T', "\t");

$_utilName = basename($_SERVER['SCRIPT_FILENAME']);
define('UTIL',$_utilName);

define('VERSION', '0.0.1');

include(dirname(__FILE__).'/CColor.php');
include(dirname(__FILE__).'/CCommandLine.php');

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
	}elseif(!is_writable($rpath)){
		throw new Exception("Файл или директория [{$path}] не доступна для записи.");
	}
	return $rpath;
}

function declOfNum($number, $titles) {
    $cases = array (2, 0, 1, 1, 1, 2);
    return $number." ".$titles[ ($number%100>4 && $number%100<20)? 2 : $cases[min($number%10, 5)] ];
}

set_exception_handler(function($exception){
	out($exception->getMessage(), 'red', UTIL);
	out('--help для вызова помощи.', 'black', UTIL);
});

class error extends Exception{}
class version extends Exception{}