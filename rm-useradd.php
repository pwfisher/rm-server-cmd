#!/usr/bin/php
<?

$version = '0.1.1';

include(dirname(__FILE__).'/utils.d/init.php');

$params = CommandLine::parseArgs($_SERVER['argv']);

$verbose = isset($params['v']);

if(isset($params['version'])){
	throw new version($version);
}

if(isset($params['help'])){
	out('Использование: '.UTIL.' USER');
	out('Устанавливает владельца \(chown\) на домашнюю директорию пользователя USER.');
	out('Параметры:');
	out(' USER      - логин пользователя, который будет создан.');
	out(' --help    - показать эту справку.');
	out(' --version - показать версию утилиты.');
	out(' -v        - дебаг.');
	return 0;
}

if(!isset($params[0])){
	throw new error('Пользователь не указан.');
}


$login = $params[0];

$users = array();
$passwd = file('/etc/passwd');
foreach($passwd as $row){
	$user = explode(':',$row);
	$users[] = $user[0];
}

if(in_array($login, $users)){
	throw new error("Пользователь {$login} уже существует.");
}

$home = "/home/{$login}";

if($verbose){
	out("Создание группы {$login}.", 'black', UTIL);
}
system("sudo groupadd {$login}");

if($verbose){
	out("Создание пользователя {$login}.", 'black', UTIL);
}
system("sudo useradd --create-home --home-dir {$home} --shell /bin/bash --gid www-data --groups {$login} {$login}");

if($verbose){
	out("Подготовка домашенго каталога [{$home}].", 'black', UTIL);
}
system("sudo rm {$home}/.profile");
system("sudo cp ~/.bash_profile {$home}/.bash_profile");
system("rm-home-chmod {$login}");
system("sudo passwd {$login}");

out("Пользователь {$login} успешно создан.", 'green', UTIL);

return endCommand();