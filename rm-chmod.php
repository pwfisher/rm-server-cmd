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
	return help('Использование rm-chmod [OPTION] --path=PATH [--files=MODS --dirs=MODS]
Устанавливает права доступа (chmod) на директорию PATH и все файлы внутри.

Параметры:
      --path=PATH  - путь к директории, на которую необходимо установить
                     права доступа (chmod).
      --files=MODS - маска прав доступа, которые необходимо установить на файлы.
      --dirs=MODS  - маска прав доступа, которые необходимо установить на
                     директории.
  -v, --verbose    - более подробный вывод.
  -h, --help       - показать эту справку.
      --version    - показать версию утилиты.');
}

$aIgnorePaths = array('*.git*','*.svn*','*.ssh*','*.cache*');
$sIgnorePaths = '';
foreach($aIgnorePaths as $pattern){
	$sIgnorePaths .= ' -not -path "'.$pattern.'"';
}

if(!isset($params['path'])){
	return error('Не указан параметр --path=PATH');
}

$path = checkValidPath($params['path']);

$mode_f = isset($params['files']) ? $params['files'] : '0664';
$mode_d = isset($params['dirs']) ? $params['dirs'] : '0775';

exec("sudo find {$path} -type d {$sIgnorePaths}",$dirs);
exec("sudo find {$path} -type f {$sIgnorePaths}",$files);
exec("sudo find {$path} -type f {$sIgnorePaths} -executable",$executable);

foreach($dirs as $dir){
	$mode = isset($system[$dir]) ? $system[$dir] : $mode_d;
	system("sudo chmod {$mode} {$dir}");
}
foreach($files as $file){
	$mode = isset($system[$dir]) ? $system[$dir] : $mode_f;
	system("sudo chmod {$mode} {$file}");
}
foreach($executable as $file){
	system("sudo chmod u+x {$file}");
}

if($verbose){
	$mes = 'Найдено '
		.declOfNum(count($dirs),array('директория','директории','директорий')).' и '
				.declOfNum(count($files),array('файл','файла','файлов'));
	out($mes, 'black', UTIL);
}

out("Права доступа \(chmod\) для директории [{$path}] \({$mode_f},{$mode_d}\) успешно установлены.", 'black', UTIL);

return endCommand();
