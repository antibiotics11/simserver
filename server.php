<?php

require_once "autoloader.php";
require_once "config.php";

cli_set_process_title("simserver");
simserver\System\Time::setTimeZone("GMT");

$streamServer = new simserver\Server\StreamServer();
$streamServer->create(
	new simserver\Network\InetAddress(HTTP_SERVER_NAME),
	HTTP_SERVER_PORT
);

$httpServer = new simserver\Server\HttpServer($streamServer);
$httpServer->run();
