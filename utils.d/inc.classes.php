<?php 

include(dirname(__FILE__).'/CColor.php');

set_exception_handler(function($exception){
	out($exception->getMessage(), 'red', UTIL);
	out('--help для вызова помощи.', 'black', UTIL);
});

function error($message){
	out($message, 'red', UTIL);
	out('--help для вызова помощи.', 'black', UTIL);
	return endCommand(1);
}

function version($version, $verbose=false){
	if($verbose){
		echo UTIL.' ';
	}
	echo $version.NL;
	return endCommand();
}

function help($text){
	echo $text.NL;
	return endCommand();
}

function endCommand($code=0){
	return $code;
}

include(dirname(__FILE__).'/CCommandLine.php');