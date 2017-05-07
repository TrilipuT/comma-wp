<?php error_reporting(E_ALL ^ E_NOTICE);

function GetRealIp() {
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	return $ip;
}

ini_set("display_errors", 1);
ini_set("track_errors", 1);
ini_set("html_errors", 1);

// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/backend.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
//Yii::createWebApplication($config)->run();
// стартуем приложение с помощью нашего WebApplicaitonEndBehavior, указав ему, что нужно загрузить бекэнд
Yii::createWebApplication($config)->runEnd('backend');