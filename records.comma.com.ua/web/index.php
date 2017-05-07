<?php
error_reporting(0);

header('P3P: CP="NOI ADM DEV COM NAV OUR STP"');

define( "FV_PROFILE", isset( $_GET['profiler'] ) );
define( "FV_PRODUCTION", FALSE );

if( FV_PROFILE ){
    $startTime = microtime(true);
}

if( ! preg_match("/\\/web$/", $_SERVER['DOCUMENT_ROOT']) ){
    $_SERVER['DOCUMENT_ROOT'] .= "/web";
}

require_once("../data/bootstrap.php");
fvResponse::getInstance()->setPragma(true);

$dispatcher = new fvDispatcher();
$response = $dispatcher->dispatch();
$response->send();

if( FV_PROFILE && ! fvRequest::getInstance()->isXmlHttpRequest() ){
    Profile::startTime( $startTime );
    Profile::show();
}
