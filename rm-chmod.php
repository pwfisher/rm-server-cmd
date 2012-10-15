#!/usr/bin/php
<?

include(dirname(__FILE__).'/utils.d/init.php');

$params = CommandLine::parseArgs($_SERVER['argv']);

if(isset($params['v'])){
	showVersion('0.1.1', UTIL);
	return ;
}

if(!isset($params['path'])){
	out('Unknown path.', 'red', UTIL);
}else{
	
	$path = $params['path'];
	
	$mode_f = isset($params['files']) ? $params['files'] : '0664';
	$mode_d = isset($params['dirs']) ? $params['dirs'] : '0775';
	
	if(!file_exists($path) or !is_dir($path)){
		out('Dir '.$path.' not found.', 'red', UTIL);
	}else{
		exec("sudo find {$path} -type d",$dirs);
		exec("sudo find {$path} -type f",$files);
		exec("sudo find {$path} -type f -executable",$executable);
		
		foreach($dirs as $dir){
			$mode = isset($system[$dir]) ? $system[$dir] : $mode_d;
			system("sudo chmod {$mode} {$dir}");
		}
		foreach($files as $file){
			$mode = isset($system[$dir]) ? $system[$dir] : $mode_f;
			system("sudo chmod {$mode} {$file}");
		}
		foreach($executable as $file){
			system("sudo chmod +x {$file}");
		}
		out('Permissions of directory '.$path.' been installed.', 'black', UTIL);
	}
	
}

