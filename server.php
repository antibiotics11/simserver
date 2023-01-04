#!/usr/bin/php
<?php

include_once __DIR__."/HTTP/InetAddress.class.php";
include_once __DIR__."/HTTP/SocketServer.class.php";
include_once __DIR__."/HTTP/Message.class.php";
include_once __DIR__."/HTTP/Status.class.php";
include_once __DIR__."/HTTP/Request.class.php";
include_once __DIR__."/HTTP/Response.class.php";
include_once __DIR__."/HTTP/StaticResource.class.php";
include_once __DIR__."/HTTP/Logger.class.php";
include_once __DIR__."/HTTP/SocketIOException.class.php";


cli_set_process_title("simserver");
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

if (PHP_MAJOR_VERSION < 8) {
	\HTTP\Logger::printLog("PHP 8.0 or higher is required.", true);
	exit(0);
}

if (strlen($e = extensionsAllLoaded()) !== 0) {
	\HTTP\Logger::printLog($e." extension not loaded.", true);
	exit(0);
}
	
$config = trim(file_get_contents("config.json"));

try {
	$socketServer = new \HTTP\SocketServer($config);
	
	\HTTP\Logger::printLog(
		"Starting server at "
		.$socketServer->getServerName()->getAddress().":"
		.$socketServer->getListeningPort().".",
		false
	);
		
	$socketServer->listen();

} catch (Throwable $e) {
	\HTTP\Logger::printLog("Internal Server Error: ".$e->getMessage(), true);
} catch (\HTTP\SocketIOException $e) {
	\HTTP\Logger::printLog("Socket IO Error: ".$e->getMessage(), true);
}

function extensionsAllLoaded(): String {
	
	$extensions = [ "sockets", "pcntl" ];
	foreach ($extensions as $e) {
		if (!extension_loaded($e)) {
			return $e;
		}
	}

	return "";

}
