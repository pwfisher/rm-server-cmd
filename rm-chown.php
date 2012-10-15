#!/usr/bin/php
<?

include(dirname(__FILE__).'/utils.d/init.php');

$params = CommandLine::parseArgs($_SERVER['argv']);

if(!isset($params['path'])){
	out('Unknown path.', 'red', UTIL);
}else{
	
	$path = realpath($params['path']);
	
	if(!isset($params['user'])){
		out('Unknown user.', 'red', UTIL);
	}else{
		
		$login = $params['user'];
		$group = isset($params['group']) ? $params['group'] : $login;
		$recursive = @$params['r'] === true ? '-R' : '';

		if(!file_exists($path) or !is_dir($path)){
			out('Dir '.$path.' not found.', 'red', UTIL);
		}else{
			system("sudo chown {$recursive} {$login}:{$group} {$path}");
			out("Ownership of directory {$path} {$recursive} [{$login}:{$group}] been installed.", 'black', UTIL);
		}
		
	}
	
}

