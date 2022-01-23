#!/usr/bin/php
<?php
	include "func.php";
	include "conf.php";
	include "http.php";
	
	// PHP 버전 7.* 아니면 메세지 출력하고 종료
	if (!checkPHPVersion()) {
		consoleError("PHP version 7.* is required.", true); 
	}
	
	// 소켓 만들고 바인딩
	$httpSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	socket_bind($httpSocket, $_SERVERCONF["SERVER_NAME"], $_SERVERCONF["PORT"]);
	// 소켓 에러 발생하면 메세지 출력하고 종료
	if (socket_last_error()) {
		consoleError("Failed to listening port ".$_SERVERCONF["PORT"], true); 
	}
	
	// 설정된 포트로 리스닝 시작
	socket_listen($httpSocket);
	// 시그널 핸들러 생성
	pcntl_signal(SIGCHLD, SIG_IGN);
	
	consoleMessage("Starting simserver v0.2 ...");
	consoleMessage("Current time ".getCurrentTime());
	consoleMessage("Listening ".$_SERVERCONF["SERVER_NAME"].":".$_SERVERCONF["PORT"]);
	consoleMessage(chr(13).chr(10));
	
	// 클라이언트가 접속한 경우
	while ($clientSocket = socket_accept($httpSocket)) {
		socket_getpeername($clientSocket, $clientName, $clientPort);
		consoleMessage(getCurrentTime().chr(13).chr(10)." Client ".$clientName." connected...");
		
		// 자식 프로세스 생성, 실패하면 메세지 출력
		$processPid = pcntl_fork();
		if ($processPid == -1) {
			consoleError("Failed to create sub process for connection", false); 
		
		// 요청 처리는 자식 프로세스에 할당
		} else if ($processPid == 0) {
			
			// 소켓 읽어와서 요청 헤더 정보 저장
			$requestHeader = socket_read($clientSocket, 4096);
			$_HEADER = getRequestHeader($requestHeader);
			$_HEADER["CLIENT_NAME"] = $clientName;
			
			// 응답 헤더 설정
			$httpStatusCode = setStatusCode($_HEADER, $_SERVERCONF);
			$responseHeader = setResponseHeader($_HEADER, $_SERVERCONF, $httpStatusCode);
			
			// 응답 헤더 전송, 실패하면 메세지 출력
			$responseSent = socket_write($clientSocket, $responseHeader);
			consoleMessage($_HEADER["METHOD"]." ".$_HEADER["URI"]." ".$httpStatusCode);
			consoleMessage("Sending response header to client...");
			if (!$responseSent) {
				consoleError("Failed to send response header", false);
			}
			
			// 로그 입력
			putConnectionLog($_HEADER, $_SERVERCONF["LOG_DIR"]);
		}
		
		// 접속 종료
		socket_close($clientSocket);
		consoleMessage("Connection closed");
	}