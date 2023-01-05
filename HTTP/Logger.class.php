<?php

namespace HTTP;

class Logger {

	const BUFFER_SIZE = 10;

	public static Array $logBuffer = [];
	
	public static function writeLog(String $dir, String $expression): void {
		
		$time = SocketServer::getSystemTime();
		$log = $time."\t".$expression;
		
		Logger::$logBuffer[] = $log;
		
		if (count(Logger::$logBuffer) >= Logger::BUFFER_SIZE) {
		
			Logger::writeLogBuffer($dir);
			Logger::resetLogBuffer();
			
		} 
		
	}
	
	public static function writeLogBuffer(String $dir): bool {
		
		$file = realpath($dir);
		if ($file === false) {
			return false;
		}
		
		$file .= DIRECTORY_SEPARATOR.date("Y.m.d").".log";
		$contents = implode("\r\n", Logger::$logBuffer);
		$contents .= "\r\n";
		
		$handle = fopen($file, "a");
		fwrite($handle, $contents);
		fclose($handle);
		
		return true;
		
	}
	
	public static function printLog(String $expression, bool $error = false): void {
	
		$color = ($error) ? Logger::ANSI_FONT_RED : Logger::ANSI_FONT_GREEN;
		printf("\033[1;%dm%s\033[%dm\r\n", $color, $expression, Logger::ANSI_FONT_RESET);
	
	}
	
	public static function resetLogBuffer(): void {
	
		Logger::$logBuffer = [];
	
	}
	
	const ANSI_FONT_RESET   = 0;
	const ANSI_FONT_BLACK   = 30;
	const ANSI_FONT_RED     = 31;
	const ANSI_FONT_GREEN   = 32;
	const ANSI_FONT_WELLOW  = 33;
	const ANSI_FONT_BLUE    = 34;
	const ANSI_FONT_PURPLE  = 35;
	const ANSI_FONT_CYAN    = 36;
	const ANSI_FONT_WHITE   = 37;
};
