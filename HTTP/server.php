#!/usr/bin/php
<?php

cli_set_process_title("HTTP");

include_once __DIR__."/config.php";
include_once __DIR__."/HTTP/Socket.class.php";
include_once __DIR__."/HTTP/IP.class.php";
include_once __DIR__."/HTTP/HTTP.interface.php";
include_once __DIR__."/HTTP/Request.class.php";
include_once __DIR__."/HTTP/Response.class.php";

use \HTTP\{Socket, HTTP, Request, Response};

function main(Array $argv = array()): void {

	if (PHP_MAJOR_VERSION < 7 || php_sapi_name() !== "cli") {
		printf("ERROR: PHP-CLI 7+ or 8+ required.");
		return;
	}
	if (strlen($e = extensions_loaded()) > 0) {
		printf("ERROR: ".$e." extension not loaded.");
		return;
	}

	$doc_index_files = explode(",", \_CONF["DOC_INDEX"]);

	// Closure function to handle Socket connections
	$handle = function(\Socket $connection) use ($doc_index_files) {

		// Read stream from Socket and Create new \HTTP\Request object
		$received_stream = socket_read($connection, 4096);
		$request = new Request($received_stream);

		// Create new \HTTP\Response object and send reponse header through Socket
		$response = new Response($request, \_CONF["DOC_ROOT"], $doc_index_files);
		socket_write($connection, $response->get_header_stream());

		unset($request, $response);

	};

	// Create new \HTTP\Socket object and start listening
	$socket = new Socket(\_CONF["SERVER_NAME"], \_CONF["PORT"]);
	$socket->listen($handle);

	return;

}

function extensions_loaded(): String {

	$extensions = array("sockets", "pcntl");
	foreach ($extensions as $e) {
		if (!extension_loaded($e)) {
			return $e;
		}
	}

	return "";

}

main($_SERVER["argv"]);

