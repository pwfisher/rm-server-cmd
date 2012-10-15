<?

define('NL', "\n");

$_utilName = basename($_SERVER['SCRIPT_FILENAME']);
define('UTIL',$_utilName);

include(dirname(__FILE__).'/CColor.php');
include(dirname(__FILE__).'/CCommandLine.php');

function out($str, $color='std', $util=false){
	$util = empty($util) ? '' : $util.': ';
	system ('echo '.CColor::$c['black'].$util.CColor::$c[$color].$str.CColor::$c['std'].NL);
}