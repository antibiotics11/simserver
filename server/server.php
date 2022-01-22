#!/usr/bin/php
<?php

	//ini_set("display_errors", "0");
	
	include "func.php";
	include "conf.php";
	include "http.php";
	
	if (!checkPHPVersion()) {
		echo "\033[1;31m >> Fatal Error: PHP version 7.0 or higher is requierd. \033[0m\n";
		exit(0);
	}
	
	$http = new httpServer($_SERVERCONF["SERVER_NAME"], $_SERVERCONF["PORT"], $_SERVERCONF["DOC_ROOT"], $_SERVERCONF["DOC_INDEX"]);
	
	// 소켓 만들고 바인딩
	$httpSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	socket_bind($httpSocket, $http->getServerName(), $http->getListeningPort());
	
	// 소켓 에러 발생했으면 강제종료
	if (socket_last_error()) {
		echo "\033[1;31m >> Fatal Error: An unknown error occured creating socket. \033[0m\n";
		exit(0);
	}
	
	// 설정된 포트로 리스닝 시작
	socket_listen($httpSocket);
	
	echo "\033[1;34m >> Starting SimServer v0.1... \033[0m \n";
	echo "\033[1;34m >> Current time ".getCurrentTime()." \n";
	echo "\033[1;34m >> Listening ".$http->getServerName().":".$http->getListeningPort()." \033[0m \n";
	
	while ($clientSocket = socket_accept($httpSocket)) {
		$requestHeader = socket_read($clientSocket, 4096);
		socket_getpeername($clientSocket, $clientName, $clientPort);
		
		$_HEADER = $http->getRequestHeader($requestHeader);
		$_HEADER["CLIENT_NAME"] = $clientName;
		
		$connectionLog = putHttpLogFile($_HEADER, getCurrentTime(), $_SERVERCONF["LOG_DIR"]);
		echo "\n\033[1;34m >> Client Connected. \033[0m \n";
		//echo $connectionLog."\n";
		
		$httpStatusCode = $http->setStatusCode($_HEADER);
		$responseHeader = $http->setResponseHeader($httpStatusCode, $_HEADER);
		
		$responseSent = socket_write($clientSocket, $responseHeader);
		echo "\033[1;34m >> Sending response header to client... \033[0m \n";
		if (!$responseSent) {
			echo "\n\033[1;31m >> ERROR: Failed to send response header. \033[0m \n\n";
		}
		
		echo "\n\033[1;34m >> Connection closed. \033[0m \n\n";
	}
	
