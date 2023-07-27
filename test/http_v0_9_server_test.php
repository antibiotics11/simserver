<?php

require_once "../src/Exception/ServerException.php";
require_once "../src/Network/StreamServer.php";
require_once "../src/Network/InetAddress.php";
require_once "../src/Http/Message.php";
require_once "../src/Http/HeaderField.php";
require_once "../src/Http/StatusCode.php";
require_once "../src/System/Logger.php";
use simserver\Network\{InetAddress, StreamServer};

$server = new StreamServer(InetAddress::getByAddress("127.0.0.1"), 80);
$server->setRequestMaxSize(1024 * 4);
$server->setPersistentConnection(false);
$server->setStreamBlocking(false);

$logger = new simserver\System\Logger("./");
$logger->print("Starting HTTP/0.9 test server at 127.0.0.1:80");

$server->listen(function(String $request, String $client) use ($logger): String {

  $request = simserver\Http\Message::getFromRequestMessage($request);
  if (strcmp($request->path, "/") === 0) {
    $request->path = "/index.html";
  }
  $path = sprintf("document_root%s", $request->path);

  $response = "";
  if (is_file($path) && is_readable($path)) {
    $response = file_get_contents($path);
  }
  
  $logger->print(sprintf("%d bytes sent to %s", strlen($response), $client));

  return $response;

});

