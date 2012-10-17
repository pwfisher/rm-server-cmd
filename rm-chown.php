#!/usr/bin/php
<?

$version = '0.1.0';

include(dirname(__FILE__).'/utils.d/init.php');

$params = CommandLine::parseArgs($_SERVER['argv']);

$verbose = isset($params['v']) || isset($params['verbose']);

if(isset($params['version'])){
	return version($version, $verbose);
}

if(isset($params['help']) || isset($params['h'])){
	return help('Использование rm-chown [OPTION] --path=PATH --user=USER [--group=GROUP]
Устанавливает владельца USER (chown) на директорию PATH и все файлы внутри.

Параметры:
      --path=PATH   - путь к директории, на которую необходимо установить 
                    владельца (chown).
      --user=USER   - логин пользователя, чья домашняя директория будет
                      обработанна.
      --group=GROUP - группа пользователя, который будет назначен владельцем
                      (по умолчанию GROUP равен USER).
  -v, --verbose     - более подробный вывод.
  -h, --help        - показать эту справку.
      --version     - показать версию утилиты.');
}

if(!isset($params['path'])){
	return error('Не указан параметр --path=PATH');
}

if(!isset($params['user'])){
	return error('Не указан параметр --user=USER');
}

$path = checkValidPath($params['path']);

$login = $params['user'];
$group = isset($params['group']) ? $params['group'] : $login;
$recursive = (isset($params['r']) and $params['r'] === true) ? '-R' : '';

$type = is_dir($path) ? 'директории' : 'файла';

system("sudo chown {$recursive} {$login}:{$group} {$path}");
out("Владелец для {$type} [{$path}] {$recursive} \({$login}:{$group}\) успешно установлен.", 'black', UTIL);

return endCommand();