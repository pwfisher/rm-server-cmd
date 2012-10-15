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
	out('Использование: '.UTIL.' --path=PATH [--files=MODS --dirs=MODS]');
	out('Устанавливает права доступа (chmod) на директорию PATH и все файлы внутри.');
	out('Параметры:');
	out(' --path=PATH  - путь к директории, на которую необходимо установить права доступа \(chmod\).');
	out(' --files=MODS - маска прав доступа, которые необходимо установить на файлы.');
	out(' --dirs=MODS  - маска прав доступа, которые необходимо установить на директории.');
	out(' --help       - показать эту справку.');
	out(' --version    - показать версию утилиты.');
	out(' -v           - дебаг.');
	return 0;
}

if(!isset($params['path'])){
	throw new error('Не указан --path=PATH');
}

$path = checkValidPath($params['path']);

$mode_f = isset($params['files']) ? $params['files'] : '0664';
$mode_d = isset($params['dirs']) ? $params['dirs'] : '0775';

exec("sudo find {$path} -type d",$dirs);
exec("sudo find {$path} -type f",$files);
exec("sudo find {$path} -type f -executable",$executable);

foreach($dirs as $dir){
	$mode = isset($system[$dir]) ? $system[$dir] : $mode_d;
	system("sudo chmod {$mode} {$dir}");
}
foreach($files as $file){
	$mode = isset($system[$dir]) ? $system[$dir] : $mode_f;
	system("sudo chmod {$mode} {$file}");
}
foreach($executable as $file){
	system("sudo chmod +x {$file}");
}

if($verbose){
	$mes = 'Найдено '
		.declOfNum(count($dirs),array('директория','директории','директорий')).' и '
				.declOfNum(count($files),array('файл','файла','файлов'));
	out($mes, 'black', UTIL);
}

out("Права доступа \(chmod\) для директории [{$path}] \({$mode_f},{$mode_d}\) успешно установлены.", 'black', UTIL);

