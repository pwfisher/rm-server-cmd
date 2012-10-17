#!/usr/bin/php
<?

$version = '0.1.0';

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
	out(' USER      - логин пользователя, который будет назначен владельцем.');
	out(' --help    - показать эту справку.');
	out(' --version - показать версию утилиты.');
	out(' -v        - дебаг.');
	return 0;
}

if(!isset($params[0])){
	throw new error('Пользователь не указан.');
}

$login = $params[0];
$path = '/home/'.$login;

if($verbose){
	out("Установка права владения для директории [{$path}].", 'black', UTIL);
	system("rm-chown -v --path={$path} --user={$login}");
}else{
	system("rm-chown --path={$path} --user={$login}");
}

if($verbose){
	out("Установка права доступа для директории [{$path}].", 'black', UTIL);
	system("rm-chmod -v --path={$path} --files=0644 --dirs=0755");
}else{
	system("rm-chmod --path={$path} --files=0644 --dirs=0755");
}

out("Права доступа и права владения для директории [{$path}] успешно установлены.", 'black', UTIL);
