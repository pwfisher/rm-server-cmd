#!/usr/bin/php
<?

include(dirname(__FILE__).'/utils.d/init.php');

$params = CommandLine::parseArgs($_SERVER['argv']);

if(!isset($params[0])){
	out(CColor::$c['red'].'Not name of new user.');
}else{
	$login = $params[0];
	
	$users = array();
	$passwd = file('/etc/passwd');
	foreach($passwd as $row){
		$user = explode(':',$row);
		$users[] = $user[0];
	}

	if(in_array($login, $users)){
		out(CColor::$c['yellow']."User {$login} already exists.");
	}else{
		$home = "/home/{$login}";

		system("sudo groupadd {$login}");
		system("sudo useradd -md {$home} -g www-data -G {$login} -p {$login} {$login}");
		//system("cp ~/bin/data/");
		system("sudo rm {$home}/.profile");
		system("sudo cp ~/.bash_profile {$home}/.bash_profile");
		system("sudo chown {$login}:{$login} {$home}/.bash_logout {$home}/.bashrc {$home}/.bash_profile");
		
	}
	
}

out(CColor::$c['black'].'End of operation.');