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
	return help('Использование rm-find-in-files [OPTION] PATH PATTERN
Ищет вхождения PATTERN во всех файлах директории PATH.

Опции:
  -v, --verbose     - более подробный вывод.
  -h, --help        - показать эту справку.
      --version     - показать версию утилиты.');
}

if(!isset($params[0])){
	return error('Не указан путь для поиска.');
}

if(!isset($params[1])){
	return error('Не указан паттерн для поиска.');
}

$path = checkValidPath($params[0]);
$pattern = addslashes($params[1]);
$contextNum = (int)$params['context'];

out("Файлы, в которых встречается вхождение '{$pattern}':", 'yellow', UTIL);
system("sudo find {$path} -type f | xargs grep '{$pattern}' -l");

out("Поиск завершен.", 'green', UTIL);

return endCommand();
