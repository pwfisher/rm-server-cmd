<?

define('NL', "\n");

$_utilName = basename($_SERVER['SCRIPT_FILENAME']);
define('UTIL',$_utilName);

define('VERSION', '0.0.1');

include(dirname(__FILE__).'/CColor.php');
include(dirname(__FILE__).'/CCommandLine.php');

function out($str, $color='std', $util=false){
	$util = empty($util) ? '' : $util.': ';
	system ('echo '.CColor::$c['black'].$util.CColor::$c[$color].$str.CColor::$c['std'].NL);
}

function checkPath($path){
	if(empty($path)){
		throw new Exception("Директория не указана.");
	}elseif(!file_exists(realpath($path))){
		throw new Exception("Директория [{$path}] не найдена.");
	}elseif(!is_writable(realpath($path))){
		throw new Exception("Директория [{$path}] не доступна для записи.");
	}
}

set_exception_handler(function($exception){
	out($exception->getMessage(), 'red', UTIL);
});

class error extends Exception{}
class version extends Exception{}