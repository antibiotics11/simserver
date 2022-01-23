<?php
	
	// PHP 버전이 7.*인지 확인
	function checkPHPVersion() {
		$getPHPVersion = explode(".",phpversion());
		if ((int)$getPHPVersion[0] == 7) {
			return true;
		}
		return false;
	}
	
	// UTC 기준 현재 시간을 문자열로 반환
	function getCurrentTime() {
		date_default_timezone_set("UTC"); 
		$currentTime = "UTC ".date("Y-m-d H:i:s", time());
		return $currentTime;
	}
	
	// 콘솔 메세지 출력
	function consoleMessage($message) {
		echo "\033[1;34m ".$message." \033[0m".chr(13).chr(10);
	}
	
	// 콘솔 에러 출력
	function consoleError($message, $exit) {
		echo "\033[1;31m Error: ".$message." \033[0m".chr(13).chr(10);
		if ($exit) {
			exit(0);
		}
		return;
	}
	
	// HTTP 로그 추가
	function putConnectionLog($_HEADER, $logDir) {
		$logContents = getCurrentTime().chr(13).chr(10);
		$logContents .= $_HEADER["CLIENT_NAME"]." ".$_HEADER["METHOD"]." ".$_HEADER["URI"]." ".$_HEADER["PROTOCOL"].chr(13).chr(10);
		$logContents .= $_HEADER["User-Agent"].chr(13).chr(10);
		if (!file_exists($logDir."/http.simserver.log")) {
			file_put_contents($logDir."/http.simserver.log", $logContents);
		} else {
			$fileOpen = fopen($logDir."/http.simserver.log", "a+");
			fwrite($fileOpen, $logContents);
			fclose($fileOpen);
		}
		return;
	}