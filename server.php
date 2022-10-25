#!/usr/bin/php
<?php

include_once __DIR__."/config.php";
include_once __DIR__."/HTTP/InetAddress.class.php";
include_once __DIR__."/HTTP/Socket.class.php";
include_once __DIR__."/HTTP/Server.class.php";
include_once __DIR__."/HTTP/Status.class.php";
include_once __DIR__."/HTTP/StaticResource.class.php";
include_once __DIR__."/HTTP/Request.class.php";
include_once __DIR__."/HTTP/Response.class.php";

cli_set_process_title("simserver");
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

function main(Array $argv = []): void {

	if (strlen($e = extensions_loaded()) !== 0) {
		console_log("The ".$e." extension not loaded.", true);
		terminate(true);
	}

	foreach (HOSTS as $alias => $host) {

		$pid = pcntl_fork();
		if ($pid === 0) {
			
			try {
				$server = new \HTTP\Server($host);
				$server->run();
			} catch (Throwable $e) {
				console_log("Server Error: ".$e->getMessage(), true);
			} catch (Exception $e) {
				console_log("Server Error: ".$e->getMessage(), true);
			}

		} else if ($pid === -1) {
			
			console_log("Failed to fork child process.", true);
			terminate(true);

		}

	}

}

function console_log(String $expression, bool $is_error = false): void {
	
	$color = ($is_error) ? 31 : 32;
	printf(
		"\033[1;%dm%s\033[0m\r\n",
		$color, $expression
	);

}

function extensions_loaded(): String {

	$extensions = ["pcntl", "sockets"];
	foreach ($extensions as $e) {
		if (!extension_loaded($e)) {
			return $e;
		}
	}

	return "";

}

function terminate(bool $with_error = false): void {

	console_log("Terminating execution...", $with_error);
	exit(0);

}

main($_SERVER["argv"]);
