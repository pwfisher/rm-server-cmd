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
	out('Использование: '.UTIL.' --path=PATH --user=USER [--group=GROUP]');
	out('Устанавливает владельца \(chown\) на директорию PATH и все файлы внутри.');
	out('Параметры:');
	out(' --path=PATH   - путь к директории, на которую необходимо установить владельца \(chown\).');
	out(' --user=USER   - логин пользователя, который будет назначен владельцем.');
	out(' --group=GROUP - группа пользователя, который будет назначен владельцем \(по умолчанию GROUP равен USER\).');
	out(' -r            - рекурсивно для дочерних элементов.');
	out(' --help        - показать эту справку.');
	out(' --version     - показать версию утилиты.');
	out(' -v            - дебаг.');
	return 0;
}

if(!isset($params['path'])){
	throw new error('Не указан --path=PATH');
}

if(!isset($params['user'])){
	throw new error('Не указан --user=USER');
}

$path = checkValidPath($params['path']);

$login = $params['user'];
$group = isset($params['group']) ? $params['group'] : $login;
$recursive = (isset($params['r']) and $params['r'] === true) ? '-R' : '';

$type = is_dir($path) ? 'директории' : 'файла';

system("sudo chown {$recursive} {$login}:{$group} {$path}");
out("Владелец для {$type} [{$path}] {$recursive} \({$login}:{$group}\) успешно установлен.", 'black', UTIL);
