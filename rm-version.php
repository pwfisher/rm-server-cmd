#!/usr/bin/php
<?

$version = '0.0.3';

include(dirname(__FILE__).'/utils.d/init.php');

$params = CommandLine::parseArgs($_SERVER['argv']);

$verbose = isset($params['v']) || isset($params['verbose']);

if(isset($params['version'])){
	return version($version, $verbose);
}

if(isset($params['help']) || isset($params['h'])){
	return help('Использование rm-version [OPTION]
Выводит версию пакета.

Параметры:
  -v, --verbose   - более подробный вывод.
  -h, --help      - показать эту справку.
      --version   - показать версию утилиты.');
}

return version(VERSION, $verbose);