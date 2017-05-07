<?php error_reporting(E_ALL ^ E_NOTICE);

$exit_vote_time = strtotime(date('2015-04-18 23:59:59'));

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

$goTo = '';
switch ($_SERVER['REQUEST_URI']) {
	case '/photos/60':
	case '/photos/60/':
		$goTo = '/photos/gromadsketv/';
		break;

	case '/photos/61':
	case '/photos/61/':
		$goTo = '/photos/patrick_wolf/';
		break;

	case '/photos/23':
	case '/photos/23/':
		$goTo = '/photos/otorvald/';
		break;

	case '/photos/22':
	case '/photos/22/':
		$goTo = '/photos/ochildren/';
		break;
	case '/photos/21':
	case '/photos/21/':
		$goTo = '/photos/brainstorm/';
		break;

}

if (GetRealIp() == '178.216.9.12') {
	error_reporting(E_ALL ^ E_NOTICE);
	defined('YII_DEBUG') or define('YII_DEBUG',true);
	defined('ADMIN') or define('ADMIN',true);
	defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
	defined('START_TIME') or define('START_TIME', microtime(true));
} else {
	error_reporting(0);
	defined('ADMIN') or define('ADMIN',false);
}

if ($goTo != '') {
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: " . $goTo);
	exit;
}

// change the following paths if necessary
$yii = dirname(__FILE__) . '/framework/yii.php';
$config = dirname(__FILE__) . '/protected/config/frontend.php';

require_once($yii);
Yii::createWebApplication($config)->runEnd('frontend');
