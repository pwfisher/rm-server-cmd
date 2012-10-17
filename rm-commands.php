#!/usr/bin/php
<?

$version = '0.0.1';

include(dirname(__FILE__).'/utils.d/init.php');

$params = CommandLine::parseArgs($_SERVER['argv']);

$verbose = isset($params['v']) || isset($params['verbose']);

if(isset($params['version'])){
	return version($version, $verbose);
}

if(isset($params['help']) || isset($params['h'])){
	return help('Использование rm-commands [OPTION]
Выводит на экран список комманд с краткой информацией.

Параметры:
  -v, --verbose   - более подробный вывод.
  -h, --help      - показать эту справку.
      --version   - показать версию утилиты.');
}

return help('Комманды пакета rm-server-cmd.
rm-build         - установка и обновление комманд.
rm-version       - показать версию пакета.
rm-commands      - показать список доступных комманд.
rm-user-add      - добавление пользователя и настройка группы.
rm-home-chmod    - установить права доступа и владения на домашнюю директорию 
                   полдьзователя.
rm-find-in-files - поиск подстроки в файлах. 
rm-host-add      - добавляет конфиги apache и nginx и перезагружает сервер.
rm-chmod         - устанавливает права доступа на файлы и директории.
rm-chown         - устанавливает права владения на файлы и директории.');