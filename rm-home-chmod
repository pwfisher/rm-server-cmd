#!/usr/bin/php
<?

include(dirname(__FILE__).'/utils.d/init.php');

$params = CommandLine::parseArgs($_SERVER['argv']);

if(!isset($params[0])){
	out('Unknown user.', 'red', UTIL);
}else{
	
	$login = $params[0];
	$path = '/home/'.$login;
	
	system("rm-chown --path={$path} --user={$login}");
	system("rm-chmod --path={$path}");
	
	out("Set chmod and chown on path {$path} end.", 'black', UTIL);
	
}

