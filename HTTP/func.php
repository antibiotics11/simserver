<?php

	// UTC 기준 현재 시간을 문자열로 반환
	function getCurrentTime(): string {
		date_default_timezone_set("UTC"); 
		return $currentTime = "UTC ".date("Y-m-d H:i:s", time());
	}
	
	// 콘솔 메세지 출력
	function consoleMessage(string $message): void {
		echo "\033[1;34m ".$message." \033[0m".chr(13).chr(10);
	}
	
	// 콘솔 에러 출력, exit 1이면 종료
	function consoleError(string $message, int|bool $exit = 0): void {
		echo "\033[1;31m ERROR: ".$message." \033[0m".chr(13).chr(10);
		if ($exit) exit(0);
	}
	
	// 설정이 올바른지 확인
	function configValid(array $_SERVERCONF): bool {
		$confOptions = array("PORT", "LOG_DIR");
		foreach($_SERVERCONF as $value => $key) {
			if ($key == "PORT") {
				if (!is_numeric($key) || (int)$key > 65535 || (int)$key <= 0) {
					return false;
				}
			}
			if ($key == "LOG_DIR") {
				if (!is_dir($value) || !is_writable($value)) {
					return false;
				}
			}
			if (!in_array($key, $confOptions)) {
				continue;
			}
		}
		
		return true;
	}
	