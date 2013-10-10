<?php
define('START_TIME', microtime(true));
define('START_MEMORY_USAGE', memory_get_usage());
define("APP_PATH",dirname(__FILE__));
define("SP_PATH",dirname(__FILE__).'/SpeedPHP');
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);
define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

require(APP_PATH."/config.php");
require(APP_PATH."/version.php");
require(SP_PATH."/SpeedPHP.php");
import(APP_PATH.'/controller/basecontroller.php');
import(APP_PATH.'/controller/baseuser.php');
import(APP_PATH.'/include/function_global.php');
import(APP_PATH.'/include/function_file.php');
spRun();