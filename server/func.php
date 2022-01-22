<?php
	
	/* PHP 버전이 7.0 이상인지 확인 */
	function checkPHPVersion() {
		$getPHPVersion = explode(".",phpversion());
		if ((int)$getPHPVersion[0] >= 7) {
			return true;
		}
		return false;
	}
	
	/* UTC 기준 현재 시간을 문자열로 반환 */
	function getCurrentTime() {
		date_default_timezone_set("UTC"); 
		$currentTime = "UTC ".date("Y-m-d H:i:s", time());
		return $currentTime;
	}
	
	/* HTTP 로그 추가 */
	function putHttpLogFile($_HEADER, $currentTime, $logDirectory) {
		$logContents = $currentTime.chr(13).chr(10);
		$logContents .= (string)$_HEADER["CLIENT_NAME"]." ".$_HEADER["METHOD"]." ".$_HEADER["URI"]." ".$_HEADER["PROTOCOL"].chr(13).chr(10);
		$logContents .= $_HEADER["User-Agent"].chr(13).chr(10).chr(13).chr(10);
		if (!file_exists($logDirectory."http.log")) {
			file_put_contents($logDirectory."http.log", $logContents);
		} else {
			$fileOpen = fopen($logDirectory."http.log", "a+");
			fwrite($fileOpen, $logContents);
			fclose($fileOpen);
		}
		return $logContents;
	}