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
	return help('Использование rm-home-chmod [OPTION] [--user=USER]
Устанавливает владельца (chown) и права доступа (chmod) на домашнюю директорию
пользователя USER. Если USER не указан, используется текущий ({$user}).

Внимание! Для пользователя root утилита недоступна.

Параметры:
      --user=USER - логин пользователя, чья домашняя директория будет обработанна.
  -v, --verbose   - более подробный вывод.
  -h, --help      - показать эту справку.
      --version   - показать версию утилиты.');
}

if(!isset($params[0])){
	$params[0] = USER;
}

$login = $params[0];
$path = '/home/'.$login;

if($login === 'root'){
	return error('Невозможно провести операцию для пользователя root.');
}

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

out("Права доступа и права владения для директории [{$path}] успешно установлены.", 'green', UTIL);

return endCommand();