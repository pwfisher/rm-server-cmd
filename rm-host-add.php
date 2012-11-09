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
	return help('Использование rm-host-add [OPTION] DOMAIN --php=TYPE
Создает настройки для домена DOMAIN.

Параметры:
      --php=TYPE - тип интерпретатора php. Варианты: apache, fpm
  -v, --verbose   - более подробный вывод.
  -h, --help      - показать эту справку.
      --version   - показать версию утилиты.');
}

if(!isset($params[0])){
	return error('Домен нового сайта не указан.');
}

if(!isset($params['php'])){
	return error('Не указан параметр --php=TYPE.');
}

$domain = $params[0];
$domainEscape = str_replace('.','-',$domain);
$php = $params['php'];

if(!in_array($php, array('apache','fpm'))){
	return error('Неверное значение параметра --php=TYPE.');
}

$pathSourse = '/var/www';
$pathHosts = '/var/www/hosts.d';

if(!file_exists($pathSourse) || !is_dir($pathSourse)){
	return error("Директория с сайтами [{$pathSourse}] не существует.");
}elseif(!is_writable($pathSourse)){
	return error("Директория с сайтами [{$pathSourse}] недоступна для записи.");
}

if(!file_exists($pathHosts) || !is_dir($pathHosts)){
	return error("Директория с конфигами сайтов [{$pathHosts}] не существует.");
}elseif(!is_writable($pathHosts)){
	return error("Директория с конфигами сайтов [{$pathHosts}] недоступна для записи.");
}

$nginxConfigPath = $pathHosts.'/'.$domainEscape.'.nginx.conf';
$apacheConfigPath = $pathHosts.'/'.$domainEscape.'.apache.conf';

if(file_exists($nginxConfigPath)){
	return error("Конфиг [{$nginxConfigPath}] уже существует.");
}

if(file_exists($apacheConfigPath)){
	return error("Конфиг [{$apacheConfigPath}] уже существует.");
}

$nginxConfig = file_get_contents(dirname(__FILE__).'/data.d/config.nginx.'.$php.'.scelet');
$nginxConfig = str_replace('[DOMAIN]', $domain, $nginxConfig);
file_put_contents($nginxConfigPath, $nginxConfig);
system("sudo chmod 0660 {$nginxConfigPath}; sudo chown www-data:www-data {$nginxConfigPath}");

$apacheConfig = file_get_contents(dirname(__FILE__).'/data.d/config.apache.scelet');
$apacheConfig = str_replace('[DOMAIN]', $domain, $apacheConfig);
file_put_contents($apacheConfigPath, $apacheConfig);
system("sudo chmod 0660 {$apacheConfigPath}; sudo chown www-data:www-data {$apacheConfigPath}");

$pathDomain = $pathSourse.'/'.$domain;

mkdir($pathDomain);
mkdir($pathDomain.'/www');

system('sudo chown -R www-data:www-data '.$pathDomain);

system('sudo service nginx restart');
system('sudo service apache2 restart');

out("Хост успешно создан.", 'green', UTIL);

return endCommand();