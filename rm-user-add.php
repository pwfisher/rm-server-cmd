#!/usr/bin/php
<?

$version = '0.1.1';

include(dirname(__FILE__).'/utils.d/init.php');

$params = CommandLine::parseArgs($_SERVER['argv']);

$verbose = isset($params['v']) || isset($params['verbose']);

if(isset($params['version'])){
	return version($version, $verbose);
}

if(isset($params['help']) || isset($params['h'])){
	return help('Использование rm-user-add [OPTION] USER
Создает пользователя USER с группой www-data.

Параметры:
  -v, --verbose   - более подробный вывод.
  -h, --help      - показать эту справку.
      --version   - показать версию утилиты.');
}

if(!isset($params[0])){
	throw new error('Не указан логин нового пользователя.');
}

$login = $params[0];

$users = array();
$passwd = file('/etc/passwd');
foreach($passwd as $row){
	$user = explode(':',$row);
	$users[] = $user[0];
}

if(in_array($login, $users)){
	return error("Пользователь {$login} уже существует.");
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