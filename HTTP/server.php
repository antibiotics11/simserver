#!/usr/bin/php
<?php
	
	@error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING ^ E_STRICT);
	
	define("_VERSION", "0.3");
	include_once "func.php";
	include_once "config.php";
	
	// 서버 설정이 올바르지 않으면 종료
	if (!configValid($_SERVERCONF)) {
		consoleError("Failed to start server. One or more configurations are not valid.", true);
	}
	// PHP 버전이 8.* 아니거나 pcntl 확장 없으면 아니면 종료
	if (PHP_MAJOR_VERSION != (int)8 || !function_exists("pcntl_fork")) {
		consoleError("Unsupported PHP version. PHP 8.0 with pcntl extension required.", true);
	}

	include_once "http.php";
	$http = new HTTP($_SERVERCONF);
	
	// 소켓 만들고 설정된 포트로 리스닝
	$httpSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	socket_bind($httpSocket, $_SERVERCONF["SERVER_NAME"], $_SERVERCONF["PORT"]);
	if (socket_last_error()) {
		consoleError("Failed to listening port ".$_SERVERCONF["PORT"], true); 
	}
	socket_listen($httpSocket);
	pcntl_signal(SIGCHLD, SIG_IGN);
	consoleMessage("[".getCurrentTime()."] Listening ".$_SERVERCONF["SERVER_NAME"].":".$_SERVERCONF["PORT"]);

	while ($clientSocket = socket_accept($httpSocket)) {
		socket_getpeername($clientSocket, $clientName, $clientPort);
		consoleMessage("[".getCurrentTime()."] Client ".$clientName." connected...");
		
		// 프로세스 생성, 실패하면 메세지 출력
		$processPid = pcntl_fork();
		if ($processPid == -1) {
			consoleError("Failed to create sub process for connection", false); 
			
		// 요청 처리는 하위 프로세스에 할당
		} else if ($processPid == 0) {
			$requestHeader = socket_read($clientSocket, 4096);
			$http->getRequestHeader($requestHeader);
			$http->_HEADER["CLIENT_NAME"] = $clientName;
			$httpStatusCode = $http->setStatusCode();
			$responseHeader = $http->setResponseHeader($httpStatusCode);
			$responseSent = socket_write($clientSocket, $responseHeader);
			socket_close($clientSocket);
			$http->putConnectionLog();
			
			if ($responseSent) {
				consoleMessage("[".getCurrentTime()."] ".$http->_HEADER["METHOD"].chr(32).$http->_HEADER["URI"].chr(32).$httpStatusCode);
			} else {
				consoleError("Failed to send response header.", false);
			}
			
			consoleMessage("[".getCurrentTime()."] Connection closed.");
		}
	}
	